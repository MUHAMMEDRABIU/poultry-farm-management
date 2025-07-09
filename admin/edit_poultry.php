<?php
session_start();
include '../config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request. No ID provided.");
}

$id = intval($_GET['id']);

// Fetch the current poultry record
$sql = "SELECT * FROM poultry_records WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Record not found.");
}

// Handle form submission for updating
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bird_type = $_POST['bird_type'];
    $quantity = $_POST['quantity'];
    $feed_stock = $_POST['feed_stock'];
    $last_vaccination = $_POST['last_vaccination'];
    $expenses = $_POST['expenses'];

    $update_sql = "UPDATE poultry_records SET 
                   bird_type = ?, 
                   quantity = ?, 
                   feed_stock = ?, 
                   last_vaccination = ?, 
                   expenses = ? 
                   WHERE id = ?";

    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sisssi", $bird_type, $quantity, $feed_stock, $last_vaccination, $expenses, $id);

    if ($update_stmt->execute()) {
        $_SESSION['success_message'] = "Record updated successfully!";
        header("Location: poultry_data.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Poultry Record</title>
</head>
<body>
    <h2>Edit Poultry Record</h2>
    <form method="POST">
        <label>Bird Type:</label>
        <input type="text" name="bird_type" value="<?= htmlspecialchars($row['bird_type']) ?>" required><br>

        <label>Quantity:</label>
        <input type="number" name="quantity" value="<?= $row['quantity'] ?>" required><br>

        <label>Feed Stock:</label>
        <input type="number" step="0.01" name="feed_stock" value="<?= $row['feed_stock'] ?>" required><br>

        <label>Last Vaccination:</label>
        <input type="date" name="last_vaccination" value="<?= $row['last_vaccination'] ?>"><br>

        <label>Expenses:</label>
        <input type="number" step="0.01" name="expenses" value="<?= $row['expenses'] ?>" required><br>

        <button type="submit">Update Record</button>
    </form>
</body>
</html>
