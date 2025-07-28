<?php
require_once("Connexion.php"); // Adjusted path

class MenuModel {
    public $id_menu;
    public $type_service;
    public $statut;
    public $date_creation;
    public $date_publication;

    public function create() {
        try {
            $bdd = Connexion::getConnexion();
            $req = "INSERT INTO menu (type_service, statut, date_creation, date_publication) 
                    VALUES (:type_service, :statut, NOW(), :date_publication)";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":type_service", $this->type_service);
            $etat->bindParam(":statut", $this->statut);
            $etat->bindParam(":date_publication", $this->date_publication);
            $etat->execute();
            $etat->closeCursor();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($id) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "UPDATE menu SET type_service = :type_service, statut = :statut, date_publication = :date_publication 
                    WHERE id_menu = :id";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id", $id);
            $etat->bindParam(":type_service", $this->type_service);
            $etat->bindParam(":statut", $this->statut);
            $etat->bindParam(":date_publication", $this->date_publication);
            $etat->execute();
            $etat->closeCursor();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "DELETE FROM menu WHERE id_menu = :id";
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
            $req = "SELECT * FROM menu";
            $etat = $bdd->prepare($req);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();
            return $data;
        } catch (Exception $e) {
            return array();
        }
    }

    public function getById($id) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "SELECT * FROM menu WHERE id_menu = :id";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id", $id);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();
            if (count($data) > 0) {
                return $data[0];
            }
            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function getPlatsByTypeService($type_service) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "SELECT p.*, mp.quota_max, mp.quota_reserve, mp.id_menu_plat 
                    FROM plat p 
                    JOIN menu_plat mp ON p.id_plat = mp.id_plat 
                    JOIN menu m ON mp.id_menu = m.id_menu 
                    WHERE m.type_service = :type_service";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":type_service", $type_service);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();
            return $data;
        } catch (Exception $e) {
            return array();
        }
    }
}
?>
