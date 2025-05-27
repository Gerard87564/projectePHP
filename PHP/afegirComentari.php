<?php
    session_start();
    require_once('connexioDB.php');

    if (!isset($_SESSION['username']) || empty($_POST['comentari']) || empty($_POST['idpublicacio'])) {
        header('Location: userprofile.php');
        exit;
    }

    $username = $_SESSION['username'];
    $comentari = trim($_POST['comentari']);
    $idpublicacio = intval($_POST['idpublicacio']);

    $stmt = $db->prepare("SELECT iduser FROM usuaris WHERE username = :username");
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $iduser = $user['iduser'];
        $stmt = $db->prepare("INSERT INTO comentaris (iduser, idpublicacio, contingut, data_creacio) VALUES (:iduser, :idpublicacio, :contingut, NOW())");
        $stmt->bindValue(':iduser', $iduser);
        $stmt->bindValue(':idpublicacio', $idpublicacio);
        $stmt->bindValue(':contingut', $comentari);
        $stmt->execute();
    }

    $return_url = isset($_POST['return_url']) ? $_POST['return_url'] : 'userprofile.php';
    header('Location: ' . $return_url);
    exit;
?>