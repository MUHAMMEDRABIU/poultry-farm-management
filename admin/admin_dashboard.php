<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include "../config.php"; // Ensure database connection

// Check if connection is valid
if (!$conn) {
    die("Database connection failed: " . 'mysqli_connect_error'());
}

// Fetch current month and year
$current_month = date('m');
$current_year = date('Y');

// Fetch stored overview data
$query = $conn->prepare("SELECT * FROM farm_overview WHERE month = ? AND year = ?");
$query->bind_param("ii", $current_month, $current_year);
$query->execute();
$result = $query->get_result();
$overview = $result->fetch_assoc();

// Set default values if no record exists
$total_poultry = $overview['total_poultry'] ?? 0;
$total_feed = $overview['total_feed'] ?? 0.00;
$total_expenses = $overview['total_expenses'] ?? 0.00;
$total_revenue = $overview['total_revenue'] ?? 0.00;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .sidebar {
            transition: width 0.3s ease-in-out;
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .sidebar.collapsed span {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Sidebar -->
    <div class="sidebar fixed top-0 left-0 h-full bg-gray-900 text-white w-64 p-4">
        <button id="toggleSidebar" class="mb-4 text-white focus:outline-none">
            <i class="ph ph-list text-2xl"></i>
        </button>
        <div class="logo text-center text-xl font-bold border-b border-gray-700 pb-2">
            Admin Panel
        </div>
        <nav class="mt-4">
            <a href="admin_dashboard.php" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-700">
                <i class="ph ph-gauge"></i> <span>Dashboard</span>
            </a>
            
            <a href="poultry_data.php" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-700">
                <i class="ph ph-bird"></i> <span>poultry_data  </span>
            </a>
            <a href="reports.php" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-700">
                <i class="ph ph-chart-line"></i> <span>Report Management</span>
            </a>
            <a href="overview.php" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-700">
                <i class="ph ph-eye"></i> <span>Overview</span>
            </a>
            <a href="feeding_system.php" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-700">
                <i class="ph ph-fork-knife"></i> <span>Feeding System</span>
            </a>
            <a href="user_management.php" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-700">
                <i class="ph ph-users"></i> <span>User Management</span>
            </a>
            <a href="logout.php" class="flex items-center gap-2 px-4 py-3 text-red-400 hover:bg-red-600 hover:text-white">
                <i class="ph ph-sign-out"></i> <span>Logout</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-6 transition-all duration-300">
        <h1 class="text-3xl font-bold text-gray-800">Welcome to Admin Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage your farm's operations efficiently.</p>
    </div>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>

</body>
</html>



