<?php
session_start();
$connection = mysqli_connect("localhost", "kkharkivska", "a24p7jqz", "kkharkivska");

// Checking if the form has been submitted
if (isset($_POST['checkout'])) {

    // Checking if the user id is set
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

    } else {
        echo '<p class="alert alert-error">No user is currently logged in. Please <a href="signIn.php">log in</a> to proceed.</p>';
        exit();
    }

    // Retrieving cart items from the POST data
    if (isset($_POST['productIDs'])) {
        // Decoding JSON string into the array
        $cartItemsArray = json_decode($_POST['productIDs'], true);

        // Checking if decoding was successful and if $cartItemsArray is the array
        if (is_array($cartItemsArray)) {
            // Sanitize each element of the array to integers
            $cartProductIds = array_map('intval', $cartItemsArray);
        } else {
            // If decoding was not successful or $cartItemsArray is not the array
            echo "Error: Invalid cart data";
            exit();
        }
    } else {
        echo "Error: Cart data not provided";
        exit();
    }


    // Proceed if we have product ids
    if (!empty($cartProductIds)) {
        // Creating placeholders for the prepared statement
        $inQuery = implode(',', $cartProductIds); // Ensuring they are integers
        $productQuery = "SELECT product_id FROM tbl_products WHERE product_id IN ($inQuery)";

         // Preparing the statement
         $stmt = mysqli_prepare($connection, $productQuery);

         // Checking if the statement is prepared successfully
         if ($stmt) {
             // Executing the statement
    mysqli_stmt_execute($stmt);

    // Result set
    $result = mysqli_stmt_get_result($stmt);

    // Checking if the result is not empty
    if ($result) {
        // Fetching data from the result
        while ($row = mysqli_fetch_assoc($result)) {
            // Accessing each row of data
        }

        // Result is free
        mysqli_free_result($result);
    } else {
        echo "Error: Unable to get result set";
    }

    // Closing the statement
    mysqli_stmt_close($stmt);
         } else {
             echo "Error preparing statement: " . mysqli_error($connection);
             exit();
         }
    }

    // Checking if the connection was successful
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $orderDate = date('Y-m-d H:i:s');
$query = "INSERT INTO tbl_orders (order_date, user_id, product_ids) VALUES (?, ?, ?)";

// Converting the array of product ids into a comma-separated string
$productIdsString = implode(',', $cartProductIds);

// Creating a prepared statement
if ($stmt1 = mysqli_prepare($connection, $query)) {
    // Binding parameters to the statement
    mysqli_stmt_bind_param($stmt1, "sis", $orderDate, $userId, $productIdsString);

    // Executing the statement
    if (mysqli_stmt_execute($stmt1)) {
        echo '<script>sessionStorage.removeItem("cart");alert("Order successfully saved.");</script>';

    } else {
        echo "Error: " . mysqli_error($connection);
    }

    // Closing the statement
    mysqli_stmt_close($stmt1);
} else {
    echo "Error preparing statement: " . mysqli_error($connection);
}


    // Closing the connection
    mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student's Union Shop - Cart</title>

    <!-- Links to the main stylesheet -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Links to a specifique stylesheet for the cart page -->
    <link rel="stylesheet" type="text/css" href="css/cart.css">

</head>

<body>
    <header>
        <img src="images_assignment/logo.png" alt="Shop Logo">
        <h1>Student's Union Shop</h1>

        <button id="menuButton">☰</button> <!-- Burger Menu Button -->
        <!-- Navigation bar to be redirected to three different pages  -->
        <div id="burgerMenu">
            <!-- Navigation bar to be redirected to  different pages -->
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <li>
                        <?php if (!isset($_SESSION['username'])): ?>
                        <a href="signUp.php">Sign up</a>
                        <?php endif; ?>
                    </li>
                    <li>
                        <?php if (!isset($_SESSION['username'])): ?>
                        <a href="signIn.php">Log in</a>
                        <?php endif; ?>
                    </li>
                    <li>
                        <?php if (isset($_SESSION['username'])): ?>
                        <a href="logOut.php">Log out</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </div>

        <div id="userStatus">
            <div id="userStatus">
                <!-- Display the username if the user is logged in -->
                <?php
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                echo '<p>Welcome '. htmlspecialchars($username . '!') . '</p>';
            } else {
                echo '<p>Welcome guest!</p>';
            }
            ?>
            </div>
        </div>

    </header>

    <!-- Main content area, for the cart -->
    <main id="cart">
        <h2>Shopping Cart</h2>

            <?php if (!empty($message)): ?>
                <p class="alert alert-error"><?php echo $message; ?></p>
            <?php endif; ?>

        <div id="empty_cart">
            <a onclick="clearCart()" href=""><img id="binImg" src="images_assignment/bin.png" alt="Bin image"></a>
        </div>

        <!-- Container for the cart items (JavaScript) -->
        <div id="cart-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="table_body"></tbody>
            </table>
            <span id="purchase_price"></span>


            <!-- Checkout form -->
            <form method="post" id="checkoutForm">
                <input type="submit" name="checkout" value="Checkout" class="checkout-button">
            </form>
        </div>

    </main>

    <!-- Footer section of the page -->
    <footer>
        <div id="fdiv">

            <div class="fdiv">
                <h2>Links</h2>
                <!-- Unordered list for links -->
                <ul>
                    <li><a href="https://www.uclancyprus.ac.cy">UCLan Cyprus</a></li>
                    <li><a href="https://www.uclansu.co.uk">Student's Union</a></li>
                </ul>
            </div>

            <!-- Individual block for contact information -->
            <div class="fdiv">
                <h2>Contact</h2>
                <p>+357 24 694000</p>
            </div>

            <!-- Individual block for location information -->
            <div class="fdiv">
                <h2>Location</h2>
                <p>University Ave 12-14<br>
                    Pyla 7080<br>
                    Cyprus</p>
            </div>
        </div>
    </footer>
    <script>
document.getElementById('checkoutForm').addEventListener('submit', function(event) {
    // Retrieving the data from session storage
    const cartItems = JSON.parse(sessionStorage.getItem('cart'));

// Extracting productIDs from cartItems
const productIDs = cartItems.map(item => item.productID);

// Checking if productIDs is not empty
if (productIDs && productIDs.length > 0) {
    // Defining a hidden input field to hold the JSON data
    const hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'productIDs');
    hiddenInput.setAttribute('value', JSON.stringify(productIDs));

    // Appending the hidden input field to the form
    this.appendChild(hiddenInput);

    // Continue with the form submission
    return true;
} else {
    // Prevent the form from submitting
    event.preventDefault();
    console.error('Error: No product IDs found in session storage');
}

});
</script>
    <!-- Links to the JavaScript file for the cart page functionality -->
    <script src="js/cart.js"></script>
    <script src="js/home.js"></script>
</body>

</html>
