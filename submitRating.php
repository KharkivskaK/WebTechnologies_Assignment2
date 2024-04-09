<?php
session_start();
$connection = mysqli_connect("localhost", "kkharkivska", "a24p7jqz", "kkharkivska");

// Checking connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "You must log in to submit a review.";
    header("Location: products.php");
    exit();
}

// Variables for the query
$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$review_title = $_POST['ratingTitle'];
$review_desc = $_POST['ratingDescription'];
$review_rating = $_POST['ratingNumber'];
$review_timestamp = date("Y-m-d H:i:s");

$query = "INSERT INTO tbl_reviews (user_id, product_id, review_title, review_desc, review_rating, review_timestamp) 
          VALUES ('$user_id', '$product_id', '$review_title', '$review_desc', '$review_rating', '$review_timestamp')";

if (mysqli_query($connection, $query)) {
    // Redirecting the user to the success page
    $_SESSION['success_message'] = "Review submitted successfully.";
    header("Location: products.php?success=1");
    exit();
} else {
    header("Location: error.php?message=" . urlencode(mysqli_error($connection)));
    exit();
}

$averageRatingOutput = "";

// Checking if product_id is set
if (isset($_GET['product_id'])) {
    $product_id_rating = $_GET['product_id'];
    $queryRating = "SELECT AVG(review_rating) as average_rating FROM tbl_reviews WHERE product_id = ?";
    $stmt = mysqli_prepare($connection, $queryRating);
    mysqli_stmt_bind_param($stmt, "i", $product_id_rating);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $average_rating);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Returning the average rating in JSON format if requested via AJAX
    if (isset($_GET['ajax']) && $_GET['ajax'] == 'getAverageRating') {
        echo json_encode(["averageRating" => $average_rating]);
        exit();
    }

    // Formatting the average rating for display
    $averageRatingOutput = $average_rating ? "Average Rating: " . number_format($average_rating, 1) : "No ratings yet.";
}
 else {
    echo "Product ID is not set.";
}

// Closing the connection
mysqli_close($connection);

?>