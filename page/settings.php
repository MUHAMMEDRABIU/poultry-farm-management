<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (!empty($name) && !empty($email)) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $user_id);
        $stmt->execute();
        $message = "Settings updated successfully!";
    } else {
        $message = "All fields are required.";
    }
}

// Fetch current user info
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-xl mx-auto mt-12 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Account Settings</h2>

        <?php if ($message): ?>
            <p class="mb-4 text-green-600"><?= $message ?></p>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-600">Full Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-green-300">
            </div>
            <div>
                <label class="block text-gray-600">Email Address</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-green-300">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save Changes</button>
            </div>
        </form>

        <div class="mt-6 text-sm text-gray-500">
            Want to change your password? <a href="../auth/forgot_password.php" class="text-green-600 hover:underline">Click here</a>.
        </div>
    </div>
</body>
</html>
