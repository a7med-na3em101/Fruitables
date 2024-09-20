<?php
require('dp.php'); // Ensure this file contains the correct database connection setup

// Initialize variables for form fields and error messages
$name = $description = $price = '';
$product_id = '';
$errors = [];

// Check if the ID parameter is present and valid
if (isset($_GET['idupdate']) && !empty($_GET['idupdate'])) {
    $product_id = $_GET['idupdate'];

    try {
        // Fetch the existing product data
        $query = 'SELECT * FROM product WHERE id = :id';
        $sqlquery = $conn->prepare($query);
        $sqlquery->bindParam(':id', $product_id);
        $sqlquery->execute();
        $product = $sqlquery->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $name = $product['name'];
            $description = $product['description'];
            $price = $product['price'];
            $photo = $product['photo'];
        } else {
            $errors[] = "Product not found.";
        }
    } catch (PDOException $e) {
        $errors[] = "Error: " . $e->getMessage();
    }
} else {
    $errors[] = "No product ID provided.";
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and validate form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Validate the uploaded file
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoTmpPath = $_FILES['photo']['tmp_name'];
        $photoName = $_FILES['photo']['name'];
        $photoSize = $_FILES['photo']['size'];
        $photoType = $_FILES['photo']['type'];

        // Validate the file type (only images in this example)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($photoType, $allowedTypes)) {
            $errors[] = "Only JPEG, PNG, and GIF files are allowed.";
        }

        // Validate the file size (5MB max)
        if ($photoSize > 5 * 1024 * 1024) {
            $errors[] = "File size should not exceed 5MB.";
        }

        // Move the uploaded file to a permanent location
        if (empty($errors)) {
            $uploadDir = './img/';
            $photoPath = $uploadDir . basename($photoName);

            if (move_uploaded_file($photoTmpPath, $photoPath)) {
                $photo = $photoPath; // Update the photo path
            } else {
                $errors[] = "Failed to move uploaded file.";
            }
        }
    }

    // Basic validation
    if (empty($name) || empty($description) || empty($price)) {
        $errors[] = "All fields are required.";
    } elseif (!is_numeric($price)) {
        $errors[] = "Price must be a number.";
    }

    // If no errors, proceed with updating data in the database
    if (empty($errors)) {
        try {
            $query = 'UPDATE product SET name = :name, description = :description, price = :price, photo = :photo WHERE id = :id';
            $sqlquery = $conn->prepare($query);

            // Bind parameters to placeholders
            $sqlquery->bindParam(':name', $name);
            $sqlquery->bindParam(':description', $description);
            $sqlquery->bindParam(':price', $price);
            $sqlquery->bindParam(':photo', $photo);
            $sqlquery->bindParam(':id', $product_id);

            // Execute the query
            $sqlquery->execute();

            // Redirect to product management page after success
            header('Location: product_manage.php');
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
    <title>Update Product</title>
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
        input[type="number"],
        textarea {
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
        <h2>Update Product</h2>
        <?php if (!empty($errors)) : ?>
            <div class="errors">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="updateproduct.php?idupdate=<?php echo htmlspecialchars($product_id); ?>" method="POST" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($description); ?></textarea>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($price); ?>" required>

            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo" />

            <br>
            <input type="submit" value="Update Product">
        </form>
    </div>

</body>

</html>