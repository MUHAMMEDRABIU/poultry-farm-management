<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-gray-50">

    <div class="max-w-6xl mx-auto px-6 py-12">
        <h1 class="text-4xl font-bold text-green-700 text-center">About Our Poultry Management System</h1>
        <p class="text-gray-700 text-lg mt-4 text-center">Effortlessly manage your poultry farm with automated tracking, real-time monitoring, and financial insights.</p>

        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <h2 class="text-2xl font-semibold text-green-800">📊 Farm Performance Tracking</h2>
                <p class="text-gray-600 mt-2">Monitor your poultry data, feeding schedules, and vaccinations in one place.</p>
            </div>

            <div class="bg-white p-6 shadow-lg rounded-lg">
                <h2 class="text-2xl font-semibold text-green-800">💰 Financial Insights</h2>
                <p class="text-gray-600 mt-2">Keep track of expenses, sales, and profitability to make informed decisions.</p>
            </div>

            <div class="bg-white p-6 shadow-lg rounded-lg">
                <h2 class="text-2xl font-semibold text-green-800">🔔 Real-Time Alerts</h2>
                <p class="text-gray-600 mt-2">Get notifications for upcoming vaccinations, low feed stock, and more.</p>
            </div>

            <div class="bg-white p-6 shadow-lg rounded-lg">
                <h2 class="text-2xl font-semibold text-green-800">🖥️ User-Friendly Interface</h2>
                <p class="text-gray-600 mt-2">Designed for both small- and large-scale poultry farmers.</p>
            </div>
        </div>

        <div class="mt-10 text-center">
            <a href="contact.php" class="bg-green-600 text-white px-6 py-3 rounded-lg text-lg hover:bg-green-700">Get in Touch</a>
        </div>
    </div>
</body>
</html>
update_overview.php 