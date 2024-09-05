<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
</head>
<body>
<sidebar class="w-1/4 border-r-2 border-black flex flex-col text-xl font-bold text-right text-purple-800 gap-12 h-screen p-4">
            <ul class="flex flex-col gap-6 ">
                <li class="flex items-center border-2 border-black rounded-lg">
                    <a href="dashboard.php" class="flex items-center gap-2 p-2">
                    <i class="fa fa-pie-chart" aria-hidden="true"></i>
                        DashBoard
                    </a>
                </li>
                <li class="flex items-center border-2 border-black rounded-lg">
                    <a href="add_category.php" class="flex items-center gap-2 p-2">
                        <img class="w-6 h-6" src="images/add_icon.png" alt="Add Icon">
                        Add Category
                    </a>
                </li>
                <li class="flex items-center border-2 border-black rounded-lg">
                    <a href="add_item.php" class="flex items-center gap-2 p-2">
                        <img class="w-6 h-6" src="images/add_icon.png" alt="Add Icon">
                        Add Item
                    </a>
                </li>
                <li class="flex items-center border-2 border-black rounded-lg">
                    <a href="manage_item.php" class="flex items-center gap-2 p-2">
                    <i class="fa-solid fa-wrench"></i>
                        Manage Item
                    </a>
                </li>
                <li class="flex items-center border-2 border-black rounded-lg">
                    <a href="manage_order.php" class="flex items-center gap-2 p-2">
                        <img class="w-6 h-6" src="images/order_icon.png" alt="Add Icon">
                        Manage Order
                    </a>
                </li>
                <li class="flex items-center border-2 border-black rounded-lg">
                    <a href="admin_message.php" class="flex items-center gap-2 p-2">
                    <i class="fa fa-envelope-open" aria-hidden="true"></i>
                        Messages
                    </a>
                </li>
            </ul>
        </sidebar>
</body>
</html>