<?php
session_start();
$connection = mysqli_connect("localhost", "kkharkivska", "a24p7jqz", "kkharkivska");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student's Union Shop - Products</title>

    <!-- Links to the main stylesheet -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Links to a specifique stylesheet for the products page -->
    <link rel="stylesheet" href="css/products.css">

    <script type="text/javascript" src="js/products2.js"></script>

</head>

<body>

    <?php if (isset($_SESSION['error_message'])): ?>
    <div class="error">
        <?php
        echo htmlspecialchars($_SESSION['error_message']);
        unset($_SESSION['error_message']);
        ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
    <div class="success">
        <?php
        echo htmlspecialchars($_SESSION['success_message']);
        unset($_SESSION['success_message']);
        ?>
    </div>
    <?php endif; ?>


    <!-- Head section of the document -->
    <header>
        <img src="images_assignment/logo.png" alt="Shop Logo">
        <h1>Student's Union Shop</h1>

        <form class="searchBar" method="get">
            <label for="searchCriterion">Search:</label>
            <input type="text" name="searchCriterion" id="searchCriterion">
            <div class="form-buttons">
                <input type="submit" value="Submit">
                <input type="reset" value="Clear form">
            </div>
        </form>
        <br><br><br><br><br>

        <button id="menuButton">☰</button> <!-- Burger Menu Button -->
        <!-- Navigation bar to be redirected to three different pages  -->
        <div id="burgerMenu">
            <!-- Navigation bar to be redirected to five different pages  -->
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li>
                        <a href="cart.php">Cart</a>
                    </li>
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
    <br>


    <nav id="pjnav">
        <ul class="categories">
            <li><a href="products.php?category=all" class="form-control">All Products</a></li>
            <li><a href="products.php?category=tshirt" class="form-control">T-Shirts</a></li>
            <li><a href="products.php?category=hoodie" class="form-control">Hoodies</a></li>
            <li><a href="products.php?category=jumper" class="form-control">Jumpers</a></li>
        </ul>
    </nav>

    <!-- Main content area for products -->
    <main id="products">
        <!-- Content for products goes here -->
        <?php

    // Initializing variables
    $search ="";

    if(isset($_GET["searchCriterion"])){

        $search = $_GET["searchCriterion"];
    }
    $category = isset($_GET['category']) ? $_GET['category'] : 'all';


// The base query
$query = "SELECT * FROM tbl_products";


$conditions = []; // Array to hold query conditions

// Appending conditions based on search criteria
if (!empty($search)) {
    $search = mysqli_real_escape_string($connection, $search);
    $conditions[] = "product_title LIKE '%" . $search . "%'";
}

// Appending conditions based on category
if ($category != 'all') {
    $category = mysqli_real_escape_string($connection, $category);
    $conditions[] = "product_type LIKE '%" . $category . "%'";
}

// If there are any conditions, append them to the query
if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

// Execute the query
$result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) > 0) {
            // Displaying the search results header only if there are results
            if (!empty($search)) {
                echo "<h2>Search Results for '" . htmlspecialchars($search) . "'</h2>";
            }

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $queryRating = "SELECT ROUND(AVG(review_rating), 2) as average_rating FROM tbl_reviews WHERE product_id = ?";
            $stmt = mysqli_prepare($connection, $queryRating);
            mysqli_stmt_bind_param($stmt, "i", $row['product_id']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $average_rating);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            // Generating an id for each product container
            $productContainerId = 'product-' . $row['product_id'];
            // Generating an id for each image
            $imageId = 'image-' . $row['product_id'];

            echo '<div class="col product-container" id="' . $productContainerId . '" data-category="' . $row["product_type"] . '">';
            echo '<div class="col2">';
            echo '<div class="col3">';
            echo '<a href="item.php?product_id=' . $row['product_id'] . '">';
            echo '<img class="product-image" id="' . $imageId . '" alt="' . $row["product_title"] . '" src="' . $row["product_image"] . '">';
            echo '<div class="title">' . $row["product_title"] . '</div>';
            echo '</a>';
            echo "<p>" . $row["product_desc"] . "</p>";
            echo "<p>Price: €" . $row["product_price"] . "</p>";
            echo '<button class="add-to-cart-btn" id="add-to-cart-btn-' . $row['product_id'] . '" onclick="addToCart(' . htmlspecialchars(json_encode($row['product_id']),
            ENT_QUOTES) . ', '. htmlspecialchars(json_encode($row['product_title']),
                    ENT_QUOTES) . ', ' . htmlspecialchars(json_encode($row['product_image']), ENT_QUOTES) . ', ' . htmlspecialchars(json_encode($row['product_price']),
                    ENT_QUOTES) . ', ' . $row['product_id'] . ')">Add to cart</button>';
            echo '<button class="view-detail-btn" id="view-detail-btn-' . $row['product_id'] .
                '" onclick="viewProductDetail(\'' .
                $row['product_id'] . '\', \'' .
                htmlspecialchars($row['product_title'], ENT_QUOTES) . '\', \'' .
                htmlspecialchars($row['product_image'], ENT_QUOTES) . '\', \'' .
                $row['product_price'] . '\', \'' .
                htmlspecialchars($row['product_desc'], ENT_QUOTES) . '\', \'' .
                htmlspecialchars($average_rating?$average_rating:0, ENT_QUOTES) . '\')">View Details</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        } } else {
            // Displaying a message if no products match the search
            echo "<h2>No products are matched.</h2>";
        }
?>

    </main>

    <!-- Secondary content area for detailed product view -->
    <main id="products2det">

        <!-- Container, that displays product details -->
        <div id="productDetails" class="product"></div>

        <div id="review"></div>

        <!-- Container, that displays product rating -->
        <div id="productRating" class="products"></div>
    </main>

    <!-- Footer section of the page -->
    <footer>
        <div id="fdiv">

            <div class="fdiv">
                <h2>Links</h2>

                <!-- Unordered list for the links -->
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

    <script src="js/home.js"></script>
</body>

</html>