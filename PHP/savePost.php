<?php
    session_start();
    require_once('connexioDB.php');

    if (!isset($_SESSION['username'])) {
        header('Location: ../HTML/index.php');
        exit;
    }

    $username = $_SESSION['username'];
    $contingut = trim($_POST['contingut']);

    $stmt = $db->prepare("SELECT iduser FROM usuaris WHERE username = :username");
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $iduser = $stmt->fetchColumn();

    $imagePath = null;

    if (isset($_FILES['imatge']) && $_FILES['imatge']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../IMG/publicacions/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileTmp = $_FILES['imatge']['tmp_name'];
        $fileName = basename($_FILES['imatge']['name']);
        $targetPath = $uploadDir . time() . "_" . $fileName;

        if (move_uploaded_file($fileTmp, $targetPath)) {
            $imagePath = $targetPath;
        }
    }

    if ($iduser && !empty($contingut)) {
        $stmt = $db->prepare("INSERT INTO publicacions (iduser, contingut, image_path) VALUES (:iduser, :contingut, :image)");
        $stmt->bindValue(':iduser', $iduser);
        $stmt->bindValue(':contingut', $contingut);
        $stmt->bindValue(':image', $imagePath);
        $stmt->execute();

        $postID = $db->lastInsertId();
        if (!empty($_POST['etiquetes'])) {
            $etiquetes_input = $_POST['etiquetes'];
            $etiquetes = array_map('trim', explode(',', $etiquetes_input)); 

            foreach ($etiquetes as $etiqueta) {
                $stmt = $db->prepare("INSERT IGNORE INTO etiquetes (nom) VALUES (:nom)");
                $stmt->bindValue(':nom', $etiqueta);
                $stmt->execute();

                $stmt = $db->prepare("SELECT idetiqueta FROM etiquetes WHERE nom = :nom");
                $stmt->bindValue(':nom', $etiqueta);
                $stmt->execute();
                $idetiqueta = $stmt->fetchColumn();

                $stmt = $db->prepare("INSERT INTO publicacions_etiquetes (idpublicacio, idetiqueta) VALUES (:idpost, :idetiqueta)");
                $stmt->bindValue(':idpost', $postID);
                $stmt->bindValue(':idetiqueta', $idetiqueta);
                $stmt->execute();
            }
        }
    }

    header('Location: userprofile.php');
    exit;
?>