<?php
include '../config.php';

// Fetch Total Birds
$total_birds_query = "SELECT COUNT(*) as total_birds FROM poultry_stock";
$total_birds_result = $conn->query($total_birds_query);
$total_birds = $total_birds_result->fetch_assoc()['total_birds'] ?? 0;
// Fetch total feed used


// Fetch Total Feed Consumption
$total_feed_query = "SELECT SUM(quantity) as total_feed FROM feeding_schedule";
$total_feed_result = $conn->query($total_feed_query);
$total_feed = $total_feed_result->fetch_assoc()['total_feed'] ?? 0;

// Fetch Total Expenses
$total_expenses_query = "SELECT SUM(amount) as total_expenses FROM expenses";
$total_expenses_result = $conn->query($total_expenses_query);
$total_expenses = $total_expenses_result->fetch_assoc()['total_expenses'] ?? 0;

// Fetch Total Sales
$total_sales_query = "SELECT SUM(amount) as total_sales FROM sales";
$total_sales_result = $conn->query($total_sales_query);
$total_sales = $total_sales_result->fetch_assoc()['total_sales'] ?? 0;

// Fetch Pending Vaccinations
$pending_vaccinations_query = "SELECT COUNT(*) as pending_vaccinations FROM vaccinations WHERE status = 'pending'";
$pending_vaccinations_result = $conn->query($pending_vaccinations_query);
$pending_vaccinations = $pending_vaccinations_result->fetch_assoc()['pending_vaccinations'] ?? 0;

?>
<?php
session_start();
include "../config.php"; // Database connection

// Fetch the latest report data
$query = "SELECT * FROM farm_reports ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$farm_data = mysqli_fetch_assoc($result);

// Handle undefined variables
$total_birds = $farm_data['total_birds'] ?? 0;
$total_feed_used = $farm_data['total_feed_used'] ?? 0.00;
$total_expenses = $farm_data['total_expenses'] ?? 0.00;
$total_sales = $farm_data['total_sales'] ?? 0.00;
$pending_vaccinations = $farm_data['pending_vaccinations'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Farm Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Farm Performance Reports</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
            <div class="bg-blue-500 text-white p-4 rounded-lg">
                <h4 class="text-lg font-semibold">Total Birds</h4>
                <p class="text-2xl font-bold"><?= number_format($total_birds) ?></p>
            </div>

            <div class="bg-green-500 text-white p-4 rounded-lg">
                <h4 class="text-lg font-semibold">Total Feed Used (kg)</h4>
                <p class="text-2xl font-bold"><?= number_format($total_feed_used, 2) ?> kg</p>
            </div>

            <div class="bg-yellow-500 text-white p-4 rounded-lg">
                <h4 class="text-lg font-semibold">Total Expenses</h4>
                <p class="text-2xl font-bold">₦<?= number_format($total_expenses, 2) ?></p>
            </div>

            <div class="bg-red-500 text-white p-4 rounded-lg">
                <h4 class="text-lg font-semibold">Total Sales</h4>
                <p class="text-2xl font-bold">₦<?= number_format($total_sales, 2) ?></p>
            </div>

            <div class="bg-purple-500 text-white p-4 rounded-lg">
                <h4 class="text-lg font-semibold">Pending Vaccinations</h4>
                <p class="text-2xl font-bold"><?= number_format($pending_vaccinations) ?></p>
            </div>
        </div>

        
    </div>
</body>
</html>

<?php
$reports_query = "SELECT report_title, category, description, amount, date FROM reports ORDER BY date DESC";
$reports_result = $conn->query($reports_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">

        <!-- Reports Table Section -->
        <div class="mt-10 bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-700">Uploaded Reports</h3>

            <!-- Check if there are reports -->
            <?php if ($reports_result->num_rows > 0) { ?>
                <table class="w-full border-collapse border border-gray-200 mt-2">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border p-2">Report Title</th>
                            <th class="border p-2">Category</th>
                            <th class="border p-2">Description</th>
                            <th class="border p-2">Amount (₦)</th>
                            <th class="border p-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $reports_result->fetch_assoc()) { ?>
                            <tr class="bg-white hover:bg-gray-100">
                                <td class="border p-2"><?= htmlspecialchars($row['report_title']) ?></td>
                                <td class="border p-2"><?= htmlspecialchars($row['category']) ?></td>
                                <td class="border p-2"><?= htmlspecialchars($row['description']) ?></td>
                                <td class="border p-2"><?= number_format($row['amount'], 2) ?></td>
                                <td class="border p-2"><?= date("F j, Y", strtotime($row['date'])) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="mt-4">
   
    <a href="export.php" class="bg-red-500 text-white px-4 py-2 rounded-md">
        Download Report
    </a>
   
</div>

            <?php } else { ?>
                <p class="text-gray-600 mt-4">No reports available.</p>
            <?php } ?>
        </div>
    </div>
</body>
</html>
