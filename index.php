<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poultry Farm Management</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"> <!-- Link to external CSS -->
</head>

<body>
    <!-- Header Section -->
    <header class="p-2 text-white" style="background-color:#228B22;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h4><img src="assets/favicon.png" alt="favicon" class="favicon"> ADY Farm Limited </h4>

                <!-- Navbar -->
                <nav class="navbar navbar-expand-md">
                    <!-- Toggle button for small screens -->
                    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Collapsible navbar links -->
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
        </div>
    </header>


    <!-- Carousel Section -->
    <div id="poultryCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="carousel-caption">
                    <h1>Welcome to the Future of Poultry Management</h1>
                    <p class="text-white mb-4">
                        Discover an integrated platform designed to transform every aspect of your poultry operation.
                        Our system harnesses real-time data and intelligent automation to streamline workflows,
                        optimize feeding schedules, and deliver comprehensive performance insights—all in one user-friendly interface.
                    </p>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-caption">
                    <h1>Empower Your Poultry Farm</h1>
                    <p class="text-white mb-4">
                        Empower your poultry farm with a unified solution that delivers every essential tool in one accessible platform.
                    </p>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-caption">
                    <h1>Get Started Today and Take Your Poultry Management to the Next Level</h1>
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

    <!-- Footer Section -->
    <footer class="bg-light text-dark text-center p-3 mt-4">
        <p>&copy; <?php echo date("Y"); ?> Poultry Farm Management. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
