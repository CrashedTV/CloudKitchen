<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include '../db/conn.php';
// Fetch the number of users
$result = $conn->query("SELECT COUNT(*) as count FROM registration1");
if ($result) {
    $row = $result->fetch_assoc();
    $numUsers = $row['count'];
}
$message = $conn->query("SELECT COUNT(*) as count FROM message");
if ($message) {
    $rowMessage = $message->fetch_assoc();
    $numMessage = $rowMessage['count'];
} else {
    $numMessage = "Unable to fetch data.";
}
$items = $conn->query("SELECT COUNT(*) as count FROM add_items");
if ($items) {
    $rowItems = $items->fetch_assoc();
    $numItems = $rowItems['count'];
} else {
    $numItems = "Unable to fetch data.";
}
$category = $conn->query("SELECT COUNT(*) as count FROM add_categories");
if ($category) {
    $rowCategory = $category->fetch_assoc();
    $numCategory = $rowCategory['count'];
} else {
    $numCategory = "Unable to fetch data.";
}

// Fetch the number of completed orders (from orders_archive)
$resultOrders = $conn->query("SELECT COUNT(DISTINCT invoice_no) as count FROM orders_archive");
if ($resultOrders) {
    $rowOrders = $resultOrders->fetch_assoc();
    $numOrders = $rowOrders['count'];
} else {
    $numOrders = "Unable to fetch data.";
}

// Fetch the total amount collected (Paid) from orders table
$resultOrdersPaidTotal = $conn->query("SELECT SUM(total) as totalAmount FROM orders WHERE status = 'Paid'");
if ($resultOrdersPaidTotal) {
    $rowOrdersPaidTotal = $resultOrdersPaidTotal->fetch_assoc();
    $totalOrdersPaidAmount = $rowOrdersPaidTotal['totalAmount'];
} else {
    $totalOrdersPaidAmount = 0;
}

// Fetch the total amount collected (Paid) from orders_archive table
$resultArchivePaidTotal = $conn->query("SELECT SUM(total) as totalAmount FROM orders_archive WHERE status = 'Paid'");
if ($resultArchivePaidTotal) {
    $rowArchivePaidTotal = $resultArchivePaidTotal->fetch_assoc();
    $totalArchivePaidAmount = $rowArchivePaidTotal['totalAmount'];
} else {
    $totalArchivePaidAmount = 0;
}

// Fetch the total amount pending from orders table
$resultOrdersPendingTotal = $conn->query("SELECT SUM(total) as totalAmount FROM orders WHERE status = 'Pending'");
if ($resultOrdersPendingTotal) {
    $rowOrdersPendingTotal = $resultOrdersPendingTotal->fetch_assoc();
    $totalOrdersPendingAmount = $rowOrdersPendingTotal['totalAmount'];
} else {
    $totalOrdersPendingAmount = 0;
}

// Fetch the total amount pending from orders_archive table
$resultArchivePendingTotal = $conn->query("SELECT SUM(total) as totalAmount FROM orders_archive WHERE status = 'Pending'");
if ($resultArchivePendingTotal) {
    $rowArchivePendingTotal = $resultArchivePendingTotal->fetch_assoc();
    $totalArchivePendingAmount = $rowArchivePendingTotal['totalAmount'];
} else {
    $totalArchivePendingAmount = 0;
}

// Sum the total paid amounts from both tables
$totalAmounts = $totalOrdersPaidAmount + $totalArchivePaidAmount;

// Sum the total pending amounts from both tables
$totalPendingAmounts = $totalOrdersPendingAmount + $totalArchivePendingAmount;

// Fetch the number of pending orders from orders table
$resultOrdersPending = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'Pending'");
if ($resultOrdersPending) {
    $rowOrdersPending = $resultOrdersPending->fetch_assoc();
    $numOrdersPending = $rowOrdersPending['count'];
} else {
    $numOrdersPending = 0;
}

// Sum the total pending orders from both tables
$numOrdersPendingTotal = $numOrdersPending;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body >
    <?php include 'Navbar.php'; ?>
    <div class="flex">
        <?php include 'Sidebar.php'; ?>
        <div class="mt-8 ml-16 mb-52 mr-52 w-3/4 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md ">
                <h3 class="text-lg font-semibold text-gray-700">Number of Users</h3>
                <p class="text-xl text-gray-900"><?php echo $numUsers; ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md ">
                <h3 class="text-lg font-semibold text-gray-700">Number of Messages</h3>
                <p class="text-xl text-gray-900"><?php echo $numMessage; ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Number of Orders Completed</h3>
                <p class="text-xl text-gray-900"><?php echo $numOrders; ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Total Amounts Received</h3>
                <p class="text-xl text-gray-900">Rs. <?php echo number_format($totalAmounts, 2); ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Total Amounts Pending</h3>
                <p class="text-xl text-gray-900">Rs. <?php echo number_format($totalPendingAmounts, 2); ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Number of Orders Pending</h3>
                <p class="text-xl text-gray-900"><?php echo $numOrdersPendingTotal; ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Number of Category</h3>
                <p class="text-xl text-gray-900"><?php echo $numCategory; ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Number of Items</h3>
                <p class="text-xl text-gray-900"><?php echo $numItems; ?></p>
            </div>
        </div>
    </div>    
</body>
</html>

