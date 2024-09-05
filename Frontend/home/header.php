<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link rel="stylesheet" href="header.css">
</head>
<body>
   
</body>
</html>
<header class="header">

   <section class="flex">
      <a href="#" class="logo">Logo</a>

      <nav class="navbar">
         <a href="add_product.php">add product</a>
         <a href="view_products.php">view products</a>
         <a href="orders.php">my orders</a>
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->bind_param("s", $user_id);
            $count_cart_items->execute();
            $count_cart_items_result = $count_cart_items->get_result();
            $total_cart_items = $count_cart_items_result->num_rows;
         ?>
         <a href="shopping_cart.php" class="cart-btn">cart<span><?= htmlspecialchars($total_cart_items); ?></span></a>
      </nav>

      <div id="menu-btn" class="fas fa-bars"></div>
   </section>

</header>
