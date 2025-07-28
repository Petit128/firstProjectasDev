<div class="admin-container">
    <h2>Gérer les Réservations</h2>

    <?php
    if (isset($message) && $message != '') {
        echo "<p class='message'>" . htmlspecialchars($message) . "</p>";
    }
    if (isset($error) && $error != '') {
        echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>ID Réservation</th>
                <th>Client</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Détails</th>
                <th>Actions</th>
            </tr>
        </thead>
                <tbody>
            <?php
            if (isset($reservations) && is_array($reservations)) {
                $i = 0;
                while ($i < count($reservations)) {
                    $reservation = $reservations[$i];
                    $plats = $reservation_plat_model->getPlatsByReservation($reservation["id_reservation"]); // Utilisation correcte du modèle
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation["id_reservation"]); ?></td>
                        <td>
                            <?php
                            if (isset($reservation["nom_client"])) {
                                echo htmlspecialchars($reservation["nom_client"]) . " " . htmlspecialchars($reservation["prenom_client"]);
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars(substr($reservation["date_reservation"], 0, 16)); ?></td>
                        <td><?php echo htmlspecialchars($reservation["statut_reservation"]); ?></td>
                        <td>
                            <?php
                            if (is_array($plats)) {
                                $j = 0;
                                while ($j < count($plats)) {
                                    $plat = $plats[$j];
                                    echo htmlspecialchars($plat["nom_plat"]) . " (x" . htmlspecialchars($plat["quantite"]) . ")<br>";
                                    $j = $j + 1;
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <form method="POST" action="index.php?page=admin_update_reservation_status">
                                <input type="hidden" name="id_reservation" value="<?php echo htmlspecialchars($reservation["id_reservation"]); ?>">
                                <select name="statut_reservation">
                                    <option value="en_attente" <?php if ($reservation["statut_reservation"] == "en_attente") { echo "selected"; } ?>>En attente</option>
                                    <option value="confirmee" <?php if ($reservation["statut_reservation"] == "confirmee") { echo "selected"; } ?>>Confirmée</option>
                                    <option value="annulee" <?php if ($reservation["statut_reservation"] == "annulee") { echo "selected"; } ?>>Annulée</option>
                                </select>
                                <button type="submit">Mettre à jour</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                    $i = $i + 1;
                }
            } else {
                ?>
                <tr><td colspan="6">Aucune réservation trouvée.</td></tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
