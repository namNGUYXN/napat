<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="{{ asset('css/bootstrap-5.3.3.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('modules/auth/css/login.css') }}">


  @yield('styles')
</head>

<style>
  :root { --bg-auth: url('{{ asset('images/goc-san-truong-cao-thang-2.jpg') }}') }
</style>

<body>

  <div class="login-overlay">
    <div id="login-wp" class="bg-white">

      @yield('form-title')

      @yield('form-content')

    </div>
  </div>

  <script src="{{ asset('js/jquery-3.7.1.js') }}"></script>
  <script src="{{ asset('js/bootstrap-5.3.3.bundle.min.js') }}"></script>
  <script src="{{ asset('modules/auth/js/auth.js') }}"></script>

  @yield('scripts')
</body>

</html>
