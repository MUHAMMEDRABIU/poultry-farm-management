<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include "../config.php";

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "DELETE FROM users WHERE id = $user_id";

    if ($conn->query($sql)) {
        header("Location: user_management.php?success=User deleted successfully");
        exit();
    } else {
        header("Location: user_management.php?error=Error deleting user");
        exit();
    }
} else {
    header("Location: user_management.php");
    exit();
}
?>
