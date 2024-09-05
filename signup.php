<?php
session_start();

include("db/conn.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $cpassword = isset($_POST['CPassword']) ? $_POST['CPassword'] : '';

    if (!empty($username) && ctype_alpha($username)) {
        if (!empty($phone) && preg_match('/^(98|97|96)[0-9]{8}$/', $phone)) {
            if (!empty($email) && preg_match('/^[a-zA-Z0-9.%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,}$/', $email)) {
                if (!empty($password) && strlen($password) >= 8 && preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password) && preg_match('/\d/', $password) && preg_match('/[\W_]/', $password)) {
                    if ($password === $cpassword) {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        // Generate OTP (you can customize the length as per your requirement)
                        $otp = mt_rand(100000, 999999);

                        // Insert the new record if the email is not already registered
                        $check_query = "SELECT * FROM registration1 WHERE Email='$email'";
                        $check_result = mysqli_query($conn, $check_query);
                        if (mysqli_num_rows($check_result) > 0) {
                            echo "<script type='text/javascript'> alert('Email already exists. Please use a different email.')</script>";
                        } else {
                            // Insert user data into database
                            $query = "INSERT INTO registration1 (username, email, phone, password, otp) VALUES ('$username', '$email', '$phone', '$hashed_password', '$otp')";
                            if (mysqli_query($conn, $query)) {
                                // Send verification email with OTP
                                $mail = new PHPMailer();
                                $mail->isSMTP();
                                $mail->Host = 'smtp.gmail.com';
                                $mail->Port = 587;
                                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                                $mail->SMTPAuth = true;
                                $mail->Username = 'crashedtv0@gmail.com'; // Replace with your Gmail address
                                $mail->Password = 'fjvy qeqd nekq grqj'; // Replace with your Gmail password or App Password

                                $mail->setFrom('crashedtv0@gmail.com', 'CrashedTV'); // Replace with your name

                                $mail->addAddress($email); // Add a recipient

                                $mail->Subject = 'Verification OTP for Registration';
                                $mail->Body = "Your OTP for email verification is: $otp";

                                if ($mail->send()) {
                                    $_SESSION['email'] = $email;
                                    $_SESSION['otp'] = $otp;
                                    echo "<script type='text/javascript'>alert('Registered Successfully. Check your email for verification.')</script>";
                                    echo "<script>window.location.href='verify_otp.php';</script>";
                                    exit();
                                } else {
                                    echo "<script type='text/javascript'>alert('Failed to send verification email. Please try again later.')</script>";
                                }
                            } else {
                                echo "<script type='text/javascript'>alert('Failed to register. Please try again later.')</script>";
                            }
                        }
                    } else {
                        echo "<script type='text/javascript'> alert('Passwords do not match')</script>";
                    }
                } else {
                    echo "<script type='text/javascript'> alert('Please enter a valid password. The password should have a minimum length of 8 characters and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.')</script>";
                }
            } else {
                echo "<script type='text/javascript'> alert('Please enter a valid email')</script>";
            }
        } else {
            echo "<script type='text/javascript'> alert('Please enter a valid Nepali phone number')</script>";
        }
    } else {
        echo "<script type='text/javascript'> alert('Please enter username')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen flex items-center justify-center font-sans text-white bg-cover">
    <div class="signup w-[360px] bg-gray-800 mt-5px  border-2  ray-800 bg-opacity-70 rounded-md p-8">
        <h2 class="text-center text-4xl font-semibold">Register</h2>
        <h5 class="text-center text-lg mb-4">Please enter your details.</h5>
        <form method="POST">
            <div class="mb-4">
                <label class="block">User Name</label>
                <input type="text" name="username" class="w-full py-2 px-4 bg-gray-700 rounded-md text-white focus:outline-none focus:bg-gray-600" placeholder="First Name" required>
            </div>
           
            <div class="mb-4">
                <label class="block">Mobile</label>
                <input type="tel" name="phone" class="w-full py-2 px-4 bg-gray-700 rounded-md text-white focus:outline-none focus:bg-gray-600" placeholder="Mobile no.">
            </div>
            <div class="mb-4">
                <label class="block">Email</label>
                <input type="email" name="email" class="w-full py-2 px-4 bg-gray-700 rounded-md text-white focus:outline-none focus:bg-gray-600" placeholder="Email" required>
            </div>
            <div class="mb-4">
                <label class="block">Password</label>
                <input type="password" name="password" class="w-full py-2 px-4 bg-gray-700 rounded-md text-white focus:outline-none focus:bg-gray-600" placeholder="Password" required>
            </div>
            <div class="mb-4">
                <label class="block">Confirm Password</label>
                <input type="password" name="CPassword" class="w-full py-2 px-4 bg-gray-700 rounded-md text-white focus:outline-none focus:bg-gray-600" placeholder="Confirm Password" required>
            </div>
            <input type="submit" id="submit-btn" value="Register" class="w-full bg-blue-600 text-white py-3 px-6 rounded-md mt-4 hover:bg-blue-700 cursor-pointer">
        </form>
         
        <p class="text-center mt-4">Already have an account? <a href="login.php" class="text-blue-400">Login Here.</a>
        </p>
    </div>
</body>
</html>
