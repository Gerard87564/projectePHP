<?php
    session_start();
    require_once('../PHP/connexioDB.php');

    if (!isset($_SESSION['username'])) {
        header('Location: ../HTML/index.php');
        exit;
    }

    $username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAzone</title>
    <link rel="icon" href="../IMG/ghost-svgrepo-com.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/M9/UF2/Projecte/CSS/home.css?v=<?= time() ?>" type="text/css">
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
    <main>
        <div id="main-a">
            <h1>Benvingut, <?php echo $_SESSION['username'];?>!</h1>
            <p>Aquesta és la teva àrea privada.</p>
            <a href="../PHP/logout.php">Tancar Sessió</a>
            <a href="#" onclick="popUP()">Forgot Password?</a>
        </div>

        <div id="popUP" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
            <div style="background:#fff; padding:20px; margin:100px auto; width:300px; position:relative;">
                <h3>Reset Password</h3>
                <form action="../resetPasswordSend.php" method="POST">
                    <label for="user_input">Email or Username:</label><br>
                    <input type="text" name="user_input" id="user_input" required><br><br>
                    <button type="submit">Send Reset Password Email</button>
                </form>
                <button onclick="popUPclose()" style="position:absolute; top:5px; right:10px;">Close</button>
            </div>
        </div>


        <section class="posts-section">
            <h2>Publicacions recents</h2>
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
                        $sql = $db->prepare("
                            SELECT
                                p.id AS idpublicacio,
                                p.contingut,
                                p.data_creacio,
                                p.image_path,
                                u.username,
                                (SELECT COUNT(*) FROM reaccions r WHERE r.idpublicacio = p.id AND r.tipus = 'like') AS likes,
                                (SELECT COUNT(*) FROM comentaris c WHERE c.idpublicacio = p.id) AS num_comentaris
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
                    $sql = $db->prepare("
                        SELECT
                            p.id AS idpublicacio,
                            p.contingut,
                            p.data_creacio,
                            p.image_path,
                            u.username,
                            (SELECT COUNT(*) FROM reaccions r WHERE r.idpublicacio = p.id AND r.tipus = 'like') AS likes,
                            (SELECT COUNT(*) FROM comentaris c WHERE c.idpublicacio = p.id) AS num_comentaris
                        FROM (
                            SELECT p1.*
                            FROM publicacions p1
                            WHERE (
                                SELECT COUNT(*) 
                                FROM publicacions p2 
                                WHERE p2.iduser = p1.iduser 
                                AND p2.data_creacio >= p1.data_creacio
                            ) <= 2
                        ) AS p
                        INNER JOIN usuaris u ON p.iduser = u.iduser
                        ORDER BY u.username, p.data_creacio DESC
                    ");
                    $sql->execute();
                    $posts = $sql->fetchAll(PDO::FETCH_ASSOC);
                }

                if ($posts) {
                    foreach ($posts as $post) {
                        $puntuacio = 
                            $post['likes'] + 
                            $post['num_comentaris'];
            ?>
                        <div class="post publicacio" data-id=<?php echo $post['idpublicacio']; ?>>
                            <label class="profile-pic-container">
                                <?php
                                    $profileImage = "../IMG/profiles/" . $post['username'] . ".jpg";
                                    if (file_exists($profileImage)) {
                                        echo '<img src="' . $profileImage . '?v=' . time() . '" alt="Foto de perfil" class="profile-pic">';
                                    } else {
                                        echo '<div class="default-profile-pic"><i class="fa-solid fa-user"></i></div>';
                                    }
                                ?>
                            </label>

                            <p><strong>@<?php echo htmlspecialchars($post['username']); ?></strong></p>
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
                                    $sqlComent = $db->prepare("
                                        SELECT c.contingut, c.data_creacio, u.username
                                        FROM comentaris c
                                        INNER JOIN usuaris u ON c.iduser = u.iduser
                                        WHERE c.idpublicacio = :idpublicacio
                                        ORDER BY c.data_creacio ASC
                                    ");
                                    $sqlComent->bindValue(':idpublicacio', $post['idpublicacio'], PDO::PARAM_INT);
                                    $sqlComent->execute();
                                    $comentaris = $sqlComent->fetchAll(PDO::FETCH_ASSOC);

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
                                    <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
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
    <footer>
        <p>&copy; 2025 GAzone. All Rights Reserved.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function popUP() {
            document.getElementById('popUP').style.display = 'block';
        }

        function popUPclose() {
            document.getElementById('popUP').style.display = 'none';
        }

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

        function toggleComentaris(id) {
            const container = document.getElementById('comentaris-' + id);
            if (container.style.display === 'none') {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        }
    </script>
</body>
</html>