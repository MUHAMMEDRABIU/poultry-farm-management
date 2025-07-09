<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../config.php'; // Database connection

// Handle Feeding Record Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_feeding_record'])) {
    $feed_type = isset($_POST['feed_type']) ? trim($_POST['feed_type']) : '';
    $quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : '';
    $feeding_time = isset($_POST['feeding_time']) ? trim($_POST['feeding_time']) : '';

    // Validate inputs
    if ($feed_type == '' || $quantity == '' || $feeding_time == '') {
        $_SESSION['error_message'] = "All fields are required!";
    } elseif (!is_numeric($quantity)) {
        $_SESSION['error_message'] = "Quantity must be a valid number!";
    } else {
        $stmt = $conn->prepare("INSERT INTO feeding_records (feed_type, quantity, feeding_time) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $feed_type, $quantity, $feeding_time);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Feeding record added successfully!";
        } else {
            $_SESSION['error_message'] = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    header("Location: feeding_system.php");
    exit();
}

// Fetch Feeding Records
$sql = "SELECT * FROM feeding_records ORDER BY feeding_time DESC";
$result = $conn->query($sql);

// Handle Feeding Schedule Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['set_feeding_schedule'])) {
    $time_of_day = isset($_POST['time_of_day']) ? trim($_POST['time_of_day']) : '';
    $food_type = isset($_POST['food_type']) ? trim($_POST['food_type']) : '';
    $feeding_time = isset($_POST['feeding_time']) ? trim($_POST['feeding_time']) : '';
    $quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : '';

    if ($time_of_day == '' || $food_type == '' || $feeding_time == '' || $quantity == '') {
        $_SESSION['error_message'] = "All fields are required!";
        header("Location: feeding_system.php");
        exit();
    }

    if (!is_numeric($quantity) || $quantity <= 0) {
        $_SESSION['error_message'] = "Quantity must be a valid number!";
        header("Location: feeding_system.php");
        exit();
    }

    // Check if schedule exists
    $check_query = "SELECT id FROM feeding_schedule WHERE time_of_day = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $time_of_day);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update existing schedule
        $update_query = "UPDATE feeding_schedule SET food_type = ?, feeding_time = ?, quantity = ? WHERE time_of_day = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssds", $food_type, $feeding_time, $quantity, $time_of_day);
    } else {
        // Insert new schedule
        $insert_query = "INSERT INTO feeding_schedule (time_of_day, food_type, feeding_time, quantity) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sssd", $time_of_day, $food_type, $feeding_time, $quantity);
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Feeding schedule updated successfully!";
    } else {
        $_SESSION['error_message'] = "Error updating feeding schedule!";
    }

    $stmt->close();
    header("Location: feeding_system.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Feeding Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
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
    
<body class="bg-gray-100">
    <!-- Display Feeding Records -->
<div class="bg-white shadow-md rounded-lg p-6 mt-6">
    <h3 class="text-xl font-bold text-gray-700 mb-4">Feeding Records</h3>
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 p-2">Feed Type</th>
                <th class="border border-gray-300 p-2">Quantity (kg)</th>
                <th class="border border-gray-300 p-2">Feeding Time</th>
                <th class="border border-gray-300 p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $result = $conn->query("SELECT * FROM feeding_records ORDER BY feeding_time DESC");
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($row['feed_type']); ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($row['quantity']); ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($row['feeding_time']); ?></td>
                    <td class="border border-gray-300 p-2">
                        <a href="edit_feeding.php?id=<?= $row['id']; ?>" class="text-blue-500">Edit</a> |
                        <a href="delete_feeding.php?id=<?= $row['id']; ?>" class="text-red-500" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Display Feeding Schedule -->
<div class="bg-white shadow-md rounded-lg p-6 mt-6">
    <h3 class="text-xl font-bold text-gray-700 mb-4">Feeding Schedule</h3>
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 p-2">Time of Day</th>
                <th class="border border-gray-300 p-2">Food Type</th>
                <th class="border border-gray-300 p-2">Feeding Time</th>
                <th class="border border-gray-300 p-2">Quantity (kg)</th>
                <th class="border border-gray-300 p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $schedule_result = $conn->query("SELECT * FROM feeding_schedule ORDER BY feeding_time ASC");
            while ($row = $schedule_result->fetch_assoc()) { ?>
                <tr>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($row['time_of_day']); ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($row['food_type']); ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($row['feeding_time']); ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($row['quantity']); ?></td>
                    <td class="border border-gray-300 p-2">
                        <a href="edit_schedule.php?id=<?= $row['id']; ?>" class="text-blue-500">Edit</a> |
                        <a href="delete_schedule.php?id=<?= $row['id']; ?>" class="text-red-500" onclick="return confirm('Are you sure you want to delete this schedule?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<div class="main-content ml-64 p-6">
<div class="topbar bg-white shadow p-4 rounded-lg flex justify-between items-center">
    <div class="main-content p-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Feeding Management</h2>

        <!-- Success and Error Messages -->
        <?php if (isset($_SESSION['success_message'])) { ?>
            <p class="bg-green-500 text-white p-2 rounded"> <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?> </p>
        <?php } ?>
        
        <?php if (isset($_SESSION['error_message'])) { ?>
            <p class="bg-red-500 text-white p-2 rounded"> <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?> </p>
        <?php } ?>

        <!-- Add Feeding Record Form -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <h3 class="text-xl font-bold text-gray-700 mb-4">Add Feeding Record</h3>
            <form method="POST" action="">
                <input type="hidden" name="add_feeding_record">
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="feed_type" placeholder="Feed Type" class="border p-2 rounded" required>
                    <input type="number" step="0.01" name="quantity" placeholder="Quantity (kg)" class="border p-2 rounded" required>
                    <input type="datetime-local" name="feeding_time" class="border p-2 rounded" required>
                </div>
                <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Add Record</button>
            </form>
        </div>

        <!-- Feeding Schedule Form -->
        <div class="bg-white shadow-md rounded-lg p-6 mt-6">
            <h3 class="text-xl font-bold text-gray-700 mb-4">Set Feeding Schedule</h3>
            <form method="POST" action="">
                <input type="hidden" name="set_feeding_schedule">
                <label class="block text-gray-700">Time of Day</label>
                <select name="time_of_day" class="w-full border p-2 rounded" required>
                    <option value="morning">Morning</option>
                    <option value="afternoon">Afternoon</option>
                    <option value="evening">Evening</option>
                </select>
                <label class="block text-gray-700 mt-4">Food Type</label>
                <input type="text" name="food_type" class="w-full border p-2 rounded" required>
                <label class="block text-gray-700 mt-4">Feeding Time</label>
                <input type="time" name="feeding_time" class="w-full border p-2 rounded" required>
                <label class="block text-gray-700 mt-4">Quantity (kg)</label>
                <input type="number" step="0.01" name="quantity" class="w-full border p-2 rounded" required>
                <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Save Feeding Schedule</button>
            </form>
        </div>
    </div>
    
</body>
</html>
