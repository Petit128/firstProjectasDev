<?php
require_once("Connexion.php");

class RepasModel {
    public $id_plat;
    public $nom_plat;
    public $description;
    public $type_plat;
    public $type_menu;
    public $categorie;
    public $quota_max;
    public $quota_reserver;
    public $prix;
    public $calories;
    public $statut;
    public $stock;
    public $image;

    public function Create() {
        try {
            $bdd = Connexion::getConnexion();
            $req = "INSERT INTO plat (nom_plat, description, type_plat, type_menu, categorie, quota_max, quota_reserver, prix, calories, statut, stock, image)
                    VALUES (:nom_plat, :description, :type_plat, :type_menu, :categorie, :quota_max, :quota_reserver, :prix, :calories, :statut, :stock, :image)";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":nom_plat", $this->nom_plat);
            $etat->bindParam(":description", $this->description);
            $etat->bindParam(":type_plat", $this->type_plat);
            $etat->bindParam(":type_menu", $this->type_menu); // Correction ici
            $etat->bindParam(":categorie", $this->categorie);
            $etat->bindParam(":quota_max", $this->quota_max);
            $etat->bindParam(":quota_reserver", $this->quota_reserver);
            $etat->bindParam(":prix", $this->prix);
            $etat->bindParam(":calories", $this->calories);
            $etat->bindParam(":statut", $this->statut);
            $etat->bindParam(":stock", $this->stock);
            $etat->bindParam(":image", $this->image);
            $etat->execute();
            $id = $bdd->lastInsertId();
            $etat->closeCursor();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($id) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "UPDATE plat SET nom_plat = :nom_plat, description = :description, type_plat = :type_plat, type_menu = :type_menu,
                    categorie = :categorie, quota_max = :quota_max, quota_reserver = :quota_reserver, prix = :prix, calories = :calories, statut = :statut, stock = :stock, image = :image
                    WHERE id_plat = :id";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":nom_plat", $this->nom_plat);
            $etat->bindParam(":description", $this->description);
            $etat->bindParam(":type_plat", $this->type_plat);
            $etat->bindParam(":type_menu", $this->type_menu);
            $etat->bindParam(":categorie", $this->categorie);
            $etat->bindParam(":quota_max", $this->quota_max);
            $etat->bindParam(":quota_reserver", $this->quota_reserver);
            $etat->bindParam(":prix", $this->prix);
            $etat->bindParam(":calories", $this->calories);
            $etat->bindParam(":statut", $this->statut);
            $etat->bindParam(":stock", $this->stock);
            $etat->bindParam(":image", $this->image);
            $etat->bindParam(":id", $id);
            $etat->execute();
            $etat->closeCursor();
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du plat: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "DELETE FROM plat WHERE id_plat = :id";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id", $id);
            $etat->execute();
            $etat->closeCursor();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getAll() {
        try {
            $bdd = Connexion::getConnexion();
            $req = "SELECT * FROM plat";
            $etat = $bdd->prepare($req);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();
            return $data;
        } catch (Exception $e) {
            return array();
        }
    }

    // Nouvelle méthode pour récupérer tous les plats avec leur id_menu_plat
    // Modifiée pour s'assurer que id_menu_plat est toujours présent si une association existe
    public function getAllPlatsWithMenuPlatId() {
        try {
            $bdd = Connexion::getConnexion();
            // Joindre avec menu_plat pour obtenir id_menu_plat
            // On prend le premier id_menu_plat trouvé si un plat est dans plusieurs menus
            // Utilisation de LEFT JOIN pour inclure les plats sans association de menu_plat
            $req = "SELECT p.*, mp.id_menu_plat 
                    FROM plat p 
                    LEFT JOIN menu_plat mp ON p.id_plat = mp.id_plat 
                    WHERE p.statut = 'actif'
                    GROUP BY p.id_plat
                    ORDER BY p.nom_plat";
            $etat = $bdd->prepare($req);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();
            return $data;
        } catch (Exception $e) {
            error_log("Erreur getAllPlatsWithMenuPlatId: " . $e->getMessage());
            return array();
        }
    }

    public function getAllPlats() {
        try {
            $bdd = Connexion::getConnexion();
            $req = "SELECT * FROM plat ORDER BY nom_plat";
            $etat = $bdd->prepare($req);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();
            return $data;
        } catch (PDOException $e) {
            error_log("Erreur getAllPlats: " . $e->getMessage());
            return array();
        }      
    }

    public function getPlatsByTypeMenu($type_menu) {
        try {
            $bdd = Connexion::getConnexion();
            $type_menu = trim($type_menu); // Nettoyer le paramètre

            // Requête pour récupérer les plats actifs du type de menu spécifié
            // et joindre avec menu_plat pour les quotas
            $req = "SELECT p.*, mp.id_menu_plat, mp.quota_max AS quota_max_menu, mp.quota_reserve
                    FROM plat p
                    LEFT JOIN menu_plat mp ON p.id_plat = mp.id_plat
                    WHERE p.type_menu = :type_menu AND p.statut = 'actif'
                    ORDER BY p.type_plat, p.nom_plat";
            
            $etat = $bdd->prepare($req);
            $etat->bindParam(":type_menu", $type_menu);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();
            
            return $data;
        } catch (Exception $e) {
            error_log("Erreur getPlatsByTypeMenu: " . $e->getMessage());
            return array();
        }
    }
    public function getById($id_plat) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "SELECT * FROM plat WHERE id_plat = :id_plat";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id_plat", $id_plat);
            $etat->execute();
            $data = $etat->fetch();
            $etat->closeCursor();
            return $data;
        } catch (Exception $e) {
            return null; // En cas d'erreur, retourner null
        }
    }
    
}
