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

// Check if a category is selected
if (isset($_GET['category'])) {
    $selected_category = $_GET['category'];
} else {
    // Default to the first category if none is selected
    $selected_category = $categories[0];
}

// Retrieve items based on the selected category
$sql_items = "SELECT * FROM add_items WHERE Item_Category = '$selected_category'";
$result_items = $conn->query($sql_items);

$items = array();
if ($result_items->num_rows > 0) {
    while ($row = $result_items->fetch_assoc()) {
        $items[] = $row;
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
        header("Location: manage_item.php?category=" . urlencode($selected_category) . "&update_message=Item updated successfully!");
        exit;
    } else {
        echo "Error updating item: " . $conn->error;
    }
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $item_id = $_GET['id'];
    $sql_delete = "DELETE FROM add_items WHERE Item_ID = '$item_id'";
    if ($conn->query($sql_delete) === TRUE) {
        header("Location: manage_item.php?category=" . urlencode($selected_category) . "&delete_message=Item deleted successfully!");
        exit;
    } else {
        echo "Error deleting item: " . $conn->error;
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
    <title>Manage Item</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body class="">
    <?php include 'Navbar.php'; ?>
    <div class="flex">
    <?php include 'Sidebar.php'; ?>
        <div class="mt-8 ml-16 w-3/4">
            <h1 class="text-3xl font-bold mb-6 text-center">Manage Item</h1>
            <div class="mb-6">
                <label for="category" class="block text-sm font-bold mb-2">Select Category:</label>
                <select id="category" name="category" class="px-4 py-2 border rounded-lg w-full focus:outline-none focus:border-blue-500" onchange="location = this.value;">
                    <?php foreach ($categories as $category) : ?>
                        <option value="manage_item.php?category=<?php echo urlencode($category); ?>" <?php if ($selected_category == $category) echo "selected"; ?>><?php echo $category; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-6">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2">Item Name</th>
                            <th class="px-4 py-2">Item Image</th>
                            <th class="px-4 py-2">Item Price</th>
                            <th class="px-4 py-2">Item Category</th>
                            <th class="px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item) : ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo $item['Item_Name']; ?></td>
                                <td class="border px-4 py-2">
                                    <img src="<?php echo $item['Item_Image']; ?>" alt="<?php echo $item['Item_Name']; ?>" class="w-20 h-20 object-cover">
                                </td>
                                <td class="border px-4 py-2"><?php echo $item['Item_Price']; ?></td>
                                <td class="border px-4 py-2"><?php echo $item['Item_Category']; ?></td>
                                <td class="border px-4 py-2">
                                    <a href="edit_item.php?id=<?php echo $item['Item_ID']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded-lg">Edit</a>
                                    <a href="manage_item.php?action=delete&category=<?php echo urlencode($selected_category); ?>&id=<?php echo $item['Item_ID']; ?>" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        <?php
        if (isset($_GET['delete_message'])) {
            $delete_message = $_GET['delete_message'];
            echo "alert('$delete_message');";
        }

        if (isset($_GET['update_message'])) {
            $update_message = $_GET['update_message'];
            echo "alert('$update_message');";
        }
        ?>
    </script>

</body>

</html>
