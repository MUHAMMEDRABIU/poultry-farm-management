<?php
include '../config.php';

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['password_confirmation'] ?? '');
    $role = "user"; // Default role
    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $message = "<p class='text-red-600 text-center mt-4'>Error: All fields are required.</p>";
    } elseif (!preg_match("/^[0-9]{11,15}$/", $phone)) {
        $message = "<p class='text-red-600 text-center mt-4'>Error: Invalid phone number. Enter 11-15 digits.</p>";
    } elseif ($password !== $confirm_password) {
        $message = "<p class='text-red-600 text-center mt-4'>Error: Passwords do not match.</p>";
    } else {
        // Hash password for security
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        // Ensure email is unique
        $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $checkEmail->store_result();
        
        if ($checkEmail->num_rows > 0) {
            $message = "<p class='text-red-600 text-center mt-4'>Error: Email is already registered.</p>";
        } else {
            // Insert user into the database
            $sql = "INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $phone, $hashedPassword, $role);

            if ($stmt->execute()) {
                $message = "<p class='text-green-600 text-center mt-4'>User registered successfully! <a href='login.php' class='text-green-700 font-semibold hover:underline'>Login here</a>.</p>";
                $name = $email = $phone = ""; // Clear input fields after success
            } else {
                $message = "<p class='text-red-600 text-center mt-4'>Error registering user: " . addslashes($stmt->error) . "</p>";
            }
            // Close statement only if it's created
            if (isset($stmt)) {
                $stmt->close();
            }
        }
        $checkEmail->close();
    }
    $conn->close();
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body{
            background-image: image-set('ii.jpeg');
            background-repeat: no-repeat;
        }
    </style>
</head>
</head>
<body class="flex items-center justify-center min-h-screen bg-cover bg-center">
<div class="w-3/4 max-w-sm bg-white p-4 rounded-lg shadow-lg backdrop-blur-md bg-opacity-80">
    <h2 class="text-center text-2xl text-green-700 font-bold mb-2">Create an Account</h2>
    <p class="text-center text-gray-600 mb-4">Sign up to manage your farm efficiently</p>

    <form method="POST" action="Signup.php">
        <div class="mb-2">
            <label class="block text-gray-700 font-medium">Full Name</label>
            <input type="text" name="name" required class="w-full px-3 py-1.5 border rounded-md focus:ring focus:ring-green-300">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 font-medium">Email</label>
            <input type="email" name="email" required class="w-full px-3 py-1.5 border rounded-md focus:ring focus:ring-green-300">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 font-medium">Phone</label>
            <input type="tel" name="phone" required pattern="[0-9]{11,15}" class="w-full px-3 py-1.5 border rounded-md focus:ring focus:ring-green-300">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 font-medium">Password</label>
            <input type="password" name="password" required class="w-full px-3 py-1.5 border rounded-md focus:ring focus:ring-green-300">
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 font-medium">Confirm Password</label>
            <input type="password" name="password_confirmation" required class="w-full px-3 py-1.5 border rounded-md focus:ring focus:ring-green-300">
        </div>
        <div class="flex items-center justify-between mb-2">
            <a href="login.php" class="text-green-600 text-sm hover:text-green-800">Already registered?</a>
            <button type="submit" class="bg-green-600 text-white px-4 py-1.5 rounded-md hover:bg-green-700 transition-all">
                Register
            </button>
        </div>
    </form>
     <!-- Message Displayed Below the Form -->
        <?php if (!empty($message)): ?>
            <div class="mt-4 text-center"><?= $message ?></div>
        <?php endif; ?>

    </div>
</div>
</body>
</html>
