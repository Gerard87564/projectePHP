<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header('Location: ../HTML/index.php');
        exit;
    }

    $username = $_SESSION['username'];
    $uploadDir = "../IMG/banners/";
    $targetFile = $uploadDir . $username . ".jpg";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
        move_uploaded_file($_FILES['banner']['tmp_name'], $targetFile);
    }

    header("Location: userprofile.php");
    exit;
?>