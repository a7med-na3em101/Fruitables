<?php
                session_start();
require('dp.php');

   @ $email = $_SESSION['email'];

    $query = 'SELECT name, email, password,role FROM users WHERE email = :email';
    $sqlquery = $conn->prepare($query);
    $sqlquery->bindParam(':email', $email);
    $sqlquery->execute();
    $result = $sqlquery->fetch(PDO::FETCH_ASSOC);
                
if(isset($_SESSION['email']))
{
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
    <link rel="stylesheet" href="login.css">
    <title>login</title>
</head>

<body>
    <div style="    justify-content: center;
    display: flex;">
        <div class="container">
            <div class="heading">Login</div>
            <form method="post" action="server.php" class="form">
                <input required="" class="input" type="email" name="email" id="email" placeholder="E-mail">
                <input required="" class="input" type="password" name="password" id="password" placeholder="Password">
                <p style="    font-family: serif;
    color: #afafaf;">if u don't have account ? <a href="signup.php">Sign Up</a> </p>
                <input class="login-button" name="login" type="submit" value="Login">
            </form>
            <div>
                <?php
                if (isset($_GET['message'])) {
                    echo '<p style="color: red;">' . $_GET['message']. '</p>';
                }
                ?>
            </div>

        </div>
    </div>
</body>

</html>