<?php
    session_start();

    require_once('../PHP/connexioDB.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuari = $_POST['username'];
        $contrasenya = $_POST['password'];

        if(!isset($_SESSION['username'])) {
            $sql = "SELECT username FROM usuaris WHERE username = '$usuari'";
            $select = $db->query($sql);

            if($select->rowCount()>=1) {
                $_SESSION['username']= $usuari;
            }

            $sql2= "SELECT active FROM usuaris WHERE username = '$usuari' AND active = 1";
            $select2 = $db->query($sql2);

            if($select2->rowCount()==0) {
                $_SESSION['active']= 0;
            } else {
                $_SESSION['active']= 1;
            }

            $sql2 = "SELECT passHash FROM usuaris WHERE username = '$usuari'";
            $select2 = $db->query($sql2);

            if ($select2->rowCount() >= 1) {
                $result = $select2->fetch(PDO::FETCH_ASSOC);
                $_SESSION['passHash'] = $result['passHash'];
            } else {
                echo "No se encontró el usuario.";
            }


        }

        $active= $_SESSION['active'];
        $hash= $_SESSION['passHash'];

        if ($active==1) {
            if ($_SESSION['username'] == $usuari && password_verify($contrasenya, $hash) ||
            $_SESSION['mail'] == $usuari && password_verify($contrasenya, $hash)) { 
                $username= $_SESSION['username'];
                $lastSignIn = date("Y-m-d H:i:s");

                $sql = "UPDATE usuaris SET lastSignIn = :lastSignIn WHERE username = :username";
                $stmt = $db->prepare($sql);

                $stmt->execute([
                    ':lastSignIn' => $lastSignIn,
                    ':username' => $username
                ]);

                if ($stmt->rowCount() > 0) {
                    header('Location: ../PHP/home.php');
                }
            } else {
                echo 'Credencials incorrectes';
                header('Location: ../HTML/index.php');
            }   
        } else {
            echo "Algo ha sortit malament, intenta un altre cop...";
            header('Location: ../HTML/index.php');
        }
    } 
?>