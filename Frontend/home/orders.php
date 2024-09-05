<?php
session_start();
include '../../db/conn.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to view your orders.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the registration1 table
$user_query = $conn->prepare("SELECT username, email, phone FROM registration1 WHERE id = ?");
$user_query->bind_param("s", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user_data = $user_result->fetch_assoc();
$user_query->close();

// Function to fetch orders
function fetch_orders($conn, $user_id, $table) {
    $query = $conn->prepare("SELECT * FROM $table WHERE user_id = ?");
    $query->bind_param("s", $user_id);
    $query->execute();
    $result = $query->get_result();
    $orders = [];
    while ($order = $result->fetch_assoc()) {
        $invoice_no = htmlspecialchars($order['invoice_no']);
        if (!isset($orders[$invoice_no])) {
            $orders[$invoice_no] = [
                'invoice_no' => $invoice_no,
                'name' => htmlspecialchars($order['name']),
                'address' => htmlspecialchars($order['address']),
                'payment_method' => htmlspecialchars($order['payment_method']),
                'date' => htmlspecialchars($order['date']),
                'time' => htmlspecialchars($order['time']),
                'status' => htmlspecialchars($order['status']),
                'order_status' => htmlspecialchars($order['order_status']),
                'items' => []
            ];
        }
        $orders[$invoice_no]['items'][] = [
            'product_name' => htmlspecialchars($order['product_name']),
            'price' => htmlspecialchars($order['price']),
            'qty' => htmlspecialchars($order['qty'])
        ];
    }
    $query->close();
    return $orders;
}

// Fetch current orders
$current_orders = fetch_orders($conn, $user_id, 'orders');

// Fetch archived orders
$archived_orders = fetch_orders($conn, $user_id, 'orders_archive');

// Combine orders
$all_orders = array_merge($current_orders, $archived_orders);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 2rem auto;
            padding: 1rem;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #444;
        }

        .order {
            border: 1px solid #ddd;
            padding: 16px;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .order h2 {
            margin-top: 0;
            color: #333;
        }

        .order p {
            margin: 8px 0;
        }

        .order .details {
            display: flex;
            justify-content: space-between;
        }

        .order .details div {
            width: 48%;
        }

        .order .details div p {
            margin: 4px 0;
        }

        .order .product {
            border-top: 1px solid #ddd;
            padding-top: 8px;
            margin-top: 8px;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="flex">
        <div class="container mx-auto p-6 bg-white shadow-lg rounded-lg mt-6">
            <h1 class="text-3xl font-bold text-center mb-6">My Orders</h1>
            <?php
            if (count($all_orders) > 0) {
                foreach ($all_orders as $invoice_no => $order) {
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
                                <p><strong>Address:</strong> <?= $order['address']; ?></p>
                                <p><strong>Payment Method:</strong> <?= $order['payment_method']; ?></p>
                                <p><strong>Payment Status:</strong> <?= $order['status']; ?></p>
                            </div>
                            <div class="w-1/2 pl-2">
                                <p><strong>Product Names:</strong> <?= implode(', ', $product_names); ?></p>
                                <p><strong>Prices:</strong> <?= implode(' + ', $product_prices); ?></p>
                                <p><strong>Total Price:</strong> Rs. <?= $total_price; ?></p>
                                <p><strong>Order Status:</strong> <?= $order['order_status']; ?></p>
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
