<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection and model files
require_once("models/Connexion.php");
require_once("models/Utilisateurs.php");
require_once("models/RepasModel.php");
require_once("models/ReservationModel.php");
require_once("models/StatistiqueModel.php");
require_once("models/ReservationPlat.php");
require_once("models/Allergene.php");
require_once("models/MenuPlat.php"); 

// Include controller files
require_once("controllers/PagesController.php");
require_once("controllers/AdminController.php");
require_once("controllers/StudentController.php");
require_once("controllers/Utilities.php");

// Instantiate controllers
$menuPlatModel = new MenuPlat();
$menuPlatModel->checkAndCreateAssociations();
$pagesController = new PagesController();
$adminController = new AdminController();
$studentController = new StudentController();

// Determine the requested page, default to 'login'
$page = 'login';
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

// Redirect logged-in users from login/register pages
if (isset($_SESSION['user_id'])) {
    if ($page == 'login' || $page == 'register') {
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') {
            header("Location: index.php?page=dashboard_admin");
            exit();
        } else {
            header("Location: index.php?page=accueil");
            exit();
        }
    }
}

// Routing logic based on the requested page
switch ($page) {
    // Public Pages
    case 'login':
        $pagesController->LoginPage();
        break;
    case 'register':
        $pagesController->RegisterPage();
        break;
    case 'logout':
        $pagesController->Logout();
        break;

    // Admin Pages
    case 'dashboard_admin':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        if ($_SESSION['user_type'] != 'admin') {
            header('Location: index.php?page=login');
            exit();
        }
        $adminController->DashboardAdmin();
        break;
    case 'admin_manage_reservations':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        if ($_SESSION['user_type'] != 'admin') {
            header('Location: index.php?page=login');
            exit();
        }
        $adminController->manageReservations();
        break;
    case 'admin_update_reservation_status':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        if ($_SESSION['user_type'] != 'admin') {
            header('Location: index.php?page=login');
            exit();
        }
        $adminController->updateReservationStatus();
        break;
    case 'admin_stats':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        if ($_SESSION['user_type'] != 'admin') {
            header('Location: index.php?page=login');
            exit();
        }
        $adminController->statistics();
        break;
    case 'update_stock':
        if (!isset($_SESSION['user_id'])) {
                header('Location: index.php?page=login');
                exit();
            }
            $adminController->updateStock();
       break;          
    case 'admin_stock_status':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        if ($_SESSION['user_type'] != 'admin') {
            header('Location: index.php?page=login');
            exit();
        }
        $adminController->checkStock();
        break;

    // Student Pages
    case 'accueil':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        $studentController->DashboardEtudiant();
        break;
    case 'student_menu':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        $type_service_param = '';
        if (isset($_GET['type_menu'])) { 
            $type_service_param = $_GET['type_menu'];
        }
        $studentController->menu($type_service_param);
        break;
    case 'add_to_cart':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        $studentController->addToCart();
        break;
    case 'remove_from_cart':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        $studentController->removeFromCart();
        break;
    case 'panier':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        $studentController->panier();
        break;
    case 'create_reservation':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        $studentController->createReservation();
        break;
    case 'student_notifications':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        $studentController->notifications();
        break;
    case 'student_reservations':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        $studentController->listReservations();
        break;
    case 'student_profile':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        $studentController->profile();
        break;
    case 'update_profile':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        $studentController->updateProfile();
        break;
    case 'student_options':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        $studentController->options();
        break;

    default:
        header('Location: index.php?page=login');
        exit();
}
?>
