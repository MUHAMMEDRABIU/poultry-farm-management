<?php
session_start();
include '../config.php'; // Database connection
// Fetch poultry records from the database
$sql = "SELECT * FROM poultry_records ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Poultry Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
        <!-- Poultry Data Table --> 
    <div class="bg-white shadow-md rounded-lg p-4 w-full">    
          <h2 class="text-3xl font-bold text-gray-800 mb-4 text-center">Poultry Data</h2>   
    
        <table class="border-collapse border border-gray-200 w-full text-center">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">ID</th>
                    <th class="border p-2">Bird Type</th>
                    <th class="border p-2">Quantity</th>
                    <th class="border p-2">Feed Stock</th>
                    <th class="border p-2">Expenses</th>
                    <th class="border p-2">Date Added</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr class="bg-white hover:bg-gray-100">
                        <td class="border p-2"><?= $row['id'] ?></td>
                        <td class="border p-2"><?= $row['bird_type'] ?></td>
                        <td class="border p-2"><?= $row['quantity'] ?></td>
                        <td class="border p-2"><?= $row['feed_stock'] ?> kg</td>
                        <td class="border p-2">₦<?= number_format($row['expenses'], 2) ?></td>
                        <td class="border p-2"><?= $row['created_at'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
