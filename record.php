<?php
session_start();

require('dp.php');

$id = $_GET['ido'];  // Retrieve the product ID from the URL

// Prepare and execute the query to fetch product details
$query = 'SELECT * FROM product WHERE id = :ido';
$sqlquery = $conn->prepare($query);
$sqlquery->bindParam(':ido', $id);
$sqlquery->execute();
$result = $sqlquery->fetch(PDO::FETCH_ASSOC);  // Fetch a single product

// Check if a product was fetched successfully
if ($result) {
    // Check if $_SESSION['product'] is set and if it's an array
    if (!isset($_SESSION['product']) || !is_array($_SESSION['product'])) {
        $_SESSION['product'] = [];  // Initialize as an array if not set or if it’s not an array
    }

    // Add the fetched product to the session array
    $_SESSION['product'][] = $result;  // Append the product to the session array
}

// Redirect with success message
header('Location: shop.php?message=add successfully');
exit();
