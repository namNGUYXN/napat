<?php

namespace App\Services;

use App\BaiTap;
use App\CauHoiBaiTap;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BaiTapService
{
     // Lấy tất cả bài tập (chưa bị xóa)
    public function getAll()
    {
        return BaiTap::where('is_delete', false)->get();
    }

    // Lấy bài tập theo ID
    public function getById($id)
    {
        return BaiTap::where('id', $id)->where('is_delete', false)->first();
    }

    // Lấy danh sách bài tập theo id bài giảng
    public function getByBaiGiangId($id_bai_giang)
    {
        return BaiTap::with('cauHoiBaiTaps') // eager load quan hệ câu hỏi
                    ->where('id_bai_giang', $id_bai_giang)
                    ->where('is_delete', false)
                    ->get();
    }

    // Tạo mới bài tập
    public function create($data)
    {
        return BaiTap::create([
            'tieu_de' => $data['tieu_de'],
            'slug' => Str::slug($data['tieu_de']) . '-' . uniqid(),
            'diem_toi_da' => $data['diem_toi_da'],
            'id_bai_giang' => $data['id_bai_giang'],
        ]);
    }

    // Cập nhật bài tập
    public function update($id, $data)
    {
        $baiTap = BaiTap::where('id', $id)->where('is_delete', false)->first();
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
        $baiTap = BaiTap::where('id', $id)->first();
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
            $baiTap = BaiTap::create([
                'tieu_de' => $data['tieuDe'],
                'slug' => Str::slug($data['tieuDe']),
                'diem_toi_da' => $data['diemToiDa'] ?? null,
                'id_bai_giang' => $data['idBaiGiang'],
                'is_delete' => false
            ]);

            foreach ($data['danhSachCauHoi'] as $q) {
                CauHoiBaiTap::create([
                    'id_bai_tap' => $baiTap->id,
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
