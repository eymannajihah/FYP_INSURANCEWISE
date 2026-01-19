<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Welcome | InsuranceWise</title>

  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Material+Icons" rel="stylesheet">

  <!-- Material Kit CSS -->
  <link href="../assets/css/material-kit.css?v=3.1.0" rel="stylesheet">
</head>

<body class="index-page bg-gray-200">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg position-sticky top-0 z-index-3 shadow-none bg-white py-3">
    <div class="container">
      <a class="navbar-brand text-dark font-weight-bold" href="#">
        InsuranceWise
      </a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a href="{{ route('login') }}" class="nav-link text-dark">Login</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('register') }}" class="nav-link text-dark">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->

  <!-- Header Section -->
  <header class="header-2">
    <div class="page-header min-vh-75 relative" style="background-image: url('../assets/img/bg9.jpg')">
      <span class="mask bg-gradient-info opacity-6"></span>
      <div class="container">
        <div class="row">
          <div class="col-lg-7 text-white">
            <h1 class="text-white">Welcome to InsuranceWise</h1>
            <p class="lead">Compare, choose, and get recommendations for the best insurance plans — powered by AI.</p>
            <a href="{{ route('login') }}" class="btn bg-gradient-light mt-3 text-info">Get Started</a>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- End Header Section -->

  <!-- Stats Section -->
  <section class="pt-4 pb-6" id="count-stats">
    <div class="container">
      <div class="row justify-content-center text-center">
        <div class="col-md-3">
          <h1 class="text-gradient text-info" id="state1" countTo="5234">0</h1>
          <h5>Users</h5>
          <p>Trusted by thousands of users across Malaysia.</p>
        </div>
        <div class="col-md-3">
          <h1 class="text-gradient text-info"><span id="state2" countTo="3400">0</span>+</h1>
          <h5>Plans Compared</h5>
          <p>Accurate comparisons using smart AI algorithms.</p>
        </div>
        <div class="col-md-3">
          <h1 class="text-gradient text-info"><span id="state3" countTo="24">0</span>/7</h1>
          <h5>Support</h5>
          <p>We’re here to help you anytime, anywhere.</p>
        </div>
      </div>
    </div>
  </section>
  <!-- End Stats Section -->

  <!-- Newsletter / Subscribe Section -->
  <section class="my-5 pt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-6 m-auto">
          <h4>Stay updated with InsuranceWise</h4>
          <p class="mb-4">
            Subscribe to receive the latest updates and tips about insurance recommendations.
          </p>
          <div class="row">
            <div class="col-8">
              <div class="input-group input-group-outline">
                <label class="form-label">Email Here...</label>
                <input type="text" class="form-control mb-sm-0">
              </div>
            </div>
            <div class="col-4 ps-0">
              <button type="button" class="btn bg-gradient-info mb-0 h-100 position-relative z-index-2">Subscribe</button>
            </div>
          </div>
        </div>
        <div class="col-md-5 ms-auto">
          <div class="position-relative">
            <img class="max-width-50 w-100 position-relative z-index-2" src="../assets/img/macbook.png" alt="image">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer pt-5 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-3 mb-4 ms-auto">
          <h6 class="font-weight-bolder mb-4">InsuranceWise</h6>
          <p>Your trusted AI-powered insurance comparison platform.</p>
        </div>

        <div class="col-md-2 col-sm-6 col-6 mb-4">
          <h6 class="text-sm">Company</h6>
          <ul class="flex-column ms-n3 nav">
            <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Careers</a></li>
          </ul>
        </div>

        <div class="col-md-2 col-sm-6 col-6 mb-4">
          <h6 class="text-sm">Legal</h6>
          <ul class="flex-column ms-n3 nav">
            <li class="nav-item"><a class="nav-link" href="#">Terms</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Privacy</a></li>
          </ul>
        </div>

        <div class="col-12 text-center mt-4">
          <p class="text-dark my-4 text-sm font-weight-normal">
            © <script>document.write(new Date().getFullYear())</script> InsuranceWise. All rights reserved.
          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Core JS -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/countup.min.js"></script>
  <script src="../assets/js/material-kit.min.js?v=3.1.0"></script>

  <script>
    // Animate numbers when visible
    document.addEventListener('scroll', function () {
      var elements = document.querySelectorAll('[countTo]');
      elements.forEach(function (el) {
        var rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight && !el.started) {
          el.started = true;
          var endVal = parseInt(el.getAttribute('countTo'));
          var countUp = new CountUp(el.id, endVal);
          countUp.start();
        }
      });
    });
  </script>

</body>
</html>
