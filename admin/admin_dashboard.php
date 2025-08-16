<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include "../config.php";

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$current_month = date('m');
$current_year = date('Y');

$query = $conn->prepare("SELECT * FROM farm_overview WHERE month = ? AND year = ?");
$query->bind_param("ii", $current_month, $current_year);
$query->execute();
$result = $query->get_result();
$overview = $result->fetch_assoc();
                                // Replace dynamic fetch with static demo values
                                $total_poultry = 2750;
                                $total_feed = 1540.75;
                                $total_expenses = 375000.00;
                                $total_revenue = 520000.00;
                                $profit = $total_revenue - $total_expenses;

                                ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <a href="admin_dashboard.php" class="flex items-center gap-2 px-4 py-3 bg-gray-700 rounded">
                <i class="ph ph-gauge"></i> <span>Dashboard</span>
            </a>
            <a href="poultry_data.php" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-700">
                <i class="ph ph-bird"></i> <span>Poultry Data</span>
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
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Welcome to Admin Dashboard</h1>
            <p class="text-gray-600 mt-1">Overview of your farm’s performance this month</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <h4 class="text-gray-600 text-sm">Total Poultry</h4>
                <p class="text-2xl font-bold text-green-600"><?= number_format($total_poultry) ?></p>
                <i class="ph ph-bird text-green-500 text-xl mt-2"></i>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                <h4 class="text-gray-600 text-sm">Total Feed (kg)</h4>
                <p class="text-2xl font-bold text-yellow-600"><?= number_format($total_feed, 2) ?></p>
                <i class="ph ph-fork-knife text-yellow-500 text-xl mt-2"></i>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                <h4 class="text-gray-600 text-sm">Total Expenses (₦)</h4>
                <p class="text-2xl font-bold text-red-600"><?= number_format($total_expenses, 2) ?></p>
                <i class="ph ph-money text-red-500 text-xl mt-2"></i>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <h4 class="text-gray-600 text-sm">Total Revenue (₦)</h4>
                <p class="text-2xl font-bold text-blue-600"><?= number_format($total_revenue, 2) ?></p>
                <i class="ph ph-currency-circle-dollar text-blue-500 text-xl mt-2"></i>
            </div>
        </div>

        <!-- Profit Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-700">Net Profit This Month</h2>
            <p class="text-3xl font-bold text-indigo-700 mt-2">₦<?= number_format($profit, 2) ?></p>
        </div>

        <!-- Chart Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Pie Chart -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Revenue vs Expenses</h3>
                <canvas id="pieChart"></canvas>
            </div>

            <!-- Bar Chart -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Poultry vs Feed (Monthly)</h3>
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>

    <!-- JavaScript for Sidebar and Charts -->
    <script>
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });

        // Pie Chart
        new Chart(document.getElementById("pieChart"), {
            type: "pie",
            data: {
                labels: ["Revenue", "Expenses"],
                datasets: [{
                    backgroundColor: ["#3b82f6", "#ef4444"],
                    data: [<?= $total_revenue ?>, <?= $total_expenses ?>]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Bar Chart
        new Chart(document.getElementById("barChart"), {
            type: "bar",
            data: {
                labels: ["<?= date('F') ?>"],
                datasets: [{
                        label: "Poultry",
                        backgroundColor: "#10b981",
                        data: [<?= $total_poultry ?>]
                    },
                    {
                        label: "Feed (kg)",
                        backgroundColor: "#facc15",
                        data: [<?= $total_feed ?>]
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>

</html>