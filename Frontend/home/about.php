    <?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMAGG: Cloud Kitchen - About Us</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="about.css">
</head>
<body>
        <?php
        include 'navbar.php';
    ?>
    

    <section id="about-us">
        <div class="about-us-container hover-box">
            <h2>About Us</h2>
            <p>Welcome to <strong>D-MAGG: Cloud Kitchen</strong>, your premier online kitchen serving delicious, freshly prepared meals right to your doorstep. We are dedicated to bringing you a diverse menu crafted with the finest ingredients, ensuring every bite is a delight.</p>
            <p>At D-MAGG, we believe that great food can bring people together, even when they're apart. Our cloud kitchen model allows us to focus on what matters most – the quality of our food and the satisfaction of our customers. Whether you're craving a hearty meal, a quick snack, or something sweet, we've got you covered.</p>
            <p>Our team of talented chefs works tirelessly to create a variety of dishes that cater to all tastes and dietary preferences. From traditional favorites to innovative new recipes, there's always something exciting to try. We are committed to sustainability and source our ingredients locally whenever possible, supporting our community and the environment.</p>
            <p>Thank you for choosing D-MAGG: Cloud Kitchen. We are thrilled to be a part of your dining experience and look forward to serving you the best meals with convenience and care.</p>
        </div>
        <div class="photo-gallery">
            <h3>Our Kitchen</h3>
            <div class="gallery" id="gallery">
            
            </div>
        </div>
    </section>

    <!-- footer section -->
    <footer>
        <div class="waves">
            <div class="wave" id="wave1"></div>
            <div class="wave" id="wave2"></div>
            <div class="wave" id="wave3"></div>
            <div class="wave" id="wave4"></div>
        </div>
        <ul class="social_icon">
            <li><a href="#"><ion-icon name="logo-facebook"></ion-icon></a></li>
            <li><a href="#"><ion-icon name="logo-instagram"></ion-icon></a></li>
            <li><a href="#"><ion-icon name="logo-linkedin"></ion-icon></a></li>
        </ul>
        <ul class="menu">
            <li><a href="#">Home</a></li>
            <li><a href="#">Menu</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Our team</a></li>
            <li><a href="#">Contact us</a></li>
        </ul>
        <div class="contact">
            <p><ion-icon name="location-outline"></ion-icon> Tikathali, Lalitpur</p>
            <p><ion-icon name="call-outline"></ion-icon> 9860652398, 9864391793</p>
            <p><ion-icon name="mail-outline"></ion-icon> contact@dmagcloudkitchen.com</p>
        </div>
        <p>2024 © DMAGG Cloud Kitchen | All rights reserved.</p>
    </footer>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="about.js"></script>
  
    
    
</body>
</html>