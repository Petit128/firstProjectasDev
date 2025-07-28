
<?php
require_once("Connexion.php");

class Utilisateurs {
    public $id_utilisateur;
    public $matricule;
    public $nom;
    public $prenom;
    public $email;
    public $telephone;
    public $mot_de_passe;
    public $formation;
    public $type_utilisateur;
    public $statut;
    public $image;

    public function verifyLogin($email, $password) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "SELECT * FROM utilisateur WHERE email = :email AND statut = 'actif'";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":email", $email);
            $etat->execute();
            $data = $etat->fetchAll();

            $user = null;
            $i = 0;
            $password_hash = md5($password); // Keep md5 for consistency with provided DB

            while ($i < count($data)) {
                if ($data[$i]["mot_de_passe"] == $password_hash) {
                    $user = $data[$i];
                    break;
                }
                $i++;
            }

            $etat->closeCursor();
            return $user;
        } catch (Exception $e) {
            return null;
        }
    }

    public function findByEmail($email) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "SELECT * FROM utilisateur WHERE email = :email";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":email", $email);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();

            if (count($data) > 0) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function findByMatricule($matricule) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "SELECT * FROM utilisateur WHERE matricule = :matricule";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":matricule", $matricule);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();

            if (count($data) > 0) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function create() {
        try {
            $bdd = Connexion::getConnexion();
            $password_hash = md5($this->mot_de_passe);
            
            $req = "INSERT INTO utilisateur (matricule, nom, prenom, email, telephone, mot_de_passe, formation, type_utilisateur, statut, image) 
                    VALUES (:matricule, :nom, :prenom, :email, :telephone, :mot_de_passe, :formation, :type_utilisateur, :statut, :image)";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":matricule", $this->matricule);
            $etat->bindParam(":nom", $this->nom);
            $etat->bindParam(":prenom", $this->prenom);
            $etat->bindParam(":email", $this->email);
            $etat->bindParam(":telephone", $this->telephone);
            $etat->bindParam(":mot_de_passe", $password_hash);
            $etat->bindParam(":formation", $this->formation);
            $etat->bindParam(":type_utilisateur", $this->type_utilisateur);
            $etat->bindParam(":statut", $this->statut);
            $etat->bindParam(":image", $this->image);
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
            $req = "SELECT * FROM utilisateur";
            $etat = $bdd->prepare($req);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();
            return $data;
        } catch (Exception $e) {
            return array();
        }
    }
    
    public function getAllActiveStudents() {
        try {
            $bdd = Connexion::getConnexion();
            $req = "SELECT * FROM utilisateur WHERE type_utilisateur = 'etudiant' AND statut = 'actif'";
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
            $req = "SELECT * FROM utilisateur WHERE id_utilisateur = :id";
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

    public function update($id) {
        try {
            $bdd = Connexion::getConnexion();
            $password_hash = md5($this->mot_de_passe);
            
            $req = "UPDATE utilisateur SET matricule = :matricule, nom = :nom, prenom = :prenom, 
                    email = :email, telephone = :telephone, mot_de_passe = :mot_de_passe, 
                    formation = :formation, type_utilisateur = :type_utilisateur, 
                    statut = :statut, image = :image WHERE id_utilisateur = :id";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id", $id);
            $etat->bindParam(":matricule", $this->matricule);
            $etat->bindParam(":nom", $this->nom);
            $etat->bindParam(":prenom", $this->prenom);
            $etat->bindParam(":email", $this->email);
            $etat->bindParam(":telephone", $this->telephone);
            $etat->bindParam(":mot_de_passe", $password_hash);
            $etat->bindParam(":formation", $this->formation);
            $etat->bindParam(":type_utilisateur", $this->type_utilisateur);
            $etat->bindParam(":statut", $this->statut);
            $etat->bindParam(":image", $this->image);
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
            $req = "DELETE FROM utilisateur WHERE id_utilisateur = :id";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id", $id);
            $etat->execute();
            $etat->closeCursor();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
