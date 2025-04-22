<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header('Location: ../HTML/index.html');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAzone</title>
    <link rel="icon" href="../IMG/ghost-svgrepo-com.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/M9/UF2/Projecte/CSS/home.css?v=<?= time() ?>" type="text/css">
    <script src="../JS/script.js"></script>
    <script src="https://kit.fontawesome.com/f2dbf97883.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <img src="../IMG/ghost-svgrepo-com.png" alt="logo" width="100em" height="auto">
        <h1>GAzone</h1>
        <nav class="col-12 col-lg-12 col-dm-12 col-sm-12">
            <i class="fa-solid fa-user" id="user"></i>
            <a href="../PHP/logout.php" style="color: black;">
                <i class="fa-solid fa-right-from-bracket" id="logout"></i>
            </a>
        </nav>
    </header>
    <main>
        
    </main>
    <footer>
        <p>&copy; 2025 GAzone. All Rights Reserved.</p>
    </footer>
</body>
</html>