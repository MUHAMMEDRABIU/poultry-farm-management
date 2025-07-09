<?php
session_start();
include '../config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request. No ID provided.");
}

$id = intval($_GET['id']);

$delete_sql = "DELETE FROM poultry_records WHERE id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("i", $id);

if ($delete_stmt->execute()) {
    $_SESSION['success_message'] = "Record deleted successfully!";
    header("Location: poultry_data.php");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}
?>
