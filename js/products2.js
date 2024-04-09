// Function to add items to the cart
function addToCart(product_id,product_title, product_image, product_price) {

    // Getting existing cart items from session storage
    let cartItems = JSON.parse(sessionStorage.getItem('cart')) || [];

    const existingProductIndex = cartItems.findIndex(item => item.title === product_title);

    if (existingProductIndex !== -1) {

        // If the product is already in the cart, increasing the quantity
        cartItems[existingProductIndex].quantity++;
    } else {

        // If the product is not in the cart, adding it with quantity 1
        cartItems.push({
            productID:product_id,
            title: product_title,
            image: product_image,
            price: product_price,
            quantity: 1
        });
    }

    alert('Added to cart successfully.');

    // Saving the updated cart items to the session storage
    sessionStorage.setItem('cart', JSON.stringify(cartItems));

    // Updating the cart count in the header
    updateCartCount();
}

// Function to display the count of items
function updateCartCount() {
    const cartItems = JSON.parse(sessionStorage.getItem('cart')) || [];

    // ID 'cart-count' to display the count
    const totalCount = cartItems.reduce((count, item) => count + item.quantity, 0);
    document.getElementById('cart-count').textContent = totalCount;
}

// Function to display the product card
function viewProductDetail(id, name, image, price, desc, rating) {

    // Generating html for the product details
    var htmlTx = `
        <div class="product2">
            <img src="${image}" alt="${name}">
            <h3 style="color:#EECC61;font-weight:bold;">${name}</h3>
            <p align="left"><b>€${price}</b></p>
            <p style="text-align:justify;">${desc}</p>
            <p style="text-align:justify;">Rating: ${rating}</p>
        </div>`;

    // Generating html for the form
    var ratingHtml = `
    <form id="ratingForm" action='submitRating.php' method="post">
        <h2>Rate This Product</h2>

        <input type="hidden" name="product_id" value="${id}">

        <div class="formField">
            <label for="ratingTitle">Title:</label>
            <input type="text" id="ratingTitle" name="ratingTitle" required>
        </div>

        <div class="formField">
            <label for="ratingDescription">Description:</label>
            <textarea id="ratingDescription" name="ratingDescription" rows="4" required></textarea>
        </div>

        <div class="formField">
            <label for="ratingNumber">Rating:</label>
            <select id="ratingNumber" name="ratingNumber" required>
                <option value="">Select a Rating</option>
                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>
            </select>
        </div>

        <button type="submit">Submit Rating</button>
    </form>
`;

    // Updating html of the productDetails div
    document.getElementById('productDetails').innerHTML = htmlTx;
    document.getElementById('productRating').innerHTML = ratingHtml;

    // Adjusting visibility
    document.getElementById('products').style.display = 'none';
    document.getElementById('products2det').style.display = 'block';
}

// Function to filter products
function filterProducts(category) {

    // Getting all product containers
    var products = document.querySelectorAll('.product-container');

    // Display styling
    var displayStyle = 'block';

    // Looping through all products
    products.forEach(function(product) {
        // Check if the product matches the selected category or if 'all' is selected
        if (category === 'all' || product.getAttribute('data-category') === category) {
            product.style.display = displayStyle; // Show product using the determined display style
        } else {
            product.style.display = 'none'; // Hide product
        }
    });

    // Updating the active class for the category button
    var buttons = document.querySelectorAll('.categories .form-control');
    buttons.forEach(function(button) {
        console.log(category)
        if (button.id === category || (category === 'all' && button.id === 'all')) {
            button.classList.add('active');
        } else {
            button.classList.remove('active');
        }
    });
}