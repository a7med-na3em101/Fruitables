<?php
require('dp.php');

$query = 'SELECT * FROM product';
$sqlquery = $conn->prepare($query);
$sqlquery->execute();
$result = $sqlquery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <nav class="navbar">
        <div class="logo">Admin Dashboard</div>
        <ul class="nav-links">
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="dash_user.php">Users</a></li>
            <li><a href="dash_product.php">Products</a></li>
            <li><a href="dash_logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><a href="user_manage.php">User Management</a></li>
                <li><a href="product_manage.php">Product Management</a></li>
                <li><a href="sales_manage.php">Sales</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <a href="addproduct.php"><button class="btn-btn-danger">Add Product</button></a>

            <section>
                <table>
                    <tr>
                        <th>Photo</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Update</th>
                        <th>Remove</th>
                    </tr>
                    <?php
                    foreach ($result as $value) {
                        echo '<tr>';

                        // Fetch the image filename based on the product ID
                        $query = 'SELECT filename FROM product_images WHERE product_id = :product_id'; // Correct column name
                        $sqlquery = $conn->prepare($query);
                        $sqlquery->bindParam(':product_id', $value['id']);
                        $sqlquery->execute();
                        $imageResult = $sqlquery->fetch(PDO::FETCH_ASSOC);

                        // Check if an image was found and handle accordingly
                        $filename = !empty($imageResult['filename']) ? htmlspecialchars($imageResult['filename']) : 'default.jpg'; // Use a default image if none is found

                        echo '<td><img style="width: 3rem; border-radius: 2rem;" src="./img/' . $filename . '" alt="Product Image"></td>';
                        echo '<td>' . htmlspecialchars($value['id']) . '</td>';
                        echo '<td>' . htmlspecialchars($value['name']) . '</td>';
                        echo '<td>' . htmlspecialchars($value['description']) . '</td>';
                        echo '<td>' . htmlspecialchars($value['price']) . '</td>';
                        echo '<td><a href="updateproduct.php?idupdate=' . htmlspecialchars($value['id']) . '"><button class="btn-btn-danger" style="background-color: #249700;">Update</button></a></td>';
                        echo '<td>';
                        echo '<form action="deleteproduct.php" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this product?\');">';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($value['id']) . '">';
                        echo '<button type="submit" class="btn-btn-danger" style="background-color: #970000;">Remove</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </section>
        </main>
    </div>
</body>

</html>