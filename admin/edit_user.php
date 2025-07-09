<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include "../config.php"; // Database connection

// Get user details
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM users WHERE id = $user_id");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die("User not found.");
    }
} else {
    die("Invalid user ID.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);

    $sql = "UPDATE users SET name = '$username', email = '$email', role = '$role' WHERE id = $user_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: user_management.php?success=User updated successfully");
        exit();
    } else {
        $error = "Error updating user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="main-content mx-auto p-6 w-1/2">
        <h2 class="text-xl font-semibold text-gray-700">Edit User</h2>
        <?php if (isset($error)) { echo "<p class='text-red-500'>$error</p>"; } ?>
        
        <form method="POST" class="bg-white p-6 rounded-lg shadow mt-4">
            <div class="mb-4">
                <label class="block text-gray-700">Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['name']) ?>" required class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Role</label>
                <select name="role" class="w-full p-2 border rounded">
                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="farmer" <?= $user['role'] == 'farmer' ? 'selected' : '' ?>>Farmer</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update User</button>
            <a href="user_management.php" class="text-gray-500 ml-4">Cancel</a>
        </form>
    </div>
</body>
</html>
