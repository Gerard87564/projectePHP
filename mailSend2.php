<?php
    session_start();
    use  PHPMailer\PHPMailer\PHPMailer;
    require  'vendor/autoload.php';

    $receptor= $_SESSION['mail'];
    
    $mail  =  new  PHPMailer();
    $mail->IsSMTP();
    
    $mail->SMTPDebug  =  0;
    $mail->SMTPAuth  =  true;
    $mail->SMTPSecure  =  'tls';
    $mail->Host  =  'smtp.gmail.com';
    $mail->Port  =  587;
    
    $mail->Username  =  '#';
    $mail->Password  =  '#';

    $mail->SetFrom('#', '#');
    $mail->Subject='Password reset successfully!';
    $mail->AddEmbeddedImage('IMG/ghost-svgrepo-com.png', 'logo');
    $mail->MsgHTML("<!DOCTYPE html>
                    <html lang='es'>
                        <body>
                            <p>Your password has reset successfully!<p>
                        </body>
                    </html>"
                );
    $mail->addAttachment("");
    
    $mail->AddAddress($receptor, 'Info mail');
    $result=$mail->Send();
    if(!$result){
        echo'Error:'.$mail->ErrorInfo;
    }else{
        header('Location: PHP/home.php');
    }    
?>
