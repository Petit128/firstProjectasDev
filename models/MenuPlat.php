<?php
require_once("Connexion.php");

class MenuPlat {
    public $id_menu_plat;
    public $id_menu;
    public $id_plat;
    public $quota_max;
    public $quota_reserve;

    public function create() {
        try {
            $bdd = Connexion::getConnexion();
            $req = "INSERT INTO menu_plat (id_menu, id_plat, quota_max, quota_reserve) 
                    VALUES (:id_menu, :id_plat, :quota_max, 0)";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id_menu", $this->id_menu);
            $etat->bindParam(":id_plat", $this->id_plat);
            $etat->bindParam(":quota_max", $this->quota_max);
            $etat->execute();
            $etat->closeCursor();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id_menu, $id_plat) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "DELETE FROM menu_plat WHERE id_menu = :id_menu AND id_plat = :id_plat";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id_menu", $id_menu);
            $etat->bindParam(":id_plat", $id_plat);
            $etat->execute();
            $etat->closeCursor();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getPlatsByMenu($id_menu) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "SELECT p.*, mp.quota_max, mp.quota_reserve, mp.id_menu_plat 
                    FROM plat p 
                    JOIN menu_plat mp ON p.id_plat = mp.id_plat 
                    WHERE mp.id_menu = :id_menu";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id_menu", $id_menu);
            $etat->execute();
            $data = $etat->fetchAll();
            $etat->closeCursor();
            return $data;
        } catch (Exception $e) {
            return array();
        }
    }

    public function updateQuota($id_menu, $id_plat, $quota_max) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "UPDATE menu_plat SET quota_max = :quota_max 
                    WHERE id_menu = :id_menu AND id_plat = :id_plat";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id_menu", $id_menu);
            $etat->bindParam(":id_plat", $id_plat);
            $etat->bindParam(":quota_max", $quota_max);
            $etat->execute();
            $etat->closeCursor();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function incrementQuotaReserve($id_menu_plat, $quantity) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "UPDATE menu_plat SET quota_reserve = quota_reserve + :quantity 
                    WHERE id_menu_plat = :id_menu_plat";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id_menu_plat", $id_menu_plat);
            $etat->bindParam(":quantity", $quantity);
            $etat->execute();
            $etat->closeCursor();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getByMenuAndPlat($id_menu, $id_plat) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "SELECT * FROM menu_plat WHERE id_menu = :id_menu AND id_plat = :id_plat";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id_menu", $id_menu);
            $etat->bindParam(":id_plat", $id_plat);
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

    // Nouvelle méthode pour récupérer par id_menu_plat
    public function getByIdMenuPlat($id_menu_plat) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "SELECT * FROM menu_plat WHERE id_menu_plat = :id_menu_plat";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id_menu_plat", $id_menu_plat);
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

    // Nouvelle méthode pour récupérer un id_menu_plat par id_plat (le premier trouvé)
    public function getMenuPlatByPlatId($id_plat) {
        try {
            $bdd = Connexion::getConnexion();
            // Ajout d'une condition pour s'assurer que le menu est actif si nécessaire
            // Pour l'instant, on prend juste le premier id_menu_plat associé au plat
            $req = "SELECT mp.* FROM menu_plat mp WHERE mp.id_plat = :id_plat LIMIT 1"; 
            $etat = $bdd->prepare($req);
            $etat->bindParam(":id_plat", $id_plat);
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


    public function checkAndCreateAssociations(){
        // 1. Initialiser la connexion
        $bdd = Connexion::getConnexion();

        // 2. Récupérer tous les plats disponibles
        $req_plats = "SELECT id_plat FROM plat WHERE statut = 'actif'";
        $etat_plats = $bdd->prepare($req_plats);
        $etat_plats->execute();
        $plats = $etat_plats->fetchAll();
        $etat_plats->closeCursor();

        // 3. Paramètres de configuration
        $id_menu_principal = 1; // ID du menu par défaut
        $quota_par_defaut = 20; // Quota maximum par défaut

        // 4. Traitement des associations
        $i = 0;
        while ($i < count($plats)) {
            $id_plat = $plats[$i]['id_plat'];
            
            // Vérifier si l'association existe déjà
            $req_check = "SELECT COUNT(*) FROM menu_plat 
                        WHERE id_menu = :id_menu AND id_plat = :id_plat";
            $etat_check = $bdd->prepare($req_check);
            $etat_check->bindParam(":id_menu", $id_menu_principal);
            $etat_check->bindParam(":id_plat", $id_plat);
            $etat_check->execute();
            $exists = $etat_check->fetchColumn();
            $etat_check->closeCursor();
            
            if ($exists == 0) {
                // Créer la nouvelle association
                $req_insert = "INSERT INTO menu_plat (id_menu, id_plat, quota_max, quota_reserve) 
                            VALUES (:id_menu, :id_plat, :quota_max, 0)";
                $etat_insert = $bdd->prepare($req_insert);
                $etat_insert->bindParam(":id_menu", $id_menu_principal);
                $etat_insert->bindParam(":id_plat", $id_plat);
                $etat_insert->bindParam(":quota_max", $quota_par_defaut);
                
                if ($etat_insert->execute()) {
                    echo "SUCCES: Plat #".$id_plat." ajouté au menu.<br>";
                }
                $etat_insert->closeCursor();
            } 
            $i = $i + 1;
        }

    }

    public function decrementQuotaReserve($id_menu_plat, $quantite) {
        try {
            $bdd = Connexion::getConnexion();
            $req = "UPDATE menu_plat SET quota_reserve = quota_reserve - :quantite WHERE id_menu_plat = :id_menu_plat";
            $etat = $bdd->prepare($req);
            $etat->bindParam(":quantite", $quantite);
            $etat->bindParam(":id_menu_plat", $id_menu_plat);
            $etat->execute();
            $etat->closeCursor();
            return true;
        } catch (Exception $e) {
            error_log("Erreur decrementQuotaReserve: " . $e->getMessage());
            return false;
        }
    }
    
    
}
