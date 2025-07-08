<?php

namespace App\Services;

use App\BaiGiangLop;
use App\LopHocPhan;
use App\ThanhVienLop;
use Illuminate\Http\Request;
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

    public function getLopHocCuaToi(Request $request, $idNguoiDung, $page = -1)
    {
        if ($idNguoiDung != null) {
            $idLopHoc = ThanhVienLop::where('id_nguoi_dung', $idNguoiDung)
                ->where(function ($query) {
                    $query->where('is_accept', true)
                        ->orWhereNull('is_accept');
                })
                ->pluck('id_lop_hoc_phan');

            $listLopHocPhan = LopHocPhan::with(['giang_vien'])
                ->whereIn('id', $idLopHoc);

            // Tìm kiếm
            if ($search = $request->input('search')) {
                $listLopHocPhan->where(function ($q) use ($search) {
                    $q->where('ten', 'LIKE', '%' . $search . '%')
                        ->orWhere('ma', 'LIKE', '%' . $search . '%');
                });
            }

            // Sắp xếp
            switch ($request->input('sort')) {
                case 'newest':
                    $listLopHocPhan->orderByDesc('ngay_tao');
                    break;
                case 'oldest':
                    $listLopHocPhan->orderBy('ngay_tao');
                    break;
                case 'name_asc':
                    $listLopHocPhan->orderBy('ten');
                    break;
                case 'name_desc':
                    $listLopHocPhan->orderByDesc('ten');
                    break;
                default:
                    $listLopHocPhan->orderByDesc('ngay_tao'); // mặc định: mới nhất
            }

            // Số lượng hiển thị mỗi trang
            $limit = $request->input('limit', 3); // mặc định: 3

            if ($page > 0) {
                return $listLopHocPhan->paginate($limit, ['*'], 'page', $page)->appends($request->query());
            } else {
                return $listLopHocPhan->paginate($limit)->appends($request->query());
            }

            return $listLopHocPhan->get();
        }
        return collect();
    }

    public function layListTheoKhoa(Request $request, $idKhoa, $page = -1)
    {
        $listLopHocPhan = LopHocPhan::with(['giang_vien'])->where([
            ['id_khoa', $idKhoa]
        ]);

        // Tìm kiếm
        if ($search = $request->input('search')) {
            $listLopHocPhan->where(function ($q) use ($search) {
                $q->where('ten', 'LIKE', '%' . $search . '%')
                    ->orWhere('ma', 'LIKE', '%' . $search . '%');
            });
        }

        // Sắp xếp
        switch ($request->input('sort')) {
            case 'newest':
                $listLopHocPhan->orderByDesc('ngay_tao');
                break;
            case 'oldest':
                $listLopHocPhan->orderBy('ngay_tao');
                break;
            case 'name_asc':
                $listLopHocPhan->orderBy('ten');
                break;
            case 'name_desc':
                $listLopHocPhan->orderByDesc('ten');
                break;
            default:
                $listLopHocPhan->orderByDesc('ngay_tao'); // mặc định: mới nhất
        }

        // Số lượng hiển thị mỗi trang
        $limit = $request->input('limit', 3); // mặc định: 3

        if ($page > 0) {
            return $listLopHocPhan->paginate($limit, ['*'], 'page', $page)->appends($request->query());
        } else {
            return $listLopHocPhan->paginate($limit)->appends($request->query());
        }

        return $listLopHocPhan->get();
    }

    public function layChiTietLopHoc($slug)
    {
        return LopHocPhan::with(['giang_vien'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function them(array $data)
    {
        try {
            DB::beginTransaction();

            $checkExists = LopHocPhan::where([
                ['id_giang_vien', session('id_nguoi_dung')],
                ['ten', $data['ten']]
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

            $lopHocPhan->slug = Str::slug($data['ten']) . '-' . $lopHocPhan->id;
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
                ['ten', $data['ten']]
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

    public function xoa($lopHocPhan, $nguoiDung)
    {
        try {
            DB::beginTransaction();

            if ($lopHocPhan->id_giang_vien != $nguoiDung->id) {
                throw new \Exception("Bạn không thể xóa lớp học phần của người khác");
            }

            $lopHocPhan->delete();

            DB::commit();
            return [
                'success' => true,
                'message' => "Xóa lớp học phần thành công",
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function timKiemNhanhBai(Request $request, $idLopHocPhan)
    {
        $inputSearch = $request->input('search', '');
        $search = Str::of($inputSearch)->trim();
        $lopHocPhan = $this->layTheoId($idLopHocPhan);
        $listChuong = $lopHocPhan->bai_giang->list_chuong;

        $listChuongTrongLop = $lopHocPhan->list_bai()->where([
            ['tieu_de', 'LIKE', '%' . $search . '%']
        ])->get()->groupBy('id_chuong');

        return [
            'lopHocPhan' => $lopHocPhan,
            'listChuong' => $listChuong,
            'listChuongTrongLop' => $listChuongTrongLop
        ];
    }
}
