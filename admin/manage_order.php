<?php
include '../db/conn.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle order status update
if (isset($_POST['update_status'])) {
    $invoice_no = $_POST['invoice_no'];
    $new_status = $_POST['status'];

    if ($new_status === 'Completed') {
        // Archive completed order and set payment status to 'Paid'
        $archive_query = $conn->prepare("INSERT INTO orders_archive (invoice_no, user_id, product_name, qty, price, total, payment_method, status, order_status, name, address, date, time) SELECT invoice_no, user_id, product_name, qty, price, total, payment_method, 'Paid', ?, name, address, date, time FROM orders WHERE invoice_no = ?");
        $order_status = 'Completed';
        $archive_query->bind_param("ss", $order_status, $invoice_no);
        $archive_query->execute();
        $archive_query->close();

        // Delete completed order from orders table
        $delete_query = $conn->prepare("DELETE FROM orders WHERE invoice_no = ?");
        $delete_query->bind_param("s", $invoice_no);
        $delete_query->execute();
        $delete_query->close();
    } else {
        $update_query = $conn->prepare("UPDATE orders SET order_status = ?, status = 'Paid' WHERE invoice_no = ?");
        $update_query->bind_param("ss", $new_status, $invoice_no);
        $update_query->execute();
        $update_query->close();
    }
}

// Fetch orders from the database
$order_query = $conn->prepare("SELECT * FROM orders");
$order_query->execute();
$order_result = $order_query->get_result();
$order_query->close();

// Group orders by invoice number
$orders = [];
while ($order = $order_result->fetch_assoc()) {
    $invoice_no = htmlspecialchars($order['invoice_no']);
    $user_id = $order['user_id'];

    // Fetch user details for each order
    $user_query = $conn->prepare("SELECT username, email, phone FROM registration1 WHERE id = ?");
    $user_query->bind_param("s", $user_id);
    $user_query->execute();
    $user_result = $user_query->get_result();
    $user_data = $user_result->fetch_assoc();
    $user_query->close();

    if (!isset($orders[$invoice_no])) {
        $orders[$invoice_no] = [
            'invoice_no' => $invoice_no,
            'user_id' => $user_id,
            'name' => htmlspecialchars($order['name']),
            'address' => htmlspecialchars($order['address']),
            'payment_method' => htmlspecialchars($order['payment_method']),
            'date' => htmlspecialchars($order['date']),
            'time' => htmlspecialchars($order['time']),
            'status' => htmlspecialchars($order['status']),
            'order_status' => htmlspecialchars($order['order_status']),
            'user_data' => $user_data,
            'items' => []
        ];
    }
    $orders[$invoice_no]['items'][] = [
        'product_name' => htmlspecialchars($order['product_name']),
        'price' => htmlspecialchars($order['price']),
        'qty' => htmlspecialchars($order['qty'])
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="">

    <?php include 'navbar.php'; ?>

    <div class="flex">
        <?php include 'Sidebar.php'; ?>
        <div class="container mx-auto p-6 bg-white shadow-lg rounded-lg mt-6 w-full">
            <h1 class="text-3xl font-bold text-center mb-6">Manage Orders</h1>
            <?php
            if (count($orders) > 0) {
                foreach ($orders as $invoice_no => $order) {
                    $product_names = [];
                    $product_prices = [];
                    $total_price = 0;

                    foreach ($order['items'] as $item) {
                        $product_names[] = $item['product_name'] . " (" . $item['qty'] . ")";
                        $product_prices[] = "Rs. " . $item['price'];
                        $total_price += $item['price'] * $item['qty'];
                    }
            ?>
                    <div class="bg-gray-50 p-6 mb-4 rounded-lg shadow-md">
                        <h2 class="text-xl font-semibold mb-2">Invoice No.: <?= $invoice_no; ?></h2>
                        <div class="flex justify-between">
                            <div class="w-1/2 pr-2">
                                <p><strong>Order Date:</strong> <?= $order['date']; ?></p>
                                <p><strong>Order Time:</strong> <?= $order['time']; ?></p>
                                <p><strong>Name:</strong> <?= $order['name']; ?></p>
                                <p><strong>User ID:</strong> <?= $order['user_id']; ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($order['user_data']['email']); ?></p>
                                <p><strong>Phone:</strong> <?= htmlspecialchars($order['user_data']['phone']); ?></p>
                                <p><strong>Address:</strong> <?= $order['address']; ?></p>
                                <p><strong>Payment Method:</strong> <?= $order['payment_method']; ?></p>
                                <p><strong>Payment Status:</strong> <?= $order['status']; ?></p>
                                
                            </div>
                            <div class="w-1/2 pl-2">
                                <p><strong>Product Names:</strong> <?= implode(', ', $product_names); ?></p>
                                <p><strong>Prices:</strong> <?= implode(' + ', $product_prices); ?></p>
                                <p><strong>Total Price:</strong> Rs. <?= $total_price; ?></p>
                                <form action="" method="POST" class="mt-4">
                                    <input type="hidden" name="invoice_no" value="<?= $invoice_no; ?>">
                                    <strong><label for="status" class="block mb-2">Update Order Status:</label></strong>
                                    <select name="status" id="status" class="block w-full p-2 border rounded">
                                        <option value="Pending" <?= $order['order_status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Processing" <?= $order['order_status'] === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="Completed" <?= $order['order_status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                    <button type="submit" name="update_status" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">Update Status</button>
                                </form>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p class='text-center'>No orders found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
