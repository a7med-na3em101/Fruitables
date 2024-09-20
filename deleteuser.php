<?php
require('dp.php'); // Ensure this file contains the correct database connection setup

// Check if the ID parameter is present
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $user_id = $_POST['id'];

    try {
        // Prepare and execute the delete query
        $query = 'DELETE FROM users WHERE id = :id';
        $sqlquery = $conn->prepare($query);
        $sqlquery->bindParam(':id', $user_id, PDO::PARAM_INT);
        $sqlquery->execute();

        // Redirect to user management page after success
        header('Location: user_manage.php');
        exit(); // Ensure no further code is executed after the redirect
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No user ID provided.";
}
