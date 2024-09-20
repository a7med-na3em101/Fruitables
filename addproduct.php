<?php
require('dp.php'); // Ensure this file contains the correct database connection setup

// Initialize variables for form fields and error messages
$name = $description = $price = '';
$errors = [];

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and validate form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Validate the uploaded file
    if (isset($_FILES['uploadfile']) && $_FILES['uploadfile']['error'] === UPLOAD_ERR_OK) {
        $photoTmpPath = $_FILES['uploadfile']['tmp_name'];
        $photoName = $_FILES['uploadfile']['name'];
        $photoSize = $_FILES['uploadfile']['size'];
        $photoType = $_FILES['uploadfile']['type'];

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
                // Insert new product into the database
                $insertQuery = 'INSERT INTO product (name, description, price) VALUES (:name, :description, :price)';
                $insertSql = $conn->prepare($insertQuery);
                $insertSql->bindParam(':name', $name);
                $insertSql->bindParam(':description', $description);
                $insertSql->bindParam(':price', $price);
                $insertSql->execute();

                // Get the last inserted product ID
                $productId = $conn->lastInsertId();

                // Store image filename in the product_images table
                $imageQuery = 'INSERT INTO product_images (product_id, filename) VALUES (:product_id, :filename)';
                $imageSql = $conn->prepare($imageQuery);
                $imageSql->bindParam(':product_id', $productId);
                $imageSql->bindParam(':filename', $photoName);
                $imageSql->execute();

                header('Location: product_manage.php');
                exit(); // Ensure no further code is executed after the redirect
            } else {
                $errors[] = "Failed to move uploaded file.";
            }
        }
    } else {
        $errors[] = "Photo is required.";
    }

    // Basic validation
    if (empty($name) || empty($description) || empty($price)) {
        $errors[] = "All fields are required.";
    } elseif (!is_numeric($price)) {
        $errors[] = "Price must be a number.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
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
        <h2>Add Product</h2>
        <?php if (!empty($errors)) : ?>
            <div class="errors">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="addproduct.php" method="POST" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($description); ?></textarea>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($price); ?>" required>

            <label for="uploadfile">Product Photo:</label>
            <input type="file" id="uploadfile" name="uploadfile" required>

            <input type="submit" value="Add Product">
        </form>
    </div>

</body>

</html>