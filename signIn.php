<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: logOut.php");
    exit();
}

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection variables
    $servername = "localhost";
    $username = "kkharkivska";
    $password = "a24p7jqz";
    $dbname = "kkharkivska";

    $userInputName = isset($_POST["username"]) ? $_POST["username"] : '';
    $userInputPassword = isset($_POST["password"]) ? $_POST["password"] : '';

    // Creating connection
    $connection = new mysqli($servername, $username, $password, $dbname);

    // Checking connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $query = "SELECT user_id, user_full_name, user_pass FROM tbl_users WHERE user_full_name = ?";

    // Preparing and bind
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $userInputName);

    // Executing
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($userInputPassword, $row['user_pass'])) {
            $_SESSION['username'] = $row['user_full_name'];
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: products.php");
            exit();
        } else {
            $errorMessage = "Invalid username or password. Please try again.";
        }
    } else {
        $errorMessage = "Invalid username or password. Please try again.";
    }

    $stmt->close();
    $connection->close();
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
                <li>
                    <?php if (!isset($_SESSION['username'])): ?>
                        <a href="signUp.php">Sign up</a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="form">
        <h1>Log In</h1>
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
            <button type="submit">Log In</button>
        </form>
    </div>
</main>

<!-- Footer section of the page -->
<footer>
    <div id="fdiv">
        <!-- Container for the footer content -->

        <div class="fdiv">
            <!-- Unordered list for links -->
            <h2>Links</h2>
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


