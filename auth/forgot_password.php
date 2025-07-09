<?php
include '../config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');

    // Check if email is empty
    if (empty($email)) {
        die("<script>alert('Error: Email is required.'); window.history.back();</script>");
    }

    // Check if the email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        die("<script>alert('Error: Email not found.'); window.history.back();</script>");
    }

    // Generate a reset token
    $reset_token = bin2hex(random_bytes(32));
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
    $stmt->bind_param("ss", $reset_token, $email);

    if ($stmt->execute()) {
        echo "<script>alert('Password reset link has been sent to your email.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error updating reset token.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}

//Handle password reset
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        die("<script>alert('Passwords do not match.'); window.history.back();</script>");
    }

    // Check if token is valid
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Update password and clear reset token
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $user['id']);
        $stmt->execute();

        echo "<script>alert('Password updated successfully!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Invalid or expired reset link.'); window.location.href='forgot_password.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-center text-2xl text-green-700 font-semibold mt-2">Reset Your Password</h2>
        <p class="text-center text-gray-600 mb-4">Enter your email to receive a reset link</p>

        <!-- Request Reset Form -->
        <form method="POST" action="forgot_password.php">
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-indigo-200">
            </div>
            <button type="submit" name="reset_request" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 w-full">
                Send Reset Link
            </button>
        </form>

        <!-- Reset Password Form -->
        <?php if (isset($_GET['token'])): ?>
            <form method="POST" action="forgot_password.php">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                <div class="mb-4">
                    <label class="block text-gray-700">New Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-indigo-200">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Confirm Password</label>
                    <input type="password" name="confirm_password" required class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-indigo-200">
                </div>
                <button type="submit" name="reset_password" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 w-full">
                    Reset Password
                </button>
            </form>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="login.php" class="text-green-500 text-sm hover:text-green-700">Back to Login</a>
        </div>
    </div>
</body>
</html>
