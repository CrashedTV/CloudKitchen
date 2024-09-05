document.addEventListener('DOMContentLoaded', () => {
    const gallery = document.getElementById('gallery');
    const images = [
        'img/kitchen/curry.jpg',
        'img/kitchen/matar paneer.jpg',
        'img/kitchen/momo1.jpg',
    ];

    images.forEach(src => {
        const img = document.createElement('img');
        img.src = src;
       
        gallery.appendChild(img);
    });
});


// navbar.js
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenu = document.getElementById('mobile-menu');
    const navbar = document.getElementById('navbar');

    mobileMenu.addEventListener('click', function() {
        navbar.classList.toggle('active');
    });
});