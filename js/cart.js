// Function to display items in the cart
function displayItemsInCart() {

    // Getting the table body
    const tableBody = document.getElementById("table_body");
    tableBody.innerHTML = ""; // Clearing existing items

    // Initializing total price
    let totalPrice = 0;
    let cartItems = JSON.parse(sessionStorage.getItem('cart')) || []; // Retrieve cart items from session storage

    // Iterating over all items in the cart
    cartItems.forEach((item, index) => {

        // Creating a table row and fill it with the cart item data
        let row = document.createElement("tr");
        row.innerHTML = `
            <td>${index + 1}</td>
            <td><img class='item_img' src='${item.image}' alt='${item.title}'></td>
            <td>${item.title}</td>
            <td>€${parseFloat(item.price).toFixed(2)}</td>
            <td>${item.quantity}</td>
            <td>
                <button onclick="removeItemFromCart(${index})">Remove</button>
            </td>
        `;

        // Appending the row to the table body
        tableBody.appendChild(row);

        totalPrice += parseFloat(item.price) * item.quantity; // Adding the item price to the total
    });

    // Displaying the total price
    const purchasePriceElement = document.getElementById('purchase_price');
    purchasePriceElement.textContent = `Total: €${totalPrice.toFixed(2)}`;
}

// Function to remove 1 particular item from the cart
function removeItemFromCart(index) {

    // Getting the current array of cart items
    let cartItems = JSON.parse(sessionStorage.getItem('cart')) || [];

    // Confirmation before removing the item
    let clear = confirm("Are you sure you want to remove the item?");

    if (clear) {

        // Checking if the item quantity is > 1
        if (cartItems[index].quantity > 1) {

            // Decreasing the quantity
            cartItems[index].quantity--;
        } else {

            // If the quantity = 1, removing item from array
            cartItems.splice(index, 1);
        }

        // Saving the updated array back to the session storage
        sessionStorage.setItem('cart', JSON.stringify(cartItems));
        alert("Item has been removed.");

        // Refreshing the cart display
        displayItemsInCart();
    }
}

// When the page is loading to display cart items
document.addEventListener('DOMContentLoaded', displayItemsInCart);


// Function to clear the whole cart
function clearCart() {
    if (sessionStorage.length > 0) {
        let clear = confirm("Are you sure you want to clear the cart?");
        if (clear) {
            sessionStorage.clear();
            alert("Cart has been cleared.");
        }
    } else {
        alert("The cart is already empty!");
    }
}

// Function to send cart items to the server
function sendCartToServer() {

    // Retrieving cart items from session storage
    let cartItems = sessionStorage.getItem('cart');

    // Check if 'cartItems' is a stringified JSON array
    if (cartItems) {
        try {

            // Parsing it to ensure it's valid JSON
            JSON.parse(cartItems);

            // Fetch request to send this JSON to the server
            fetch('handleCart.php', {
                method: 'POST',
                headers: {

                    // Sending JSON content type
                    'Content-Type': 'application/json'
                },

                // Sending the actual cart items as JSON
                body: cartItems
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok.');
                    }

                    // Assuming your PHP script returns JSON
                    return response.json();
                })
                .then(data => {

                    // Handling the response from the server
                    console.log('Server response:', data);

                    // Updating the cart display with the new data
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        } catch (error) {
            console.error('Parsing error:', error);
        }
    }
}

// Calling this function when the cart page loads
sendCartToServer();




