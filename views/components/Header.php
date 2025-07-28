<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
    <?php
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'etudiant') { ?>
        <div class="text" style="z-index: 1;">Bonjour <?php
        $user_nom = '';
        if (isset($_SESSION['user_nom'])) {
            $user_nom = $_SESSION['user_nom'];
        }
        $user_prenom = '';
        if (isset($_SESSION['user_prenom'])) {
            $user_prenom = $_SESSION['user_prenom'];
        }
        echo htmlspecialchars($user_nom) . " " . htmlspecialchars($user_prenom);
        ?>!</div>
    <?php } elseif (isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') { ?>
        <div class="text">Tableau de Bord Admin</div>
    <?php } else { ?>
        <div class="text">Bienvenue au Restaurant Universitaire</div>
    <?php } ?>
</header>
