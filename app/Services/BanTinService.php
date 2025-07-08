<?php

namespace App\Services;

use App\BanTin;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class BanTinService
{
    public function layBanTinLopHoc($idLopHoc)
    {
        return BanTin::with([
            'thanh_vien_lop.nguoi_dung',
            'list_ban_tin_con.thanh_vien_lop.nguoi_dung' // load người dùng của từng bình luận
        ])
            ->whereNull('id_ban_tin_cha') // chỉ bản tin cha
            ->where('id_lop_hoc_phan', $idLopHoc)
            ->orderByDesc('ngay_tao')
            ->get();
    }

    public function them(array $data)
    {
        $type = $data['id_ban_tin_cha'] ? 'phản hồi' : 'bản tin';

        try {
            DB::beginTransaction();

            if (isset($data['duocPhepPhanHoi']) && !$data['duocPhepPhanHoi']) {
                throw new Exception('Không thể phản hồi bản tin ở lớp học phần khác');
            }

            BanTin::create([
                'noi_dung' => $data['noi_dung'],
                'id_thanh_vien_lop' => $data['id_thanh_vien_lop'],
                'id_lop_hoc_phan' => $data['id_lop_hoc_phan'],
                'id_ban_tin_cha' => $data['id_ban_tin_cha'],
                'ngay_tao' => Carbon::now()
            ]);

            DB::commit();
            return [
                'success' => true,
                'message' => "Đăng {$type} thành công"
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => "Lỗi khi đăng {$type}: " + $e->getMessage()
            ];
        }
    }

    public function layTheoId($id)
    {
        return BanTin::findOrFail($id);
    }

    public function chinhSua($id, array $data)
    {
        try {
            DB::beginTransaction();

            $banTin = BanTin::findOrFail($id);
            $type = $banTin->id_ban_tin_cha ? 'phản hồi' : 'bản tin';

            $banTin->noi_dung = $data['noi_dung'];
            $banTin->save();

            DB::commit();
            return [
                'success' => true,
                'message' => "Cập nhật {$type} thành công",
                'data' => $banTin->fresh()
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'message' => "Lỗi khi cập nhật {$type}: " . $e->getMessage()
            ];
        }
    }

    public function xoa($banTin, $thanhVienLop)
    {
        $type = $banTin->id_ban_tin_cha ? 'phản hồi' : 'bản tin';

        try {
            DB::beginTransaction();

            if ($banTin->id_thanh_vien_lop != $thanhVienLop->id) {
                throw new Exception("Bạn không thể xóa {$type} của người khác");
            }

            $banTin->delete();

            DB::commit();
            return [
                'success' => true,
                'message' => "Xóa {$type} thành công",
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'message' => "Lỗi khi xóa {$type}: " . $e->getMessage()
            ];
        }
    }
}
