<div class="admin-container">
    <h2>Gestion des Plats</h2>
    <?php
    if (isset($message) && $message != '') {
        echo "<p class='message'>" . htmlspecialchars($message) . "</p>";
    }
    if (isset($error) && $error != '') {
        echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
    }
    ?>
    <a href="index.php?page=admin_add_plat">Ajouter un Plat</a>
    <table>
       <tr>
           <th>Nom du Plat</th>
           <th>Type</th>
           <th>Prix</th>
           <th>Stock</th>
           <th>Statut</th>
           <th>Actions</th>
       </tr>
       <?php
       if (isset($plats) && is_array($plats)) {
           foreach ($plats as $plat) { ?>
               <tr>
                   <td><?php echo htmlspecialchars($plat["nom_plat"]); ?></td>
                   <td><?php echo htmlspecialchars($plat["type_plat"]); ?></td>
                   <td><?php echo htmlspecialchars($plat["prix"]); ?> AR</td>
                   <td><?php
                        if (isset($plat["stock"])) {
                            echo htmlspecialchars($plat["stock"]);
                        } else {
                            echo "N/A";
                        }
                    ?></td>
                   <td><?php echo htmlspecialchars($plat["statut"]); ?></td>
                   <td>
                       <a href="index.php?page=admin_update_plat&id=<?php echo htmlspecialchars($plat['id_plat']); ?>">Modifier</a>
                       <form method="POST" action="index.php?page=admin_delete_plat" style="display:inline;">
                           <input type="hidden" name="id_plat" value="<?php echo htmlspecialchars($plat['id_plat']); ?>">
                           <button type="submit">Supprimer</button>
                       </form>
                   </td>
               </tr>
           <?php }
       } else { ?>
           <tr><td colspan="6">Aucun plat trouvé.</td></tr>
       <?php } ?>
   </table>
</div>
