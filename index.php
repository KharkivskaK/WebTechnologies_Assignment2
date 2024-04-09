<?php
session_start();
$connection = mysqli_connect("localhost", "kkharkivska", "a24p7jqz", "kkharkivska");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student's Union Shop</title>

    <!-- Links to the stylesheet -->
    <link rel="stylesheet" href="css/style.css">
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
            <!-- Displaying the username if the user is logged in -->
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

<main>
    <!-- Main content area -->
    <h2>Where opportunity creates success</h2>

    <!-- Paragraph, that describes the student union -->
    <p>Every student at The University of Central Lancashire is automatically enrolled as a member of the Students' Union.
        Our mission is to enhance the student experience - motivating and supporting you to reach your aspirations and objectives.
        Discover all the essential information about your membership with the UCLan Students' Union.</p>

    <h2>Offers</h2>
    <div class="offers">
        <?php
        $query = "SELECT * FROM tbl_offers";
        $result = mysqli_query($connection, $query);

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo "<div class='offer'>";
            echo "<h3>" . $row['offer_title'] . "</h3>";
            echo "<p>" . $row['offer_dec'] . "</p>";
            echo "</div>";
        }
        ?>

    </div>
    <!-- First YouTube Video -->
    <div class="video-container">
        <h2>Together</h2>
        <iframe class="responsive-iframe" width="560" height="315" src="https://www.youtube.com/embed/-klVgcFTrL0?si=J1HP5LjWypI9xPVm" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>


    <!-- Second YouTube Video -->
    <div class="video-container">
        <h2>Join our global community</h2>
        <iframe class="responsive-iframe" width="560" height="315" src="https://www.youtube.com/embed/f99oeM4rvgs?si=a1a601f_bFNLKM4O" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
</main>

<!-- Footer section of the page -->
<footer>
    <div class="fdiv">
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

</html>