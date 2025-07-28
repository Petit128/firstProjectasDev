<?php
require_once("Connexion.php"); // Adjusted path

class Allergene {
    public $id_allergene;
    public $nom_allergene;
    public $icone;
    public $description;

    public function create() {
        try {
            $bdd = Connexion::getConnexion();
            $req = "INSERT INTO allergene (nom_allergene, icone, description) 
                    VALUES (:nom_allergene, :icone, :description)";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":nom_allergene", $this->nom_allergene);
            $etat->bindParam(":icone", $this->icone);
            $etat->bindParam(":description", $this->description);
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
            $req = "UPDATE allergene SET nom_allergene = :nom_allergene, icone = :icone, description = :description 
                    WHERE id_allergene = :id";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id", $id);
            $etat->bindParam(":nom_allergene", $this->nom_allergene);
            $etat->bindParam(":icone", $this->icone);
            $etat->bindParam(":description", $this->description);
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
            $req = "DELETE FROM allergene WHERE id_allergene = :id";
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
            $req = "SELECT * FROM allergene";
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
            $req = "SELECT * FROM allergene WHERE id_allergene = :id";
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
}
?>
