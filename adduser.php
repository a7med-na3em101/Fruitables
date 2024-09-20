<?php
// Include database connection file
require('dp.php'); // Ensure this file contains the correct database connection setup

// Initialize variables for form fields
$name = $email = $password = $role = '';
$errors = [];

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and validate
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // If no errors, proceed with inserting data into the database
    if (empty($errors)) {
        try {

            // Prepare SQL statement with placeholders
            $query = 'INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)';
            $sqlquery = $conn->prepare($query);

            // Bind parameters to placeholders
            $sqlquery->bindParam(':name', $name);
            $sqlquery->bindParam(':email', $email);
            $sqlquery->bindParam(':password', $password);
            $sqlquery->bindParam(':role', $role);

            // Execute the query
            $sqlquery->execute();

            // Redirect to user management page after success
            header('Location: user_manage.php');
            exit(); // Ensure no further code is executed after the redirect
        } catch (PDOException $e) {
            // Handle errors
            $errors[] = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        .errors {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Add New User</h2>
        <?php if (!empty($errors)) : ?>
            <div class="errors">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="adduser.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Admin" <?php echo $role === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="User" <?php echo $role === 'User' ? 'selected' : ''; ?>>User</option>
            </select>

            <input type="submit" value="Add User">
        </form>
    </div>

</body>

</html>