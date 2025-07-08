<?php

namespace App\Http\Controllers;

use App\Helpers\UploadImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\AuthService;
use App\Services\NguoiDungService;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class NguoiDungController extends Controller
{
    protected $authService;
    protected $nguoiDungService;
    protected $uploadImageHelper;

    function __construct(AuthService $authService, NguoiDungService $nguoiDungService, UploadImageHelper $uploadImageHelper)
    {
        $this->authService = $authService;
        $this->nguoiDungService = $nguoiDungService;
        $this->uploadImageHelper = $uploadImageHelper;
    }

    public function chiTiet()
    {
        $id = $this->authService->layIdNguoiDungDangNhap();
        $nguoiDung = $this->nguoiDungService->layTheoId($id);

        if ($nguoiDung->vai_tro == 'Admin') {
            return view('admin.modules.tai-khoan.chi-tiet', compact('nguoiDung'));
        }

        return view('modules.tai-khoan.chi-tiet', compact('nguoiDung'));
    }

    public function doiMatKhau(Request $request)
    {
        $id = $this->authService->layIdNguoiDungDangNhap();
        $nguoiDung = $this->nguoiDungService->layTheoId($id);

        $result = $this->nguoiDungService->doiMatKhau(
            $nguoiDung,
            $request->currentPassword,
            $request->newPassword
        );

        return response()->json($result);
    }

    public function capNhatThongTinCaNhan(Request $request)
    {
        $data = $request->validate(
            [
                'ho_ten' => 'required|string|max:100',
                'sdt' => [
                    'nullable',
                    'unique:nguoi_dung,sdt,' . session('id_nguoi_dung'),
                    'regex:/^(\+84|0)\d{9}$/',
                ],
                'hinh_anh' => 'image'
            ],
            [
                'ho_ten.required' => 'Vui lòng nhập họ tên',
                'ho_ten.max' => 'Họ tên tối đa 100 ký tự',
                'sdt.unique' => 'Số điện thoại đã tồn tại trong hệ thống',
                'sdt.regex' => 'Số điện thoại phải bắt đầu +84 hoặc 0 và kế tiếp tối đa là 9 số',
                'hinh_anh.image' => 'Hình ảnh không hợp lệ'
            ]
        );

        $data['hinh_anh'] = NULL;

        $id = $this->authService->layIdNguoiDungDangNhap();
        $nguoiDung = $this->nguoiDungService->layTheoId($id);

        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');

            // Xóa ảnh trong storage (nếu ảnh mặc định thì ko xóa)
            $hinh_anh_goc = $nguoiDung->hinh_anh;
            if (!Str::contains($hinh_anh_goc, 'no-avatar.png')) {
                $this->uploadImageHelper->delete($hinh_anh_goc);
            }

            $data['hinh_anh'] = $this->uploadImageHelper->upload($file, 'nguoi-dung');
        }

        $result = $this->nguoiDungService->capNhatThongTin($data, $nguoiDung);

        if ($result['success']) {
            return response()->json([
                'message' => $result['message'],
                'icon' => 'success'
            ]);
        }

        return response()->json([
            'message' => $result['message'],
            'icon' => 'error'
        ]);
    }

    public function hienThiFormThem()
    {
        return view('admin.modules.nguoi-dung.them');
    }

    public function danhSachNguoiDung(Request $request)
    {
        $vaiTro = $request->input('vai_tro'); // null, 1, 2
        $keyword = $request->input('keyword');
        $perPage = $request->input('per_page', 5);

        $danhSach = $this->nguoiDungService->danhSachNguoiDung($vaiTro, $keyword, $perPage);

        if ($request->ajax()) {
            return view('admin.partials.nguoi-dung._table', compact('danhSach'))->render();
        }

        return view('admin.modules.nguoi-dung.danh-sach', compact('danhSach', 'vaiTro', 'keyword'));
    }

    //Xử lý thêm người dùng thủ công
    public function xuLyThemNguoiDung(Request $request)
    {
        $validated = $request->validate([
            'ho_ten' => ['required', 'regex:/^[\p{L}\s]+$/u', 'max:255'],
            'email' => 'required|email|unique:nguoi_dung,email',
            'sdt' => ['nullable', 'regex:/^0\d{9,10}$/', 'unique:nguoi_dung,sdt'],
            'vai_tro' => ['required', Rule::in(['Giảng viên', 'Sinh viên', 'Admin'])],
        ], [
            'ho_ten.required' => 'Vui lòng nhập họ tên.',
            'ho_ten.regex' => 'Họ tên chỉ được chứa chữ cái và khoảng trắng, không chứa số hoặc ký tự đặc biệt.',
            'ho_ten.max' => 'Họ tên không được vượt quá :max ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
            'sdt.regex' => 'Số điện thoại phải là số và bắt đầu bằng số 0 và không quá 11 số',
            'sdt.unique' => 'Số điện thoại đã tồn tại trong hệ thống.',
            'vai_tro.required' => 'Vui lòng chọn vai trò.',
            'vai_tro.in' => 'Vai trò chỉ được là Giảng viên, Sinh viên hoặc Admin.',
        ], [
            'ho_ten' => 'Họ tên',
            'email' => 'Email',
            'sdt' => 'Số điện thoại',
            'vai_tro' => 'Vai trò',
        ]);


        $result = $this->nguoiDungService->themNguoiDung($validated);

        if ($result['success']) {

            return redirect()->route('nguoi-dung.index')->with([
                'message' => $result['message'],
                'icon' => 'success'
            ]);
        }
        
        return redirect()->route('nguoi-dung.index')->with([
            'message' => $result['message'],
            'icon' => 'error'
        ]);
    }

    //Xử lý khi thêm hàng loạt người dùng(Excel)
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
            $result = $this->nguoiDungService->importTuExcel($request->file('file_excel'));

            if (!$result['success']) {
                return back()
                    ->with('active_tab', 'import')
                    ->with('errors_import', $result['failures'])
                    ->with('message', 'Một số dòng bị lỗi, dữ liệu chưa được thêm!')
                    ->with('icon', 'warning');
            }

            return redirect()->route('nguoi-dung.index')
                ->with('message', 'Thêm người dùng thành công!')
                ->with('icon', 'success');
        } catch (\Exception $e) {
            return back()
                ->with('active_tab', 'import')
                ->with('errors_import',)
                ->with('message', 'Lỗi: ' . $e->getMessage())
                ->with('icon', 'error');
        }
    }

    //Xử lý khi chỉnh sửa thông tin người dùng
    public function suaNguoiDung($id)
    {
        $nguoiDung = $this->nguoiDungService->layTheoId($id);
        return view('admin.modules.nguoi-dung.chinh-sua', compact('nguoiDung'));
    }

    // Xử lý khi submit form sửa
    public function capNhatNguoiDung(Request $request, $id)
    {
        $validated = $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('nguoi_dung')->ignore($id)],
            'sdt' => 'nullable|string|max:20',
            'vai_tro' => 'required',
        ], [
            'ho_ten.required' => 'Vui lòng nhập họ tên.',
            'ho_ten.max' => 'Họ tên không được vượt quá :max ký tự.',

            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',

            'sdt.max' => 'Số điện thoại không được vượt quá :max ký tự.',

            'vai_tro.required' => 'Vui lòng chọn vai trò.',
        ], [

            'ho_ten' => 'Họ tên',
            'email' => 'Email',
            'sdt' => 'Số điện thoại',
            'vai_tro' => 'Vai trò',
        ]);

        $this->nguoiDungService->capNhatNguoiDung($id, $validated);

        return redirect()->route('nguoi-dung.index')
            ->with('message', 'Chỉnh sửa người dùng thành công!')
            ->with('icon', 'success');
    }
}
