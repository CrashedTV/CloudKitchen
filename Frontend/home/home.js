document.addEventListener('DOMContentLoaded', () => {
    // Function to toggle navigation on small screens
    function toggleNavbar() {
        const navbar = document.querySelector('#navbar');
        const navbarToggle = document.querySelector('#navbar-toggle');
        navbar.classList.toggle('active');
        
        // Toggle between bars and cross icons
        if (navbar.classList.contains('active')) {
            navbarToggle.querySelector('.fa-bars').style.display = 'none';
            navbarToggle.querySelector('.fa-times').style.display = 'inline-block';
        } else {
            navbarToggle.querySelector('.fa-bars').style.display = 'inline-block';
            navbarToggle.querySelector('.fa-times').style.display = 'none';
        }
    }

    // Event listener for navbar toggle button (bars icon)
    document.querySelector('#navbar-toggle .fa-bars').addEventListener('click', () => {
        toggleNavbar();
    });

    // Event listener for cross icon to revert to bars icon
    document.querySelector('#navbar-toggle .fa-times').addEventListener('click', () => {
        toggleNavbar(); // Close the navbar
    });
});


// Menu Section 
function showMenu(menuId) {
    const sections = document.querySelectorAll('.menu-section');
    const buttons = document.querySelectorAll('.menu-categories button');

    sections.forEach(section => {
        section.classList.remove('active');
    });

    buttons.forEach(button => {
        button.classList.remove('active');
    });

    document.getElementById(menuId).classList.add('active');
    document.querySelector(`.menu-categories button[onclick="showMenu('${menuId}')"]`).classList.add('active');
}

//Gallery Section JS
document.addEventListener('DOMContentLoaded', () => {
    const gallery = document.getElementById('gallery');
    const images = [
        'img/kitchen/bara.jpg',
        'img/kitchen/Chatamari.jpg',
        'img/kitchen/choila.png',
        'img/kitchen/curry.jpg',
        'img/kitchen/matar paneer.jpg',
        'img/kitchen/momo1.jpg',
        'img/kitchen/pizza.jpg',
        'img/kitchen/thakali.jpg',
        'img/kitchen/thukpa.jpg',
    ];

    images.forEach(src => {
        const img = document.createElement('img');
        img.src = src;

        gallery.appendChild(img);
    });
});


