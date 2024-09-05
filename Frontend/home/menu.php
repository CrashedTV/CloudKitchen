<?php
session_start();
include '../../db/conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    
    <link rel="stylesheet" href="_NavBar.css">
    <link rel="stylesheet" href="home.css">
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
    margin: 2rem 0;
    font-size: 2rem;
    color: var(--black);
}

.products {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem;
}

.box-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
    width: 100%;
    max-width: 1200px;
}

.box {
    background-color: var(--white);
    border: var(--border);
    border-radius: .5rem;
    box-shadow: var(--box-shadow);
    padding: 1rem;
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
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: var(--black);
}

.price {
    font-size: 1.2rem;
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

/* Media Queries for responsiveness */
@media (min-width: 600px) {
    .heading {
        font-size: 2.5rem;
    }
}

@media (min-width: 768px) {
    .box {
        padding: 2rem;
    }

    .box h3 {
        font-size: 1.8rem;
    }

    .price {
        font-size: 1.5rem;
    }
}

@media (min-width: 1024px) {
    .products {
        padding: 2rem;
    }

    .box-container {
        grid-template-columns: repeat(4, 1fr);
    }

    .box h3 {
        font-size: 2rem;
    }

    .price {
        font-size: 1.7rem;
    }
}

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
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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
</head>

<body>

    <?php include 'navbar.php'; ?>

    <section class="products">


        <?php
        if (isset($_SESSION['message'])) {
            echo '<script>alert("' . htmlspecialchars($_SESSION['message']) . '");</script>';
            unset($_SESSION['message']); // Clear the message after displaying it
        }
        ?>

        <?php
        // Fetch all categories
        $sql_categories = "SELECT * FROM add_categories";
        $result_categories = $conn->query($sql_categories);

        if ($result_categories->num_rows > 0) {
            while ($category = $result_categories->fetch_assoc()) {
                $category_name = htmlspecialchars($category['Category']);

                // Fetch products based on category
                $stmt = $conn->prepare("SELECT * FROM `add_items` WHERE `Item_Category` = ?");
                $stmt->bind_param("s", $category_name);
                $stmt->execute();
                $result_products = $stmt->get_result();

                if ($result_products->num_rows > 0) {
                    echo '<h2 class="heading"><center>' . $category_name . '</center></h2>';
                    echo '<div class="box-container">';
                    
                    while ($product = $result_products->fetch_assoc()) {
        ?>
                        <form action="add_to_cart.php" method="POST" class="box">
                            <img src="../../admin/<?= htmlspecialchars($product['Item_Image']); ?>" class="image" alt="">
                            <h3 class="name"><?= htmlspecialchars($product['Item_Name']); ?></h3>
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['Item_ID']); ?>">
                            <div class="info">
                                <p class="price">Rs. <?= htmlspecialchars($product['Item_Price']); ?></p>
                                <div class="qty-container">
                                    <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
                                </div>
                            </div>
                            <input type="submit" name="add_to_cart" value="Add to Cart" class="btn">
                            <a href="checkout.php?get_id=<?= htmlspecialchars($product['Item_ID']); ?>" class="delete-btn">Buy Now</a>
                        </form>
        <?php
                    }
                    echo '</div>';
                }

                $stmt->close();
            }
        } else {
            echo '<p class="empty">No categories found!</p>';
        }
        $conn->close();
        ?>

    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'alert.php'; ?>
    <script src="home.js"></script>
    <script src="js/script.js"></script>

</body>

</html>
