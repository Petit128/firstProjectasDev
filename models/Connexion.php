<?php
class Connexion {
    public static $connex;
    public static function getConnexion() {
        if(self::$connex == null) {
            try {
                $user = "mysql:host=127.0.0.1;dbname=reservation_ru";
                $login = "root";
                $mdp = "";
                self::$connex = new PDO($user, $login, $mdp);
                self::$connex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error reporting
            } catch (PDOException $e) {
                // In a real application, log this error and show a user-friendly message
                die("Erreur 404 not found " . $e->getMessage());
            }
        }
        return self::$connex;
    }
}
?>
