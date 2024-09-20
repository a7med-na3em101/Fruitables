<?php
require('dp.php');
session_start();

// LOGIN
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to find the user by email and fetch name, email, and password
    $query = 'SELECT name, email, password,role FROM users WHERE email = :email';
    $sqlquery = $conn->prepare($query);
    $sqlquery->bindParam(':email', $email);
    $sqlquery->execute();
    $result = $sqlquery->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        if ($password === $result['password']) {  // Plaintext comparison (use password_hash in production)
            $_SESSION['username'] = $result['name'];
            $_SESSION['email'] = $result['email']; // Store the user's name in the session
            if($result['role']=='User')
            {
                header('Location: home.php');  // Redirect to the home page
                exit();
            }
            if ($result['role'] == 'Admin') {
                header('Location: dashboard.php');  // Redirect to the home page
                exit();
            }

        } else {
            // Redirect to login page with an error message
            header('Location: Login.php?message=Incorrect+password');
            exit();  // Ensure script stops after header redirect
        }
    } else {
        // Redirect to login page with an error message for email not found
        header('Location: Login.php?message=No+user+found');
        exit();
    }
}

// REGISTER
if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    // Check if password and confirm password match
    if ($password !== $confirmpassword) {
        header('Location: signup.php?message=Passwords+do+not+match!');
        exit();
    }

    // Query to check if the email already exists
    $query = 'SELECT email FROM users WHERE email = :email';
    $sqlquery = $conn->prepare($query);
    $sqlquery->bindParam(':email', $email);
    $sqlquery->execute();
    $result = $sqlquery->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        header('Location: signup.php?message=Email+already+exists!');
        exit();
    } else {
        // Handle image upload
        $filename = $_FILES["uploadfile"]["name"];
        $tempname = $_FILES["uploadfile"]["tmp_name"];
        $folder = "./img/" . $filename;

        if (move_uploaded_file($tempname, $folder)) {
            echo "<h3>&nbsp; Image uploaded successfully!</h3>";
        } else {
            echo "<h3>&nbsp; Failed to upload image!</h3>";
            exit();  // Stop execution if image upload fails
        }

        // Insert new user and image into the database
        $insertQuery = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
        $insertSql = $conn->prepare($insertQuery);
        $insertSql->bindParam(':name', $name);
        $insertSql->bindParam(':email', $email);
        $insertSql->bindParam(':password', $password);  // Use password_hash() for production security
        $insertSql->execute();

        // Store image filename in the images table
        $imageQuery = 'INSERT INTO images (email, filename) VALUES (:email, :filename)';
        $imageSql = $conn->prepare($imageQuery);
        $imageSql->bindParam(':email', $email);
        $imageSql->bindParam(':filename', $filename);
        $imageSql->execute();

        header('Location: Login.php?message=Registration+successful!');
        exit();
    }
}

