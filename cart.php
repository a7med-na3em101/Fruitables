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

    <div class="container-fluid fixed-top">
        <div class="container topbar bg-primary d-none d-lg-block">
            <div class="d-flex justify-content-between">
                <div class="top-info ps-2">
                    <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">219 st, Ciro</a></small>
                    <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">ahmed_naeem@gmail.com</a></small>
                </div>
                <div class="top-link pe-2">
                    <?php
                    session_start();  // Resume the session

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
                            <i class="fas fa-user fa-2x"></i>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>




    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Cart</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item"><a href="contact.php">contact</a></li>
            <li class="breadcrumb-item active text-white">Cart</li>
        </ol>
    </div>
    <!-- Single Page Header End -->


    <!-- Cart Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Products</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                    @    session_start();
         @               $variable = $_SESSION['product'];

                        require('dp.php'); // Database connection

                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            // Handle 'minus', 'plus', and 'remo' actions
                            if (isset($_POST['minus']) && isset($_POST['product_id'])) {
                                $product_id = $_POST['product_id'];
                                $_SESSION['quantity'][$product_id] = max(1, $_SESSION['quantity'][$product_id] - 1); // Decrease the quantity, minimum 1
                            }

                            if (isset($_POST['plus']) && isset($_POST['product_id'])) {
                                $product_id = $_POST['product_id'];
                                $_SESSION['quantity'][$product_id] = min(10, $_SESSION['quantity'][$product_id] + 1); // Increase the quantity, maximum 10
                            }

                            if (isset($_POST['remo']) && isset($_POST['product_id'])) {
                                $product_id = $_POST['product_id'];
                                unset($_SESSION['product'][$product_id]); // Remove the product from the session
                                unset($_SESSION['quantity'][$product_id]); // Remove the quantity for the product
                            }
                        }

                        foreach ($variable as $key => $value) {
                            if (!isset($value['id'])) {
                                continue; // Skip if 'id' is not set
                            }

                            // Fetch product details
                            $query = 'SELECT * FROM product WHERE id = :id';
                            $sqlquery = $conn->prepare($query);
                            $sqlquery->bindParam(':id', $value['id']);
                            $sqlquery->execute();
                            $result = $sqlquery->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($result as $product) {
                                // Fetch the image filename based on the product ID
                                $query = 'SELECT filename FROM product_images WHERE product_id = :product_id';
                                $sqlquery = $conn->prepare($query);
                                $sqlquery->bindParam(':product_id', $product['id']);
                                $sqlquery->execute();
                                $imageResult = $sqlquery->fetch(PDO::FETCH_ASSOC);

                                // Use a default image if no image is found
                                $filename = !empty($imageResult['filename']) ? htmlspecialchars($imageResult['filename']) : 'default.jpg';

                                // Initialize the quantity if not set
                                if (!isset($_SESSION['quantity'][$product['id']])) {
                                    $_SESSION['quantity'][$product['id']] = 1;
                                }

                                $bood = $_SESSION['quantity'][$product['id']]; // Retrieve quantity from session

                                // Display the product information and image
                                echo '<form method="POST">';
                                echo '<tr>';
                                echo '<th scope="row">';
                                echo ' <div class="d-flex align-items-center">';
                                echo '     <img src="img/' . $filename . '" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="">';
                                echo ' </div>';
                                echo '  </th>';
                                echo '  <td>';
                                echo '      <p class="mb-0 mt-4">' . htmlspecialchars($product['name']) . '</p>';
                                echo '  </td>';
                                echo '  <td>';
                                echo '      <p class="mb-0 mt-4">' . htmlspecialchars($product['price']) . '$' . '</p>';
                                echo '  </td>';
                                echo '   <td>';
                                echo '       <div class="input-group quantity mt-4" style="width: 100px;">';
                                echo '          <div class="input-group-btn">';
                                echo '               <button type="submit" name="minus" class="btn btn-sm btn-minus rounded-circle bg-light border">';
                                echo '                   <i class="fa fa-minus"></i>';
                                echo '               </button>';
                                echo '            </div>';
                                echo '            <input type="text" class="form-control form-control-sm text-center border-0" value="' . $bood . '" readonly>';
                                echo '            <div class="input-group-btn">';
                                echo '                <button type="submit" name="plus" class="btn btn-sm btn-plus rounded-circle bg-light border">';
                                echo '                    <i class="fa fa-plus"></i>';
                                echo '                </button>';
                                echo '            </div>';
                                echo '       </div>';
                                echo '    </td>';
                                echo '    <td>';
                                echo '        <p class="mb-0 mt-4">' . ($product['price'] * $bood) . '$</p>';
                                echo '    </td>';
                                echo '     <td>';
                                echo '        <button type="submit" name="remo" class="btn btn-md rounded-circle bg-light border mt-4">';
                                echo '            <i class="fa fa-times text-danger"></i>';
                                echo '        </button>';
                                echo '    </td>';
                                echo '    <input type="hidden" name="product_id" value="' . $product['id'] . '">';
                                echo '</tr>';
                                echo '</form>';
                            }
                        }
                        ?>



                        <!-- 
                        <div class="row g-4 justify-content-end">
                            <div class="col-8"></div>
                            <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                                <div class="bg-light rounded">
                                    <div class="p-4">
                                        <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>
                                        <div class="d-flex justify-content-between mb-4">
                                            <h5 class="mb-0 me-4">Subtotal:</h5>
                                            <p class="mb-0">$96.00</p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-0 me-4">Shipping</h5>
                                            <div class="">
                                                <p class="mb-0">Flat rate: $3.00</p>
                                            </div>
                                        </div>
                                        <p class="mb-0 text-end">Shipping to Ukraine.</p>
                                    </div>
                                    <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                                        <h5 class="mb-0 ps-4 me-4">Total</h5>
                                        <p class="mb-0 pe-4">$99.00</p>
                                    </div>
                                    <button class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4" type="button">Proceed Checkout</button>
                                </div>
                            </div>
                        </div> -->
            </div>
        </div>
        <!-- Cart Page End -->


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