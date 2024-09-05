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
