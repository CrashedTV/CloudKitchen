<?php
session_start();
include '../../db/conn.php';

function create_unique_id() {
    return bin2hex(random_bytes(16));
}

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = create_unique_id();
    setcookie('user_id', $user_id, time() + 60 * 60 * 24 * 30, "/");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Page</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Include your styles here */
        :root {
            --border: 1px solid #ccc;
            --box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            --white: #fff;
            --black: #333;
            --light-color: #999;
            --light-bg: #f7f7f7;
            --red: #e74c3c;
            --orange: #f39c12;
            --blue: #3498db;
            --green: #2ecc71;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
            grid-template-columns: repeat(3, 1fr);
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

        .qty-input {
            width: 50px;
            padding: .5rem;
            margin-bottom: 1rem;
            border: var(--border);
            border-radius: .5rem;
            text-align: center;
        }

        .btn,
        .buy-now {
            display: inline-block;
            padding: 0.5rem 1rem;
            margin-top: 1rem;
            background-color: var(--red);
            color: var(--white);
            text-decoration: none;
            border-radius: .5rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border: none;
            font-size: 1rem;
            margin: 0.5rem;
        }

        .btn:hover,
        .buy-now:hover {
            background-color: var(--black);
        }

        .buy-now {
            background-color: var(--orange);
        }

        .search-form {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .search-form form {
            display: flex;
            width: 100%;
            max-width: 600px;
        }

        .search-form .box {
            flex: 1;
            padding: 1rem;
            border: var(--border);
            border-radius: .5rem;
            margin-right: .5rem;
            font-size: 1rem;
        }

        .search-form button {
            padding: 1rem;
            border: none;
            background-color: var(--blue);
            color: var (--white);
            font-size: 1.2rem;
            border-radius: .5rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-form button:hover {
            background-color: var(--black);
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <section class="search-form">
        <form method="post" action="">
            <input type="text" name="search_box" placeholder="Search here..." class="box">
            <button type="submit" name="search_btn" class="fas fa-search"></button>
        </form>
    </section>

    <section class="products" style="min-height: 100vh; padding-top: 0;">
        <div class="box-container">
            <?php
            if (isset($_POST['search_box']) || isset($_POST['search_btn'])) {
                $search_box = $_POST['search_box'];
                $stmt = $conn->prepare("SELECT * FROM `add_items` WHERE item_name LIKE ?");
                $like_search_box = "%" . $search_box . "%";
                $stmt->bind_param("s", $like_search_box);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($fetch_products = $result->fetch_assoc()) {
            ?>
                        <form action="add_to_cart.php" method="post" class="box">
                            <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['Item_ID']); ?>">
                            <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_products['Item_Name']); ?>">
                            <input type="hidden" name="price" value="<?= htmlspecialchars($fetch_products['Item_Price']); ?>">
                            <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_products['Item_Image']); ?>">
                            <?php
                            $img_path = '../../admin/' . htmlspecialchars($fetch_products['Item_Image']);
                            echo '
                            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                            <img src="' . $img_path . '" class="w-full h-48 object-cover" alt="' . htmlspecialchars($fetch_products['Item_Name']) . '">
                            <div class="p-4">
                            <h3 class="text-lg text-center font-semibold">' . htmlspecialchars($fetch_products['Item_Name']) . '</h3>
                            <p class="price">Rs. ' . htmlspecialchars($fetch_products['Item_Price']) . '</p>
                            </div>
                            </div>';
                            ?>
                            <input type="number" name="qty" value="1" min="1" class="qty-input"><br>
                            <div class="flex justify-center space-x-2">
                                <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                                <a href="checkout.php?pid=<?= htmlspecialchars($fetch_products['Item_ID']); ?>" class="buy-now">Buy Now</a>
                            </div>
                        </form>
            <?php
                    }
                } else {
                    echo '<p class="empty">No products found!</p>';
                }

                $stmt->close();
            }
            ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>
