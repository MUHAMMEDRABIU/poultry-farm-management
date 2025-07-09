<?php
session_start();
include '../config.php'; // Database connection

$error_message = ""; // Variable to store login errors

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error_message = "Email and Password are required.";
    } else {
        // Fetch user from database
        $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user || !password_verify($password, $user['password'])) {
            $error_message = "Invalid email or password.";
        } else {
            // Store session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            // Redirect to dashboard
            header("Location:/page/dashboard.php");
            exit();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script> 
    <style>
        body{
            background-image: image-set('jj.jpeg');
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-cover bg-center">

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg border-t-4 border-green-600">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-green-700">Welcome Back</h2>
            <p class="text-gray-600 mt-2">Sign in to continue</p>
        </div>

        <form method="POST" action="" class="mt-6">
            <div class="mb-4">
                <label class="block text-green-700 font-semibold">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required 
                    class="w-full px-4 py-2 border border-green-300 rounded-md focus:ring focus:ring-green-400">
            </div>

            <div class="mb-4">
                <label class="block text-green-700 font-semibold">Password</label>
                <input type="password" name="password" required 
                    class="w-full px-4 py-2 border border-green-300 rounded-md focus:ring focus:ring-green-400">
                <?php if (!empty($error_message)): ?>
                    <p class="text-red-600 text-sm mt-2"><?= $error_message ?></p>
                <?php endif; ?>
            </div>

            <div class="flex items-center justify-between mb-4">
                <label class="flex items-center text-green-700">
                    <input type="checkbox" name="remember" class="mr-2 accent-green-600"> Remember me
                </label>
                <a href="forgot_password.php" class="text-green-600 text-sm hover:underline">Forgot Password?</a> 
            </div>
            <button type="submit" 
                class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition duration-300">
                Log In
            </button>
        </form>

        <p class="text-center text-gray-600 mt-4">
            Don't have an account? 
            <a href="Signup.php" class="text-green-700 font-semibold hover:underline">Sign Up</a>
        </p>
    </div>

</body>
</html>

    