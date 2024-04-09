<?php
session_start();
$connection = mysqli_connect("localhost", "kkharkivska", "a24p7jqz", "kkharkivska");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$search ="";

if(isset($_GET["searchCriterion"])){
    $search = $_GET["searchCriterion"];
}

$query = !empty($search) ? "SELECT * FROM tbl_products WHERE product_title LIKE '%" . $search . "%'" : "SELECT * FROM tbl_products";
$result = mysqli_query($connection, $query);

if (!empty($search)) {
    echo "<h2>Search Results for '" . htmlspecialchars($search) . "'</h2>";
}

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    // Generating an id for each product container
    $productContainerId = 'product-' . $row['product_id'];
    // Generating an id for each image
    $imageId = 'image-' . $row['product_id'];

    echo '<div class="col product-container" id="' . $productContainerId . '">';
    echo '<div class="col2">';
    echo '<div class="col3">';
    echo '<a href="item.php?product_id=' . $row['product_id'] . '">';
    // Class to images_assignment for styling and an id for identification
    echo '<img class="product-image" id="' . $imageId . '" alt="' . $row["product_title"] . '" src="' . $row["product_image"] . '">';
    echo '<div class="title">' . $row["product_title"] . '</div>';
    echo '</a>'; // End of the anchor tag
    echo "<p>" . $row["product_desc"] . "</p>";
    echo "<p>Price: €" . $row["product_price"] . "</p>";
    // Adding classes to these buttons for styling
    echo '<button class="add-to-cart-btn" onclick="addToCart(' . $row['product_id'] . ')">Add to cart</button>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

// If no products are found
if(mysqli_num_rows($result) === 0) {
    echo "<p>No products found matching your search criteria.</p>";
}

?>

