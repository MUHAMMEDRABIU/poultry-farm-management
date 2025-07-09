<?php
session_start();
include "../config.php"; // Database connection

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch the latest report data
$query = "SELECT * FROM farm_reports ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$farm_data = mysqli_fetch_assoc($result);

$total_birds = $farm_data['total_birds'] ?? 0;
$total_feed_used = $farm_data['total_feed_used'] ?? 0.00;
$total_expenses = $farm_data['total_expenses'] ?? 0.00;
$total_sales = $farm_data['total_sales'] ?? 0.00;
$pending_vaccinations = $farm_data['pending_vaccinations'] ?? 0;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $total_birds = $_POST['total_birds'];
    $total_feed_used = $_POST['total_feed_used'];
    $total_expenses = $_POST['total_expenses'];
    $total_sales = $_POST['total_sales'];
    $pending_vaccinations = $_POST['pending_vaccinations'];

    // Check if data exists, then update or insert
    $check_query = "SELECT * FROM farm_reports LIMIT 1";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $update_query = "UPDATE farm_reports SET 
            total_birds = '$total_birds', 
            total_feed_used = '$total_feed_used',
            total_expenses = '$total_expenses', 
            total_sales = '$total_sales', 
            pending_vaccinations = '$pending_vaccinations'";

        mysqli_query($conn, $update_query);
    } else {
        $insert_query = "INSERT INTO farm_reports (total_birds, total_feed_used, total_expenses, total_sales, pending_vaccinations) 
            VALUES ('$total_birds', '$total_feed_used', '$total_expenses', '$total_sales', '$pending_vaccinations')";
        
        mysqli_query($conn, $insert_query);
    }

    $_SESSION['update_message'] = "Farm report updated successfully!";
    header("Location: admin_dashboard.php"); // Redirect to refresh data
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Update Farm Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <i class="ph ph-chart-line"></i> <span>poultry_data </span>
            </a>
            <a href="reports.php" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-700">
                <i class="ph ph-chart-line"></i> <span>Report </span>
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
    <div class="main-content ml-64 p-6">
    <div class="topbar bg-white shadow p-4 rounded-lg flex justify-between items-center">
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Update Farm Performance Reports</h2>

        <form method="POST" class="bg-white p-6 rounded shadow">
            <label class="block text-gray-700">Total Birds</label>
            <input type="number" name="total_birds" value="<?= $total_birds ?>" class="w-full p-2 border rounded mb-4">

            <label class="block text-gray-700">Total Feed Used (kg)</label>
            <input type="number" step="0.01" name="total_feed_used" value="<?= $total_feed_used ?>" class="w-full p-2 border rounded mb-4">

            <label class="block text-gray-700">Total Expenses (₦)</label>
            <input type="number" step="0.01" name="total_expenses" value="<?= $total_expenses ?>" class="w-full p-2 border rounded mb-4">

            <label class="block text-gray-700">Total Sales (₦)</label>
            <input type="number" step="0.01" name="total_sales" value="<?= $total_sales ?>" class="w-full p-2 border rounded mb-4">

            <label class="block text-gray-700">Pending Vaccinations</label>
            <input type="number" name="pending_vaccinations" value="<?= $pending_vaccinations ?>" class="w-full p-2 border rounded mb-4">

            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Update</button>
        </form>
    </div>
</body>
</html>
