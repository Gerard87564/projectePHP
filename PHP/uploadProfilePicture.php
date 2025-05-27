<?php
    session_start();

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $username = $_SESSION['username'];
        $targetDir = "../IMG/profiles/";
        $targetFile = $targetDir . $username . ".jpg";

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
                header('Location: userprofile.php');
            } else {
                echo "Error al pujar img";
            }
        } else {
            echo "Només son permesos arxius JPG, JPEG, PNG i GIF.";
        }
    } else {
        header('Location: userprofile.php');
    }
?>