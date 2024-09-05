<?php

include '../db/conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if delete request is made
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_message.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Messages</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body class="">
   
<?php include 'Navbar.php'; ?>

<div class="flex">
    <?php include 'Sidebar.php'; ?>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-center">Manage Messages</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
            $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
            if (mysqli_num_rows($select_message) > 0) {
                while ($fetch_message = mysqli_fetch_assoc($select_message)) {
        ?>
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <p class="mb-2"><span class="font-semibold">User ID:</span> <?php echo $fetch_message['user_id']; ?></p>
            <p class="mb-2"><span class="font-semibold">Name:</span> <?php echo $fetch_message['name']; ?></p>
            <p class="mb-2"><span class="font-semibold">Subject:</span> <?php echo $fetch_message['subject']; ?></p>
            <p class="mb-2"><span class="font-semibold">Phone:</span> <?php echo $fetch_message['phone']; ?></p>
            <p class="mb-2"><span class="font-semibold">Message:</span> <?php echo $fetch_message['message']; ?></p>
            <p class="mb-2"><span class="font-semibold">Date:</span> <?php echo date('d M Y', strtotime($fetch_message['created_at'])); ?></p>
            <p class="mb-2"><span class="font-semibold">Time:</span> <?php echo date('h:i A', strtotime($fetch_message['created_at'])); ?></p>
            <a href="admin_message.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('Are you sure you want to delete this message?');" class="inline-block mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete Message</a>
        </div>
        <?php
                }
            } else {
                echo '<p class="col-span-3 text-center text-gray-500">You have no messages!</p>';
            }
        ?>
        </div>
    </div>
</div>

<!-- Custom admin JS file link -->
<script src="js/admin_script.js"></script>

</body>
</html>
