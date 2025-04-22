<?php
    session_start();
    require_once('../PHP/connexioDB.php');

    if ($_POST['passHash'] == $_POST['passwdV']) {
        $passHash= $_POST['passHash'];   
        $resetPassCode= $_SESSION['resetPassCode'];

        $newPass= password_hash($passHash, PASSWORD_DEFAULT);

        $sql = "UPDATE usuaris SET passHash = :newPass WHERE resetPassCode = :resetPassCode";
        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':newPass' => $newPass,
            ':resetPassCode' => $resetPassCode
        ]);

        if ($stmt->rowCount() > 0) {
            header('Location: ../mailSend2.php');
        }
    }
?>