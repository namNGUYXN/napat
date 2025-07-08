<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\KhoaService;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Exception;

class KhoaController extends Controller
{
    protected $khoaService;

    public function __construct(KhoaService $khoaService)
    {
        $this->khoaService = $khoaService;
    }

    //Danh sách
    public function danhSachKhoa(Request $request)
    {
        $keyword = $request->input('keyword');
        $perPage = $request->input('per_page', 5);

        $danhSach = $this->khoaService->danhSachKhoa($keyword, $perPage);

        if ($request->ajax()) {
            return view('admin.partials.khoa._table', compact('danhSach'))->render();
        }

        return view('admin.modules.khoa.danh-sach', compact('danhSach', 'keyword'));
    }

    //Trang thêm khoa
    public function hienThiFormThem()
    {
        return view('admin.modules.khoa.them');
    }

    //Thêm khoa mới
    public function xuLyThemKhoa(Request $request)
    {
        $validated = $request->validate([
            'ma' => ['required', 'string', 'max:50', 'unique:khoa,ma'],
            'ten' => ['required', 'string', 'max:255', 'unique:khoa,ten'],
            'mo_ta_ngan' => ['nullable', 'string'],
        ], [
            'ma.required' => 'Vui lòng nhập mã khoa.',
            'ma.unique' => 'Mã khoa đã tồn tại.',
            'ten.required' => 'Vui lòng nhập tên khoa.',
            'ten.unique' => 'Tên khoa đã tồn tại.',

        ]);

        $this->khoaService->themKhoa($validated);

        return redirect()->route('khoa.index')
            ->with('message', 'Thêm khoa thành công!')
            ->with('icon', 'success');
    }

    public function xuLyImportExcel(Request $request)
    {
        try {
            $request->validate([
                'file_excel' => 'required|file|mimes:xlsx,xls,csv',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->with('active_tab', 'import')
                ->with('message', 'File không hợp lệ! Vui lòng chọn tệp Excel có định dạng xlsx, xls hoặc csv.')
                ->with('icon', 'warning');
        }

        try {
            $result = $this->khoaService->importTuExcel($request->file('file_excel'));

            if (!$result['success']) {
                return back()
                    ->with('active_tab', 'import')
                    ->with('errors_import', $result['failures'])
                    ->with('message', 'Một số dòng bị lỗi, dữ liệu chưa được thêm!')
                    ->with('icon', 'warning');
            }

            return redirect()->route('khoa.index')
                ->with('message', 'Thêm khoa thành công!')
                ->with('icon', 'success');
        } catch (\Exception $e) {
            return back()
                ->with('active_tab', 'import')
                ->with('message', 'Lỗi: ' . $e->getMessage())
                ->with('icon', 'error');
        }
    }

    //Trang cập nhật
    public function hienThiFormCapNhat($id)
    {
        $khoa = $this->khoaService->layTheoId($id);
        return view('admin.modules.khoa.chinh-sua', compact('khoa'));
    }


    //Sửa
    public function capNhat(Request $request)
    {
        $khoa = $this->khoaService->layTheoId($request->input('id'));

        if ($khoa == null) {
            return view('modules.lop-hoc.thong-bao', [
                'thanhCong' => false,
                'noiDung' => "Lỗi cập nhật",
                'thongBao' => "Khoa không tồn tại!!!",
            ]);
        }


        $validated = $request->validate([
            'ma' => [
                'required',
                'string',
                'max:20',
                Rule::unique('khoa', 'ma')->ignore($request->input('id')),
            ],
            'ten' => [
                'required',
                'string',
                'max:255',
                Rule::unique('khoa', 'ten')->ignore($request->input('id')),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('khoa', 'email')->ignore($request->input('id')),
            ],
            'mo_ta_ngan' => 'nullable|string|max:1000',
        ], [
            // Thông điệp cho mã khoa
            'ma.required' => 'Vui lòng nhập mã khoa.',
            'ma.string' => 'Mã khoa phải là chuỗi ký tự.',
            'ma.max' => 'Mã khoa không được vượt quá 20 ký tự.',
            'ma.unique' => 'Mã khoa đã tồn tại.',

            // Thông điệp cho tên khoa
            'ten.required' => 'Vui lòng nhập tên khoa.',
            'ten.string' => 'Tên khoa phải là chuỗi ký tự.',
            'ten.max' => 'Tên khoa không được vượt quá 255 ký tự.',
            'ten.unique' => 'Tên khoa đã tồn tại.',

            // Thông điệp cho email
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email đã được sử dụng.',

            // Thông điệp cho mô tả
            'mo_ta_ngan.string' => 'Mô tả ngắn phải là chuỗi ký tự.',
            'mo_ta_ngan.max' => 'Mô tả ngắn không được vượt quá 1000 ký tự.',
        ]);

        try {
            $this->khoaService->capNhat($request->input('id'), $validated);

            return redirect()->route('khoa.index')
                ->with('message', 'Cập nhật thông tin thành công!')
                ->with('icon', 'success');
        } catch (QueryException $e) {
            // Lỗi database, ví dụ trùng hoặc ràng buộc
            return back()->withErrors(['database' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()])
                ->withInput();
        } catch (Exception $e) {
            // Lỗi chung
            return back()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()])
                ->withInput();
        }
    }

    //Xóa
    public function xoa($id)
    {
        $this->khoaService->xoaKhoa($id);

        return redirect()->back()->with('success', 'Xóa khoa thành công!');
    }
}
