<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar">
    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'etudiant'): ?>
        <h3>Menu</h3>
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="index.php?page=student_menu&type_menu=dejeuner">Déjeuner</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=student_menu&type_menu=diner">Dîner</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=student_menu&type_menu=petit-dej">Petit-déjeuner</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=student_menu&type_menu=dessert">Dessert</a></li>
        </ul>
    <?php elseif (isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'): ?>
        <h3>Administration</h3>
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="index.php?page=dashboard_admin">Dashboard Admin</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=admin_manage_reservations">Gérer Réservations</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=admin_stats">Statistiques</a></li>
        </ul>
    <?php else: ?>
        <h3>Navigation</h3>
    <?php endif; ?>

    <div id="menuToggleBtn" class="menu-toggle-btn">
        <i class="fas fa-bars"></i>
    </div>
</nav>
