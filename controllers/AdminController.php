<?php
require_once("Utilities.php");
require_once("models/RepasModel.php");
require_once("models/MenuModel.php"); // Add this line
require_once("models/MenuPlat.php"); // Add this line
require_once("models/ReservationModel.php"); // Assurez-vous que ReservationModel est inclus
require_once("models/ReservationPlat.php"); // Assurez-vous que ReservationPlat est inclus
   
    

class AdminController {
    
    public function dashboardAdmin() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Vérification des droits admin
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
            header("Location: index.php?page=login");
            exit();
        }
        
        $message = '';
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            unset($_SESSION['message']);
        }
        
        $error = '';
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        
        $action = '';
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        }
        
        $repas_model = new RepasModel();
        $plat_modifier = null;
        
        // Traitement des actions
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($action == 'ajouter') {
                $this->ajouterPlat($repas_model);
                return;
            } elseif ($action == 'modifier') {
                $this->modifierPlat($repas_model);
                return;
            }
        } elseif ($action == 'supprimer') {
            $this->supprimerPlat($repas_model);
            return;
        } elseif ($action == 'modifier_form') {
            $id = 0;
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
            }
            $plat_modifier = $repas_model->getById($id);
        }
        
        // Récupération de tous les plats
        $plats = $repas_model->getAll();
        
        $data_page = array(
            "title" => "Dashboard Admin",
            "view" => "views/admin/dashboardadmin.php",
            "layout" => "views/components/Layout.php",
            "message" => $message,
            "error" => $error,
            "plats" => $plats,
            "plat_modifier" => $plat_modifier
        );
        
        $util = new Utilities();
        $util->drawPage($data_page);
    }
    
    public function ajouterPlat($repas_model) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        // Initialisation des variables avec des valeurs par défaut sûres
        $nom_plat = '';
        $description = '';
        $type_plat = '';
        $type_menu = '';
        $categorie = '';
        $quota_max = 0;
        $prix = 0.0;
        $calories = 0;
        $statut = '';
        $image = 'default_plats.jpg'; // Valeur par défaut pour l'image
    
        // Récupération des données du POST avec vérification isset
        if (isset($_POST["nom_plat"])) {
            $nom_plat = $_POST["nom_plat"];
        }
        if (isset($_POST["description"])) {
            $description = $_POST["description"];
        }
        if (isset($_POST["type_plat"])) {
            $type_plat = $_POST["type_plat"];
        }
        if (isset($_POST["type_menu"])) {
            $type_menu = $_POST["type_menu"];
        }
        if (isset($_POST["categorie"])) {
            $categorie = $_POST["categorie"];
        }
        if (isset($_POST["quota_max"])) {
            $quota_max = intval($_POST["quota_max"]);
        }
        if (isset($_POST["stock"])) {
            $stock = intval($_POST["stock"]);
        }
        if (isset($_POST["prix"])) {
            $prix = floatval($_POST["prix"]);
        }
        if (isset($_POST["calories"])) {
            $calories = intval($_POST["calories"]);
        }
        if (isset($_POST["statut"])) {
            $statut = $_POST["statut"];
        }
    
        // Gestion de l'upload d'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploaded_image = $this->uploadImage($_FILES['image']);
            if ($uploaded_image != false) { // Vérifier si l'upload a réussi
                $image = $uploaded_image;
            }
        }
    
        // Vérification des champs obligatoires
        if ($nom_plat == "" || $type_plat == "" || $type_menu == "" || $categorie == "" || $prix <= 0 || $statut == "") {
            $_SESSION['error'] = "Tous les champs obligatoires (Nom, Type de Plat, Type de Menu, Catégorie, Prix, Statut) doivent être remplis et le prix doit être supérieur à 0.";
            header("Location: index.php?page=dashboard_admin");
            exit();
        }
    
        // Assignation des propriétés du modèle RepasModel
        $repas_model->nom_plat = $nom_plat;
        $repas_model->description = $description;
        $repas_model->type_plat = $type_plat;
        $repas_model->type_menu = $type_menu;
        $repas_model->categorie = $categorie;
        $repas_model->quota_max = $quota_max;
        $repas_model->quota_reserver = 0; // Initialiser à 0
        $repas_model->prix = $prix;
        $repas_model->calories = $calories;
        $repas_model->statut = $statut;
        $repas_model->stock = $stock; // Initialiser à 0 ou à une valeur par défaut si non fourni
        $repas_model->image = $image;
    
        // Tenter de créer le plat
        if ($repas_model->Create()) {
            $bdd = Connexion::getConnexion();
            $last_id = $bdd->lastInsertId(); // Récupérer l'ID du plat nouvellement créé
            
            // Tenter de créer l'association dans menu_plat
            $menu_model = new MenuModel();
            $all_menus = $menu_model->getAll();
            $id_menu_found = null;
            
            // Parcourir tous les menus pour trouver l'ID correspondant au type_menu
            foreach ($all_menus as $menu_item) {
                if ($menu_item['type_service'] == $type_menu) {
                    $id_menu_found = $menu_item['id_menu'];
                    break; // Sortir de la boucle une fois trouvé
                }
            }
            
            if ($id_menu_found != null) {
                $menu_plat_model = new MenuPlat();
                $menu_plat_model->id_menu = $id_menu_found;
                $menu_plat_model->id_plat = $last_id;
                $menu_plat_model->quota_max = $quota_max;
                
                if ($menu_plat_model->create()) {
                    $_SESSION['message'] = "Plat ajouté et associé au menu avec succès.";
                } else {
                    $_SESSION['error'] = "Plat ajouté, mais erreur lors de l'association au menu.";
                }
            } else {
                $_SESSION['message'] = "Plat ajouté, mais aucun menu correspondant au type '" . htmlspecialchars($type_menu) . "' n'a été trouvé pour l'association.";
            }
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout du plat dans la base de données.";
        }
    
        header("Location: index.php?page=dashboard_admin");
        exit();
    }


    public function modifierPlat($repas_model) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $id_plat = 0;
        if (isset($_POST["id_plat"])) {
            $id_plat = intval($_POST["id_plat"]);
        }
        
        $nom_plat = '';
        if (isset($_POST["nom_plat"])) {
            $nom_plat = $_POST["nom_plat"];
        }
        
        $description = '';
        if (isset($_POST["description"])) {
            $description = $_POST["description"];
        }
        
        $type_plat = '';
        if (isset($_POST["type_plat"])) {
            $type_plat = $_POST["type_plat"];
        }
        
        $type_menu = '';
        if (isset($_POST["type_menu"])) {
            $type_menu = $_POST["type_menu"];
        }
        
        $categorie = '';
        if (isset($_POST["categorie"])) {
            $categorie = $_POST["categorie"];
        }
        
        $quota_max = 0;
        if (isset($_POST["quota_max"])) {
            $quota_max = intval($_POST["quota_max"]);
        }
        
        $stock = 0;
        if (isset($_POST["stock"])) {
            $stock = intval($_POST["stock"]);
        }
        
        $prix = 0;
        if (isset($_POST["prix"])) {
            $prix = floatval($_POST["prix"]);
        }
        
        $calories = 0;
        if (isset($_POST["calories"])) {
            $calories = intval($_POST["calories"]);
        }
        
        $statut = '';
        if (isset($_POST["statut"])) {
            $statut = $_POST["statut"];
        }
        
        // Récupération du plat existant pour l'image
        $plat_existant = $repas_model->getById($id_plat);
        $image = $plat_existant['image'];
        
        // Gestion de l'image si une nouvelle est uploadée
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $new_image = $this->uploadImage($_FILES['image']);
            if ($new_image != false) {
                $image = $new_image;
            }
        }
        
        if ($id_plat > 0 && $nom_plat != "" && $type_plat != "" && $type_menu != "" && $categorie != "" && $prix > 0 && $statut != "") {
            $repas_model->nom_plat = $nom_plat;
            $repas_model->description = $description;
            $repas_model->type_plat = $type_plat;
            $repas_model->type_menu = $type_menu;
            $repas_model->categorie = $categorie;
            $repas_model->quota_max = $quota_max;
            $repas_model->quota_reserver = $plat_existant['quota_reserver'];
            $repas_model->prix = $prix;
            $repas_model->calories = $calories;
            $repas_model->statut = $statut;
            $repas_model->stock = $stock;
            $repas_model->image = $image;
            
            if ($repas_model->update($id_plat)) {
                $_SESSION['message'] = "Plat modifié avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification du plat.";
            }
        } else {
            $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
        }
        
        header("Location: index.php?page=dashboard_admin");
        exit();
    }
    
    public function supprimerPlat($repas_model) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = 0;
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
        }
        
        if ($id > 0) {
            if ($repas_model->delete($id)) {
                $_SESSION['message'] = "Plat supprimé avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression du plat.";
            }
        } else {
            $_SESSION['error'] = "ID du plat invalide.";
        }
        
        header("Location: index.php?page=dashboard_admin");
        exit();
    }
    
    public function uploadImage($file) {
        $target_dir = "public/assets/images/plats/";
        
        // Créer le dossier s'il n'existe pas
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Vérifier si c'est une vraie image
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            return false;
        }
        
        // Vérifier la taille du fichier (5MB max)
        if ($file["size"] > 5000000) {
            return false;
        }
        
        // Autoriser certains formats d'image
        if ($file_extension != "jpg" && $file_extension != "png" && $file_extension != "jpeg" && $file_extension != "gif") {
            return false;
        }
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $new_filename;
        } else {
            return false;
        }
    }
    

    public function studentMenu() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $type_menu = '';
        if (isset($_GET['type_menu'])) {
            $type_menu = $_GET['type_menu'];
        }
        
        // Conversion pour correspondre à la base de données
        $type_service = $type_menu;
        switch (strtolower($type_menu)) {
            case 'dejeuner':
                $type_menu_db = 'DEJEUNER';
                break;
            case 'diner':
                $type_menu_db = 'DINER';
                break;
            case 'petit-dej':
            case 'petit-dejeuner':
                $type_menu_db = 'PETIT-DEJ';
                break;
            case 'dessert':
                $type_menu_db = 'DESSERT';
                break;
            default:
                $type_menu_db = 'DEJEUNER';
                $type_service = 'dejeuner';
        }
        
        $repas_model = new RepasModel();
        
        // Utiliser la méthode getPlatsByTypeMenu pour filtrer directement
        $plats = $repas_model->getPlatsByTypeMenu($type_menu_db);
        
        // Debug pour vérifier
        error_log("Type menu demandé: " . $type_menu);
        error_log("Type menu DB: " . $type_menu_db);
        error_log("Nombre de plats trouvés: " . count($plats));
        
        $data_page = array(
            "title" => "Menu - " . ucfirst($type_service),
            "view" => "views/studentmenu.php",
            "layout" => "views/components/Layout.php",
            "plats" => $plats,
            "type_service" => $type_service,
            "type_menu_db" => $type_menu_db
        );
        
        $util = new Utilities();
        $util->drawPage($data_page);
    }

    public function manageReservations() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
            header('Location: index.php?page=login');
            exit();
        }
    
        $reservation_model = new ReservationModel();
        $reservations = $reservation_model->getAllReservationsWithUserDetails();
    
        // Instanciation du modèle ReservationPlat
        $reservation_plat_model = new ReservationPlat();
    
        $message = '';
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            unset($_SESSION['message']);
        }
        $error = '';
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }
    
        $data_page = array(
            "title" => "Gérer les Réservations",
            "view" => "views/admin/AdminReservations.php",
            "layout" => "views/components/Layout.php",
            "message" => $message,
            "error" => $error,
            "reservations" => $reservations,
            "reservation_plat_model" => $reservation_plat_model // Passer le modèle à la vue
        );
    
        $util = new Utilities();
        $util->drawPage($data_page);
    }
    

    public function updateReservationStatus() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_reservation = '';
            if (isset($_POST["id_reservation"])) {
                $id_reservation = $_POST["id_reservation"];
            }
            $status = '';
            if (isset($_POST["statut_reservation"])) {
                $status = $_POST["statut_reservation"];
            }

            if ($id_reservation != "" && $status != "") {
                $reservation_model = new ReservationModel();
                $updated = $reservation_model->updateStatus($id_reservation, $status);
                if ($updated) {
                    // Récupérer l'ID utilisateur directement de la réservation
                    $reservation_details = $reservation_model->getById($id_reservation);
                    if ($reservation_details != null) {
                        $id_utilisateur_notif = $reservation_details['id_utilisateur'];
                        $util = new Utilities();
                        $util->sendNotification($id_utilisateur_notif, "Votre réservation #" . htmlspecialchars($id_reservation) . " a été mise à jour à '" . htmlspecialchars($status) . "'.");
                    }
                    $_SESSION["message"] = "Statut de la réservation mis à jour avec succès.";
                } else {
                    $_SESSION["error"] = "Erreur lors de la mise à jour du statut de la réservation.";
                }
            } else {
                $_SESSION["error"] = "Données manquantes pour la mise à jour du statut.";
            }
            header("Location: index.php?page=admin_manage_reservations");
            exit();
        }
    }

    public function statistics() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
            header('Location: index.php?page=login');
            exit();
        }

        $stat_model = new StatistiqueModel();
        $most_reserved_plats = $stat_model->getMostReservedPlats();
        $loyal_customers = $stat_model->getLoyalCustomers();
        $daily_revenue = $stat_model->getRevenue('daily');
        $weekly_revenue = $stat_model->getRevenue('weekly');
        $monthly_revenue = $stat_model->getRevenue('monthly');
        $yearly_revenue = $stat_model->getRevenue('yearly');

        $data_page = array(
            "title" => "Statistiques",
            "view" => "views/admin/AdminStatistics.php",
            "layout" => "views/components/Layout.php",
            "message" => "",
            "error" => "",
            "most_reserved_plats" => $most_reserved_plats,
            "loyal_customers" => $loyal_customers,
            "daily_revenue" => $daily_revenue,
            "weekly_revenue" => $weekly_revenue,
            "monthly_revenue" => $monthly_revenue,
            "yearly_revenue" => $yearly_revenue
        );

        $util = new Utilities();
        $util->drawPage($data_page);
    }

    public function checkStock() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
            header('Location: index.php?page=login');
            exit();
        }

        $repas_model = new RepasModel();
        $plats = $repas_model->getAll();

        $message = '';
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            unset($_SESSION['message']);
        }
        $error = '';
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        $data_page = array(
            "title" => "Gestion des Stocks",
            "view" => "views/admin/AdminStockStatus.php",
            "layout" => "views/components/Layout.php",
            "message" => $message,
            "error" => $error,
            "plats" => $plats
        );
        $util = new Utilities();
        $util->drawPage($data_page);
    }

    public function updateStock() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        if (!isset($_SESSION['user_type'])) {
            header("Location: index.php?page=login");
            exit();
        }
        if ($_SESSION['user_type'] != 'admin') {
            header("Location: index.php?page=login");
            exit();
        }
    
        $id_plat = 0;
        if (isset($_POST["id_plat"])) {
            $id_plat = intval($_POST["id_plat"]);
        }
    
        $new_stock = 0;
        if (isset($_POST["new_stock"])) {
            $new_stock = intval($_POST["new_stock"]);
        }
    
        if ($id_plat > 0) {
            $repas_model = new RepasModel(); // Instancier le modèle ici
            $plat = $repas_model->getById($id_plat);
            if ($plat) {
                // Mettre à jour les propriétés du modèle avant d'appeler update
                $repas_model->nom_plat = $plat['nom_plat'];
                $repas_model->description = $plat['description'];
                $repas_model->type_plat = $plat['type_plat'];
                $repas_model->type_menu = $plat['type_menu'];
                $repas_model->categorie = $plat['categorie'];
                $repas_model->quota_max = $plat['quota_max'];
                $repas_model->quota_reserver = $plat['quota_reserver'];
                $repas_model->prix = $plat['prix'];
                $repas_model->calories = $plat['calories'];
                $repas_model->statut = $plat['statut'];
                $repas_model->stock = $new_stock; // Nouvelle valeur de stock
                $repas_model->image = $plat['image'];

                $success = $repas_model->update($id_plat);
                if ($success) {
                    $_SESSION['message'] = "Stock mis à jour avec succès";
                } else {
                    $_SESSION['error'] = "Erreur lors de la mise à jour";
                }
            } else {
                $_SESSION['error'] = "Plat non trouvé";
            }
        } else {
            $_SESSION['error'] = "ID de plat invalide";
        }
    
        header("Location: index.php?page=dashboard_admin");
        exit();
    }
    
}
