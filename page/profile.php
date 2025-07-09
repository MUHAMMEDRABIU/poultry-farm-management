<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /auth/login.php");
    exit();
}

include "../config.php";

$user_id = $_SESSION['user_id'];

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated_name = trim($_POST['name']);
    $updated_email = trim($_POST['email']);

    if (!empty($updated_name) && !empty($updated_email)) {
        $update_query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $updated_name, $updated_email, $user_id);
        $stmt->execute();

        // Update session name if changed
        $_SESSION['user_name'] = $updated_name;

        $success = "Profile updated successfully!";
    } else {
        $error = "All fields are required.";
    }
}

// Fetch user data
$query = "SELECT name, email, role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

  <header class="bg-green-700 text-white py-4 shadow">
    <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold">Your Profile</h1>
      <a href="dashboard.php" class="bg-white text-green-700 font-semibold px-4 py-2 rounded hover:bg-green-100">← Back to Dashboard</a>
    </div>
  </header>

  <main class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4 text-gray-700">Edit Your Information</h2>

    <?php if (isset($success)): ?>
      <div class="bg-green-100 text-green-800 px-4 py-2 mb-4 rounded"><?= $success ?></div>
    <?php elseif (isset($error)): ?>
      <div class="bg-red-100 text-red-700 px-4 py-2 mb-4 rounded"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-5">
      <div>
        <label class="block text-gray-600 mb-1">Full Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
      </div>

      <div>
        <label class="block text-gray-600 mb-1">Email Address</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
      </div>

      <div>
        <label class="block text-gray-600 mb-1">Role</label>
        <input type="text" value="<?= htmlspecialchars(ucfirst($user['role'])) ?>" disabled
               class="w-full px-4 py-2 bg-gray-100 border rounded-lg text-gray-500">
      </div>

      <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
        Save Changes
      </button>
    </form>
  </main>

</body>
</html>
