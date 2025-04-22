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
        <h1>Benvingut, <?php echo $_SESSION['username'];?>!</h1>
        <p>Aquesta és la teva àrea privada.</p>
        <a href="../PHP/logout.php">Tancar Sessió</a>
        <a href="#" onclick="popUP()">Forgot Password?</a>

        <div id="popUP" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
            <div style="background:#fff; padding:20px; margin:100px auto; width:300px; position:relative;">
                <h3>Reset Password</h3>
                <form action="../resetPasswordSend.php" method="POST">
                    <label for="user_input">Email or Username:</label><br>
                    <input type="text" name="user_input" id="user_input" required><br><br>
                    <button type="submit">Send Reset Password Email</button>
                </form>
                <button onclick="popUPclose()" style="position:absolute; top:5px; right:10px;">Close</button>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 GAzone. All Rights Reserved.</p>
    </footer>
    <script>
        function popUP() {
            document.getElementById('popUP').style.display = 'block';
        }

        function popUPclose() {
            document.getElementById('popUP').style.display = 'none';
        }
    </script>
</body>
</html>