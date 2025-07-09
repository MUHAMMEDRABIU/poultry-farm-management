<?php
session_start();
include '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM feeding_records WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Record deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting record!";
    }
}

header("Location: feeding_system.php");
exit();
?>
