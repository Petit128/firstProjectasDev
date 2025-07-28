<?php
class Utilities {

    public function drawPage($data_page) {
        $title = '';
        if (isset($data_page["title"])) {
            $title = $data_page["title"];
        }
        $view = '';
        if (isset($data_page["view"])) {
            $view = $data_page["view"];
        }
        $layout = '';
        if (isset($data_page["layout"])) {
            $layout = $data_page["layout"];
        }
        $message = '';
        if (isset($data_page["message"])) {
            $message = $data_page["message"];
        }
        $error = '';
        if (isset($data_page["error"])) {
            $error = $data_page["error"];
        }

        foreach ($data_page as $key => $value) {
            if ($key != "title" && $key != "view" && $key != "layout" && $key != "message" && $key != "error") {
                ${$key} = $value;
            }
        }

        ob_start();
        if (file_exists($view)) {
            include($view);
        } else {
            echo "<p class='error'>Erreur: Vue '$view' introuvable.</p>";
        }
        $content = ob_get_clean();

        if (file_exists($layout)) {
            include($layout);
        } else {
            echo "<p class='error'>Erreur: Layout '$layout' introuvable.</p>";
        }
    }

    public function ajouterPanier($id_menu_plat, $quantite) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["panier"])) {
            $_SESSION["panier"] = array();
        }

        require_once("models/MenuPlat.php");
        require_once("models/RepasModel.php");

        $menu_plat_model = new MenuPlat();
        $menu_plat_details = $menu_plat_model->getByIdMenuPlat($id_menu_plat);

        if ($menu_plat_details == null) {
            $_SESSION["error"] = "Article de menu non trouvé.";
            return false;
        }

        $repas_model = new RepasModel();
        $plat_details = $repas_model->getById($menu_plat_details["id_plat"]);

        if ($plat_details == null) {
            $_SESSION["error"] = "Détails du plat non trouvés.";
            return false;
        }

        $item_found_in_cart = false;
        $liste_panier = $_SESSION["panier"];
        $key = 0;
        while ($key < count($liste_panier)) {
            $item = $liste_panier[$key];
            if ($item["id_menu_plat"] == $id_menu_plat) {
                $available_stock = $plat_details["stock"];
                $available_quota = $menu_plat_details["quota_max"] - $menu_plat_details["quota_reserve"];

                $new_quantity_in_cart = $item["quantite"] + $quantite;

                if ($new_quantity_in_cart > $available_stock) {
                    $_SESSION["error"] = "Quantité insuffisante en stock pour " . $plat_details["nom_plat"] . ". Stock disponible: " . $available_stock . ".";
                    return false;
                }
                if ($new_quantity_in_cart > $available_quota) {
                    $_SESSION["error"] = "Quota dépassé pour " . $plat_details["nom_plat"] . ". Quota disponible: " . $available_quota . ".";
                    return false;
                }
                $_SESSION["panier"][$key]["quantite"] = $new_quantity_in_cart;
                $item_found_in_cart = true;
                break;
            }
            $key = $key + 1;
        }

        if ($item_found_in_cart == false) {
            $available_stock = $plat_details["stock"];
            $available_quota = $menu_plat_details["quota_max"] - $menu_plat_details["quota_reserve"];

            if ($quantite > $available_stock) {
                $_SESSION["error"] = "Quantité insuffisante en stock pour " . $plat_details["nom_plat"] . ". Stock disponible: " . $available_stock . ".";
                return false;
            }
            if ($quantite > $available_quota) {
                $_SESSION["error"] = "Quota dépassé pour " . $plat_details["nom_plat"] . ". Quota disponible: " . $available_quota . ".";
                return false;
            }
            array_push($_SESSION["panier"], array(
                "id_menu_plat" => $id_menu_plat,
                "nom_plat" => $plat_details["nom_plat"],
                "prix" => $plat_details["prix"],
                "quantite" => $quantite
            ));
        }
        $_SESSION["message"] = "Plat ajouté au panier.";
        return true;
    }

    public function listerPanier() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION["panier"])) {
            return $_SESSION["panier"];
        } else {
            return array();
        }
    }

    public function sendNotification($user_id, $message) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['notifications'])) {
            $_SESSION['notifications'] = array();
        }
        $_SESSION['notifications'][] = array('user_id' => $user_id, 'message' => $message, 'timestamp' => date('Y-m-d H:i:s'));
    }
}
