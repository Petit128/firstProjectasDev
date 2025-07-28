<div class="container">
    <h2>Votre Panier</h2>
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
            <th>Plat</th>
            <th>Prix</th>
            <th>Quantité</th>
            <th>Total</th>
            <th>Actions</th>
        </tr>
        <?php
        if (isset($panier) && is_array($panier)) {
            foreach ($panier as $item) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($item["nom_plat"]); ?></td>
                    <td><?php echo htmlspecialchars($item["prix"]); ?> Ar</td>
                    <td><?php echo htmlspecialchars($item["quantite"]); ?></td>
                    <td><?php echo htmlspecialchars($item["prix"] * $item["quantite"]); ?> Ar</td>
                    <td>
                        <form method="POST" action="index.php?page=remove_from_cart" style="display:inline;">
                            <input type="hidden" name="id_menu_plat" value="<?php echo htmlspecialchars($item['id_menu_plat']); ?>">
                            <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Retirer</button>
                        </form>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr><td colspan="5">Votre panier est vide.</td></tr>
        <?php } ?>
    </table>
    <h3>Total: <?php echo htmlspecialchars($total); ?> Ar</h3>
    <?php if (isset($panier) && count($panier) > 0): ?>
        <form method="POST" action="index.php?page=create_reservation">
            <button type="submit" style="background-color: #039f60; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 1.1em; margin-top: 20px;">Confirmer la Réservation</button>
        </form>
    <?php endif; ?>
</div>
