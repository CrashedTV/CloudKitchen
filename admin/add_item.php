<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include '../db/conn.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $item_name = isset($_POST['item_name']) ? $_POST['item_name'] : '';
    $item_price = isset($_POST['item_price']) ? $_POST['item_price'] : '';
    $item_category = isset($_POST['item_category']) ? $_POST['item_category'] : '';

    // Check if the item name already exists
    $sql_check_item = "SELECT * FROM add_items WHERE Item_Name = '$item_name'";
    $result_check_item = $conn->query($sql_check_item);

    if ($result_check_item->num_rows > 0) {
        $item_exists = true;
    } else {
        // Handle file upload
        $target_dir = "uploads/"; // Directory where uploaded files will be saved
        $target_file = $target_dir . basename($_FILES["item_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["item_image"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $item_error_message = "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check file size
        if ($_FILES["item_image"]["size"] > 500000) {
            $item_error_message = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "webp"
        ) {
            $item_error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $item_error_message .= " Your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
                // Insert item into the database
                $sql_insert_item = "INSERT INTO add_items (Item_Name, Item_Image, Item_Price, Item_Category) VALUES ('$item_name', '$target_file', '$item_price', '$item_category')";
                if ($conn->query($sql_insert_item) === TRUE) {
                    $item_success = true;
                } else {
                    $item_success = false;
                    $item_error_message = "Error: " . $sql_insert_item . "<br>" . $conn->error;
                }
            } else {
                $item_error_message = "Sorry, there was an error uploading your file.";
            }
        }
    }
}

// Retrieve categories from the database
$sql_categories = "SELECT * FROM add_categories";
$result_categories = $conn->query($sql_categories);

$categories = array();
if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = $row['Category'];
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
    <title>Add Item</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    
</head>

<body class="">
    <?php include 'Navbar.php'; ?>
    <div class=" flex">
        <?php
        include 'Sidebar.php';
        ?>

        <div class="mt-8 ml-16 w-3/4">
            <h1 class="text-3xl font-bold mb-6 text-center">Add Item</h1>
            <form action="add_item.php" method="POST" class="max-w-md mx-auto" enctype="multipart/form-data">
                <div class="mb-6">
                    <label for="item_name" class="block text-sm font-bold mb-2">Item Name:</label>
                    <input type="text" id="item_name" name="item_name" class="px-4 py-2 border rounded-lg w-full focus:outline-none focus:border-blue-500" placeholder="Enter item name" required>
                </div>
                <div class="mb-6">
                    <label for="item_image" class="block text-sm font-bold mb-2">Item Image:</label>
                    <input type="file" id="item_image" name="item_image" class="px-4 py-2 border rounded-lg w-full focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-6">
                    <label for="item_price" class="block text-sm font-bold mb-2">Item Price:</label>
                    <input type="text" id="item_price" name="item_price" class="px-4 py-2 border rounded-lg w-full focus:outline-none focus:border-blue-500" placeholder="Enter item price" required>
                </div>
                <div class="mb-6">
                    <label for="item_category" class="block text-sm font-bold mb-2">Item Category:</label>
                    <select id="item_category" name="item_category" class="px-4 py-2 border rounded-lg w-full focus:outline-none focus:border-blue-500" required>
                        <option value="">Select category</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg w-full transition duration-300">Add Item</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (isset($item_success) && $item_success) : ?>
        <script>
            alert("Item added successfully!");
        </script>
    <?php elseif (isset($item_exists) && $item_exists) : ?>
        <script>
            alert("Item already exists!");
        </script>
    <?php elseif (isset($item_error_message)) : ?>
        <div class="mt-4 text-red-500"><?php echo $item_error_message; ?></div>
    <?php endif; ?>
</body>

</html>