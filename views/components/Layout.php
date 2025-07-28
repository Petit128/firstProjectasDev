<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php
    if (isset($title)) {
        echo htmlspecialchars($title);
    } else {
        echo htmlspecialchars('Restaurant Universitaire');
    }
    ?>
    </title>
    <link rel="stylesheet" href="assets/style/css/style.css">
    <script defer src="assets/js/script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php
    require_once("Header.php");
    require_once("Nav.php");
    require_once("Sidenav.php");
    ?>

    <div id="mainWrapper" class="main-wrapper">
        <div class="container">
            <div id="messageContainer" style="display:none;"></div>

            <?php
            if (isset($message) && $message != '') {
                echo "<p class='message'>" . htmlspecialchars($message) . "</p>";
            }
            if (isset($error) && $error != '') {
                echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
            }

            if (isset($content)) {
                echo $content;
            } else {
                echo "<p>Contenu de la page non disponible.</p>";
            }
            ?>
        </div>
    </div>

    <?php
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'etudiant') {
        require_once("CartButton.php");
    }
    require_once("Footer.php");
    ?>
</body>
</html>
