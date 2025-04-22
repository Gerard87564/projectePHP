<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $resetPassCode= $_GET['code'];
        $mail = $_GET['mail'];

        if ($mail==$_SESSION['mail'] && $resetPassCode==$_SESSION['resetPassCode']
        && $_SESSION['resetPassExpiry']>0) {
            header('Location: ../HTML/resetPasswd.html');
        }
    }
?>