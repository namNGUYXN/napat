<p>Xin chào <b>{{ $nguoi_dung->ho_ten }}</b>,</p>
<p>Bạn đã yêu cầu đặt lại mật khẩu. Vui lòng nhấp vào liên kết bên dưới để đặt lại:</p>
<a href="{{ url('/dat-lai-mat-khau', $token) }}">Đặt lại mật khẩu</a>
<p>Liên kết này sẽ hết hạn sau <b>10 phút.</b></p>
<p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>