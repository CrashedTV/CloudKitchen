<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="footer.css">
    <link href="_NavBar.css">
    <style>
    :root {
    --black: #333;
    --white: #fff;
    --red: #e74c3c;
    --orange: #f39c12;
    --border: 1px solid #ddd;
    --box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f7f7f7;
    color: var(--black);
}

.heading {
    text-align: center;
    margin: 2rem 0;
    font-size: 2.5rem;
    color: var(--black);
}

.products {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2rem;
}

.box-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    width: 100%;
    max-width: 1200px;
}

.box {
    background-color: var(--white);
    border: var(--border);
    border-radius: .5rem;
    box-shadow: var(--box-shadow);
    padding: 2rem;
    text-align: center;
    transition: transform 0.3s ease-in-out;
}

.box:hover {
    transform: translateY(-5px);
}

.box img {
    width: 100%;
    height: auto;
    max-height: 200px;
    object-fit: contain;
    margin-bottom: 1rem;
}

.box h3 {
    font-size: 1.8rem;
    margin-bottom: 1rem;
    color: var(--black);
}

.price {
    font-size: 1.5rem;
    color: var(--red);
    margin-bottom: 1rem;
}

.qty {
    width: 50px;
    padding: .5rem;
    margin-right: 1rem;
    border: var(--border);
    border-radius: .5rem;
    text-align: center;
}

.btn,
.delete-btn {
    display: inline-block;
    padding: 0.5rem;
    margin-top: 1rem;
    background-color: var(--red);
    color: var(--white);
    text-decoration: none;
    border-radius: .5rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
    border: var(--red);
    font-size: 1rem;
}

.btn:hover,
.delete-btn:hover {
    background-color: var(--black);
}

.delete-btn {
    background-color: var(--orange);
}

