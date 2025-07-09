<?php
session_start();

// Hardcoded admin credentials
$admin_username = "admin";
$admin_password = "admin123";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
         // Redirect to dashboard
        header("Location: admin_dashboard.php");

        exit();
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96 border-t-4 border-green-600">
        <h2 class="text-3xl font-bold text-green-700 text-center mb-4">Admin Login</h2>

        <?php if (isset($error)) : ?>
            <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label class="block text-green-700 font-semibold">Username:</label>
                <input type="text" name="username" 
                    class="w-full px-4 py-2 border border-green-300 rounded-md focus:ring focus:ring-green-400" 
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-green-700 font-semibold">Password:</label>
                <input type="password" name="password" 
                    class="w-full px-4 py-2 border border-green-300 rounded-md focus:ring focus:ring-green-400" 
                    required>
            </div>

            <button type="submit" 
                class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition duration-300">
                Login
            </button>
        </form>
    </div>

</body>
</html>
