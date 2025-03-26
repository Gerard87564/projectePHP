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
    <link rel="stylesheet" href="{{ url_for('static', filename='CSS/style.css') }}" type="text/css">
    <script src="../JS/script.js"></script>
</head>
<body>
    <header>
        <h1>GAzone</h1>
        <nav class="col-12 col-lg-12 col-dm-12 col-sm-12">
            <ul id="htopnav">
                
            </ul>
            
            <div id="menu">
                <div id="bar"></div>
            </div> 
        </nav>
    </header>
    <main>
        <h1>Benvingut, <?php echo $_SESSION['username'];?>!</h1>
        <p>Aquesta és la teva àrea privada.</p>
        <a href="../PHP/logout.php">Tancar Sessió</a>

    </main>
    <footer>
        <p>&copy; 2025 GAzone. All Rights Reserved.</p>
    </footer>
</body>
</html>