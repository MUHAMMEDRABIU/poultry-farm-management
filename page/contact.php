<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // You can save this to a database or send it via email
    $success = "Thank you, $name! Your message has been received.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-gray-50">


    <div class="max-w-4xl mx-auto px-6 py-12">
        <h1 class="text-4xl font-bold text-green-700 text-center">Contact Us</h1>
        <p class="text-gray-700 text-lg mt-4 text-center">Have a question or need support? Reach out to us below.</p>

        <?php if (isset($success)) : ?>
            <p class="mt-4 text-green-600 text-center font-semibold"><?php echo $success; ?></p>
        <?php endif; ?>

        <div class="">
            <!-- Contact Details -->
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <h2 class="text-2xl font-semibold text-green-800">📍 Our Office</h2>
                <p class="text-gray-600 mt-2">Poultry Farm Lane, Agriculture City, Nigeria</p>

                <h2 class="text-2xl font-semibold text-green-800 mt-4">📞 Call Us</h2>
                <p class="text-gray-600 mt-2">+234 08110237625</p>

                <h2 class="text-2xl font-semibold text-green-800 mt-4">📧 Email</h2>
                <p class="text-gray-600 mt-2">rademu910@gmail.com</p>

                <h2 class="text-2xl font-semibold text-green-800 mt-4">🕒 Working Hours</h2>
                <p class="text-gray-600 mt-2">Monday - Friday: 9:00 AM - 5:00 PM</p>
            </div>

            <!-- Contact Form -->
            <form method="POST" action="contact.php" class="bg-white p-6 shadow-lg rounded-lg">
                <h2 class="text-2xl font-semibold text-green-800 mb-4">Send Us a Message</h2>
                


                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Name</label>
                    <input type="text" name="name" required class="w-full p-3 border border-gray-300 rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Email</label>
                    <input type="email" name="email" required class="w-full p-3 border border-gray-300 rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Message</label>
                    <textarea name="message" required rows="4" class="w-full p-3 border border-gray-300 rounded-lg"></textarea>
                </div>

                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg text-lg hover:bg-green-700">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html>



<