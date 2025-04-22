<?php
    session_start();
    use  PHPMailer\PHPMailer\PHPMailer;
    require  'vendor/autoload.php';
    require_once('PHP/connexioDB.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userMail = $_POST["user_input"];
        $mail= $_SESSION['mail'];
        $username=$_SESSION['username'];

        if ($userMail==$mail) {
            $sql = "SELECT mail FROM usuaris WHERE mail = '$userMail'";
            $select = $db->query($sql);

            if($select->rowCount()>=1) {
                $caracters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $random = str_shuffle(substr($caracters, 0, 64));

                $resetPassCode= hash('sha256', $random);
                $receptor= $_SESSION['mail'];
                $currentDateTime = new DateTime();

                $currentDateTime->modify('+30 minutes');

                $resetPassExpiry = $currentDateTime->format('Y-m-d H:i:s');

                $_SESSION['resetPassExpiry'] = $resetPassExpiry;
                $_SESSION['resetPassCode'] = $resetPassCode;

                $sql = "UPDATE usuaris SET resetPassCode = '$resetPassCode' WHERE mail = '$receptor'";
                $update = $db->query($sql);

                if ($update) {

                    $sql = "UPDATE usuaris SET resetPassExpiry = '$resetPassExpiry' WHERE mail = '$receptor'";
                    $update2 = $db->query($sql);

                    if($update2) {
                        header('Location: HTML/index.php');
                    }
                }
                
                $date = date("Y-m-d H:i:s");
                $mail  =  new  PHPMailer();
                $mail->IsSMTP();
                
                $mail->SMTPDebug  =  0;
                $mail->SMTPAuth  =  true;
                $mail->SMTPSecure  =  'tls';
                $mail->Host  =  'smtp.gmail.com';
                $mail->Port  =  587;
                
                $mail->Username  =  'gerard.gonzalezp@educem.net';
                $mail->Password  =  'caax vymj ndcr zlqs';

                $mail->SetFrom('gerard.gonzalezp@educem.net', 'gerard.gonzalezp@educem.net');
                $mail->Subject='Forgot password';
                $mail->AddEmbeddedImage('IMG/ghost-svgrepo-com.png', 'logo');
                $mail->MsgHTML("<!DOCTYPE html>
                    <html lang='es'>
                        <body>
                            <p>This link expires in 30 minutes from $date</p>
                            <a href='http://localhost/M9/UF2/Projecte/PHP/resetPassword.php?code=$resetPassCode&mail=$receptor'>I want to Reset My Password</a>
                        </body>
                    </html>"
                );

                $mail->addAttachment("");
                
                $mail->AddAddress($receptor, 'reset password mail');
                $result=$mail->Send();
                if(!$result){
                    echo'Error:'.$mail->ErrorInfo;
                }else{
                    header('Location: HTML/index.php');
                }    
            }  
        } else if ($userMail==$username) {
            $sql = "SELECT mail FROM usuaris WHERE username = '$userMail'";
            $select = $db->query($sql);

            if($select->rowCount()>=1) {
                $caracters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $random = str_shuffle(substr($caracters, 0, 64));

                $resetPassCode= hash('sha256', $random);
                $receptor= $_SESSION['mail'];
                $currentDateTime = new DateTime();

                $currentDateTime->modify('+30 minutes');

                $resetPassExpiry = $currentDateTime->format('Y-m-d H:i:s');

                $_SESSION['resetPassExpiry'] = $resetPassExpiry;
                $_SESSION['resetPassCode'] = $resetPassCode;

                $sql = "UPDATE usuaris SET resetPassCode = '$resetPassCode' WHERE mail = '$receptor'";
                $update = $db->query($sql);

                if ($update) {

                    $sql = "UPDATE usuaris SET resetPassExpiry = '$resetPassExpiry' WHERE mail = '$receptor'";
                    $update2 = $db->query($sql);

                    if($update2) {
                        header('Location: HTML/index.php');
                    }
                }
                
                $date = date("Y-m-d H:i:s");
                $mail  =  new  PHPMailer();
                $mail->IsSMTP();
                
                $mail->SMTPDebug  =  0;
                $mail->SMTPAuth  =  true;
                $mail->SMTPSecure  =  'tls';
                $mail->Host  =  'smtp.gmail.com';
                $mail->Port  =  587;
                
                $mail->Username  =  'gerard.gonzalezp@educem.net';
                $mail->Password  =  'caax vymj ndcr zlqs';

                $mail->SetFrom('gerard.gonzalezp@educem.net', 'gerard.gonzalezp@educem.net');
                $mail->Subject='Forgot password';
                $mail->AddEmbeddedImage('IMG/ghost-svgrepo-com.png', 'logo');
                $mail->MsgHTML("<!DOCTYPE html>
                    <html lang='es'>
                        <body>
                            <p>This link expires in 30 minutes from $date</p>
                            <a href='http://localhost/M9/UF2/Projecte/PHP/resetPassword.php?code=$resetPassCode&mail=$receptor'>I want to Reset My Password</a>
                        </body>
                    </html>"
                );

                $mail->addAttachment("");
                
                $mail->AddAddress($receptor, 'reset password mail');
                $result=$mail->Send();
                if(!$result){
                    echo'Error:'.$mail->ErrorInfo;
                }else{
                    header('Location: HTML/index.php');
                }    
            }  
        }
    }
?>