/* Responsive Styles */
@media (max-width: 1024px) {
    .heading {
        font-size: 2rem;
    }

    .box-container {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .box {
        padding: 1.5rem;
    }

    .box h3 {
        font-size: 1.5rem;
    }

    .price {
        font-size: 1.2rem;
    }

    .btn,
    .delete-btn {
        font-size: 0.9rem;
        padding: 0.4rem;
    }
}

@media (max-width: 768px) {
    .heading {
        font-size: 1.8rem;
    }

    .box-container {
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
    }

    .box {
        padding: 1rem;
    }

    .box h3 {
        font-size: 1.3rem;
    }

    .price {
        font-size: 1rem;
    }

    .btn,
    .delete-btn {
        font-size: 0.8rem;
        padding: 0.3rem;
    }

    .qty {
        width: 45px;
        padding: 0.4rem;
    }
}

@media (max-width: 480px) {
    .heading {
        font-size: 1.5rem;
    }

    .box-container {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }

    .box {
        padding: 0.75rem;
    }

    .box h3 {
        font-size: 1.2rem;
    }

    .price {
        font-size: 0.9rem;
    }

    .btn,
    .delete-btn {
        font-size: 0.7rem;
        padding: 0.25rem;
    }

    .qty {
        width: 40px;
        padding: 0.35rem;
    }
}

    </style>
    <link rel="stylesheet" href="footer.css">

    </style>
</head>

<body>

    <?php
    include "navbar.php";
    ?>


    <!-- Hero Section -->
    <section id="hero">
        <div class="slider">
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
        </div>
        
        <h4>Discover</h4>
        <h2>The OG taste of Nepal</h2>
        <h1>Nepali Chulo</h1>
        <p>Order Your Favourite Nepali Food Anytime Anywhere!</p>
        <a href="menu.php">
            <button>Order Now</button>
        </a>
    </section>

    <script>
        let currentIndex = 0;
        const slides = document.querySelectorAll('.slide');

        function showNextSlide() {
            currentIndex = (currentIndex + 1) % slides.length;
            const offset = currentIndex * -100;
            document.querySelector('.slider').style.transform = `translateX(${offset}%)`;
        }

        setInterval(showNextSlide, 3000);
    </script>
    
    <!-- Menu Section  -->
    <div id="our-menu">
        <h2>Our Menu</h2>
    </div>
    <div class="menu-categories">
        <button class="active" onclick="showMenu('veg')">Veg</button>
        <button onclick="showMenu('non-veg')">Non-Veg</button>
        <button onclick="showMenu('dal-bhat')">Dal Bhat</button>
        <button onclick="showMenu('momo')">Momo</button>
    </div>

    <div id="veg" class="menu-section active">
        <h2>Veg Menu</h2>
        <div class="menu-item">
            <h3>Vegetable Thukpa</h3>
            <p>Noodle soup with mixed vegetables and Nepali spices</p>
        </div>
        <div class="menu-item">
            <h3>Aloo Tama</h3>
            <p>Potato and bamboo shoot curry</p>
        </div>
        <div class="menu-item">
            <h3>Gundruk Sadeko</h3>
            <p>Fermented leafy greens mixed with spices and herbs</p>
        </div>
        <a href="menu.php">
            <button class="order-button">Order Now</button></a>
    </div>

    <div id="non-veg" class="menu-section">
        <h2>Non-Veg Menu</h2>
        <div class="menu-item">
            <h3>Chicken Sekuwa</h3>
            <p>Grilled chicken marinated with Nepali spices</p>
        </div>
        <div class="menu-item">
            <h3>Buff Choila</h3>
            <p>Spicy grilled buffalo meat</p>
        </div>
        <div class="menu-item">
            <h3>Kukhura ko Masu</h3>
            <p>Traditional Nepali chicken curry</p>
        </div>
        <button class="order-button">Order Now</button>
    </div>

    <div id="dal-bhat" class="menu-section">
        <h2>Dal Bhat</h2>
        <div class="menu-item">
            <h3>Dal Bhat Tarkari</h3>
            <p>Lentil soup served with rice and seasonal vegetables</p>
        </div>
        <div class="menu-item">
            <h3>Dal Bhat with Chicken Curry</h3>
            <p>Lentil soup served with rice and chicken curry</p>
        </div>
        <div class="menu-item">
            <h3>Dal Bhat with Fish Curry</h3>
            <p>Lentil soup served with rice and fish curry</p>
        </div>
        <button class="order-button">Order Now</button>
    </div>

    <div id="momo" class="menu-section">
        <h2>Momo</h2>
        <div class="menu-item">
            <h3>Veg Momo</h3>
            <p>Steamed dumplings stuffed with vegetables</p>
        </div>
        <div class="menu-item">
            <h3>Chicken Momo</h3>
            <p>Steamed dumplings stuffed with chicken</p>
        </div>
        <div class="menu-item">
            <h3>Buff Momo</h3>
            <p>Steamed dumplings stuffed with buffalo meat</p>
        </div>
        <button class="order-button">Order Now</button>
    </div>
    <br><br>
    <section class="products">

        <h1 class="heading">
            <center>All Products</center>
        </h1>

        <?php
        if (isset($_SESSION['message'])) {
            echo '<script>alert("' . htmlspecialchars($_SESSION['message']) . '");</script>';
            unset($_SESSION['message']); // Clear the message after displaying it
        }
        ?>

        <div class="box-container">

            <?php
            $select_products = $conn->prepare("SELECT * FROM add_items");
            $select_products->execute();
            $result_select_products = $select_products->get_result();
            if ($result_select_products->num_rows > 0) {
                while ($fetch_product = $result_select_products->fetch_assoc()) {
            ?>
                    <form action="add_to_cart.php" method="POST" class="box">
                        <img src="../../admin/<?= htmlspecialchars($fetch_product['Item_Image']); ?>" class="image" alt="">
                        <h3 class="name"><?= htmlspecialchars($fetch_product['Item_Name']); ?></h3>
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($fetch_product['Item_ID']); ?>">
                        <div class="info">
                            <p class="price">Rs. <?= htmlspecialchars($fetch_product['Item_Price']); ?></p>
                            <div class="qty-container">
                                <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
                            </div>
                        </div>
                        <input type="submit" name="add_to_cart" value="Add to Cart" class="btn">
                        <a href="checkout.php?get_id=<?= htmlspecialchars($fetch_product['Item_ID']); ?>" class="delete-btn">Buy Now</a>
                    </form>
            <?php
                }
            } else {
                echo '<p class="empty">No products found!</p>';
            }
            $select_products->close();
            ?>

        </div>

    </section>


    <!-- Banner -->
    <section id="page-header">
        <h2>#OrderFromYourHome</h2>
        <p>Order Your Favourite Nepali Food Anytime Anywhere!</p>
    </section>

    <!-- Why Choose Us -->
    <h2 class="title">Why Choose Us?</h2>
    <section id="feature" class="section-p1">
        <div class="fe-box">
            <div class="fe-box-inner">
                <img src="img/feature/f1.jpg" alt="Reasonable Price">
                <h6>Reasonable Price</h6>
            </div>
        </div>
        <div class="fe-box">
            <div class="fe-box-inner">
                <img src="img/feature/f2.jpg" alt="Fast Delivery">
                <h6>Fast Delivery</h6>
            </div>
        </div>
        <div class="fe-box">
            <div class="fe-box-inner">
                <img src="img/feature/f3.jpg" alt="Easy Payment">
                <h6>Easy Payment</h6>
            </div>
        </div>
        <div class="fe-box">
            <div class="fe-box-inner">
                <img src="img/feature/f4.jpg" alt="24h Support">
                <h6>24h Support</h6>
            </div>
        </div>
    </section>

    <!-- FeedBack Section -->
    <section id="feedback" class="section-p1">
        <h2>Feedback</h2>
        <p>We value your feedback to improve our service.</p>
        <div class="feedback-container">
            <form id="feedbackForm" action="submit_feedback.php" method="post">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
                <span id="nameError" class="error-message"></span>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <span id="emailError" class="error-message"></span>

                <label for="feedback">Your Feedback</label>
                <textarea id="feedback-message" name="feedback" rows="5" required></textarea>

                <button type="submit" onclick="validateForm()">Submit</button>
            </form>
            <div id="successMessage" class="success-message"></div>
        </div>
    </section>

    <!-- Footer Section  -->
    <?php include 'footer.php'; ?>
            
    <script src="js/script.js"></script>
    <script src="home.js"></script>
    
</body>

</html>