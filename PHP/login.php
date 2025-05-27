<?php
    session_start();
    require_once('../PHP/connexioDB.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuari = $_POST['username'];
        $contrasenya = $_POST['password'];

        $sql = "SELECT * FROM usuaris WHERE username = :usuari OR mail = :usuari LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':usuari' => $usuari]);

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user['active'] !== 1) {
                echo "El compte no està actiu.";
                header("Location: ../HTML/index.php");
                exit();
            }

            if (password_verify($contrasenya, $user['passHash'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['mail'] = $user['mail'];
                $_SESSION['active'] = $user['active'];

                $sql = "UPDATE usuaris SET lastSignIn = :lastSignIn WHERE username = :username";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':lastSignIn' => date("Y-m-d H:i:s"),
                    ':username' => $user['username']
                ]);

                header('Location: ../PHP/home.php');
                exit();
            } else {
                echo "Contrasenya incorrecta.";
                header("Location: ../HTML/index.php");
                exit();
            }
        } else {
            echo "Usuari no trobat.";
            header("Location: ../HTML/index.php");
            exit();
        }
    }
?>