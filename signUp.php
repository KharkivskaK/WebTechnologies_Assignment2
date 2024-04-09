<?php
session_start();

// Redirecting if already signed in
if(isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit();
}

// Checking if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieving values submitted with the form
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Connecting to the database
    $connection = mysqli_connect("localhost", "kkharkivska", "a24p7jqz", "kkharkivska");

    // Checking if the connection was successful
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Hashing the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Preparing the query
    $query = "INSERT INTO tbl_users (user_full_name, user_address, user_email, user_pass) VALUES (?, ?, ?, ?)";

    // Creating a prepared statement
    if ($stmt = mysqli_prepare($connection, $query)) {

        // Binding the parameters to the statement
        mysqli_stmt_bind_param($stmt, "ssss", $username, $username, $email, $hashedPassword);

        // Executing the statement
        if (mysqli_stmt_execute($stmt)) {

            // Redirecting the user to a success page
            header("Location: signIn.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($connection);
    }

    // Closing connection
    mysqli_close($connection);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student's Union Shop</title>

    <!-- Links to the stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/forms.css">
</head>

<body>
<!-- Body of the webpage -->
<header>
    <img src="images_assignment/logo.png" alt="Shop Logo">
    <h1>Student's Union Shop</h1>

    <button id="menuButton">☰</button> <!-- Burger Menu Button -->
    <!-- Navigation bar to be redirected to three different pages  -->
    <div id="burgerMenu">
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="signIn.php">Log in</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>

    <div class="form">
        <h1>Sign Up</h1>
        <!-- Displaying error message if set -->
        <?php if (!empty($errorMessage)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="formField">
                <label for="username">Username:</label>
                <!-- htmlspecialchars to prevent XSS attacks -->
                <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required autofocus>
            </div>
            <div class="formField">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="formField">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Sign Up</button>
        </form>
    </div>

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
