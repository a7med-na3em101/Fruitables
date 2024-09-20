<?php
session_start();
require('dp.php');

$email = $_SESSION['email'];

$query = 'SELECT name, email, password,role FROM users WHERE email = :email';
$sqlquery = $conn->prepare($query);
$sqlquery->bindParam(':email', $email);
$sqlquery->execute();
$result = $sqlquery->fetch(PDO::FETCH_ASSOC);

if (isset($_SESSION['email'])) {
    if ($result['role'] == 'User') {
        header('Location: home.php');  // Redirect to the home page
        exit();
    }
    if ($result['role'] == 'Admin') {
        header('Location: dashboard.php');  // Redirect to the home page
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sign.css">
    <title>Document</title>
</head>

<body>
    <div style="    justify-content: center;
    display: flex;">
        <form class="form" method="post" action="server.php" enctype="multipart/form-data">
            <p class="title">Register </p>
            <p class="message">Signup now and get full access to our app. </p>

            <label>
                <input required="" name="name" placeholder="" type="text" class="input">
                <span>Name</span>
            </label>

            <label>
                <input required="" name="email" placeholder="" type="email" class="input">
                <span>Email</span>
            </label>

            <label>
                <input required="" name="password" placeholder="" type="password" class="input">
                <span>Password</span>
            </label>
            <label>
                <input required="" name="confirmpassword" placeholder="" type="password" class="input">
                <span>Confirm password</span>
            </label>
            <div class="form-group">
                <input class="form-control" type="file"
                    name="uploadfile" value="" />
            </div>

            <button class="submit" name="signup">Submit</button>
            <p class="signin">Already have an acount ? <a href="Login.php">login</a> </p>
        </form>

    </div>
    <div>
        <?php
        if (isset($_GET['message'])) {
            echo '<p style="color: red;">' . $_GET['message'] . '</p>';
        }
        ?>
    </div>
</body>

</html>