<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id'])) {
    'header'("Location: /auth/login.php"); // Redirect if not logged in
    exit();
}

include "../config.php";

// Get current month and year
$current_month = date('m');
$current_year = date('Y');

// Fetch farm data for the current month
$total_poultry = $conn->query("SELECT COUNT(*) as count FROM poultry WHERE MONTH(date_added) = $current_month AND YEAR(date_added) = $current_year")->fetch_assoc()['count'] ?? 0;

$total_feed = $conn->query("SELECT SUM(quantity) as total FROM feed_inventory WHERE MONTH(purchase_date) = $current_month AND YEAR(purchase_date) = $current_year")->fetch_assoc()['total'] ?? 0;

$total_expenses = $conn->query("SELECT SUM(amount) as total FROM expenses WHERE MONTH(date) = $current_month AND YEAR(date) = $current_year")->fetch_assoc()['total'] ?? 0;

$total_revenue = $conn->query("SELECT SUM(amount) as total FROM sales WHERE MONTH(date) = $current_month AND YEAR(date) = $current_year")->fetch_assoc()['total'] ?? 0;

$pending_vaccinations = $conn->query("SELECT COUNT(*) as count FROM vaccinations WHERE status = 'pending' AND MONTH(date) = $current_month AND YEAR(date) = $current_year")->fetch_assoc()['count'] ?? 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-green-100"> <!-- Light green for farm feel -->
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="py-4 px-6 flex justify-between items-center">
                <h2 class="text-2xl font-semibold text-green-900">Dashboard</h2>
                <a href="../auth/logout.php" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Logout</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700">Poultry Data</h3>
                    <p class="text-sm text-gray-500">Manage your poultry feed, vaccines, and medications.</p>
                    <a href="poultry_data.php" class="block mt-4 text-green-600 hover:text-green-800"> View Poultry Data →</a>
                </div>
                
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700">Feeding System</h3>
                    <p class="text-sm text-gray-500">Set feeding schedule, monitor feed consumption.</p>
                    <a href="feeding_system.php" class="block mt-4 text-green-600 hover:text-green-800">View Feeding System →</a>
                </div>

                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700">Reports</h3>
                    <p class="text-sm text-gray-500">Analyze farm performance and generate detailed reports.</p>
                    <a href="reports.php" class="block mt-4 text-green-600 hover:text-green-800">View Reports →</a>
                </div>
            </div>
            <!-- Overview Section -->
            <div class="mt-10 bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-700">Farm Overview</h3>
                <p class="text-sm text-gray-500">Here’s a summary of your poultry farm activities for this month.</p>
                
                <?php

// Fetch the latest report data
$query = "SELECT * FROM farm_reports ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$farm_data = mysqli_fetch_assoc($result);

// Handle undefined variables
$total_birds = $farm_data['total_birds'] ?? 0;
$total_feed_used = $farm_data['total_feed_used'] ?? 0.00;
$total_expenses = $farm_data['total_expenses'] ?? 0.00;
$total_sales = $farm_data['total_sales'] ?? 0.00;
$pending_vaccinations = $farm_data['pending_vaccinations'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Farm Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Farm Performance Reports</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
            <div class="bg-blue-500 text-white p-4 rounded-lg">
                <h4 class="text-lg font-semibold">Total Birds</h4>
                <p class="text-2xl font-bold"><?= number_format($total_birds) ?></p>
            </div>

            <div class="bg-green-500 text-white p-4 rounded-lg">
                <h4 class="text-lg font-semibold">Total Feed Used (kg)</h4>
                <p class="text-2xl font-bold"><?= number_format($total_feed_used, 2) ?> kg</p>
            </div>

            <div class="bg-yellow-500 text-white p-4 rounded-lg">
                <h4 class="text-lg font-semibold">Total Expenses</h4>
                <p class="text-2xl font-bold">&#8358;<?= number_format($total_expenses, 2) ?></p>
            </div>

            <div class="bg-red-500 text-white p-4 rounded-lg">
                <h4 class="text-lg font-semibold">Total Sales</h4>
                <p class="text-2xl font-bold">&#8358;<?= number_format($total_sales, 2) ?></p>
            </div>

            <div class="bg-purple-500 text-white p-4 rounded-lg">
                <h4 class="text-lg font-semibold">Pending Vaccinations</h4>
                <p class="text-2xl font-bold"><?= number_format($pending_vaccinations) ?></p>
            </div>
        </div>

        
          
      
    </div>
</body>
</html>
