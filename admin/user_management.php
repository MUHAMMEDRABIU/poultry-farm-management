<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
include "../config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
                <i class="ph ph-chart-line"></i> <span>poultry_data  </span>
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

    <div class="main-content ml-64 p-6">
        <div class="topbar bg-white shadow p-4 rounded-lg flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-700">User Management</h2>
            <a href="add_user.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Add User</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow mt-6">
            <h3 class="text-lg font-semibold text-gray-700">Users List</h3>
            <table class="w-full mt-4">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2 text-left">ID</th>
                        <th class="p-2 text-left">Username</th>
                        <th class="p-2 text-left">Email</th>
                        <th class="p-2 text-left">Role</th>
                        <th class="p-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM users");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='border-b'>";
                        echo "<td class='p-2'>{$row['id']}</td>";
                        echo "<td class='p-2'>{$row['name']}</td>";
                        echo "<td class='p-2'>{$row['email']}</td>";
                        echo "<td class='p-2'>{$row['role']}</td>";
                        echo "<td class='p-2'>
                            <a href='edit_user.php?id={$row['id']}' class='text-blue-500'>Edit</a> | 
                            <a href='delete_user.php?id={$row['id']}' class='text-red-500'>Delete</a>
                        </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
