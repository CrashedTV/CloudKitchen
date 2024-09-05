<?php
include '../db/conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
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

// Check if an item is being edited
$edit_item = null;
if (isset($_GET['id'])) {
    $edit_item_id = $_GET['id'];
    $sql_edit_item = "SELECT * FROM add_items WHERE Item_ID = '$edit_item_id'";
    $result_edit_item = $conn->query($sql_edit_item);
    if ($result_edit_item->num_rows > 0) {
        $edit_item = $result_edit_item->fetch_assoc();
    }
}

// Handle form submission to update item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_item'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $item_category = $_POST['item_category'];
    $item_image = $_POST['item_image'];

    $sql_update = "UPDATE add_items SET Item_Name = '$item_name', Item_Price = '$item_price', Item_Category = '$item_category', Item_Image = '$item_image' WHERE Item_ID = '$item_id'";
    if ($conn->query($sql_update) === TRUE) {
        header("Location: manage_item.php?update_message=Item updated successfully!");
        exit;
    } else {
        echo "Error updating item: " . $conn->error;
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
    <title>Edit Item</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="">
    <div class="flex">
        <div class="mt-8 ml-16 w-3/4">
            <h1 class="text-3xl font-bold mb-6 text-center">Edit Item</h1>
            
            <?php if ($edit_item) : ?>
                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4">Edit Item</h2>
                    <form action="edit_item.php?id=<?php echo $edit_item['Item_ID']; ?>" method="POST">
                        <input type="hidden" name="item_id" value="<?php echo $edit_item['Item_ID']; ?>">
                        <div class="mb-4">
                            <label for="item_name" class="block text-sm font-bold mb-2">Item Name:</label>
                            <input type="text" id="item_name" name="item_name" value="<?php echo $edit_item['Item_Name']; ?>" class="px-4 py-2 border rounded-lg w-full focus:outline-none focus:border-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="item_price" class="block text-sm font-bold mb-2">Item Price:</label>
                            <input type="text" id="item_price" name="item_price" value="<?php echo $edit_item['Item_Price']; ?>" class="px-4 py-2 border rounded-lg w-full focus:outline-none focus:border-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="item_category" class="block text-sm font-bold mb-2">Item Category:</label>
                            <select id="item_category" name="item_category" class="px-4 py-2 border rounded-lg w-full focus:outline-none focus:border-blue-500" required>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?php echo $category; ?>" <?php if ($edit_item['Item_Category'] == $category) echo "selected"; ?>><?php echo $category; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="item_image" class="block text-sm font-bold mb-2">Item Image URL:</label>
                            <input type="text" id="item_image" name="item_image" value="<?php echo $edit_item['Item_Image']; ?>" class="px-4 py-2 border rounded-lg w-full focus:outline-none focus:border-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <button type="submit" name="update_item" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Update Item</button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4">Select an item to edit</h2>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        <?php
        if (isset($_GET['update_message'])) {
            $update_message = $_GET['update_message'];
            echo "alert('$update_message');";
        }
        ?>
    </script>

</body>

</html>
