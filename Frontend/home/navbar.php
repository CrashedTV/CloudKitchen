<?php
include "../../db/conn.php";

// Assuming session_start() is called before this script if not already started
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
$count_cart_items->bind_param("s", $user_id);
$count_cart_items->execute();
$count_cart_items_result = $count_cart_items->get_result();
$total_cart_items = $count_cart_items_result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="_NavBar.css">
    <link rel="stylesheet" href="responsive.css">
    <style>
        /* Additional styles for dropdown */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: rgb(90, 88, 88) ;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
    
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <header id="header">
        <a href="home.php"><img src="img/logo.png" class="logo" alt="DMagg Cloud Kitchen Logo"></a>
        <div id="navbar-toggle">
            <i class="fa fa-bars"></i>
            <i class="fa fa-times"></i>
        </div>
        <ul id="navbar">
            <li><a href="home.php">Home</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="search.php"><i class="fas fa-search"></i></a></li>
            <li>
                <a href="cart.php" class="cart-btn"><i class="fas fa-shopping-cart"><span><?= htmlspecialchars($total_cart_items); ?></span></i></a>
            </li>
            <li class="dropdown">
                <?php if (isset($_SESSION['username'])) : ?>
                    <a href="#" class="dropbtn">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</a>
                    <div class="dropdown-content">
                        <a href="orders.php">My Orders</a>
                        <a href="../../logout.php">Logout</a>
                    </div>
                <?php else : ?>
                   
                    <a href="../../login.php">Login</a>
                    <div class="dropdown-content">
                       
                    </div>
                <?php endif; ?>
            </li>
        </ul>
    </header>
</body>

</html>
