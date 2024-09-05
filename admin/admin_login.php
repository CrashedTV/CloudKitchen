<?php
session_start();
include '../db/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate credentials
    $query = "SELECT * FROM admin WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Directly compare the plaintext passwords
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $email;
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Invalid Email or Password.";
            }
        } else {
            $error = "Invalid Email or Password.";
        }
    } else {
        echo "Database Query Failed: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen flex items-center justify-center bg-gray-800 text-white">
    <div class="w-[360px] bg-gray-700 bg-opacity-80 rounded-md p-8">
        <h2 class="text-center text-3xl font-semibold">Login</h2>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 text-center"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label for="email" class="block text-sm">Email</label>
                <input type="email" id="email" name="email" required class="w-full bg-gray-600 text-white py-2 px-3 rounded-md">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm">Password</label>
                <input type="password" id="password" name="password" required class="w-full bg-gray-600 text-white py-2 px-3 rounded-md">
            </div>
            <input type="submit" value="Login" class="w-full bg-blue-600 text-white py-3 px-6 rounded-md mt-4 hover:bg-blue-700 cursor-pointer">
        </form>
    </div>
</body>
</html>
