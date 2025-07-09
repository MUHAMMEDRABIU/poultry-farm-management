<?php
include '../config.php'; // Database connection

// Fetch Feeding Records
$feeding_records_query = "SELECT * FROM feeding_records ORDER BY created_at DESC";
$feeding_records_result = mysqli_query($conn, $feeding_records_query);

// Fetch Feeding Schedule
$feeding_schedule_query = "SELECT * FROM feeding_schedule ORDER BY FIELD(time_of_day, 'morning', 'afternoon', 'evening')";
$feeding_schedule_result = mysqli_query($conn, $feeding_schedule_query);

// Check for errors
if (!$feeding_records_result || !$feeding_schedule_result) {
    die("Query Failed: " . mysqli_error($conn));
}

// Fetch schedule data
$schedules = mysqli_fetch_all($feeding_schedule_result, MYSQLI_ASSOC);

include '../config.php'; // Database connection

$sql = "SELECT time_of_day, food_type, feeding_time, quantity FROM feeding_schedule ORDER BY feeding_time ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2 class='text-3xl font-bold text-gray-700 mb-4 text-center'>Feeding Schedule</h2>";
    echo "<table class='w-full border-collapse border border-gray-200'>";
    echo "<thead>
    
            <tr class='bg-gray-200'>
                <th class='border p-2'>Time of Day</th>
                <th class='border p-2'>Feed Type</th>
                <th class='border p-2'>Feeding Time</th>
                <th class='border p-2'>Quantity (kg)</th>
            </tr>
          </thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr class='bg-white hover:bg-gray-100'>
                <td class='border p-2'>{$row['time_of_day']}</td>
                <td class='border p-2'>{$row['food_type']}</td>
                <td class='border p-2'>{$row['feeding_time']}</td>
                <td class='border p-2'>{$row['quantity']} kg</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p class='text-gray-700'>No feeding schedule available.</p>";
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feeding Information & Schedule</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-4 text-center">Feeding Information</h2>

        <!-- Feeding Records Table -->
        <div class="bg-white shadow-md rounded-lg p-4">
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

        <?php
