<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>InsuranceWise</title>

  <!-- Bootstrap CSS (CDN recommended) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Cambo|Poppins:400,600" rel="stylesheet">

  <!-- Font Awesome (CDN) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Custom CSS -->
  <style>
    body { font-family: "Poppins", sans-serif; padding-top: 70px; }
    
    /* Navbar */
    .navbar { min-height: 60px; padding: 5px 0; background-color: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .navbar-brand { font-size: 20px; padding: 10px 15px; }
    .navbar-nav > li > a { padding: 15px 12px; font-size: 14px; cursor: pointer; }

    /* Hero Section */
    .hero-section { background-size: cover; background-position: center; color: white; text-align: center; padding: 150px 20px; position: relative; }
    .hero-section::before { content: ""; position: absolute; inset: 0; background-color: rgba(0,0,0,0.5); }
    .hero-content { position: relative; z-index: 1; max-width: 800px; margin: 0 auto; }
    .hero-content .btn { background-color: #ff6f61; color: #fff; border: none; padding: 15px 35px; font-size: 16px; border-radius: 6px; }

    /* Optional: dropdowns and links */
    .nav-item .dropdown-menu { min-width: 150px; }
  </style>
</head>
<body>

@php
  $user = Session::get('firebase_user');
  $isAdmin = $user && ($user['role'] ?? '') === 'admin';
@endphp

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
  <div class="container">

    <!-- BRAND -->
    <a class="navbar-brand" href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}">
      InsuranceWise
    </a>

    <!-- MENU -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
            aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav ms-auto">

        @if($isAdmin)
          <!-- ADMIN NAV -->
          <li class="nav-item"><a class="nav-link" href="{{ route('quote.assignment') }}">Quote Requests</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.manage-plans') }}">Manage Plans</a></li>
        @else
          <!-- USER NAV -->
          <li class="nav-item"><a class="nav-link" href="{{ route('recommendationform') }}">Get Recommendation</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}#category-section">Browse Plan</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/quote-request') }}">Get Quote</a></li>
        @endif

        <!-- LOGOUT -->
        <li class="nav-item">
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
          </form>
          <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
          </a>
        </li>

      </ul>
    </div>
  </div>
</nav>

<!-- PAGE CONTENT -->
<main class="main-content">
  @yield('content')
</main>

<!-- Bootstrap JS (CDN) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
