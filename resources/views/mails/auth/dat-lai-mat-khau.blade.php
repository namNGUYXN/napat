<p>Xin chào {{ $nguoi_dung->ten }},</p>
<p>Bạn đã yêu cầu đặt lại mật khẩu. Vui lòng nhấp vào liên kết bên dưới để đặt lại:</p>
<a href="{{ url('/dat-lai-mat-khau', $token) }}">Đặt lại mật khẩu</a>
<p>Liên kết này sẽ hết hạn sau 10 phút.</p>
<p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>