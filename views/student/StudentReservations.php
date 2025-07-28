<div class="container">
    <h2>Mes Réservations</h2>
    <?php
    if (isset($message) && $message != '') {
        echo "<p class='message'>" . htmlspecialchars($message) . "</p>";
    }
    if (isset($error) && $error != '') {
        echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
    }
    ?>
    <table>
        <tr>
            <th>ID Réservation</th>
            <th>Date Réservation</th>
            <th>Statut</th>
            <th>Plats</th>
            <th>Action</th>
        </tr>
        <?php
        if (isset($reservations) && is_array($reservations)) {
            $i = 0;
            while ($i < count($reservations)) {
                $reservation = $reservations[$i];
                $plats = $reservation_plat_model->getPlatsByReservation($reservation["id_reservation"]);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($reservation["id_reservation"]); ?></td>
                    <td><?php echo htmlspecialchars($reservation["date_reservation"]); ?></td>
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
                        <?php
                        if ($reservation["statut_reservation"] == "en_attente") {
                            ?>
                            <form method="POST" action="index.php?page=cancel_reservation">
                                <input type="hidden" name="id_reservation" value="<?php echo htmlspecialchars($reservation["id_reservation"]); ?>">
                                <button type="submit" class="btn-cancel">Annuler</button>
                            </form>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
                $i = $i + 1;
            }
        } else {
            ?>
            <tr><td colspan="5">Aucune réservation trouvée.</td></tr>
            <?php
        }
        ?>
    </table>
</div>
