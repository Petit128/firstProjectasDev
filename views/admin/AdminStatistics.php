<div class="admin-container">
    <h2>Statistiques</h2>
    <h3>Plats les plus réservés</h3>
    <table>
        <tr>
            <th>Plat</th>
            <th>Nombre de réservations</th>
        </tr>
        <?php
        if (isset($most_reserved_plats) && is_array($most_reserved_plats)) {
            foreach ($most_reserved_plats as $plat) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($plat["nom_plat"]); ?></td>
                    <td><?php echo htmlspecialchars($plat["total_reservations"]); ?></td>
                </tr>
            <?php }
        } else { ?>
            <tr><td colspan="2">Aucun plat réservé pour le moment.</td></tr>
        <?php } ?>
    </table>

    <h3>Revenus</h3>
    <h4>Quotidien: <?php
    if (isset($daily_revenue)) {
        echo htmlspecialchars($daily_revenue);
    } else {
        echo '0';
    }
    ?> Ar</h4>
    <h4>Hebdomadaire: <?php
    if (isset($weekly_revenue)) {
        echo htmlspecialchars($weekly_revenue);
    } else {
        echo '0';
    }
    ?> Ar</h4>
    <h4>Mensuel: <?php
    if (isset($monthly_revenue)) {
        echo htmlspecialchars($monthly_revenue);
    } else {
        echo '0';
    }
    ?> Ar</h4>
    <h4>Annuel: <?php
    if (isset($yearly_revenue)) {
        echo htmlspecialchars($yearly_revenue);
    } else {
        echo '0';
    }
    ?> AR</h4>

    <h3>Clients fideles</h3>
    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Nombre de réservations</th>
        </tr>
        <?php
        if (isset($loyal_customers) && is_array($loyal_customers)) {
            foreach ($loyal_customers as $customer) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($customer["nom"]); ?></td>
                    <td><?php echo htmlspecialchars($customer["prenom"]); ?></td>
                    <td><?php echo htmlspecialchars($customer["total_reservations"]); ?></td>
                </tr>
            <?php }
        } else { ?>
            <tr><td colspan="3">Aucun client fidèle pour le moment.</td></tr>
        <?php } ?>
    </table>
</div>
