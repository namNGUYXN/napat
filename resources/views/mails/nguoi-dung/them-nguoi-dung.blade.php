<p>Xin chào <b>{{ $nguoiDung->ho_ten }}</b>,</p>
<p>Bạn đã được tạo một tài khoản dùng để đăng nhập vào <a href="{{ route('dang-nhap') }}">hệ thống NAPAT E-Learning</a>.
  Vui lòng sử dụng thông tin dưới đây để đăng nhập:</p>
<p><b>Email:</b> {{ $nguoiDung->email }}</p>
<p><b>Họ tên:</b> {{ $nguoiDung->ho_ten }}</p>
<p><b>Số điện thoại:</b> {{ $nguoiDung->sdt }}</p>
<p><b>Mật khẩu:</b> {{ $matKhau }}</p>
<p><b>Vai trò trên hệ thống:</b> {{ $nguoiDung->vai_tro }}</p>
