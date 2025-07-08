<?php

namespace App\Services;

use App\BaiGiang;
use App\NguoiDung;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BaiGiangService
{
    public function layListTheoGiangVien(Request $request, $page = -1)
    {
        $idNguoiDungHienTai = session('id_nguoi_dung');
        $listBaiGiang = BaiGiang::where([
            ['id_giang_vien', $idNguoiDungHienTai],
        ])->withCount(['list_chuong as so_chuong']);

        // Tìm kiếm
        if ($search = $request->input('search')) {
            $listBaiGiang->where(function ($q) use ($search) {
                $q->where('ten', 'LIKE', '%' . $search . '%')
                    ->orWhere('mo_ta_ngan', 'LIKE', '%' . $search . '%');
            });
        }

        // Sắp xếp
        switch ($request->input('sort')) {
            case 'newest':
                $listBaiGiang->orderByDesc('ngay_tao');
                break;
            case 'oldest':
                $listBaiGiang->orderBy('ngay_tao');
                break;
            case 'name_asc':
                $listBaiGiang->orderBy('ten');
                break;
            case 'name_desc':
                $listBaiGiang->orderByDesc('ten');
                break;
            default:
                $listBaiGiang->orderByDesc('ngay_tao'); // mặc định: mới nhất
        }

        // Số lượng hiển thị mỗi trang
        $limit = $request->input('limit', 3); // mặc định: 3

        if ($page > 0) {
            return $listBaiGiang->paginate($limit, ['*'], 'page', $page)->appends($request->query());
        } else {
            return $listBaiGiang->paginate($limit)->appends($request->query());
        }

        return $listBaiGiang->get();
    }

    public function layTheoId($id)
    {
        return BaiGiang::where('id', $id)
            ->withCount(['list_chuong as so_chuong'])
            ->firstOrFail();
    }

    public function them(array $data)
    {
        try {
            DB::beginTransaction();

            $checkExists = BaiGiang::where([
                ['id_giang_vien', session('id_nguoi_dung')],
                ['ten', $data['ten']]
            ])->exists();

            if ($checkExists) {
                throw new \Exception('Tên bài giảng này đã tồn tại');
            }

            $baiGiang = BaiGiang::create([
                'ten' => $data['ten'],
                'slug' => '',
                'mo_ta_ngan' => $data['mo_ta_ngan'],
                'hinh_anh' => $data['hinh_anh'] ?? 'images/bai-giang/no-image.png',
                'id_giang_vien' => session('id_nguoi_dung'),
            ]);

            $baiGiang->slug = Str::slug($data['ten']) . '-' . $baiGiang->id;
            $baiGiang->save();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Thêm bài giảng thành công'
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

            $baiGiang = BaiGiang::findOrFail($id);
            $slug = Str::slug($data['ten']);

            $checkExists = BaiGiang::where([
                ['id', '!=', $id],
                ['id_giang_vien', session('id_nguoi_dung')],
                ['ten', $data['ten']]
            ])->exists();

            if ($checkExists) {
                throw new \Exception('Tên bài giảng này đã tồn tại');
            }

            $baiGiang->update([
                'ten' => $data['ten'],
                'slug' => $slug . '-' . $baiGiang->id,
                'mo_ta_ngan' => $data['mo_ta_ngan'],
                'hinh_anh' => $data['hinh_anh'] ?? $baiGiang->hinh_anh,
            ]);

            DB::commit();
            return [
                'success' => true,
                'message' => 'Cập nhật bài giảng thành công'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function xoa($id)
    {
        try {
            DB::beginTransaction();

            $baiGiang = BaiGiang::findOrFail($id);

            $soChuong = $baiGiang->list_chuong()->count();

            // Kiểm tra bài giảng có chương không
            if ($soChuong > 0) {
                throw new \Exception('Không thể xóa vì có chương trong bài giảng');
            }

            $baiGiang->delete();

            DB::commit();
            return [
                'success' => true,
                'message' => 'Xóa bài giảng thành công'
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy bài giảng để xóa'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
