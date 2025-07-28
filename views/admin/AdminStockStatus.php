<div class="admin-container">
    <h2>Gestion des Stocks</h2>
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
            <th>Nom du Plat</th>
            <th>Stock</th>
            <th>Statut</th>
        </tr>
        <?php
        if (isset($plats) && is_array($plats)) {
            foreach ($plats as $plat) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($plat["nom_plat"]); ?></td>
                    <td><?php
                    if (isset($plat["stock"])) {
                        echo htmlspecialchars($plat["stock"]);
                    } else {
                        echo 'N/A';
                    }
                    ?></td>
                    <td><?php echo htmlspecialchars($plat["statut"]); ?></td>
                </tr>
            <?php }
        } else { ?>
            <tr><td colspan="3">Aucun plat trouvé pour la gestion des stocks.</td></tr>
        <?php } ?>
    </table>
</div>
