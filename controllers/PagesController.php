<?php
// MultipleFiles/PagesController.php

require_once("Utilities.php");
require_once("models/Utilisateurs.php");

class PagesController {
    public function LoginPage() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
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

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = '';
            if (isset($_POST["email"])) {
                $email = $_POST["email"];
            }
            $password = '';
            if (isset($_POST["password"])) {
                $password = $_POST["password"];
            }

            $user_model = new Utilisateurs();
            $user = $user_model->verifyLogin($email, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id_utilisateur'];
                $_SESSION['user_type'] = $user['type_utilisateur'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_prenom'] = $user['prenom'];
                $_SESSION['message'] = "Connexion réussie.";

                if ($user['type_utilisateur'] == 'admin') {
                    header("Location: index.php?page=dashboard_admin");
                    exit();
                } else {
                    header("Location: index.php?page=accueil");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Email ou mot de passe incorrect, ou compte inactif.";
                header("Location: index.php?page=login");
                exit();
            }
        }

        $data_page = array(
            "title" => "Connexion",
            "view" => "views/auth/Login.php",
            "layout" => "views/components/Layout.php",
            "message" => $message,
            "error" => $error
        );
        $util = new Utilities();
        $util->drawPage($data_page);
    }

    public function RegisterPage() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
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
            $password = '';
            if (isset($_POST["password"])) {
                $password = $_POST["password"];
            }
            $confirm_password = '';
            if (isset($_POST["confirm_password"])) {
                $confirm_password = $_POST["confirm_password"];
            }
            $formation = '';
            if (isset($_POST["formation"])) {
                $formation = $_POST["formation"];
            }

            if ($matricule != "" && $nom != "" && $prenom != "" && $email != "" && $password != "" && $confirm_password != "" && $formation != "") {
                if ($password != $confirm_password) {
                    $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
                } else {
                    $user_model = new Utilisateurs();
                    if ($user_model->findByEmail($email)) {
                        $_SESSION['error'] = "Cet email est déjà utilisé.";
                    } elseif ($user_model->findByMatricule($matricule)) {
                        $_SESSION['error'] = "Ce matricule est déjà utilisé.";
                    } else {
                        $user_model->matricule = $matricule;
                        $user_model->nom = $nom;
                        $user_model->prenom = $prenom;
                        $user_model->email = $email;
                        $user_model->telephone = $telephone;
                        $user_model->mot_de_passe = $password;
                        $user_model->formation = $formation;
                        $user_model->type_utilisateur = 'etudiant';
                        $user_model->statut = 'actif';
                        $user_model->image = '';

                        if ($user_model->create()) {
                            $_SESSION['message'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                            header("Location: index.php?page=login");
                            exit();
                        } else {
                            $_SESSION['error'] = "Erreur lors de l'inscription.";
                        }
                    }
                }
            } else {
                $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
            }
            header("Location: index.php?page=register");
            exit();
        }

        $data_page = array(
            "title" => "Inscription",
            "view" => "views/auth/Register.php",
            "layout" => "views/components/Layout.php",
            "message" => $message,
            "error" => $error
        );
        $util = new Utilities();
        $util->drawPage($data_page);
    }

    public function Logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header("Location: index.php?page=login");
        exit();
    }
}
?>