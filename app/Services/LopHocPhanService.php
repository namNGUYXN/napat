<?php

namespace App\Services;

use App\BaiGiangLop;
use App\LopHocPhan;
use App\ThanhVienLop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LopHocPhanService
{
    public function layTheoId($id)
    {
        return LopHocPhan::findOrFail($id);
    }

    public function layTheoSlug($slug)
    {
        return LopHocPhan::where('slug', $slug)->firstOrFail();
    }

    public function getLopHocCuaToi($idNguoiDung, $perPage = -1)
    {
        if ($idNguoiDung != null) {
            $idLopHoc = ThanhVienLop::where('id_nguoi_dung', $idNguoiDung)
                ->where(function ($query) {
                    $query->where('is_accept', true)
                        ->orWhereNull('is_accept');
                })
                ->pluck('id_lop_hoc_phan');

            $listLopHocPhan = LopHocPhan::with(['giang_vien'])
                ->whereIn('id', $idLopHoc)
                ->where('is_delete', false)
                ->orderByDesc('ngay_tao');

            if ($perPage > 0) {
                return $listLopHocPhan->paginate($perPage);
            }
            
            return $listLopHocPhan->get();
        }
        return collect();
    }

    public function layChiTietLopHoc($slug)
    {
        return LopHocPhan::with(['giang_vien'])
            ->where('slug', $slug)
            ->where('is_delete', false)
            ->firstOrFail();
    }

    public function them(array $data)
    {
        try {
            DB::beginTransaction();

            $slug = Str::slug($data['ten']);
            $checkExists = LopHocPhan::where([
                ['id_giang_vien', session('id_nguoi_dung')],
                ['slug', 'LIKE', $slug . '%']
            ])->exists();

            if ($checkExists) {
                throw new \Exception('Tên lớp học phần này đã tồn tại');
            }

            $lopHocPhan = LopHocPhan::create([
                'ten' => $data['ten'],
                'ma' => Str::random(10),
                'slug' => '',
                'mo_ta_ngan' => $data['mo_ta_ngan'],
                'hinh_anh' => $data['hinh_anh'] ?? 'images/lop-hoc-phan/no-image.png',
                'id_giang_vien' => session('id_nguoi_dung'),
                'id_bai_giang' => $data['id_bai_giang'],
                'id_khoa' => $data['id_khoa']
            ]);

            $lopHocPhan->slug = $slug . '-' . $lopHocPhan->id;
            $lopHocPhan->save();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Thêm lớp học phần thành công',
                'data' => $lopHocPhan
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function chinhSua($id, array $data)
    {
        try {
            DB::beginTransaction();

            $lopHocPhan = LopHocPhan::findOrFail($id);
            $idBaiGiangBanDau = $lopHocPhan->id_bai_giang;
            $slug = Str::slug($data['ten']);

            $checkExists = LopHocPhan::where([
                ['id', '!=', $id],
                ['id_giang_vien', session('id_nguoi_dung')],
                ['slug', 'LIKE', $slug . '%']
            ])->exists();

            if ($checkExists) {
                throw new \Exception('Tên lớp học phần này đã tồn tại');
            }

            $lopHocPhan->update([
                'ten' => $data['ten'],
                'slug' => $slug . '-' . $lopHocPhan->id,
                'mo_ta_ngan' => $data['mo_ta_ngan'],
                'hinh_anh' => $data['hinh_anh'] ?? $lopHocPhan->hinh_anh,
                'id_bai_giang' => $data['id_bai_giang'],
                'id_khoa' => $data['id_khoa']
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Cập nhật lớp học phần thành công',
                'data' => $lopHocPhan->fresh(),
                'id_bai_giang_ban_dau' => $idBaiGiangBanDau
            ];

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
