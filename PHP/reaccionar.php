<?php
    session_start();
    require_once('../PHP/connexioDB.php');

    if (!isset($_SESSION['username'])) {
        header('Location: ../HTML/index.php');
        exit;
    }

    $username = $_SESSION['username'];
    $idpublicacio = $_POST['idpublicacio'];
    $tipus = $_POST['tipus'];
    $reaccions_permeses = ['like'];

    if (!in_array($tipus, $reaccions_permeses)) {
        echo "Error: reacció no vàlida.";
        exit;
    }

    $stmt = $db->prepare("SELECT tipus FROM reaccions WHERE username = :username AND idpublicacio = :idpublicacio");
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':idpublicacio', $idpublicacio, PDO::PARAM_INT);
    $stmt->execute();

    $reaccio_actual = $stmt->fetchColumn();

    if ($reaccio_actual) {
        if ($reaccio_actual === $tipus) {
            $delete = $db->prepare("DELETE FROM reaccions WHERE username = :username AND idpublicacio = :idpublicacio");
            $delete->bindValue(':username', $username, PDO::PARAM_STR);
            $delete->bindValue(':idpublicacio', $idpublicacio, PDO::PARAM_INT);
            $delete->execute();
            echo "Has eliminat la teva reacció '$tipus'.";
        } else {
            $update = $db->prepare("UPDATE reaccions SET tipus = :tipus, data = NOW() WHERE username = :username AND idpublicacio = :idpublicacio");
            $update->bindValue(':tipus', $tipus, PDO::PARAM_STR);
            $update->bindValue(':username', $username, PDO::PARAM_STR);
            $update->bindValue(':idpublicacio', $idpublicacio, PDO::PARAM_INT);
            $update->execute();
            echo "Has canviat la teva reacció a '$tipus'.";
        }
    } else {
        $insert = $db->prepare("INSERT INTO reaccions (username, idpublicacio, tipus) VALUES (:username, :idpublicacio, :tipus)");
        $insert->bindValue(':username', $username, PDO::PARAM_STR);
        $insert->bindValue(':idpublicacio', $idpublicacio, PDO::PARAM_INT);
        $insert->bindValue(':tipus', $tipus, PDO::PARAM_STR);
        $insert->execute();
        echo "Has reaccionat amb '$tipus'.";
    }
?>