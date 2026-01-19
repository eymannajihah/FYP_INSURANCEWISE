<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'InsuranceWise')</title>

  <link rel="stylesheet" href="{{ asset('assets/css/nucleo-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/nucleo-svg.css') }}">
  <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.6') }}" rel="stylesheet" />

  <style>
    .mask.bg-gradient-dark {
      background: linear-gradient(310deg, #141727, #3A416F);
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg my-3" style="background: transparent;">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#" style="color: white;">InsuranceWise</a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a href="{{ route('login') }}" class="nav-link" style="color: white;">Login</a></li>
      <li class="nav-item"><a href="{{ route('register') }}" class="nav-link" style="color: white;">Register</a></li>
    </ul>
  </div>
</nav>


  @yield('content')

  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.6') }}"></script>
</body>
</html>
