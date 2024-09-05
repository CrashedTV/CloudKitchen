<?php
session_start();
include '../../db/conn.php';

if (!isset($_SESSION['user_id'])) {
    header('location:../../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];
    mysqli_query($conn, "UPDATE `cart` SET qty = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
    $message[] = 'Cart quantity updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
    header('location:cart.php');
    exit();
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    header('location:cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cart</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   
   <!-- Custom CSS link -->
   <link rel="stylesheet" href="cart.css">
   <style>
    :root {
    --border: 1px solid #ccc;
    --box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    --white: #fff;
    --black: #333;
    --light-color: #999;
    --light-bg: #f7f7f7;
    --red: #e74c3c;
    --orange: #f39c12;
    --green: #2ecc71;
    --blue: #3498db;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: var(--light-bg);
    color: var(--black);
}

.heading {
    text-align: center;
    padding: 2rem;
    background-color: var(--white);
    box-shadow: var(--box-shadow);
    margin-bottom: 2rem;
}

.heading h3 {
    font-size: 2.5rem;
    color: var(--black);
    margin: 0;
}

.heading p {
    font-size: 1.2rem;
    color: var(--light-color);
}

.heading p a {
    color: var(--blue);
    text-decoration: none;
}

.heading p a:hover {
    text-decoration: underline;
}

.shopping-cart {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.shopping-cart .title {
    font-size: 3rem;
    text-align: center;
    margin-bottom: 2rem;
    color: var(--black);
}

.shopping-cart .box-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.shopping-cart .box {
    text-align: center;
    padding: 2rem;
    border-radius: .5rem;
    background-color: var(--white);
    box-shadow: var(--box-shadow);
    position: relative;
    border: var(--border);
}

.shopping-cart .box .fa-times {
    position: absolute;
    top: 1rem;
    right: 1rem;
    height: 3rem;
    width: 3rem;
    line-height: 3rem;
    font-size: 1.5rem;
    background-color: var(--red);
    color: var(--white);
    border-radius: 50%;
    cursor: pointer;
}

.shopping-cart .box .fa-times:hover {
    background-color: var(--black);
}

.shopping-cart .box img {
    height: 200px;
    width: auto;
    max-width: 100%;
    object-fit: cover;
    margin-bottom: 1rem;
}

.shopping-cart .box .name {
    padding: 1rem 0;
    font-size: 2rem;
    color: var(--black);
}

.shopping-cart .box .price {
    padding: 1rem 0;
    font-size: 2.5rem;
    color: var(--red);
}

.shopping-cart .box input[type="number"] {
    margin: .5rem 0;
    border: var(--border);
    border-radius: .5rem;
    padding: 1.2rem 1.4rem;
    font-size: 2rem;
    color: var(--black);
    width: 9rem;
}

.shopping-cart .box .option-btn {
    display: inline-block;
    padding: 1rem 2rem;
    border-radius: .5rem;
    background-color: var(--green);
    color: var(--white);
    font-size: 1.5rem;
    cursor: pointer;
    transition: background-color 0.3s;
    border: none;
}

.shopping-cart .box .option-btn:hover {
    background-color: var(--blue);
}

.shopping-cart .box .sub-total {
    padding-top: 1.5rem;
    font-size: 2rem;
    color: var(--light-color);
}

.shopping-cart .box .sub-total span {
    color: var(--red);
}

.shopping-cart .cart-total {
    max-width: 1200px;
    margin: 2rem auto;
    border: var(--border);
    padding: 2rem;
    text-align: center;
    border-radius: .5rem;
    background-color: var(--white);
    box-shadow: var(--box-shadow);
}

.shopping-cart .cart-total p {
    font-size: 2.5rem;
    color: var(--light-color);
}

.shopping-cart .cart-total p span {
    color: var(--red);
}

.shopping-cart .cart-total .flex {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1.5rem;
    justify-content: center;
}

.shopping-cart .cart-total .option-btn {
    display: inline-block;
    padding: 1rem 2rem;
    border-radius: .5rem;
    background-color: var(--green);
    color: var(--white);
    font-size: 1.5rem;
    cursor: pointer;
    transition: background-color 0.3s;
    border: none;
}

.shopping-cart .cart-total .option-btn:hover {
    background-color: var(--blue);
}

.shopping-cart .cart-total .btn {
    display: inline-block;
    padding: 1rem 2rem;
    border-radius: .5rem;
    background-color: var(--orange);
    color: var(--white);
    font-size: 1.5rem;
    cursor: pointer;
    transition: background-color 0.3s;
    border: none;
}

.shopping-cart .cart-total .btn:hover {
    background-color: var(--red);
}

.shopping-cart .delete-btn {
    display: inline-block;
    padding: 1rem 2rem;
    border-radius: .5rem;
    background-color: var(--red);
    color: var(--white);
    font-size: 1.5rem;
    cursor: pointer;
    transition: background-color 0.3s;
    border: none;
    margin-top: 1rem;
}

.shopping-cart .delete-btn:hover {
    background-color: var(--black);
}

.shopping-cart .disabled {
    pointer-events: none;
    opacity: .5;
    user-select: none;
}

   </style>

</head>
<body>
   
<?php include 'navbar.php'; ?>

<div class="heading">
   <h3>Shopping Cart</h3>
       <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

   <p> <a href="home.php">Home</a> / Cart </p>
</div>

<section class="shopping-cart">

   <h1 class="title">Products Added</h1>

   <div class="box-container">
      <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {   
      ?>
      <div class="box">
         <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('Delete this from cart?');"></a>
         <center> <img src="../../admin/<?php echo $fetch_cart['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_cart['product_name']; ?></div>
         <div class="price">Rs.<?php echo $fetch_cart['price']; ?>/-</div>
         <form action="" method="post">
            <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
            <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['qty']; ?>">
            <input type="submit" name="update_cart" value="Update" class="option-btn">
         </form>
         <div class="sub-total"> Sub Total : <span>Rs.<?php echo $sub_total = ($fetch_cart['qty'] * $fetch_cart['price']); ?>/-</span> </div>
      </div>
      <?php
      $grand_total += $sub_total;
         }
      } else {
         echo '<p class="empty">Your cart is empty</p>';
      }
      ?>
   </div>

   <div style="margin-top: 2rem; text-align:center;">
      <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('Delete all from cart?');">Delete All</a>
   </div>

   <div class="cart-total">
      <p>Grand Total : <span>Rs.<?php echo $grand_total; ?>/-</span></p>
      <div class="flex">
         <a href="menu.php" class="option-btn">Continue Shopping</a>
         <a href="checkout.php" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
      </div>
   </div>

</section>

<?php include 'footer.php'; ?>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

</body>
</html>
