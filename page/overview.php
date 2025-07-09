<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $month = $_POST['month'];
    $year = $_POST['year'];
    $total_poultry = $_POST['total_poultry'];
    $total_feed = $_POST['total_feed'];
    $total_expenses = $_POST['total_expenses'];
    $total_revenue = $_POST['total_revenue'];
    $pending_vaccinations = $_POST['pending_vaccinations'];

    // Insert or update data
    $query = "INSERT INTO farm_overview (month, year, total_poultry, total_feed, total_expenses, total_revenue, pending_vaccinations) 
              VALUES (?, ?, ?, ?, ?, ?, ?)
              ON DUPLICATE KEY UPDATE 
              total_poultry = VALUES(total_poultry), 
              total_feed = VALUES(total_feed), 
              total_expenses = VALUES(total_expenses), 
              total_revenue = VALUES(total_revenue), 
              pending_vaccinations = VALUES(pending_vaccinations)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiiiii", $month, $year, $total_poultry, $total_feed, $total_expenses, $total_revenue, $pending_vaccinations);
    if ($stmt->execute()) {
        $success = "Farm overview updated successfully!";
    } else {
        $error = "Error updating overview: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Farm Overview</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold">Update Farm Overview</h2>
        
        <?php if (isset($success)) echo "<p class='text-green-600'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='text-red-600'>$error</p>"; ?>

        <form method="POST">
            <label class="block mt-2">Month:</label>
            <select name="month" class="border p-2 w-full">
                <?php for ($i = 1; $i <= 12; $i++) echo "<option value='$i'>$i</option>"; ?>
            </select>

            <label class="block mt-2">Year:</label>
            <select name="year" class="border p-2 w-full">
                <?php for ($y = date('Y') - 5; $y <= date('Y'); $y++) echo "<option value='$y'>$y</option>"; ?>
            </select>

            <label class="block mt-2">Total Poultry:</label>
            <input type="number" name="total_poultry" class="border p-2 w-full">

            <label class="block mt-2">Total Feed (kg):</label>
            <input type="number" step="0.01" name="total_feed" class="border p-2 w-full">

            <label class="block mt-2">Total Expenses (₦):</label>
            <input type="number" step="0.01" name="total_expenses" class="border p-2 w-full">

            <label class="block mt-2">Total Revenue (₦):</label>
            <input type="number" step="0.01" name="total_revenue" class="border p-2 w-full">

            <label class="block mt-2">Pending Vaccinations:</label>
            <input type="number" name="pending_vaccinations" class="border p-2 w-full">

            <button type="submit" class="mt-4 bg-green-600 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</body>
</html>
