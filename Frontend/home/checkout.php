<?php
session_start();
include '../../db/conn.php';
date_default_timezone_set('Asia/Kathmandu');

if (!isset($_SESSION['user_id'])) {
    echo 'No user found in the cart!';
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$user_query = $conn->prepare("SELECT username, email, phone FROM registration1 WHERE id = ?");
$user_query->bind_param("s", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user_data = $user_result->fetch_assoc();
$user_query->close();

if ($user_data === null) {
    echo 'User data not found!';
    exit;
}

$name = filter_var($user_data['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Initialize variables
$grand_total = 0;
$selected_product_name = null;
$qty = 1; // Default quantity

// Fetch product details if a product is selected via GET
if (isset($_GET['get_name'])) {
    $selected_product_name = $_GET['get_name'];
    $qty = isset($_GET['qty']) ? (int)$_GET['qty'] : 1; // Get quantity from URL

    $select_get = $conn->prepare("SELECT * FROM `add_items` WHERE TRIM(LOWER(Item_Name)) = TRIM(LOWER(?))");
    $select_get->bind_param("s", $selected_product_name);
    $select_get->execute();
    $product_result = $select_get->get_result();

    while ($fetch_get = $product_result->fetch_assoc()) {
        $sub_total = $fetch_get['Item_Price'] * $qty;
        $grand_total += $sub_total;
    }
}

if (isset($_POST['place_order'])) {
    $invoice_no = uniqid();
    
    $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
    $address = filter_var($_POST['address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $method = isset($_POST['method']) ? $_POST['method'] : '';

    // Check for required fields
    if (empty($product_name)) {
        echo 'Product name is not specified!';
        exit;
    }
    if (empty($address)) {
        echo 'Address is missing!';
        exit;
    }
    if (empty($method)) {
        echo 'Payment method is missing!';
        exit;
    }

    $payment_method = ($method === 'esewa') ? 'Esewa' : 'Cash';
    $status = 'Pending';
    $order_status = 'Processing';
    $date = date('Y-m-d');
    $time = date('H:i:s');

    if ($payment_method === 'Esewa') {
        $product_query = $conn->prepare("SELECT Item_Name, Item_Price FROM add_items WHERE TRIM(LOWER(Item_Name)) = TRIM(LOWER(?))");
        $product_query->bind_param("s", $product_name);
        $product_query->execute();
        $product_result = $product_query->get_result();
        $fetch_product = $product_result->fetch_assoc();
        $product_query->close();

        if (!$fetch_product) {
            echo 'Product not found!';
            exit;
        }

        $price = $fetch_product['Item_Price'];
        $total = $price * $qty;

        $query = "INSERT INTO orders(user_id, invoice_no, product_name, qty, price, total, payment_method, status, order_status, name, address, date, time)
                  VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_order = $conn->prepare($query);
        $insert_order->bind_param("sssssssssssss", $user_id, $invoice_no, $fetch_product['Item_Name'], $qty, $price, $total, $payment_method, $status, $order_status, $name, $address, $date, $time);

        if (!$insert_order->execute()) {
            die('Error: ' . $conn->error);
        }

        echo "<form id='esewa_form' action='https://uat.esewa.com.np/epay/main' method='POST'>
                <input value='$grand_total' name='tAmt' type='hidden'>
                <input value='$grand_total' name='amt' type='hidden'>
                <input value='0' name='txAmt' type='hidden'>
                <input value='0' name='psc' type='hidden'>
                <input value='0' name='pdc' type='hidden'>
                <input value='epay_payment' name='scd' type='hidden'>
                <input value='$invoice_no' name='pid' type='hidden'>
                <input value='http://localhost/uc/esewa-master/esewa_payment_success.php' type='hidden' name='su'>
                <input value='http://localhost/uc/esewa-master/esewa_payment_failed.php' type='hidden' name='fu'>
              </form>
              <script type='text/javascript'>
                document.getElementById('esewa_form').submit();
              </script>";
        exit;
    }

    // Verify if the user has items in the cart
    $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $verify_cart->bind_param("s", $user_id);
    $verify_cart->execute();
    $cart_result = $verify_cart->get_result();

    if ($cart_result->num_rows > 0) {
        while ($f_cart = $cart_result->fetch_assoc()) {
            $select_products = $conn->prepare("SELECT Item_Name, Item_Price FROM `add_items` WHERE TRIM(LOWER(Item_Name)) = TRIM(LOWER(?))");
            $product_name = $f_cart['product_name'];
            $select_products->bind_param("s", $product_name);
            $select_products->execute();
            $product_result = $select_products->get_result();
            $fetch_product = $product_result->fetch_assoc();

            if (!$fetch_product) {
                continue; // Skip this cart item if product not found
            }

            $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, invoice_no, product_name, qty, price, total, payment_method, status, order_status, name, address, date, time) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $product_name = $fetch_product['Item_Name'];
            $price = $fetch_product['Item_Price'];
            $qty = $f_cart['qty'];
            $total = $price * $qty;
            $payment_method = 'Cash';
            $status = 'Pending';
            $order_status = 'Processing';
            $insert_order->bind_param("sssssssssssss", $user_id, $invoice_no, $product_name, $qty, $price, $total, $payment_method, $status, $order_status, $name, $address, $date, $time);
            $insert_order->execute();
        }

        if ($insert_order->affected_rows > 0) {
            $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart_id->bind_param("s", $user_id);
            $delete_cart_id->execute();
            echo "<script>
                    alert('Your Order has been placed successfully.');
                    window.location.href='orders.php';
                  </script>";
            exit;
        } else {
            echo 'Failed to place order!';
        }
    } else {
        echo 'Your cart is empty!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="checkout.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <section class="checkout">
        <h1 class="heading">Checkout Summary</h1>
        <div class="row">
            <form action="" method="POST">
                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($selected_product_name); ?>">

                <h3>Billing Details</h3>
                <div class="flex">
                    <div class="box">
                        <p>Payment Method <span>*</span></p>
                        <label><input type="radio" name="method" value="cash on delivery" required> Cash on Delivery</label>
                        <label><input type="radio" name="method" value="esewa" required> Esewa</label>
                        <p>Address <span>*</span></p>
                        <input type="text" name="address" required maxlength="50" placeholder="Enter Address" class="input">
                    </div>
                </div>
                <input type="submit" value="Place Order" name="place_order" class="btn">
            </form>

            <div class="summary">
                <h3 class="title">Cart Items</h3>
                <?php
                if (isset($selected_product_name)) {
                    $select_get = $conn->prepare("SELECT * FROM `add_items` WHERE TRIM(LOWER(Item_Name)) = TRIM(LOWER(?))");
                    $select_get->bind_param("s", $selected_product_name);
                    $select_get->execute();
                    $product_result = $select_get->get_result();

                    while ($fetch_get = $product_result->fetch_assoc()) {
                        ?>
                        <div class="flex">
                            <img src="../../admin/<?= htmlspecialchars($fetch_get['Item_Image']); ?>" class="image" alt="">
                            <div>
                                <h3 class="name"><?= htmlspecialchars($fetch_get['Item_Name']); ?></h3>
                                <p class="price">Rs. <?= htmlspecialchars($fetch_get['Item_Price']); ?> x <?= htmlspecialchars($qty); ?></p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                    $select_cart->bind_param("s", $user_id);
                    $select_cart->execute();
                    $cart_result = $select_cart->get_result();

                    if ($cart_result->num_rows > 0) {
                        while ($fetch_cart = $cart_result->fetch_assoc()) {
                            $select_products = $conn->prepare("SELECT * FROM `add_items` WHERE TRIM(LOWER(Item_Name)) = TRIM(LOWER(?))");
                            $product_name = $fetch_cart['product_name'];
                            $select_products->bind_param("s", $product_name);
                            $select_products->execute();
                            $product_result = $select_products->get_result();
                            $fetch_product = $product_result->fetch_assoc();
                            $sub_total = ($fetch_cart['qty'] * $fetch_product['Item_Price']);

                            $grand_total += $sub_total;
                            ?>
                            <div class="flex">
                                <img src="../../admin/<?= htmlspecialchars($fetch_product['Item_Image']); ?>" class="image" alt="">
                                <div>
                                    <h3 class="name"><?= htmlspecialchars($fetch_product['Item_Name']); ?></h3>
                                    <p class="price">Rs. <?= htmlspecialchars($fetch_product['Item_Price']); ?> x <?= htmlspecialchars($fetch_cart['qty']); ?></p>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<p class="empty">Your cart is empty</p>';
                    }
                }
                ?>
                <div class="grand-total"><span>Grand Total :</span>
                    <p>Rs. <?= htmlspecialchars($grand_total); ?></p>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/script.js"></script>

    <?php include 'alert.php'; ?>
</body>
</html>

