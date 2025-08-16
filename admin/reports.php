<?php
session_start();
include "../config.php";

// Function to display session messages
function displayMessage($type)
{
    if (isset($_SESSION[$type . '_message'])) {
        echo "<div class='p-4 mb-4 text-sm text-white bg-" . ($type == 'success' ? 'green' : 'red') . "-500 rounded-lg'>{$_SESSION[$type . '_message']}</div>";
        unset($_SESSION[$type . '_message']);
    }
}

// Handle DELETE
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

// Handle Edit
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

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $report_title = trim($_POST['report_title']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $amount = floatval($_POST['amount']);
    $date = date("d-m-Y", strtotime(trim($_POST['date']))); // formatted to DD-MM-YYYY

    if (!empty($_POST['report_id'])) {
        $report_id = intval($_POST['report_id']);
        $stmt = $conn->prepare("UPDATE reports SET report_title=?, category=?, description=?, amount=?, date=? WHERE id=?");
        $stmt->bind_param("sssdis", $report_title, $category, $description, $amount, $date, $report_id);
    } else {
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
    <title>Report Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .sidebar {
            transition: width 0.3s ease-in-out;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed span {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- Sidebar -->
    <div class="sidebar fixed top-0 left-0 h-full bg-gray-900 text-white w-64 p-4">
        <button id="toggleSidebar" class="mb-4 text-white focus:outline-none">
            <i class="ph ph-list text-2xl"></i>
        </button>
        <div class="logo text-center text-xl font-bold border-b border-gray-700 pb-2">
            Admin Panel
        </div>
        <nav class="mt-4">
            <a href="admin_dashboard.php" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-700">
                <i class="ph ph-gauge"></i> <span>Dashboard</span>
            </a>
            <a href="poultry_data.php" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-700">
                <i class="ph ph-bird"></i> <span>Poultry Data</span>
            </a>
            <a href="reports.php" class="flex items-center gap-2 px-4 py-3 bg-gray-700">
                <i class="ph ph-chart-line"></i> <span>Report Management</span>
            </a>
            <a href="overview.php" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-700">
                <i class="ph ph-eye"></i> <span>Overview</span>
            </a>
            <a href="feeding_system.php" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-700">
                <i class="ph ph-fork-knife"></i> <span>Feeding System</span>
            </a>
            <a href="user_management.php" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-700">
                <i class="ph ph-users"></i> <span>User Management</span>
            </a>
            <a href="logout.php" class="flex items-center gap-2 px-4 py-3 text-red-400 hover:bg-red-600 hover:text-white">
                <i class="ph ph-sign-out"></i> <span>Logout</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-6 transition-all duration-300">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Reports Management</h2>

        <!-- Display Messages -->
        <?php displayMessage('success'); ?>
        <?php displayMessage('error'); ?>

        <!-- Form -->
        <form method="POST" action="" class="bg-white p-6 rounded shadow-md space-y-4">
            <input type="hidden" name="report_id" value="<?= htmlspecialchars($edit_id); ?>">

            <div>
                <label class="block text-gray-700">Report Title</label>
                <input type="text" name="report_title" class="w-full border p-2 rounded" value="<?= htmlspecialchars($edit_report['report_title']); ?>" required>
            </div>

            <div>
                <label class="block text-gray-700">Category</label>
                <select name="category" class="w-full border p-2 rounded" required>
                    <?php
                    $categories = ["Total Birds", "Feed Consumption", "Expenses", "Sales", "Vaccinations"];
                    foreach ($categories as $cat) {
                        echo "<option value='$cat' " . ($edit_report['category'] == $cat ? 'selected' : '') . ">$cat</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label class="block text-gray-700">Description</label>
                <textarea name="description" class="w-full border p-2 rounded" required><?= htmlspecialchars($edit_report['description']); ?></textarea>
            </div>

            <div>
                <label class="block text-gray-700">Amount (₦)</label>
                <input type="number" name="amount" step="0.01" class="w-full border p-2 rounded" value="<?= htmlspecialchars($edit_report['amount']); ?>" required>
            </div>

            <div>
                <label class="block text-gray-700">Date</label>
                <input type="date" name="date" class="w-full border p-2 rounded" value="<?= date('Y-m-d', strtotime($edit_report['date'])); ?>" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <?= $edit_id ? 'Update Report' : 'Upload Report'; ?>
            </button>
        </form>

        <!-- Report Table -->
        <table class="w-full border-collapse border border-gray-300 mt-6 bg-white rounded">
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
                $result = $conn->query("SELECT * FROM reports ORDER BY date DESC");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='hover:bg-gray-100'>
                    <td class='border p-2'>" . htmlspecialchars($row['report_title']) . "</td>
                    <td class='border p-2'>" . htmlspecialchars($row['category']) . "</td>
                    <td class='border p-2'>" . htmlspecialchars($row['description']) . "</td>
                    <td class='border p-2'>" . number_format($row['amount'], 0) . "</td>
                    <td class='border p-2'>" . date('Y-m-d', strtotime($row['date'])) . "</td>
                    <td class='border p-2'>
                        <a href='reports.php?edit=" . $row['id'] . "' class='text-blue-600'>Edit</a> |
                        <a href='reports.php?delete=" . $row['id'] . "' class='text-red-600' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>
                  </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Sidebar Toggle Script -->
    <script>
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>

</html>