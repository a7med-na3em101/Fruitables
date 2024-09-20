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
            <section class="management" style="    text-align: center;
    font-family: emoji;
    font-size: xxx-large;">
                <h1>WELCOME TO ADMIN DASHBOARD</h1>
            </section>
        </main>
    </div>
</body>

</html>