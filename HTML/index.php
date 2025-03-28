<?php
    session_start();
    
    if (isset($_SESSION['username'])) {
        header('Location: ../PHP/home.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>GAzone</title>
        <link rel="stylesheet" href="../CSS/login.css" type="text/css">
    </head>
    <body>
        <h1>Sign In</h1>
        <form method="POST" action="../PHP/login.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>
        <a href="../HTML/register.html">Don't have an account yet? Sign Up</a>
    </body>
</html>