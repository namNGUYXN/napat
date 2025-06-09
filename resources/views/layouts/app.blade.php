<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="{{ asset('css/bootstrap-5.3.3.min.css') }}" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  @yield('styles')
</head>

<body>

  <div id="wrapper">
    
    @include('partials._header')

    @include('partials._menu-sidebar-mobile')

    <main class="container-fluid">
      <div class="row flex-nowrap content-row">
        
        @include('partials._menu-sidebar')

        @yield('content')
        
      </div>
    </main>

    @include('partials._footer')

  </div>

  <script src="{{ asset('js/jquery-3.7.1.js') }}"></script>
  <script src="{{ asset('js/bootstrap-5.3.3.bundle.min.js') }}"></script>
  <script src="{{ asset('js/main.js') }}"></script>
  
  @yield('scripts')
</body>

</html>