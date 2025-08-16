<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Poultry Farm Management</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">

  <style>
    .carousel-item {
      height: 90vh;
      background-size: cover;
      background-position: center;
      position: relative;
    }

    .carousel-caption {
      background-color: rgba(0, 0, 0, 0.5);
      padding: 2rem;
      border-radius: 10px;
    }

    .carousel-caption h1,
    .carousel-caption p {
      color: #fff;
      text-shadow: 0 1px 3px rgba(0, 0, 0, 0.7);
    }

    .carousel-item-1 {
      background-image: url('assets/slide1.jpg');
    }

    .carousel-item-2 {
      background-image: url('assets/slide2.jpg');
    }

    .carousel-item-3 {
      background-image: url('assets/slide3.jpg');
    }

    .favicon {
      width: 32px;
      height: 32px;
      margin-right: 10px;
    }
  </style>
</head>

<body>
  <div class="d-flex flex-column min-vh-100"> <!-- Wrap everything in this container -->

    <!-- Header -->
    <header class="p-2 text-white" style="background-color:#228B22;">
      <div class="container d-flex justify-content-between align-items-center">
        <h4 class="mb-0 d-flex align-items-center">
          <img src="assets/favicon.png" alt="favicon" class="favicon" />
          ADY Farm Limited
        </h4>
        <nav class="navbar navbar-expand-md">
          <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item"><a class="nav-link text-white" href="page/dashboard.php">Home</a></li>
              <li class="nav-item"><a class="nav-link text-white" href="page/About.php">About</a></li>
              <li class="nav-item"><a class="nav-link text-white" href="page/contact.php">Contact</a></li>
              <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item"><a class="nav-link text-white" href="auth/logout.php">Logout</a></li>
              <?php else: ?>
                <li class="nav-item"><a class="nav-link text-white" href="auth/signup.php">Signup</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="auth/login.php">Login</a></li>
              <?php endif; ?>
            </ul>
          </div>
        </nav>
      </div>
    </header>

    <!-- Carousel -->
    <div id="poultryCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active carousel-item-1">
          <div class="d-flex h-100 align-items-center justify-content-center">
            <div class="carousel-caption text-center">
              <h1 class="display-4 fw-bold">Welcome to the Future of Poultry Management</h1>
              <p class="lead">Discover an integrated platform to automate, monitor, and manage all aspects of your poultry farm in one place.</p>
            </div>
          </div>
        </div>
        <div class="carousel-item carousel-item-2">
          <div class="d-flex h-100 align-items-center justify-content-center">
            <div class="carousel-caption text-center">
              <h1 class="display-5 fw-bold">Empower Your Poultry Farm</h1>
              <p class="lead">Gain full control of your farm with smart tools, feeding logs, vaccination tracking, and insightful reports.</p>
            </div>
          </div>
        </div>
        <div class="carousel-item carousel-item-3">
          <div class="d-flex h-100 align-items-center justify-content-center">
            <div class="carousel-caption text-center">
              <h1 class="display-5 fw-bold">Get Started Today</h1>
              <p class="lead">Join hundreds of farmers simplifying poultry operations with ADY Farm Manager.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Carousel Controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#poultryCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#poultryCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center text-muted py-3 mt-auto"> <!-- mt-auto pushes footer to bottom -->
      <p class="mb-0">&copy; <?= date('Y') ?> Poultry Farm Management. All Rights Reserved.</p>
    </footer>

  </div> <!-- End of main flex container -->

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>