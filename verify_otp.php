<?php
session_start();

include("db/conn.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $otp_entered = isset($_POST['otp']) ? trim($_POST['otp']) : '';

    if (!empty($otp_entered)) {
        if (isset($_SESSION['email']) && isset($_SESSION['otp'])) {
            $email = $_SESSION['email'];
            $otp_stored = $_SESSION['otp'];

            if ($otp_entered == $otp_stored) {
                // OTP matched, update user status or redirect to login page
                $update_query = "UPDATE registration1 SET verified = 1 WHERE email = '$email'";
                if (mysqli_query($conn, $update_query)) {
                    // Clear OTP from session
                    unset($_SESSION['otp']);
                    echo "<script type='text/javascript'>alert('Email verification successful. You can now login.')</script>";
                    header("Location: login.php");
                    exit();
                } else {
                    echo "<script type='text/javascript'>alert('Failed to verify email. Please try again later.')</script>";
                }
            } else {
                echo "<script type='text/javascript'>alert('Invalid OTP. Please enter the correct OTP.')</script>";
            }
        } else {
            echo "<script type='text/javascript'>alert('Session expired. Please register again.')</script>";
            header("Location: register.php");
            exit();
        }
    } else {
        echo "<script type='text/javascript'>alert('Please enter OTP to verify your email.')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen flex items-center justify-center font-sans text-white bg-cover">
    <div class="verify-otp w-[360px] bg-gray-800 mt-5px  border-2  ray-800 bg-opacity-70 rounded-md p-8">
        <h2 class="text-center text-4xl font-semibold">Verify OTP</h2>
        <h5 class="text-center text-lg mb-4">Please enter the OTP sent to your email.</h5>
        <form method="POST">
            <div class="mb-4">
                <input type="text" name="otp" class="w-full py-2 px-4 bg-gray-700 rounded-md text-white focus:outline-none focus:bg-gray-600" placeholder="Enter OTP" required>
            </div>
            <input type="submit" id="submit-btn" value="Verify OTP" class="w-full bg-blue-600 text-white py-3 px-6 rounded-md mt-4 hover:bg-blue-700 cursor-pointer">
        </form>
         
        <p class="text-center mt-4">Didn't receive OTP? <a href="#" class="text-blue-400">Resend OTP</a>
        </p>
    </div>
</body>
</html>
