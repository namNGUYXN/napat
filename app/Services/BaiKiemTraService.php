<?php

namespace App\Services;

use App\BaiKiemTra;
use App\CauHoiBaiKiemTra;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BaiKiemTraService
{
    // Lấy tất cả bài tập (chưa bị xóa)
    public function getAll()
    {
        return BaiKiemTra::where('is_delete', false)->get();
    }

    // Lấy bài kiểm tra theo ID
    public function getById($id)
    {
        return BaiKiemTra::with('list_cau_hoi')
            ->where('id', $id)
            ->where('is_delete', false)->first();
    }

    // Lấy danh sách bài tập theo id bài giảng
    public function getByLopHocIdWithCauHoi($id_lop_hoc_phan)
    {
        return BaiKiemTra::with('list_cau_hoi') // eager load quan hệ câu hỏi
            ->where('id_lop_hoc_phan', $id_lop_hoc_phan)
            ->where('is_delete', false)
            ->get();
    }

    // Lấy danh sách bài tập theo id bài giảng
    public function getByLopHocId($id_lop_hoc_phan)
    {
        return BaiKiemTra::where('id_lop_hoc_phan', $id_lop_hoc_phan)
            ->where('is_delete', false)
            ->get();
    }

    // Tạo mới bài tập
    public function create($data)
    {
        return BaiKiemTra::create([
            'tieu_de' => $data['tieu_de'],
            'slug' => Str::slug($data['tieu_de']) . '-' . uniqid(),
            'diem_toi_da' => $data['diem_toi_da'],
            'id_bai_giang' => $data['id_bai_giang'],
        ]);
    }

    // Cập nhật bài tập
    public function update($id, $data)
    {
        $baiTap = BaiKiemTra::where('id', $id)->where('is_delete', false)->first();
        if (!$baiTap) {
            return null;
        }

        $baiTap->tieu_de = $data['tieu_de'];
        $baiTap->slug = Str::slug($data['tieu_de']) . '-' . uniqid();
        $baiTap->diem_toi_da = $data['diem_toi_da'];
        $baiTap->id_bai_giang = $data['id_bai_giang'];
        $baiTap->save();

        return $baiTap;
    }

    // Xóa mềm bài tập
    public function softDelete($id)
    {
        $baiTap = BaiKiemTra::where('id', $id)->first();
        if (!$baiTap) {
            return false;
        }

        $baiTap->is_delete = true;
        return $baiTap->save();
    }

    public function createExercise(array $data)
    {
        DB::beginTransaction();
        try {
            $baiKiemTra = BaiKiemTra::create([
                'tieu_de' => $data['tieuDe'],
                'slug' => Str::slug($data['tieuDe']),
                'diem_toi_da' => $data['diemToiDa'] ?? null,
                'id_lop_hoc_phan' => $data['idLopHoc'],
                'ngay_bat_dau' => Carbon::now(), // cần chỉnh lại(đang demo)
                'ngay_ket_thuc' => Carbon::now(), // cần chỉnh lại
                'ngay_tao' => Carbon::now(),
                'is_delete' => false
            ]);

            foreach ($data['danhSachCauHoi'] as $q) {
                CauHoiBaiKiemTra::create([
                    'id_bai_kiem_tra' => $baiKiemTra->id,
                    'tieu_de' => $q['cauHoi'],
                    'dap_an_a' => $q['danhSachDapAn'][0] ?? '',
                    'dap_an_b' => $q['danhSachDapAn'][1] ?? '',
                    'dap_an_c' => $q['danhSachDapAn'][2] ?? '',
                    'dap_an_d' => $q['danhSachDapAn'][3] ?? '',
                    'dap_an_dung' => ['A', 'B', 'C', 'D'][$q['dapAnDuocChon']],
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e; // Để controller xử lý trả lỗi JSON
        }
    }
}
