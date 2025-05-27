<?php
    session_start();
    require_once('../PHP/connexioDB.php');

    $username = $_SESSION['username'];
    $edat = trim($_POST['edat']);

    $stmt = $db->prepare("SELECT iduser FROM usuaris WHERE username = :username");
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user_id = $stmt->fetch(PDO::FETCH_COLUMN); 


    if ($user_id) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM user_profile WHERE iduser = :iduser");
        $stmt->bindValue(':iduser', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $stmt = $db->prepare("UPDATE user_profile SET age = :age WHERE iduser = :iduser");
            $stmt->bindValue(':age', $edat, PDO::PARAM_STR);
            $stmt->bindValue(':iduser', $user_id, PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("INSERT INTO user_profile (iduser, age) VALUES (:iduser, :age)");
            $stmt->bindValue(':iduser', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':age', $edat, PDO::PARAM_STR);
        }
        $stmt->execute();
    }

    header("Location: userprofile.php");
    exit;
?>