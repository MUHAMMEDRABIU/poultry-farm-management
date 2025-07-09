<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /auth/login.php");
    exit();
}

include "../config.php";

$user_name = $_SESSION['user_name'] ?? 'Farmer';
$current_month = date('m');
$current_year = date('Y');

// Real-time summary
$total_poultry = $conn->query("SELECT COUNT(*) as count FROM poultry WHERE MONTH(date_added) = $current_month AND YEAR(date_added) = $current_year")->fetch_assoc()['count'] ?? 0;
$total_feed = $conn->query("SELECT SUM(quantity) as total FROM feed_inventory WHERE MONTH(purchase_date) = $current_month AND YEAR(purchase_date) = $current_year")->fetch_assoc()['total'] ?? 0;
$total_expenses = $conn->query("SELECT SUM(amount) as total FROM expenses WHERE MONTH(date) = $current_month AND YEAR(date) = $current_year")->fetch_assoc()['total'] ?? 0;
$total_revenue = $conn->query("SELECT SUM(amount) as total FROM sales WHERE MONTH(date) = $current_month AND YEAR(date) = $current_year")->fetch_assoc()['total'] ?? 0;
$pending_vaccinations = $conn->query("SELECT COUNT(*) as count FROM vaccinations WHERE status = 'pending' AND MONTH(date) = $current_month AND YEAR(date) = $current_year")->fetch_assoc()['count'] ?? 0;

// Farm report (summary)
$farm_data = $conn->query("SELECT * FROM farm_reports ORDER BY id DESC LIMIT 1")->fetch_assoc();
$total_birds = $farm_data['total_birds'] ?? 0;
$total_feed_used = $farm_data['total_feed_used'] ?? 0.00;
$total_expenses_report = $farm_data['total_expenses'] ?? 0.00;
$total_sales = $farm_data['total_sales'] ?? 0.00;
$pending_vaccines = $farm_data['pending_vaccinations'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Poultry Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs" defer></script>
  <link href="https://unpkg.com/@tabler/icons@latest/iconfont/tabler-icons.min.css" rel="stylesheet" />
</head>
<body class="bg-green-50 text-gray-800">

  <!-- Header -->
  <header class="bg-green-700 text-white shadow py-4">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center px-6 space-y-2 md:space-y-0">
      <div class="flex items-center gap-4">
        <h1 class="text-lg md:text-2xl font-semibold">Welcome, <?= htmlspecialchars($user_name) ?> 👋</h1>
        <span class="text-sm text-green-100 hidden sm:inline" id="datetime"></span>
      </div>

      <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="bg-white text-green-700 px-4 py-2 rounded shadow hover:bg-green-100 transition">
          Menu <i class="ti ti-chevron-down ml-1"></i>
        </button>
        <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-white text-gray-800 shadow-lg rounded z-50">
          <a href="profile.php" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
          <a href="settings.php" class="block px-4 py-2 hover:bg-gray-100">Settings</a>
          <a href="../auth/logout.php" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Logout</a>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <div class="py-10">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

      <!-- Navigation Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-white hover:shadow-xl shadow rounded-lg p-6 transition">
          <h3 class="text-lg font-semibold text-green-800">Poultry Data</h3>
          <p class="text-sm text-gray-500">Manage feed, vaccines, and health.</p>
          <a href="poultry_data.php" class="block mt-4 text-green-600 hover:text-green-800">View Poultry Data →</a>
        </div>
        <div class="bg-white hover:shadow-xl shadow rounded-lg p-6 transition">
          <h3 class="text-lg font-semibold text-green-800">Feeding System</h3>
          <p class="text-sm text-gray-500">Monitor and automate feed usage.</p>
          <a href="feeding_system.php" class="block mt-4 text-green-600 hover:text-green-800">View Feeding System →</a>
        </div>
        <div class="bg-white hover:shadow-xl shadow rounded-lg p-6 transition">
          <h3 class="text-lg font-semibold text-green-800">Reports</h3>
          <p class="text-sm text-gray-500">Track farm performance reports.</p>
          <a href="reports.php" class="block mt-4 text-green-600 hover:text-green-800">View Reports →</a>
        </div>
      </div>

      <!-- Overview Section -->
      <div class="mt-10 bg-white shadow-lg rounded-lg p-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Farm Overview</h3>
        <p class="text-sm text-gray-500 mb-6">Summary for <?= date('F Y') ?></p>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div class="bg-blue-500 text-white p-4 rounded-lg">
            <h4 class="text-lg font-semibold">Total Birds</h4>
            <p class="text-2xl font-bold"><?= number_format($total_birds) ?></p>
          </div>
          <div class="bg-green-500 text-white p-4 rounded-lg">
            <h4 class="text-lg font-semibold">Feed Used (kg)</h4>
            <p class="text-2xl font-bold"><?= number_format($total_feed_used, 2) ?> kg</p>
          </div>
          <div class="bg-yellow-500 text-white p-4 rounded-lg">
            <h4 class="text-lg font-semibold">Expenses</h4>
            <p class="text-2xl font-bold">&#8358;<?= number_format($total_expenses_report, 2) ?></p>
          </div>
          <div class="bg-red-500 text-white p-4 rounded-lg">
            <h4 class="text-lg font-semibold">Sales</h4>
            <p class="text-2xl font-bold">&#8358;<?= number_format($total_sales, 2) ?></p>
          </div>
          <div class="bg-purple-500 text-white p-4 rounded-lg col-span-full md:col-span-1">
            <h4 class="text-lg font-semibold">Pending Vaccinations</h4>
            <p class="text-2xl font-bold"><?= number_format($pending_vaccines) ?></p>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Date/Time Script -->
  <script>
    function updateTime() {
      const now = new Date();
      const dateStr = now.toLocaleDateString(undefined, { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
      const timeStr = now.toLocaleTimeString();
      document.getElementById('datetime').textContent = `${dateStr} | ${timeStr}`;
    }
    updateTime();
    setInterval(updateTime, 1000);
  </script>

</body>
</html>
