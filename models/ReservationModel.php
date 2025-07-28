<?php
require_once("Connexion.php");

class ReservationModel {
    public $id_reservation;
    public $id_utilisateur; // Ajout de cette propriété
    public $date_reservation;
    public $statut_reservation;
    public $commentaire;
    public $date_creation;
    public $date_modification;

    public function create() {
        try {
            $bdd = Connexion::getConnexion();
            $req = "INSERT INTO reservation (id_utilisateur, date_reservation, statut_reservation, commentaire, date_creation) 
                    VALUES (:id_utilisateur, :date_reservation, :statut_reservation, :commentaire, NOW())";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id_utilisateur", $this->id_utilisateur); // Liaison de l'ID utilisateur
            $etat->bindParam(":date_reservation", $this->date_reservation);
            $etat->bindParam(":statut_reservation", $this->statut_reservation);
            $etat->bindParam(":commentaire", $this->commentaire);
            $etat->execute();
            $id = $bdd->lastInsertId();
            $etat->closeCursor();
            return $id;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateStatus($id, $status) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "UPDATE reservation SET statut_reservation = :statut, date_modification = NOW() WHERE id_reservation = :id";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":statut", $status);
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
            // Simplified query to get all reservations. User and plat details will be fetched separately.
            $req = "SELECT * FROM reservation ORDER BY date_creation DESC";
            $etat = $bdd->prepare($req);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();
            return $data;
        } catch (Exception $e) {
            return array();
        }
    }

    // Nouvelle méthode pour récupérer toutes les réservations avec les détails de l'utilisateur
    public function getAllReservationsWithUserDetails() {
        try {
            $bdd = Connexion::getConnexion();
            // La jointure est maintenant directe avec la table 'reservation' pour l'utilisateur
            $req = "SELECT r.*, u.nom AS nom_client, u.prenom AS prenom_client, u.email AS email_client
                    FROM reservation r
                    JOIN utilisateur u ON r.id_utilisateur = u.id_utilisateur
                    ORDER BY r.date_creation DESC";
            $etat = $bdd->prepare($req);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();
            return $data;
        } catch (Exception $e) {
            return array();
        }
    }

    public function getByUser($id_utilisateur) {
        try {
            $bdd = Connexion::getConnexion();
            // La requête est simplifiée car id_utilisateur est directement dans la table reservation
            $req = "SELECT * FROM reservation WHERE id_utilisateur = :id_utilisateur ORDER BY date_creation DESC";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id_utilisateur", $id_utilisateur);
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
            $req = "SELECT * FROM reservation WHERE id_reservation = :id";
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
    public function countTotalReservations() {
        try {
            $bdd = Connexion::getConnexion(); // Get the database connection
            $req = "SELECT COUNT(*) as total_count FROM reservation"; // Query to count all reservations
            $etat = $bdd->prepare($req);
            $etat->execute();
            $result = $etat->fetch(PDO::FETCH_ASSOC); // Fetch the result as an associative array
            $etat->closeCursor();
            return $result['total_count']; // Return the total count of reservations
        } catch (Exception $e) {
            // Log error or handle it appropriately
            return 0; // Return 0 on error
        }
    }
}
