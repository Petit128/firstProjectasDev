
<?php
require_once("Connexion.php");

class StatistiqueModel {
    public function getMostReservedPlats($limit = 5) {
        try {
            $bdd = Connexion::getConnexion();
            // Select id_plat as well
            $req = "SELECT p.id_plat, p.nom_plat, COUNT(rp.id_menu_plat) as total_reservations 
                    FROM plat p 
                    JOIN menu_plat mp ON p.id_plat = mp.id_plat 
                    JOIN reservation_plat rp ON mp.id_menu_plat = rp.id_menu_plat 
                    GROUP BY p.id_plat 
                    ORDER BY total_reservations DESC 
                    LIMIT :limit_val";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":limit_val", $limit, PDO::PARAM_INT);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();
            return $data;
        } catch (Exception $e) {
            return array();
        }
    }

    public function getLoyalCustomers($limit = 5) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "SELECT u.nom, u.prenom, COUNT(r.id_reservation) as total_reservations 
                    FROM utilisateur u 
                    JOIN reservation_plat rp ON u.id_utilisateur = rp.id_utilisateur 
                    JOIN reservation r ON rp.id_reservation = r.id_reservation 
                    GROUP BY u.id_utilisateur 
                    ORDER BY total_reservations DESC 
                    LIMIT :limit_val";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":limit_val", $limit, PDO::PARAM_INT);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();
            return $data;
        } catch (Exception $e) {
            return array();
        }
    }

    public function getRevenue($period = 'daily') {
        try {
            $bdd = Connexion::getConnexion();
            $req = "";
            if ($period == 'daily') {
                $req = "SELECT SUM(p.prix * rp.quantite) as total_revenue 
                        FROM plat p 
                        JOIN menu_plat mp ON p.id_plat = mp.id_plat 
                        JOIN reservation_plat rp ON mp.id_menu_plat = rp.id_menu_plat 
                        JOIN reservation r ON rp.id_reservation = r.id_reservation 
                        WHERE DATE(r.date_creation) = CURDATE()";
            } else if ($period == 'weekly') {
                $req = "SELECT SUM(p.prix * rp.quantite) as total_revenue 
                        FROM plat p 
                        JOIN menu_plat mp ON p.id_plat = mp.id_plat 
                        JOIN reservation_plat rp ON mp.id_menu_plat = rp.id_menu_plat 
                        JOIN reservation r ON rp.id_reservation = r.id_reservation 
                        WHERE WEEK(r.date_creation) = WEEK(CURDATE()) AND YEAR(r.date_creation) = YEAR(CURDATE())";
            } else if ($period == 'monthly') {
                $req = "SELECT SUM(p.prix * rp.quantite) as total_revenue 
                        FROM plat p 
                        JOIN menu_plat mp ON p.id_plat = mp.id_plat 
                        JOIN reservation_plat rp ON mp.id_menu_plat = rp.id_menu_plat 
                        JOIN reservation r ON rp.id_reservation = r.id_reservation 
                        WHERE MONTH(r.date_creation) = MONTH(CURDATE()) AND YEAR(r.date_creation) = YEAR(CURDATE())";
            } else if ($period == 'yearly') {
                $req = "SELECT SUM(p.prix * rp.quantite) as total_revenue 
                        FROM plat p 
                        JOIN menu_plat mp ON p.id_plat = mp.id_plat 
                        JOIN reservation_plat rp ON mp.id_menu_plat = rp.id_menu_plat 
                        JOIN reservation r ON rp.id_reservation = r.id_reservation 
                        WHERE YEAR(r.date_creation) = YEAR(CURDATE())";
            } else {
                return 0; // Invalid period
            }
            $etat = $bdd->prepare($req);
            $etat->execute();
            $data = $etat->fetch();
            $etat->closeCursor();
            
            $total_revenue = 0;
            if (isset($data['total_revenue'])) {
                $total_revenue = $data['total_revenue'];
            }
            return $total_revenue;
        } catch (Exception $e) {
            return 0;
        }
    }
}
