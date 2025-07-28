<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<div id="sidenav" class="sidenav">
    <!-- Sidenav close button -->
    <a href="javascript:void(0)" id="sidenavCloseBtn" class="sidenav-close-btn">&times;</a>

    <ul class="sidenav-bar">
        <?php
        // Display sidebar links based on user type
        if (isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'etudiant'): ?>
            <li class="sidenav-item"><a class="sidenav-link" href="index.php?page=accueil"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="sidenav-item sidenav-drop">
                <a class="sidenav-link drop-link" href="#"><i class="fas fa-user-circle"></i> Compte <i class="fas fa-caret-down"></i></a>
                <div class="sidenav-drop-content">
                    <a href="index.php?page=student_profile">Modifier Profil</a>
                    <a href="index.php?page=student_reservations">Mes Réservations</a>
                </div>
            </li>
            <!-- Updated notification icon -->
            <li class="sidenav-item"><a href="index.php?page=student_notifications"><i class="fas fa-bell"></i> Notifications</a></li>
        <?php elseif (isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'): ?>
            <li class="sidenav-item"><a class="sidenav-link" href="index.php?page=dashboard_admin"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="sidenav-item"><a class="sidenav-link" href="index.php?page=admin_manage_reservations"><i class="fas fa-calendar-check"></i> Gérer Réservations</a></li>
            <li class="sidenav-item"><a class="sidenav-link" href="index.php?page=admin_stats"><i class="fas fa-chart-line"></i> Statistiques</a></li>
        <?php else: ?>
            <li class="sidenav-item"><a class="sidenav-link" href="index.php?page=login"><i class="fas fa-sign-in-alt"></i> Connexion</a></li>
            <li class="sidenav-item"><a class="sidenav-link" href="index.php?page=register"><i class="fas fa-user-plus"></i> Inscription</a></li>
        <?php endif; ?>
    </ul>

    <?php
    // Display logout button if user is logged in
    if (isset($_SESSION['user_id'])): ?>
        <div class="sidenav-logout">
            <a class="sidenav-link logout" href="index.php?page=logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
    <?php endif; ?>
</div>
