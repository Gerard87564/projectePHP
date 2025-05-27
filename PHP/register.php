<?php
    session_start();
    require_once('../PHP/connexioDB.php');

    try {
        $username=$_POST['username'];
        $mail=$_POST['mail'];
        $userFirstName=$_POST['userFirstName'];
        $userLastName=$_POST['userLastName'];
        $creationDate= date("Y-m-d H:i:s");        
        $passHash=$_POST['passHash'];
        $passHashV=$_POST['passwdV'];
        $active=0;

        $caracters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $random = str_shuffle(substr($caracters, 0, 64));

        $activationCode=hash('sha256', $random);

        $camps= [
            'username' => $username,
            'mail' => $mail
        ];

        foreach($camps as $key => $value) {
            if ($key=='username') {
                $sql= "SELECT $key FROM usuaris WHERE $key = '$value'";
                $select = $db->query($sql);

                if($select->rowCount()==0) {
                    $inserir=1;
                } else {
                    $inserir=0;
                }
            } else if ($key=='mail') {
                $sql= "SELECT $key FROM usuaris WHERE $key = '$value'";
                $select = $db->query($sql);

                if($select->rowCount()==0) {
                    $inserir2=1;
                } else {
                    $inserir2=0;
                }
            }
        }

        if ($inserir2==1 && $inserir==1) {
            if($passHash == $passHashV) {
                $passHash2= password_hash($passHash, PASSWORD_DEFAULT);
                $sql= "INSERT INTO usuaris(username, mail, passHash, userFirstName, userLastName, creationDate, active, activationCode)
                VALUES('$username', '$mail', '$passHash2', '$userFirstName', '$userLastName', '$creationDate', '$active', '$activationCode')";

                $insert = $db->query($sql);

                if($insert){
                    $_SESSION['passHash'] = $passHash2;
                    $_SESSION['username'] = $username;
                    $_SESSION['mail'] = $mail;
                    $_SESSION['active'] = $active;
                    $_SESSION['activationCode'] = $activationCode;

                    header('Location: ../mailSend.php');
                }else{
                    print_r( $db->errorinfo());
                }
            } else {
                echo "Les contrasenyes no son iguals..";
            }
        } else {
            if ($inserir2==0) {
                echo "Mail $mail existent...";
            } else if ($inserir==0) {
                echo "Nom usuari $username existent...";
            }
        }
    } catch (PDOException $e) {
        echo '<br>Error amb la BDs: ' . $e->getMessage();
    }
?>