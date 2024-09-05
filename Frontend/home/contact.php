<?php
include '../../db/conn.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:../../login.php');
    exit();
}

$message = [];

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['send'])) {

    $user_id = $_SESSION['user_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND phone = '$phone' AND subject = '$subject' AND message = '$msg'") or die('query failed');

    if (mysqli_num_rows($select_message) > 0) {
        $message[] = 'Message already sent!';
    } else {
        $created_at = date('Y-m-d H:i:s'); // Current date and time
        mysqli_query($conn, "INSERT INTO `message` (user_id, name, phone, subject, message, created_at) VALUES ('$user_id', '$name', '$phone', '$subject', '$msg', '$created_at')") or die('query failed');
        $message[] = 'Message sent successfully!';
    }

    // Store messages in session to display after redirection
    $_SESSION['messages'] = $message;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Display stored messages and clear them from session
if (isset($_SESSION['messages'])) {
    foreach ($_SESSION['messages'] as $msg) {
        echo "<script>alert('$msg');</script>";
    }
    unset($_SESSION['messages']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloud Kitchen</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link rel="stylesheet" href="contact.css">
    <link rel="stylesheet" href="signup.css">
    <link rel="stylesheet" href="footer.css">
</head>

<body>

    <?php include 'navbar.php'; ?>

    <section id="contact" class="section-p1">
        <div class="contact-container">
            <div class="contact-info">
                <div class="info-item">
                    <i class="fas fa-phone-alt"></i>
                    <h4>Help Center</h4>
                    <p>+977-01-5902273 / +977-01-5902274</p>
                    <p>info@foodganj.com</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <h4>Address</h4>
                    <p>Nagpokhari, Naxal</p>
                    <p>Kathmandu, Nepal</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <h4>Marketing</h4>
                    <p>+977-01-5902273</p>
                    <p>marketing@foodganj.com</p>
                </div>
            </div>
            <div class="contact-form-map">
                <div class="contact-form">
                    <h3>Drop Us a Line</h3>
                    <form action="" method="POST">
                        <input type="text" name="name" placeholder="Name" required>
                        <input type="tel" name="phone" placeholder="Phone Number" required>
                        <input type="text" name="subject" placeholder="Subject" required>
                        <textarea name="message" placeholder="Message" rows="5" required></textarea>
                        <button type="submit" name="send" class="btn">Submit</button>
                    </form>
                </div>
                <div class="contact-map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3533.4825460205593!2d85.33856700000001!3d27.671477!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb19e8af4a5fe3%3A0x963d00cdf478c6b6!2sNepal%20College%20of%20Information%20Technology!5e0!3m2!1sen!2snp!4v1721914737999!5m2!1sen!2snp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="waves">
            <div class="wave" id="wave1"></div>
            <div class="wave" id="wave2"></div>
            <div class="wave" id="wave3"></div>
            <div class="wave" id="wave4"></div>
        </div>

        <ul class="social_icon">
            <li><a href=""><ion-icon name="logo-facebook"></ion-icon></a></li>
            <li><a href=""><ion-icon name="logo-instagram"></ion-icon></a></li>
            <li><a href=""><ion-icon name="logo-linkedin"></ion-icon></a></li>
        </ul>

        <ul class="menu">
            <li><a href="#">Home</a></li>
            <li><a href="#">Menu</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Our team</a></li>
            <li><a href="#">Contact us</a></li>
        </ul>

        <div class="contact">
            <p><ion-icon name="location-outline"></ion-icon> Tikathali,Lalitpur</p>
            <p><ion-icon name="call-outline"></ion-icon> 9860652398, 9864391793</p>
            <p><ion-icon name="mail-outline"></ion-icon> contact@dmagcloudkitchen.com</p>
        </div>

        <p>2024 © DMAGG Cloud Kitchen | All rights reserved.</p>
    </footer>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="signup.js"></script>
</body>

</html>
