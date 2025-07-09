<?php
include '../config.php';

// Fetch Feeding Records
$feeding_records_query = "SELECT * FROM feeding_records ORDER BY created_at DESC";
$feeding_records_result = mysqli_query($conn, $feeding_records_query);

// Fetch Feeding Schedule
$feeding_schedule_query = "SELECT * FROM feeding_schedule ORDER BY FIELD(time_of_day, 'morning', 'afternoon', 'evening')";
$feeding_schedule_result = mysqli_query($conn, $feeding_schedule_query);

if (!$feeding_records_result || !$feeding_schedule_result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feeding Information & Schedule</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">


        <!-- Feeding Schedule Section -->
        <h2 class="text-3xl font-bold text-gray-800 mb-4 text-center">Feeding Schedule</h2>
        <div class="bg-white shadow-md rounded-lg p-4">
            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Time of Day</th>
                        <th class="border p-2">Feed Type</th>
                        <th class="border p-2">Feeding Time</th>
                        <th class="border p-2">Quantity (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($feeding_schedule_result)) { ?>
                        <tr class="bg-white hover:bg-gray-100">
                            <td class="border p-2"><?php echo $row['time_of_day']; ?></td>
                            <td class="border p-2"><?php echo $row['food_type']; ?></td>
                            <td class="border p-2"><?php echo $row['feeding_time']; ?></td>
                            <td class="border p-2"><?php echo $row['quantity']; ?> kg</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 mb-4 text-center">Feeding Information</h2>
        <div class="bg-white shadow-md rounded-lg p-4 mb-8">
            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">ID</th>
                        <th class="border p-2">Feed Type</th>
                        <th class="border p-2">Quantity (kg)</th>
                        <th class="border p-2">Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($feeding_records_result)) { ?>
                        <tr class="bg-white hover:bg-gray-100">
                            <td class="border p-2"><?php echo $row['id']; ?></td>
                            <td class="border p-2"><?php echo $row['feed_type']; ?></td>
                            <td class="border p-2"><?php echo $row['quantity']; ?> kg</td>
                            <td class="border p-2"><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- Go Back Button -->
        <div class="mt-6 text-center">
            <a href="dashboard.php" class="inline-block bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                 Go Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
