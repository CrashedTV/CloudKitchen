<?php
session_start();
include '../../db/conn.php'; // Ensure you have a proper database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id']) && isset($_POST['qty'])) {
        $product_id = htmlspecialchars($_POST['product_id']);
        $qty = htmlspecialchars($_POST['qty']);

        // Check if the user_id and cart_id are set in the session
        if (isset($_SESSION['user_id']) && isset($_SESSION['cart_id']) && isset($_SESSION['username'])) {
            $user_id = $_SESSION['user_id'];
            $cart_id = $_SESSION['cart_id'];
            $username = $_SESSION['username'];

            // Check if the product already exists in the cart
            $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE `product_id` = ? AND `cart_id` = ?");
            $check_cart->bind_param("is", $product_id, $cart_id);
            $check_cart->execute();
            $result_check_cart = $check_cart->get_result();

            if ($result_check_cart->num_rows > 0) {
                $_SESSION['message'] = "Already added to the cart!";
            } else {
                // Fetch product price and image
                $select_product = $conn->prepare("SELECT `Item_Price`, `Item_Image`, `Item_Name` FROM `add_items` WHERE `Item_ID` = ?");
                $select_product->bind_param("i", $product_id);
                $select_product->execute();
                $result_select_product = $select_product->get_result();
                
                if ($result_select_product->num_rows > 0) {
                    $product = $result_select_product->fetch_assoc();
                    $product_name = $product['Item_Name'];
                    $price = $product['Item_Price'];
                    $image = $product['Item_Image'];

                    // Insert into cart
                    $insert_cart = $conn->prepare("INSERT INTO `cart` (`cart_id`, `product_id`, `product_name`, `user_id`, `username`, `price`, `qty`, `image`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $insert_cart->bind_param("sisisiis", $cart_id, $product_id, $product_name, $user_id, $username, $price, $qty, $image);

                    if ($insert_cart->execute()) {
                        $_SESSION['message'] = "Added to the cart!";
                    } else {
                        $_SESSION['message'] = "Failed to add to the cart!";
                    }
                } else {
                    $_SESSION['message'] = "Product not found!";
                }

                $select_product->close();
            }
            $check_cart->close();
        } else {
            $_SESSION['message'] = "User is not logged in!";
        }
    } else {
        $_SESSION['message'] = "Invalid request!";
    }
} else {
    $_SESSION['message'] = "Invalid request method!";
}

header("Location: menu.php");
exit();
?>
