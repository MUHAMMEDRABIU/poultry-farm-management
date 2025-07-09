<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
        <h2 class="text-2xl font-bold text-green-700">Logout Successful</h2>
        <p class="text-gray-600 mt-2">You have been logged out safely.</p>
        
        <div class="mt-4">
            <a href="login.php" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-300">
                Return to Login
            </a>
        </div>
    </div>

</body>
</html>
