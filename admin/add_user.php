<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include "../config.php"; // Database connection

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : ''; // Check if phone is set
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // Check if fields are empty
    if (empty($username) || empty($email) || empty($phone) || empty($password) || empty($role)) {
        $error = "All fields are required!";
    } else {
        // Hash password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into database using prepared statement
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $phone, $hashed_password, $role);

        if ($stmt->execute()) {
            header("Location: user_management.php?success=User added successfully");
            exit();
        } else {
            $error = "Error adding user: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="main-content mx-auto p-6 w-1/2">
        <h2 class="text-xl font-semibold text-gray-700">Add New User</h2>
        <?php if (isset($error)) { echo "<p class='text-red-500'>$error</p>"; } ?>

        <form method="POST" class="bg-white p-6 rounded-lg shadow mt-4">
            <div class="mb-4">
                <label class="block text-gray-700">Username</label>
                <input type="text" name="username" required class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" required class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Phone</label>
                <input type="text" name="phone" required class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" required class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Role</label>
                <select name="role" class="w-full p-2 border rounded">
                    <option value="admin">Admin</option>
                    <option value="farmer">Farmer</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add User</button>
            <a href="user_management.php" class="text-gray-500 ml-4">Cancel</a>
        </form>
    </div>
</body>
</html>
