<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../config.php'; // Database connection

// Handle form submission to add poultry data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bird_type = $_POST['bird_type'];
    $quantity = $_POST['quantity'];
    $feed_stock = $_POST['feed_stock'];
    $expenses = $_POST['expenses'];
  

    $sql = "INSERT INTO poultry_records (bird_type, quantity, feed_stock,  expenses) 
            VALUES ( '$bird_type', '$quantity', '$feed_stock',  '$expenses')";
    
    if ($conn->query($sql)) {
        $_SESSION['success_message'] = "Poultry record added successfully!";
    } else {
        $_SESSION['error_message'] = "Error: " . $conn->error;
    }

    header("Location: poultry_data.php");
    exit();
}

// Fetch existing poultry records
$sql = "SELECT * FROM poultry_records ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Poultry Management</title>
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
                <i class="ph ph-chart-line"></i> <span>poultry_data  Management</span>
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
        <?php if (isset($_SESSION['success_message'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline"><?php echo $_SESSION['success_message']; ?></span>
        <?php unset($_SESSION['success_message']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline"><?php echo $_SESSION['error_message']; ?></span>
        <?php unset($_SESSION['error_message']); ?>
    </div>
<?php endif; ?>

        <h2 class="text-3xl font-bold text-gray-800 mb-4">Poultry Management</h2>

        <!-- Add Poultry Record Form -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <h3 class="text-xl font-bold text-gray-700 mb-4">Add Poultry Record</h3>
            <form method="POST" action="">
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="bird_type" placeholder="Bird Type" class="border p-2 rounded" required>
                    <input type="number" name="quantity" placeholder="Quantity" class="border p-2 rounded" required>
                    <input type="number" step="0.01" name="feed_stock" placeholder="Feed Stock (kg)" class="border p-2 rounded" required>
                    <input type="date" name="last_vaccination" class="border p-2 rounded">
                    <input type="number" step="0.01" name="expenses" placeholder="Expenses (₦)" class="border p-2 rounded" required>
                </div>
                <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Add Record</button>
            </form>
        </div>

        <!-- Poultry Data Table -->
        <div class="bg-white shadow-md rounded-lg p-4">
            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">ID</th>
                        <th class="border p-2">Bird Type</th>
                        <th class="border p-2">Quantity</th>
                        <th class="border p-2">Feed Stock</th>
                        <th class="border p-2">Expenses</th>
                        <th class="border p-2">Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr class="bg-white hover:bg-gray-100">
                            <td class="border p-2"><?= $row['id'] ?></td>
                            <td class="border p-2"><?= $row['bird_type'] ?></td>
                            <td class="border p-2"><?= $row['quantity'] ?></td>
                            <td class="border p-2"><?= $row['feed_stock'] ?> kg</td>
                            <td class="border p-2">₦<?= number_format($row['expenses'], 2) ?></td>
                            <td class="border p-2"><?= $row['created_at'] ?></td>
                            <td class="border p-2">
                                <a href="edit_poultry.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                                |
                                <a href="delete_poultry.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
