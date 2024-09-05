document.addEventListener('DOMContentLoaded', () => {
    const addButtons = document.querySelectorAll('.add-btn');

    addButtons.forEach(button => {
        button.addEventListener('click', () => {
            const productElement = button.closest('.pro');
            const product = {
                id: productElement.getAttribute('data-product-id'),
                name: productElement.querySelector('h5').textContent,
                price: parseFloat(productElement.querySelector('h4').textContent.replace('Rs.', '').trim()),
                image: productElement.querySelector('img').src,
                quantity: 1
            };

            addToCart(product);
        });
    });

    function addToCart(product) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Check if the item already exists in the cart
        let existingItem = cart.find(item => item.id === product.id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push(product);
        }

        // Save the updated cart back to localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Update cart count
        updateCartCount();

        alert(`${product.name} has been added to your cart!`);
    }

    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
        document.getElementById('cart-count').textContent = cartCount;
    }

    // Initialize cart count on page load
    updateCartCount();
});
