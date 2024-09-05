<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include '../db/conn.php';



$success = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $category_name = isset($_POST['category_name']) ? $_POST['category_name'] : '';

    // Check if the category already exists
    $sql_check = "SELECT * FROM add_categories WHERE Category = '$category_name'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        $error_message = "Category already exists";
    } else {
        // Insert category into the database
        $sql_insert = "INSERT INTO add_categories (Category) VALUES ('$category_name')";
        if ($conn->query($sql_insert) === TRUE) {
            $success = true;
        } else {
            $error_message = "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        <?php if ($success) : ?>
            alert("Category added successfully!");
        <?php elseif ($error_message) : ?>
            alert("<?php echo $error_message; ?>");
        <?php endif; ?>
    </script>
</head>

<body class="">
    <?php include 'Navbar.php'; ?>
    <div class="flex">
    <?php
        include 'Sidebar.php';
        ?>
        <div class="mt-8 ml-16 w-3/4">
            <h1 class="text-3xl font-bold mb-6 text-center">Add Category</h1>
            <form action="add_category.php" method="POST" class="max-w-md mx-auto">
                <div class="mb-6">
                    <label for="category_name" class="block text-sm font-bold mb-2">Category Name:</label>
                    <input type="text" id="category_name" name="category_name" class="px-4 py-2 border rounded-lg w-full focus:outline-none focus:border-blue-500" placeholder="Enter category name" required>
                </div>
                <div class="mb-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg w-58 transition duration-300">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
