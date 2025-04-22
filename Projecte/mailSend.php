<?php
    session_start();
    use  PHPMailer\PHPMailer\PHPMailer;
    require  'vendor/autoload.php';

    $receptor= $_SESSION['mail'];
    $activationCode= $_SESSION['activationCode'];
    
    $mail  =  new  PHPMailer();
    $mail->IsSMTP();
    
    $mail->SMTPDebug  =  0;
    $mail->SMTPAuth  =  true;
    $mail->SMTPSecure  =  'tls';
    $mail->Host  =  'smtp.gmail.com';
    $mail->Port  =  587;
    
    $mail->Username  =  '#';
    $mail->Password  =  '#';

    $mail->SetFrom('gerard.gonzalezp@educem.net', 'gerard.gonzalezp@educem.net');
    $mail->Subject='Activation link';
    $mail->AddEmbeddedImage('IMG/ghost-svgrepo-com.png', 'logo');
    $mail->MsgHTML("<!DOCTYPE html>
                    <html lang='es'>
                        <body>
                            <a href='http://localhost/M9/UF2/Projecte/PHP/mailCheckAccount.php?activationCode=' . $activationCode>Active your account Now!</a>
                        </body>
                    </html>"
                );
    $mail->addAttachment("");
    
    $mail->AddAddress($receptor, 'Activation mail');
    $result=$mail->Send();
    if(!$result){
        echo'Error:'.$mail->ErrorInfo;
    }else{
        header('Location: HTML/register.html');
    }    
?>
