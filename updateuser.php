<?php
require('dp.php'); // Ensure this file contains the correct database connection setup

// Initialize variables for form fields and error messages
$name = $email = $role = '';
$user_id = '';
$errors = [];

// Check if the ID parameter is present and valid
if (isset($_GET['idupdate']) && !empty($_GET['idupdate'])) {
    $user_id = $_GET['idupdate'];

    try {
        // Fetch the existing user data
        $query = 'SELECT * FROM users WHERE id = :id';
        $sqlquery = $conn->prepare($query);
        $sqlquery->bindParam(':id', $user_id, PDO::PARAM_INT);
        $sqlquery->execute();
        $user = $sqlquery->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $name = $user['name'];
            $email = $user['email'];
            $role = $user['role'];
        } else {
            $errors[] = "User not found.";
        }
    } catch (PDOException $e) {
        $errors[] = "Error: " . $e->getMessage();
    }
} else {
    $errors[] = "No user ID provided.";
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and validate form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Basic validation
    if (empty($name) || empty($email) || empty($role)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // If no errors, proceed with updating data in the database
    if (empty($errors)) {
        try {
            $query = 'UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id';
            $sqlquery = $conn->prepare($query);

            // Bind parameters to placeholders
            $sqlquery->bindParam(':name', $name);
            $sqlquery->bindParam(':email', $email);
            $sqlquery->bindParam(':role', $role);
            $sqlquery->bindParam(':id', $user_id, PDO::PARAM_INT);

            // Execute the query
            $sqlquery->execute();

            // Redirect to user management page after success
            header('Location: user_manage.php');
            exit(); // Ensure no further code is executed after the redirect
        } catch (PDOException $e) {
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
    <title>Update User</title>
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
        <h2>Update User</h2>
        <?php if (!empty($errors)) : ?>
            <div class="errors">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="updateuser.php?idupdate=<?php echo htmlspecialchars($user_id); ?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Admin" <?php echo $role === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="User" <?php echo $role === 'User' ? 'selected' : ''; ?>>User</option>
            </select>

            <input type="submit" value="Update User">
        </form>
    </div>

</body>

</html>