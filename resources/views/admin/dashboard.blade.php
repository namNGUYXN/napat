<h1>Trang dashboard (Admin)</h1>
<h3>Xin chào {{ session('ho_ten') }}!</h3>
<form action="{{ route('dang-xuat') }}" method="POST">
  @csrf
  <button type="submit">Đăng xuất</button>
</form>
