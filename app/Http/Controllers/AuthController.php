<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    function giaoDienDangNhap()
    {
        if (session()->has('id_nguoi_dung')) {
            if (session('vai_tro') == 'Admin') {
                return redirect()->route('dashboard');
            } else return redirect()->route('home');
        }

        return view('auth.dang-nhap');
    }

    function giaoDienDangNhapLanDau()
    {
        return view('auth.dang-nhap-lan-dau');
    }

    function dangNhap(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'mat_khau' => [
                    'required',
                    'regex:/^([\w_\.!@#$%^&*()]+){6,32}$/'
                ],
                'ghi_nho_dang_nhap' => 'nullable'
            ],
            [
                'email.required' => 'Vui lòng nhập địa chỉ email.',
                'email.email' => 'Email không đúng định dạng.',
                'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
                'mat_khau.regex' => 'Password chỉ được sử dụng kí tự, chữ số, ký tự đặc biệt và có 6 đến 32 kí tự'
            ]
        );

        $result = $this->authService->dangNhap([
            'email' => $request->email,
            'mat_khau' => $request->mat_khau,
        ], $request->has('ghi_nho_dang_nhap'));

        if ($result['success']) {
            if (session('vai_tro') == 'Admin') {
                return redirect()->route('dashboard')->with('success', $result['message']);
            }
            return redirect()->route('home')->with('success', $result['message']);
        }

        return back()->with([
            'message' => $result['message'],
            'status' => 'danger'
        ]);
    }

    function dangXuat()
    {
        $result = $this->authService->dangXuat();
        return redirect()->route('dang-nhap')->with([
            'message' => $result['message'],
            'status' => 'success'
        ]);
    }

    function giaoDienQuenMatKhau()
    {
        return view('auth.quen-mat-khau');
    }

    function guiLienKetDatLaiMatKhau(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email'
            ],
            [
                'email.required' => 'Vui lòng nhập địa chỉ email.',
                'email.email' => 'Email không đúng định dạng.'
            ]
        );

        $result = $this->authService->guiLienKetDatLaiMatKhau($request->email);

        if ($result['success']) {
            return back()->with([
                'message' => $result['message'],
                'status' => 'success'
            ]);
        }

        return back()->with([
            'message' => $result['message'],
            'status' => 'danger'
        ]);
    }

    function giaoDienDatLaiMatKhau($token)
    {
        $result = $this->authService->xacThucTokenDatLaiMatKhau($token);

        if (!$result['success']) {
            return redirect()->route('quen-mat-khau')->with([
                'message' => $result['message'],
                'status' => 'danger'
            ]);
        }

        return view('auth.dat-lai-mat-khau', [
            'token' => $token,
            'email' => $result['email'],
        ]);
    }

    function datLaiMatKhau(Request $request)
    {
        $request->validate(
            [
                'token' => 'required',
                'email' => 'required|email',
                'mat_khau' => [
                    'required',
                    'regex:/^([\w_\.!@#$%^&*()]+){6,32}$/',
                    'confirmed'
                ],
            ],
            [
                'mat_khau.required' => 'Vui lòng nhập mật khẩu mới.',
                'mat_khau.regex' => 'Password chỉ được sử dụng kí tự, chữ số, ký tự đặc biệt và có 6 đến 32 kí tự',
                'mat_khau.confirmed' => 'Mật khẩu mới không trùng khớp'
            ]
        );

        $result = $this->authService->datLaiMatKhau([
            'token' => $request->token,
            'email' => $request->email,
            'mat_khau' => $request->mat_khau,
        ]);

        if ($result['success']) {
            return redirect()->route('dang-nhap')->with([
                'message' => $result['message'],
                'status' => 'success'
            ]);
        }

        return back()->with([
            'message' => $result['message'],
            'status' => 'danger'
        ]);
    }

    public function doiMatKhauLanDau(Request $request)
    {
        $request->validate([
            'mat_khau' => 'required|string|min:6|max:32'
        ]);

        $idNguoiDung = session('id_nguoi_dung');

        if (!$idNguoiDung) {
            return redirect()->route('dang-nhap')->with('message', 'Vui lòng đăng nhập.');
        }

        $ketQua = $this->authService->doiMatKhauLanDau($idNguoiDung, $request->mat_khau);

        if ($ketQua['status']) {
            // Đánh dấu đã đổi mật khẩu
            session(['is_change_pass' => 1]);

            return redirect()->route('home')->with('message', $ketQua['message']);
        }

        return redirect()->back()->with('message', $ketQua['message']);
    }
}
