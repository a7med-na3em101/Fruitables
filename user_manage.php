<?php
require('dp.php');
$query = 'select id,name,email from users';
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
            <a href="adduser.php"> <button class="btn-btn-danger">Add user</button></a>
            <section>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Update</th>
                        <th>Remove</th>
                    </tr>
                    <?php
                    foreach ($result as $key => $value) {
                        echo '<tr>';
                        echo '<td>' . $value['id'] . '</td>';
                        echo '<td>' . $value['name'] . '</td>';
                        echo '<td>' . $value['email'] . '</td>';
                        echo '<td><a href="updateuser.php?idupdate=' . htmlspecialchars($value['id']) . '"><button class="btn-btn-danger" style="background-color: #249700;">Update</button></a></td>';
                        echo '<td>';
                        echo '<form action="deleteuser.php" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this user?\');">';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($value['id']) . '">';
                        echo '<button type="submit" class="btn-btn-danger" style="background-color: #970000;">Remove</button>';
                        echo '</form>';
                        echo '</td>';                        echo '</tr>';
                    }
                    ?>
                </table>

            </section>
        </main>
    </div>
</body>

</html>