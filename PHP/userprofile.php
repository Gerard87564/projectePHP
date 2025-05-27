<?php
    session_start();
    require_once('../PHP/connexioDB.php');

    if (!isset($_SESSION['username'])) {
        header('Location: ../HTML/index.php');
        exit;
    }

    $username = $_SESSION['username'];
    
    $stmt = $db->prepare("
        SELECT u.username, up.bio
        FROM usuaris u
        LEFT JOIN user_profile up ON u.iduser = up.iduser
        WHERE u.username = :username
    ");
    
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $bio = $user['bio'];

    $stmt = $db->prepare("
        SELECT u.username, up.ubi
        FROM usuaris u
        LEFT JOIN user_profile up ON u.iduser = up.iduser
        WHERE u.username = :username
    ");

    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $ubi = $user['ubi'];

    $stmt = $db->prepare("
        SELECT u.username, up.age
        FROM usuaris u
        LEFT JOIN user_profile up ON u.iduser = up.iduser
        WHERE u.username = :username
    ");

    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $username = $user['username'];
    $edat = $user['age'];

    $profileImage = "../IMG/profiles/" . $_SESSION['username'] . ".jpg";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAzone</title>
    <link rel="icon" href="../IMG/ghost-svgrepo-com.png" type="image/x-icon">
    <link rel="stylesheet" href="/M9/UF2/Projecte/CSS/userprofile.css?v=<?= time() ?>" type="text/css">
    <script src="../JS/script.js"></script>
    <script src="https://kit.fontawesome.com/f2dbf97883.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <a href="../PHP/home.php">
            <img src="../IMG/ghost-svgrepo-com.png" alt="logo" width="100em" height="auto">
        </a>
        <h1>GAzone</h1>
        <nav class="col-12 col-lg-12 col-dm-12 col-sm-12">
            <a href="../PHP/userprofile.php">
                <i class="fa-solid fa-user" id="user"></i>
            </a>
            <a href="../PHP/logout.php">
                <i class="fa-solid fa-right-from-bracket" id="logout"></i>
            </a>
        </nav>
    </header>
    <main class="profile-container">
        <div class="profile-card">
            <h1>@<?php echo htmlspecialchars($username); ?></h1>
            <div class="banner">
                <?php $bannerPath = "../IMG/banners/" . $_SESSION['username'] . ".jpg"; ?>
                <img src="<?php echo file_exists($bannerPath) ? $bannerPath . "?v=" . time() : '../IMG/banners/default.jpg'; ?>" alt="Banner de perfil">

                <form action="../PHP/uploadBanner.php" method="POST" enctype="multipart/form-data" class="upload-banner-form">
                    <label for="banner-upload" class="upload-banner-btn">
                        <i class="fa-solid fa-plus"></i>
                    </label>
                    <input type="file" id="banner-upload" name="banner" accept="image/*" onchange="this.form.submit()">
                </form>

                <form class="profile-form-inside-banner" action="../PHP/uploadProfilePicture.php" method="POST" enctype="multipart/form-data">
                    <label for="profile-upload" class="profile-pic-container">
                        <?php if (file_exists($profileImage)): ?>
                            <?php $profileImage = "../IMG/profiles/" . $_SESSION['username'] . ".jpg"; ?>
                            <img src="<?php echo file_exists($profileImage) ? $profileImage . "?v=" . time() : '../IMG/profiles/default.jpg'; ?>" alt="Foto de perfil" class="profile-pic">
                        <?php else: ?>
                            <i class="fa-solid fa-user" style="font-size: 3em; color: white;"></i>
                        <?php endif; ?>
                        <div class="overlay">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </label>
                    <input type="file" id="profile-upload" name="profile_picture" accept="image/*" onchange="this.form.submit()" style="display: none;">
                </form>
            </div>


            <div class="info-block">
                <h2>Ubicació</h2>
                <p id="ubi-text"><?php echo !empty($ubi) ? htmlspecialchars($ubi) : "No has definit la teva ubicació encara!"; ?></p>
                <button id="edit-ubi-btn">Editar ubicació</button>
                <form action="../PHP/saveUbi.php" method="POST" id="ubi-form" style="display: none;">
                    <input type="text" name="ubi">
                    <button type="submit">Guardar Ubicació</button>
                </form>
            </div>

            <div class="info-block">
                <h2>Edat</h2>
                <p id="edat-text"><?php echo !empty($edat) ? htmlspecialchars($edat) : "No has definit la teva edat encara!"; ?></p>
                <button id="edit-edat-btn">Editar edat</button>
                <form action="../PHP/saveEdat.php" method="POST" id="edat-form" style="display: none;">
                    <input type="text" name="edat">
                    <button type="submit">Guardar Edat</button>
                </form>
            </div>

            <div class="info-block">
                <h2>Biografía</h2>
                <p id="bio-text"><?php echo !empty($bio) ? htmlspecialchars($bio) : "No has escrit una biografia encara."; ?></p>
                <button id="edit-bio-btn">Editar Bio</button>
                <form action="../PHP/saveBio.php" method="POST" id="bio-form" style="display: none;">
                    <textarea name="bio" rows="3"><?php echo htmlspecialchars($bio); ?></textarea>
                    <button type="submit">Guardar Bio</button>
                </form>
            </div>
        </div>

        <div class="new-post">
            <h2>Nova Publicació</h2>
            <form action="../PHP/savePost.php" method="POST" enctype="multipart/form-data">
                <textarea name="contingut" rows="4" placeholder="Què tens al cap?" required></textarea>
                <input type="file" name="imatge" accept="image/*">
                <input type="text" name="etiquetes" placeholder="Etiquetes separades per comes (ex: esport, música, videojocs..etc)">
                <button type="submit">Publicar</button>
            </form>
        </div>

        <section class="posts-section">
            <h2>Les teves publicacions</h2>
            <?php
                $etiqueta = $_GET['etiqueta'] ?? '';
                
                if ($etiqueta) {
                    $stmt = $db->prepare("SELECT idetiqueta FROM etiquetes WHERE nom = :nom");
                    $stmt->bindValue(':nom', $etiqueta);
                    $stmt->execute();
                    $idEtiqueta = $stmt->fetchColumn();

                    if (!$idEtiqueta) {
                        $posts = [];
                    } else {
                        $stmt = $db->prepare("
                            SELECT p.id AS idpublicacio, p.contingut, p.data_creacio, p.image_path,
                                COUNT(CASE WHEN r.tipus = 'like' THEN 1 END) AS likes,
                                COUNT(DISTINCT c.id) AS num_comentaris
                            FROM publicacions p
                            INNER JOIN publicacions_etiquetes pe ON p.id = pe.idpublicacio
                            INNER JOIN usuaris u ON p.iduser = u.iduser
                            WHERE pe.idetiqueta = :idetiqueta
                            ORDER BY p.data_creacio DESC
                        ");

                        $sql->bindValue(':idetiqueta', $idEtiqueta, PDO::PARAM_INT);
                        $sql->execute();
                        $posts = $sql->fetchAll(PDO::FETCH_ASSOC);

                        echo "<h3>Publicacions amb l'etiqueta: #" . htmlspecialchars($etiqueta) . "</h3>";
                    }
                } else {
                    $stmt = $db->prepare("
                        SELECT p.id AS idpublicacio, p.contingut, p.data_creacio, p.image_path,
                            COUNT(CASE WHEN r.tipus = 'like' THEN 1 END) AS likes,
                            COUNT(DISTINCT c.id) AS num_comentaris
                        FROM publicacions p
                        INNER JOIN usuaris u ON p.iduser = u.iduser
                        LEFT JOIN reaccions r ON p.id = r.idpublicacio
                        LEFT JOIN comentaris c ON p.id = c.idpublicacio
                        WHERE u.username = :username
                        GROUP BY p.id
                        ORDER BY p.data_creacio DESC
                    ");            
                
                    $stmt->bindValue(':username', $username);
                    $stmt->execute();
                    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                if ($posts) {
                    foreach ($posts as $post) {
                        $puntuacio = 
                            $post['likes'] + 
                            $post['num_comentaris'];
            ?>
                        <div class="post publicacio" data-id=<?php echo $post['idpublicacio']; ?>>
                            <p><?php echo htmlspecialchars($post['contingut']); ?></p>
                            <?php if (!empty($post['image_path']) && file_exists($post['image_path'])): ?>
                                <img src="<?php echo $post['image_path']; ?>" alt="Imatge Publicació" class="post-image">
                            <?php endif; ?>
                            <small><?php echo htmlspecialchars($post['data_creacio']); ?></small>

                            <div class="reaccions">
                                <button class="react-btn" data-type="like">❤️ <?php echo $post['likes']; ?></button>
                            </div>
                            
                            <p class="puntuacio-post">⭐ Puntuació: <?php echo $puntuacio; ?></p>

                            <div class="comentaris-toggle" onclick="toggleComentaris(<?php echo $post['idpublicacio']; ?>)">
                                💬 <?php echo $post['num_comentaris']; ?>
                            </div>

                            <div class="etiquetes">
                                <?php
                                    $stmt = $db->prepare("
                                        SELECT e.nom 
                                        FROM etiquetes e
                                        JOIN publicacions_etiquetes pe ON e.idetiqueta = pe.idetiqueta
                                        WHERE pe.idpublicacio = :idpublicacio
                                    ");
                                    $stmt->bindValue(':idpublicacio', $post['idpublicacio']);
                                    $stmt->execute();
                                    $etiquetes = $stmt->fetchAll(PDO::FETCH_COLUMN);


                                    foreach ($etiquetes as $tag) {
                                        echo "<a class='etiqueta' href='home.php?etiqueta=" . urlencode($tag) . "'>#". htmlspecialchars($tag) . "</a> ";
                                    }
                                ?>
                            </div>

                            <div class="comentaris-container" id="comentaris-<?php echo $post['idpublicacio']; ?>" style="display: none">
                                <?php
                                    $stmtComent = $db->prepare("
                                        SELECT c.contingut, c.data_creacio, u.username
                                        FROM comentaris c
                                        INNER JOIN usuaris u ON c.iduser = u.iduser
                                        WHERE c.idpublicacio = :idpublicacio
                                        ORDER BY c.data_creacio ASC
                                    ");
                                    $stmtComent->bindValue(':idpublicacio', $post['idpublicacio'], PDO::PARAM_INT);
                                    $stmtComent->execute();
                                    $comentaris = $stmtComent->fetchAll(PDO::FETCH_ASSOC);

                                    if ($comentaris) {
                                        foreach ($comentaris as $comentari) {
                                ?>
                                    <div class="comentari">
                                        <strong>@<?php echo htmlspecialchars($comentari['username']); ?></strong>: 
                                        <?php echo htmlspecialchars($comentari['contingut']); ?>
                                        <small>(<?php echo htmlspecialchars($comentari['data_creacio']); ?>)</small>
                                    </div>
                                <?php
                                        }
                                    }
                                    else {
                                        echo "<p>No hi ha comentaris encara.</p>";
                                    }
                                ?>

                                <form class="formulari-comentari" method="POST" action="../PHP/afegirComentari.php">
                                    <input type="hidden" name="idpublicacio" value="<?php echo $post['idpublicacio']; ?>">
                                    <textarea name="comentari" rows="2" placeholder="Escriu un comentari..." required></textarea>
                                    <button type="submit">Publicar</button>
                                </form>
                            </div>

                            <div class="resposta-reaccio" id=resposta-<?php echo $post['idpublicacio']; ?>></div>
                        </div>
            <?php
                    }
                }
                else {
                    echo "<p>Encara no has publicat res.</p>";
                }
            ?>
        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('.react-btn').click(function() {
                const tipus = $(this).data('type');
                const idpublicacio = $(this).closest('.publicacio').data('id');
                $.post("../PHP/reaccionar.php", {
                    tipus: tipus,
                    idpublicacio: idpublicacio
                }, function(data) {
                    $('#resposta-' + idpublicacio).html(data);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const editBtn = document.getElementById('edit-bio-btn');
            const form = document.getElementById('bio-form');
            const bioText = document.getElementById('bio-text');

            editBtn.addEventListener('click', function () {
                form.style.display = 'block';
                editBtn.style.display = 'none';
                bioText.style.display = 'none';
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const editBtnUbi= document.getElementById('edit-ubi-btn');
            const formUbi = document.getElementById('ubi-form');
            const ubiText = document.getElementById('ubi-text');

            editBtnUbi.addEventListener('click', function () {
                formUbi.style.display = 'block';
                editBtnUbi.style.display = 'none';
                ubiText.style.display = 'none';
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const editBtnEdat= document.getElementById('edit-edat-btn');
            const formEdat = document.getElementById('edat-form');
            const edatText = document.getElementById('edat-text');

            editBtnEdat.addEventListener('click', function () {
                formEdat.style.display = 'block';
                editBtnEdat.style.display = 'none';
                edatText.style.display = 'none';
            });
        });

        function toggleComentaris(id) {
            const container = document.getElementById('comentaris-' + id);
            if (container.style.display === 'none') {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        }
    </script>
    <footer>
        <p>&copy; 2025 GAzone. All Rights Reserved.</p>
    </footer>
</body>
</html>