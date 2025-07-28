<?php
require_once("Utilities.php");
require_once("models/MenuModel.php");
require_once("models/ReservationModel.php");
require_once("models/RepasModel.php");
require_once("models/ReservationPlat.php");
require_once("models/Utilisateurs.php");
require_once("models/Allergene.php");
require_once("models/StatistiqueModel.php");
require_once("models/MenuPlat.php");

class StudentController {
    public function DashboardEtudiant() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'etudiant') {
            header('Location: index.php?page=login');
            exit();
        }

        $stat_model = new StatistiqueModel();
        $recommended_plats = $stat_model->getMostReservedPlats(3);
        $repas_model = new RepasModel();

        $plats_disponibles = $repas_model->getAllPlatsWithMenuPlatId();

        $data_page = array(
            "title" => "Accueil Étudiant",
            "view" => "views/student/Accueil.php",
            "layout" => "views/components/Layout.php",
            "message" => "",
            "error" => "",
            "recommended_plats" => $recommended_plats,
            "repas_model" => $repas_model,
            "plats" => $plats_disponibles
        );

        $util = new Utilities();
        $util->drawPage($data_page);
    }

    public function menu($type_service_url) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }

        $type_menu_db = '';
        $type_service_display = '';

        if ($type_service_url == 'dejeuner') {
            $type_menu_db = 'DEJEUNER';
            $type_service_display = 'Déjeuner';
        } elseif ($type_service_url == 'diner') {
            $type_menu_db = 'DINER';
            $type_service_display = 'Dîner';
        } elseif ($type_service_url == 'petit-dej') {
            $type_menu_db = 'PETIT-DEJ';
            $type_service_display = 'Petit-déjeuner';
        } elseif ($type_service_url == 'dessert') {
            $type_menu_db = 'DESSERT';
            $type_service_display = 'Dessert';
        } else {
            $type_menu_db = 'DEJEUNER';
            $type_service_display = 'Déjeuner';
            $type_service_url = 'dejeuner';
        }

        $repas_model = new RepasModel();
        $plats = $repas_model->getPlatsByTypeMenu($type_menu_db);

        $data_page = array(
            "title" => "Menu - " . $type_service_display,
            "view" => "views/student/StudentMenu.php",
            "layout" => "views/components/Layout.php",
            "message" => "",
            "error" => "",
            "plats" => $plats,
            "type_service" => $type_service_display,
            "type_menu_db" => $type_menu_db
        );

        $util = new Utilities();
        $util->drawPage($data_page);
    }

    public function addToCart() {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_menu_plat = '';
            if (isset($_POST["id_menu_plat"])) {
                $id_menu_plat = $_POST["id_menu_plat"];
            }
            $quantite = '';
            if (isset($_POST["quantite"])) {
                $quantite = $_POST["quantite"];
            }

            if ($id_menu_plat != "" && $quantite != "") {
                $util = new Utilities();
                $added = $util->ajouterPanier($id_menu_plat, $quantite);

                if ($added) {
                    $_SESSION["message"] = "Plat ajouté au panier!";
                    header("Location: index.php?page=panier");
                    exit();
                } else {
                    header("Location: index.php?page=panier");
                    exit();
                }
            } else {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION["error"] = "Quantité ou plat manquant.";
                header("Location: index.php?page=panier");
                exit();
            }
        }
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION["error"] = "Requête invalide.";
        header("Location: index.php?page=panier");
        exit();
    }

    public function removeFromCart() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_menu_plat = '';
            if (isset($_POST["id_menu_plat"])) {
                $id_menu_plat = $_POST["id_menu_plat"];
            }

            if ($id_menu_plat != "") {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION["panier"])) {
                    $panier_temp = array();
                    $removed = false;
                    $i = 0;
                    while ($i < count($_SESSION["panier"])) {
                        $item = $_SESSION["panier"][$i];
                        if ($item["id_menu_plat"] != $id_menu_plat) {
                            array_push($panier_temp, $item);
                        } else {
                            $removed = true;
                        }
                        $i = $i + 1;
                    }
                    $_SESSION["panier"] = $panier_temp;
                    if ($removed) {
                        $_SESSION["message"] = "Plat retiré du panier.";
                    } else {
                        $_SESSION["error"] = "Plat non trouvé dans le panier.";
                    }
                } else {
                    $_SESSION["error"] = "Le panier est vide.";
                }
            } else {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION["error"] = "ID de plat manquant pour la suppression.";
            }
        }
        header("Location: index.php?page=panier");
        exit();
    }

    public function panier() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }

        $util = new Utilities();
        $panier = $util->listerPanier();

        $total = 0;
        $i = 0;
        while ($i < count($panier)) {
            $item = $panier[$i];
            $total = $total + ($item["prix"] * $item["quantite"]);
            $i = $i + 1;
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

        $data_page = array(
            "title" => "Panier",
            "view" => "views/student/Panier.php",
            "layout" => "views/components/Layout.php",
            "message" => $message,
            "error" => $error,
            "panier" => $panier,
            "total" => $total
        );

        $util->drawPage($data_page);
    }

    public function createReservation() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $panier = array();
            if (isset($_SESSION["panier"])) {
                $panier = $_SESSION["panier"];
            }

            if (count($panier) > 0) {
                $reservation_model = new ReservationModel();
                $reservation_model->date_reservation = date("Y-m-d H:i:s", strtotime("+1 hour"));
                $reservation_model->statut_reservation = "en_attente";
                $reservation_model->commentaire = "Réservation effectuée par étudiant.";
                $reservation_model->id_utilisateur = $_SESSION["user_id"]; // Ajout de l'ID utilisateur
                $id_reservation = $reservation_model->create();

                if ($id_reservation) {
                    $reservation_plat_model = new ReservationPlat();
                    $menu_plat_model = new MenuPlat();
                    $repas_model = new RepasModel();
                    
                    $i = 0;
                    while ($i < count($panier)) {
                        $item = $panier[$i];
                        $reservation_plat_model->id_reservation = $id_reservation;
                        $reservation_plat_model->id_utilisateur = $_SESSION["user_id"];
                        $reservation_plat_model->id_menu_plat = $item["id_menu_plat"];
                        $reservation_plat_model->quantite = $item["quantite"];
                        $reservation_plat_model->create();

                        // Incrémenter le quota réservé pour le menu_plat
                        $menu_plat_model->incrementQuotaReserve($item["id_menu_plat"], $item["quantite"]);

                        // Mettre à jour le stock du plat
                        $menu_plat_details = $menu_plat_model->getByIdMenuPlat($item["id_menu_plat"]);
                        if ($menu_plat_details != null) {
                            $plat_details = $repas_model->getById($menu_plat_details["id_plat"]);
                            if ($plat_details != null) {
                                $new_stock = $plat_details["stock"] - $item["quantite"];
                                // Assurez-vous que toutes les propriétés sont définies pour la mise à jour
                                $repas_model->id_plat = $plat_details["id_plat"];
                                $repas_model->nom_plat = $plat_details["nom_plat"];
                                $repas_model->description = $plat_details["description"];
                                $repas_model->type_plat = $plat_details["type_plat"];
                                $repas_model->type_menu = $plat_details["type_menu"];
                                $repas_model->categorie = $plat_details["categorie"];
                                $repas_model->quota_max = $plat_details["quota_max"];
                                $repas_model->quota_reserver = $plat_details["quota_reserver"];
                                $repas_model->prix = $plat_details["prix"];
                                $repas_model->calories = $plat_details["calories"];
                                $repas_model->statut = $plat_details["statut"];
                                $repas_model->stock = $new_stock; // Mise à jour du stock
                                $repas_model->image = $plat_details["image"];
                                $repas_model->update($plat_details["id_plat"]);
                            }
                        }
                        $i = $i + 1;
                    }
                    $_SESSION["panier"] = array();
                    $util = new Utilities();
                    $util->sendNotification($_SESSION["user_id"], "Votre réservation #" . $id_reservation . " a été créée et est en attente de validation.");
                    $_SESSION["message"] = "Réservation créée avec succès et en attente de validation.";
                    header("Location: index.php?page=student_reservations");
                    exit();
                } else {
                    $_SESSION["error"] = "Erreur lors de la création de la réservation.";
                    header("Location: index.php?page=panier");
                    exit();
                }
            } else {
                $_SESSION["error"] = "Panier vide. Veuillez ajouter des plats avant de réserver.";
                header("Location: index.php?page=panier");
                exit();
            }
        }
        $_SESSION["error"] = "Requête invalide.";
        header("Location: index.php?page=panier");
        exit();
    }

    public function listReservations() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }

        $reservation_model = new ReservationModel();
        $reservations = $reservation_model->getByUser($_SESSION['user_id']);
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
            "title" => "Mes Réservations",
            "view" => "views/student/StudentReservations.php",
            "layout" => "views/components/Layout.php",
            "message" => $message,
            "error" => $error,
            "reservations" => $reservations,
            "reservation_plat_model" => $reservation_plat_model
        );

        $util = new Utilities();
        $util->drawPage($data_page);
    }

    public function notifications() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }

        $user_notifications = array();
        if (isset($_SESSION['notifications'])) {
            $i = 0;
            while ($i < count($_SESSION['notifications'])) {
                $notification = $_SESSION['notifications'][$i];
                if ($notification['user_id'] == $_SESSION['user_id']) {
                    $user_notifications[] = $notification;
                }
                $i = $i + 1;
            }
        }

        $message = '';
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
        }
        $error = '';
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
        }

        $data_page = array(
            "title" => "Notifications",
            "view" => "views/student/StudentNotifications.php",
            "layout" => "views/components/Layout.php",
            "message" => $message,
            "error" => $error,
            "user_notifications" => $user_notifications
        );

        $util = new Utilities();
        $util->drawPage($data_page);
    }

    public function profile() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }

        $utilisateur_model = new Utilisateurs();
        $user = $utilisateur_model->getById($_SESSION['user_id']);

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
            "title" => "Mon Profil",
            "view" => "views/student/StudentProfile.php",
            "layout" => "views/components/Layout.php",
            "message" => $message,
            "error" => $error,
            "user" => $user
        );

        $util = new Utilities();
        $util->drawPage($data_page);
    }

    public function updateProfile() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }

        $id_utilisateur = $_SESSION['user_id'];
        $utilisateur_model = new Utilisateurs();
        $current_user = $utilisateur_model->getById($id_utilisateur);
        $error = '';
        $message = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $matricule = '';
            if (isset($_POST["matricule"])) {
                $matricule = $_POST["matricule"];
            }
            $nom = '';
            if (isset($_POST["nom"])) {
                $nom = $_POST["nom"];
            }
            $prenom = '';
            if (isset($_POST["prenom"])) {
                $prenom = $_POST["prenom"];
            }
            $email = '';
            if (isset($_POST["email"])) {
                $email = $_POST["email"];
            }
            $telephone = '';
            if (isset($_POST["telephone"])) {
                $telephone = $_POST["telephone"];
            }
            $formation = '';
            if (isset($_POST["formation"])) {
                $formation = $_POST["formation"];
            }
            $password = '';
            if (isset($_POST["password"])) {
                $password = $_POST["password"];
            }
            $confirm_password = '';
            if (isset($_POST["confirm_password"])) {
                $confirm_password = $_POST["confirm_password"];
            }

            if ($matricule != "" && $nom != "" && $prenom != "" && $email != "" && $formation != "") {
                $has_error = false;

                if ($email != $current_user["email"]) {
                    if ($utilisateur_model->findByEmail($email)) {
                        $error = "Cet email est déjà utilisé par un autre compte.";
                        $has_error = true;
                    }
                }
                if ($matricule != $current_user["matricule"]) {
                    if ($utilisateur_model->findByMatricule($matricule)) {
                        $error = "Ce matricule est déjà utilisé par un autre compte.";
                        $has_error = true;
                    }
                }

                if ($has_error == false) {
                    if ($password != "" && $password != $confirm_password) {
                        $error = "Les nouveaux mots de passe ne correspondent pas.";
                        $has_error = true;
                    } else {
                        $utilisateur_model->id_utilisateur = $id_utilisateur;
                        $utilisateur_model->matricule = $matricule;
                        $utilisateur_model->nom = $nom;
                        $utilisateur_model->prenom = $prenom;
                        $utilisateur_model->email = $email;
                        $utilisateur_model->telephone = $telephone;
                        if ($password != "") {
                            $utilisateur_model->mot_de_passe = $password;
                        } else {
                            $utilisateur_model->mot_de_passe = $current_user["mot_de_passe"];
                        }
                        $utilisateur_model->formation = $formation;
                        $utilisateur_model->type_utilisateur = $current_user["type_utilisateur"];
                        $utilisateur_model->statut = $current_user["statut"];

                        $uploaded_image_name = $current_user["image"];
                        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                            $target_dir = "public/assets/images/profiles/";
                            $uploaded_image_name = basename($_FILES["image"]["name"]);
                            $target_file = $target_dir . $uploaded_image_name;
                            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                $error = "Erreur lors du téléchargement de l'image de profil.";
                                $uploaded_image_name = $current_user["image"];
                            }
                        }
                        $utilisateur_model->image = $uploaded_image_name;

                        $updated = $utilisateur_model->update($id_utilisateur);
                        if ($updated) {
                            $_SESSION["user_nom"] = $nom;
                            $_SESSION["user_prenom"] = $prenom;
                            $_SESSION["message"] = "Profil mis à jour avec succès.";
                            header("Location: index.php?page=student_profile");
                            exit();
                        } else {
                            $error = "Erreur lors de la mise à jour du profil.";
                            $has_error = true;
                        }
                    }
                }
            } else {
                $error = "Tous les champs obligatoires doivent être remplis.";
                $has_error = true;
            }
        }
        $_SESSION["error"] = $error;
        header("Location: index.php?page=student_profile");
        exit();
    }

    public function options() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }

        $message = '';
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
        }
        $error = '';
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
        }

        $data_page = array(
            "title" => "Options",
            "view" => "views/student/StudentOptions.php",
            "layout" => "views/components/Layout.php",
            "message" => $message,
            "error" => $error
        );

        $util = new Utilities();
        $util->drawPage($data_page);
    }
    public function cancelReservation() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
    
            $id_reservation = 0;
            if (isset($_POST["id_reservation"])) {
                $id_reservation = $_POST["id_reservation"];
            }
    
            $reservation_model = new ReservationModel();
            $reservation = $reservation_model->getById($id_reservation);
    
            if ($reservation != null && $reservation["id_utilisateur"] == $_SESSION["user_id"]) {
                // Récupérer les plats pour remettre les stocks
                $reservation_plat_model = new ReservationPlat();
                $plats = $reservation_plat_model->getPlatsByReservation($id_reservation);
                $repas_model = new RepasModel();
                $menu_plat_model = new MenuPlat();
                
                $i = 0;
                while ($i < count($plats)) {
                    $plat = $plats[$i];
                    
                    // Remettre le stock
                    $plat_details = $repas_model->getById($plat["id_plat"]);
                    if ($plat_details != null) {
                        $new_stock = $plat_details["stock"] + $plat["quantite"];
                        $repas_model->stock = $new_stock;
                        $repas_model->update($plat["id_plat"]);
                    }
                    
                    // Décrémenter le quota réservé
                    $menu_plat_model->decrementQuotaReserve($plat["id_menu_plat"], $plat["quantite"]);
                    
                    $i = $i + 1;
                }
    
                // Mettre à jour le statut
                $reservation_model->updateStatus($id_reservation, "annulee");
                
                $_SESSION["message"] = "Réservation annulée avec succès.";
            } else {
                $_SESSION["error"] = "Impossible d'annuler cette réservation.";
            }
    
            header("Location: index.php?page=student_reservations");
            exit();
        }
    }
    
}
