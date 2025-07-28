<?php
require_once("Connexion.php");
class ReservationPlat {
    public $id_reservation;
    public $id_utilisateur; // Ajout de cette propriété
    public $id_menu_plat;
    public $quantite;

    public function create() {
        try {
            $bdd = Connexion::getConnexion();
            $req = "INSERT INTO reservation_plat (id_reservation, id_utilisateur, id_menu_plat, quantite)
                    VALUES (:id_reservation, :id_utilisateur, :id_menu_plat, :quantite)";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id_reservation", $this->id_reservation);
            $etat->bindParam(":id_utilisateur", $this->id_utilisateur); // Liaison de l'ID utilisateur
            $etat->bindParam(":id_menu_plat", $this->id_menu_plat); // Correction du nom du paramètre
            $etat->bindParam(":quantite", $this->quantite);
            $etat->execute();
            $etat->closeCursor();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getPlatsByReservation($id_reservation) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "SELECT p.id_plat, p.nom_plat, p.prix, rp.quantite, m.type_service 
                    FROM reservation_plat rp
                    JOIN menu_plat mp ON rp.id_menu_plat = mp.id_menu_plat
                    JOIN plat p ON mp.id_plat = p.id_plat
                    JOIN menu m ON mp.id_menu = m.id_menu
                    WHERE rp.id_reservation = :id_reservation";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id_reservation", $id_reservation);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();
            return $data;
        } catch (Exception $e) {
            error_log("Erreur getPlatsByReservation: " . $e->getMessage());
            return array();
        }
    }
    

}
