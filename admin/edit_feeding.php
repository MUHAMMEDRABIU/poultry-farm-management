<?php
session_start();
include '../config.php';

if (!isset($_GET['id'])) {
    header("Location: feeding_system.php");
    exit();
}

$id = $_GET['id'];

// Fetch current record
$stmt = $conn->prepare("SELECT * FROM feeding_records WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();

if (!$record) {
    $_SESSION['error_message'] = "Record not found!";
    header("Location: feeding_system.php");
    exit();
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feed_type = $_POST['feed_type'];
    $quantity = $_POST['quantity'];
    $feeding_time = $_POST['feeding_time'];

    $update_stmt = $conn->prepare("UPDATE feeding_records SET feed_type = ?, quantity = ?, feeding_time = ? WHERE id = ?");
    $update_stmt->bind_param("sdsi", $feed_type, $quantity, $feeding_time, $id);

    if ($update_stmt->execute()) {
        $_SESSION['success_message'] = "Record updated successfully!";
    } else {
        $_SESSION['error_message'] = "Error updating record!";
    }

    header("Location: feeding_system.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Feeding Record</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="main-content ml-64 p-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">Edit Feeding Record</h2>
    <form method="POST">
        <label class="block">Feed Type</label>
        <input type="text" name="feed_type" value="<?= htmlspecialchars($record['feed_type']); ?>" class="border p-2 w-full rounded" required>

        <label class="block mt-4">Quantity (kg)</label>
        <input type="number" step="0.01" name="quantity" value="<?= htmlspecialchars($record['quantity']); ?>" class="border p-2 w-full rounded" required>

        <label class="block mt-4">Feeding Time</label>
        <input type="datetime-local" name="feeding_time" value="<?= htmlspecialchars($record['feeding_time']); ?>" class="border p-2 w-full rounded" required>

        <button type="submit" class="mt-4 bg-green-600 text-white px-4 py-2 rounded">Update Record</button>
    </form>
</div>
</body>
</html>
