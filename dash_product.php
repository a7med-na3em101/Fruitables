
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
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
            <section>
                <table>
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                                <?php

                                require('dp.php');

                                $query = 'SELECT * FROM product';
                                $sqlquery = $conn->prepare($query);
                                $sqlquery->execute();
                                $result = $sqlquery->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($result as $value) {
                                    // Fetch the image filename based on the product ID
                                    $query = 'SELECT filename FROM product_images WHERE product_id = :product_id'; // Correct column name
                                    $sqlquery = $conn->prepare($query);
                                    $sqlquery->bindParam(':product_id', $value['id']);
                                    $sqlquery->execute();
                                    $imageResult = $sqlquery->fetch(PDO::FETCH_ASSOC);

                                    // Check if an image was found and handle accordingly
                                    $filename = !empty($imageResult['filename']) ? htmlspecialchars($imageResult['filename']) : 'default.jpg';


                                    echo    '<tr>';
                                    echo         ' <td>';
                                    echo '<img src="./img/' . $filename . '" class="img-fluid w-100 rounded-top" alt="Product Image">';
                                    echo '</td>';
                                    echo         ' <td>'. htmlspecialchars($value['id']) .'</td>';
                        echo       ' <td>'.htmlspecialchars($value['name']) .'</td>';
                        echo       ' <td>'.htmlspecialchars($value['description']) .'</td>';
                         echo      ' <td>'. htmlspecialchars($value['price']). '</td>';
                           ' </tr>';
                                }
                         ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>

</html>