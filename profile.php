<?php
require('dp.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email'];

// Fetch user details from the database
$query = 'SELECT name, email, filename FROM users WHERE email = :email';
$sqlquery = $conn->prepare($query);
$sqlquery->bindParam(':email', $email);
$sqlquery->execute();
$result = $sqlquery->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    echo "User not found!";
    exit();
}

$name = $result['name'];
$email = $result['email'];
$filename = $result['filename'] ?? 'default.png'; // Use a default image if no profile picture is uploaded

// Change password functionality
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current password for validation
    $password_query = 'SELECT password FROM users WHERE email = :email';
    $password_sql = $conn->prepare($password_query);
    $password_sql->bindParam(':email', $email);
    $password_sql->execute();
    $password_result = $password_sql->fetch(PDO::FETCH_ASSOC);

    // Validate the current password (in production, use password_verify())
    if ($current_password !== $password_result['password']) {
        echo "Current password is incorrect!";
    } elseif ($new_password !== $confirm_password) {
        echo "New passwords do not match!";
    } else {
        // Update the password (in production, hash the password using password_hash())
        $update_query = 'UPDATE users SET password = :new_password WHERE email = :email';
        $update_sql = $conn->prepare($update_query);
        $update_sql->bindParam(':new_password', $new_password);
        $update_sql->bindParam(':email', $email);
        $update_sql->execute();

        echo "Password changed successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Fruitables - Vegetable Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar start -->
    <div class="container-fluid fixed-top">
        <div class="container topbar bg-primary d-none d-lg-block">
            <div class="d-flex justify-content-between">
                <div class="top-info ps-2">
                    <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">219 st, Ciro</a></small>
                    <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">ahmed_naeem@gmail.com</a></small>
                </div>
                <div class="top-link pe-2">
                    <?php

                    // Check if the user is logged in
                    if (isset($_SESSION['username'])) {
                        echo '<h5 style="color: white; " >Welcome, ' . $_SESSION['username'] . '</h5>';
                    } else {
                        // Redirect to the login page if the user is not logged in
                        header('Location: Login.php');
                        exit;
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                <a href="home.php" class="navbar-brand">
                    <h1 class="text-primary display-6">Fruitables</h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="home.php" class="nav-item nav-link active">Home</a>
                        <a href="shop.php" class="nav-item nav-link">Shop</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                <a href="cart.php" class="dropdown-item">Cart</a>
                                <a href="checkout.php" class="dropdown-item">Chackout</a>
                                <a href="testomonial.php" class="dropdown-item">Testimonial</a>
                                <a href="404.php" class="dropdown-item">404 Page</a>
                            </div>
                        </div>
                        <a href="contact.php" class="nav-item nav-link">Contact</a>
                        <a href="logout.php" class="nav-item nav-link">LogOut</a>
                    </div>
                    <div class="d-flex m-3 me-0">
                        <a href="cart.php" class="position-relative me-4 my-auto">
                            <i class="fa fa-shopping-bag fa-2x"></i>
                            <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;"></span>
                        </a>
                        <a href="profile.php" class="my-auto">

                            <?php
                            require('dp.php');
                            //  session_start();
                            $email = $_SESSION['email'];

                            // Query to fetch image filename
                            $query = 'SELECT filename FROM images WHERE email = :email';  // Assuming `images` table has `email` column
                            $sqlquery = $conn->prepare($query);
                            $sqlquery->bindParam(':email', $email);
                            $sqlquery->execute();
                            $result = $sqlquery->fetch(PDO::FETCH_ASSOC);

                            if ($result) {
                            ?>
                                <img style="    width: 3rem;
    border-radius: 2rem;" src="./img/<?php echo $result['filename']; ?>" alt="Profile Image">
                            <?php
                            } else {
                                echo "No image found.";
                            }
                            ?>

                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>


    <div class="container-fluid py-5 mb-5 hero-header">
        <div class="container py-5">
            <div class="row g-5 align-items-center">

                <div style="margin: 2rem;">
                    <h1>Profile</h1>

                    <!-- Display Profile Photo -->
                    <div>
                        <?php
                        require('dp.php');
                        //  session_start();
                        $email = $_SESSION['email'];

                        // Query to fetch image filename
                        $query = 'SELECT filename FROM images WHERE email = :email';  // Assuming `images` table has `email` column
                        $sqlquery = $conn->prepare($query);
                        $sqlquery->bindParam(':email', $email);
                        $sqlquery->execute();
                        $result = $sqlquery->fetch(PDO::FETCH_ASSOC);

                        if ($result) {
                        ?>
                            <img style="box-shadow: 2px 2px 5px red, -2px -2px 2px violet;    border-radius: 5rem; width: 10rem;" src="./img/<?php echo $result['filename']; ?>" alt="Profile Image">
                        <?php
                        } else {
                            echo "No image found.";
                        }
                        ?>
                    </div>

                    <!-- Display Name and Email -->
                    <div>
                        <h1> </h1>
                        <h2>Name: <?php echo htmlspecialchars($name); ?></h2>
                        <h1></h1>
                        <p>Email: <?php echo htmlspecialchars($email); ?></p>
                    </div>

                    <!-- Change Password Form -->
                    <div>
                        <h3 style="text-align: center;">Change Password</h3>
                        <form method="POST" action="" style="    text-align: center;">
                            <label for="current_password">Current Password:</label><br>
                            <input style="    border-radius: 1rem;
    width: 25rem;" type="password" name="current_password" required><br><br>

                            <label for="new_password">New Password:</label><br>
                            <input style="    border-radius: 1rem;
    width: 25rem;" type="password" name="new_password" required><br><br>

                            <label for="confirm_password">Confirm New Password:</label><br>
                            <input style="    border-radius: 1rem;
    width: 25rem;" type="password" name="confirm_password" required><br><br>

                            <input style="    border-radius: 1rem;
    width: 25rem;
    background-color: #81c408;
    font-family: fantasy;
    font-size: xx-large;
    box-shadow: 2px 2px 5px red, -2px -2px 2px violet;" type="submit" name="change_password" value="Change Password">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
        <div class="container py-5">
            <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
                <div class="row g-4">
                    <div class="col-lg-3">
                        <a href="#">
                            <h1 class="text-primary mb-0">Fruitables</h1>
                            <p class="text-secondary mb-0">Fresh products</p>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <div class="position-relative mx-auto">
                            <input class="form-control border-0 w-100 py-3 px-4 rounded-pill" type="number" placeholder="Your Email">
                            <button type="submit" class="btn btn-primary border-0 border-secondary py-3 px-4 position-absolute rounded-pill text-white" style="top: 0; right: 0;">Subscribe Now</button>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="d-flex justify-content-end pt-3">
                            <a class="btn  btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-secondary btn-md-square rounded-circle" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Back to Top -->
            <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>


            <!-- JavaScript Libraries -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="lib/easing/easing.min.js"></script>
            <script src="lib/waypoints/waypoints.min.js"></script>
            <script src="lib/lightbox/js/lightbox.min.js"></script>
            <script src="lib/owlcarousel/owl.carousel.min.js"></script>

            <!-- Template Javascript -->
            <script src="js/main.js"></script>
</body>

</html>