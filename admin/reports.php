<?php
session_start();
include "../config.php"; // Database connection

// Function to display session messages
function displayMessage($type)
{
    if (isset($_SESSION[$type . '_message'])) {
        echo "<div class='p-4 mb-4 text-sm text-white bg-" . ($type == 'success' ? 'green' : 'red') . "-500 rounded-lg'>{$_SESSION[$type . '_message']}</div>";
        unset($_SESSION[$type . '_message']);
    }
}

// Handle DELETE request securely
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM reports WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Report deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete report.";
    }
    header("Location: reports.php");
    exit();
}

// Handle Edit Request
$edit_id = "";
$edit_report = ["report_title" => "", "category" => "", "description" => "", "amount" => "", "date" => ""];

if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM reports WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $edit_report = $result->fetch_assoc();
    }
}

// Handle Form Submission (Insert or Update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $report_title = trim($_POST['report_title']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $amount = floatval($_POST['amount']);
    $date = trim($_POST['date']);
    
    // Ensure the date is in YYYY-MM-DD format
$date = trim($_POST['date']);
$date = date("d-m-Y", strtotime($date)); // Convert to DD-MM-YYYY


    if (!empty($_POST['report_id'])) {
        // Update report
        $report_id = intval($_POST['report_id']);
        $stmt = $conn->prepare("UPDATE reports SET report_title=?, category=?, description=?, amount=?, date=? WHERE id=?");
        $stmt->bind_param("sssdis", $report_title, $category, $description, $amount, $date, $report_id);
    } else {
        // Insert new report
        $stmt = $conn->prepare("INSERT INTO reports (report_title, category, description, amount, date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssds", $report_title, $category, $description, $amount, $date);
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Report successfully " . (!empty($_POST['report_id']) ? "updated" : "uploaded") . "!";
    } else {
        $_SESSION['error_message'] = "Database error: " . $stmt->error;
    }

    header("Location: reports.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Management</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Tailwind CSS -->
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-gray-700">Reports Management</h2>

    <!-- Display Success/Error Messages -->
    <?php displayMessage('success'); ?>
    <?php displayMessage('error'); ?>

    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800"><?= $edit_id ? "Edit Report" : "Add New Report"; ?></h3>
    </div>

    <!-- Report Form -->
    <form method="POST" action="" class="space-y-4">
        <input type="hidden" name="report_id" value="<?= htmlspecialchars($edit_id); ?>">

        <label class="block text-gray-700">Report Title</label>
        <input type="text" name="report_title" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($edit_report['report_title']); ?>" required>

        <label class="block text-gray-700">Category</label>
        <select name="category" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400" required>
            <?php
            $categories = ["Total Birds", "Feed Consumption", "Expenses", "Sales", "Vaccinations"];
            foreach ($categories as $cat) {
                echo "<option value='$cat' " . ($edit_report['category'] == $cat ? 'selected' : '') . ">$cat</option>";
            }
            ?>
        </select>

        <label class="block text-gray-700">Description</label>
        <textarea name="description" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400" required><?= htmlspecialchars($edit_report['description']); ?></textarea>

        <label class="block text-gray-700">Amount (₦)</label>
        <input type="number" name="amount" step="0.01" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($edit_report['amount']); ?>" required>

        <label class="block text-gray-700">Date</label>
        <input type="date" name="date" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400" value="<?= date('Y-m-d', strtotime($edit_report['date'])); ?>" required>

        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded mt-4 hover:bg-blue-700 transition">
            <?= $edit_id ? 'Update Report' : 'Upload Report'; ?>
        </button>
    </form>

    <!-- Display Reports in Table -->
    <table class="w-full border-collapse border border-gray-300 mt-6">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">Title</th>
                <th class="border p-2">Category</th>
                <th class="border p-2">Description</th>
                <th class="border p-2">Amount (₦)</th>
                <th class="border p-2">Date</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM reports ORDER BY date DESC";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) { ?>
                <tr class="bg-white hover:bg-gray-100">
                    <td class="border p-2"><?= htmlspecialchars($row['report_title']); ?></td>
                    <td class="border p-2"><?= htmlspecialchars($row['category']); ?></td>
                    <td class="border p-2"><?= htmlspecialchars($row['description']); ?></td>
                    <td class="border p-2"><?= number_format($row['amount'], 0); ?></td>
                    <td class="border p-2"><?= date('Y-m-d', strtotime($row['date'])); ?></td>
                    <td class="border p-2">
                        <a href="reports.php?edit=<?= $row['id']; ?>" class="text-blue-600">Edit</a> |
                        <a href="reports.php?delete=<?= $row['id']; ?>" class="text-red-600" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
