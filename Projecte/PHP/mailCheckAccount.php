<?php
    session_start();
    require_once('../PHP/connexioDB.php');

    try {
        $email= $_SESSION['mail'];

        $sql = "UPDATE usuaris SET active = 1 WHERE mail = '$email'";
        $update = $db->query($sql);
        if($update){
            echo '<p>Activació Correcte</p>';
            echo '<p>Files Actualitzades: ' . $update->rowCount() . '</p>';

            $sql = "UPDATE usuaris SET activationCode = NULL WHERE mail = '$email'";
            $update2 = $db->query($sql);

            if ($update2) {
                echo '<p>Token esborrat!</p>';
                echo '<p>Files Actualitzades: ' . $update->rowCount() . '</p>';

                $activationDate= date("Y-m-d H:i:s");

                $sql = "UPDATE usuaris SET activationDate = '$activationDate' WHERE mail = '$email'";
                $update3 = $db->query($sql);

                if ($update3) {
                    header('Location: ../PHP/home.php');
                } else {
                    print_r( $db->errorinfo());
                }
            } else {
                print_r( $db->errorinfo());
            }
        }else{
            print_r( $db->errorinfo());
        }
    } catch (PDOException $e) {
        echo '<br>Error amb la BDs: ' . $e->getMessage();
    }
?>