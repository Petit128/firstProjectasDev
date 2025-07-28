<div class="admin-container">
    <h2>Gestion des Menus</h2>
    <?php
    if (isset($message) && $message != '') {
        echo "<p class='message'>" . htmlspecialchars($message) . "</p>";
    }
    if (isset($error) && $error != '') {
        echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
    }
    ?>
    <a href="index.php?page=admin_add_menu">Ajouter un Menu</a>
    <table>
       <tr>
           <th>ID Menu</th>
           <th>Type de Service</th>
           <th>Statut</th>
           <th>Date de Publication</th>
           <th>Actions</th>
       </tr>
       <?php
       if (isset($menus) && is_array($menus)) {
           foreach ($menus as $menu_item) {
               echo "<tr>";
               echo "<td>" . htmlspecialchars($menu_item["id_menu"]) . "</td>";
               echo "<td>" . htmlspecialchars($menu_item["type_service"]) . "</td>";
               echo "<td>" . htmlspecialchars($menu_item["statut"]) . "</td>";
               echo "<td>" . htmlspecialchars($menu_item["date_publication"]) . "</td>";
               echo "<td>";
               echo "<a href='index.php?page=admin_update_menu&id=" . htmlspecialchars($menu_item['id_menu']) . "'>Modifier</a>";
               echo "<form method='POST' action='index.php?page=admin_delete_menu' style='display:inline;'>";
               echo "<input type='hidden' name='id_menu' value='" . htmlspecialchars($menu_item['id_menu']) . "'>";
               echo "<button type='submit'>Supprimer</button>";
               echo "</form>";
               echo "<a href='index.php?page=admin_manage_menu_plats&id_menu=" . htmlspecialchars($menu_item['id_menu']) . "'>Gérer Plats</a>";
               echo "</td>";
               echo "</tr>";
           }
       } else {
           echo "<tr><td colspan='5'>Aucun menu trouvé.</td></tr>";
       }
       ?>
   </table>
</div>

<div class="admin-container">
    <?php
    if (isset($_SESSION['message_plats_menu']) && $_SESSION['message_plats_menu'] != '') {
        echo "<p class='message'>" . htmlspecialchars($_SESSION['message_plats_menu']) . "</p>";
        unset($_SESSION['message_plats_menu']);
    }
    if (isset($_SESSION['error_plats_menu']) && $_SESSION['error_plats_menu'] != '') {
        echo "<p class='error'>" . htmlspecialchars($_SESSION['error_plats_menu']) . "</p>";
        unset($_SESSION['error_plats_menu']);
    }
    ?>

    <div class="section">
        <h2>Adapter les Plats au Menu</h2>
        <form action="index.php?page=admin_add_plat_to_menu" method="post">
            <div class="form-group">
                <label for="plat_id_adapter">Plat :</label>
                <select name="id_plat" id="plat_id_adapter" required>
                    <option value="">-- Sélectionner un plat --</option>
                    <?php
                    if (isset($all_plats) && is_array($all_plats)) {
                        foreach ($all_plats as $plat_option) {
                            echo '<option value="' . htmlspecialchars($plat_option['id_plat']) . '">' . htmlspecialchars($plat_option['nom_plat']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="menu_id_adapter">Menu :</label>
                <select name="id_menu" id="menu_id_adapter" required>
                    <option value="">-- Sélectionner un menu --</option>
                    <?php
                    if (isset($menus) && is_array($menus)) {
                        foreach ($menus as $menu_option) {
                            echo '<option value="' . htmlspecialchars($menu_option['id_menu']) . '">' . htmlspecialchars($menu_option['type_service']) . ' (ID: ' . htmlspecialchars($menu_option['id_menu']) . ')</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quota_max_adapter">Quota Maximum :</label>
                <input type="number" name="quota_max" id="quota_max_adapter" required min="1">
            </div>

            <button type="submit" name="adapter_plat">Adapter le plat au menu</button>
        </form>
    </div>

    <div class="section">
        <h2>Plats Associés aux Menus</h2>
        <table>
            <thead>
                <tr>
                    <th>Plat</th>
                    <th>Menu</th>
                    <th>Quota Max</th>
                    <th>Quota Réservé</th>
                    <th>Stock Plat</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($plats_associes_aux_menus) && is_array($plats_associes_aux_menus)) {
                    foreach ($plats_associes_aux_menus as $plat_menu_assoc) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($plat_menu_assoc['nom_plat']) . '</td>';
                        echo '<td>' . htmlspecialchars($plat_menu_assoc['type_service']) . '</td>';
                        echo '<td>' . htmlspecialchars($plat_menu_assoc['quota_max']) . '</td>';
                        echo '<td>' . htmlspecialchars($plat_menu_assoc['quota_reserve']) . '</td>';

                        echo '<td>';
                        echo '<form action="index.php?page=dashboard_admin" method="post" style="display:flex;">';
                        echo '<input type="hidden" name="action" value="update_plat_stock">';
                        echo '<input type="hidden" name="id_plat" value="' . htmlspecialchars($plat_menu_assoc['id_plat']) . '">';
                        echo '<input type="number" name="stock_value" value="';
                        if (isset($plat_menu_assoc['stock'])) {
                            echo htmlspecialchars($plat_menu_assoc['stock']);
                        } else {
                            echo '0';
                        }
                        echo '" min="0" style="width:60px;">';
                        echo '<button type="submit" style="margin-left:5px;">✓</button>';
                        echo '</form>';
                        echo '</td>';

                        echo '<td>';
                        echo '<a href="index.php?page=admin_update_plat_quota&id_menu=' . htmlspecialchars($plat_menu_assoc['id_menu']) . '&id_plat=' . htmlspecialchars($plat_menu_assoc['id_plat']) . '" style="margin-right:5px;">Modifier Quota</a>';
                        echo '<form action="index.php?page=admin_remove_plat_from_menu" method="post" style="display:inline;">';
                        echo '<input type="hidden" name="id_menu" value="' . htmlspecialchars($plat_menu_assoc['id_menu']) . '">';
                        echo '<input type="hidden" name="id_plat" value="' . htmlspecialchars($plat_menu_assoc['id_plat']) . '">';
                        echo '<button type="submit">Retirer</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="6">Aucun plat associé à un menu.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Ajouter un nouveau plat et l'associer à un menu</h2>
        <form action="index.php?page=dashboard_admin" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add_or_update_plat">
            <input type="hidden" name="id_plat" value="">

            <div class="form-group">
                <label for="nouveau_plat_nom">Nom du plat :</label>
                <input type="text" name="nom_plat" id="nouveau_plat_nom" required>
            </div>
            <div class="form-group">
                <label for="nouveau_plat_description">Description :</label>
                <textarea name="description" id="nouveau_plat_description"></textarea>
            </div>
            <div class="form-group">
                <label for="nouveau_plat_type">Type de plat :</label>
                <select name="type_plat" id="nouveau_plat_type" required>
                    <option value="entree">Entrée</option>
                    <option value="plat_principal">Plat Principal</option>
                    <option value="dessert">Dessert</option>
                    <option value="boisson">Boisson</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nouveau_plat_categorie">Catégorie :</label>
                <select name="categorie" id="nouveau_plat_categorie" required>
                    <option value="viande">Viande</option>
                    <option value="poisson">Poisson</option>
                    <option value="vegetarien">Végétarien</option>
                    <option value="vegan">Vegan</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nouveau_plat_prix">Prix (AR) :</label>
                <input type="number" step="0.01" name="prix" id="nouveau_plat_prix" required min="0">
            </div>
            <div class="form-group">
                <label for="nouveau_plat_calories">Calories :</label>
                <input type="number" name="calories" id="nouveau_plat_calories" required min="0">
            </div>
            <div class="form-group">
                <label for="nouveau_plat_stock">Stock initial :</label>
                <input type="number" name="stock" id="nouveau_plat_stock" required min="0">
            </div>
            <div class="form-group">
                <label for="nouveau_plat_statut">Statut :</label>
                <select name="statut" id="nouveau_plat_statut" required>
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nouveau_plat_image">Image:</label>
                <input type="file" name="image" id="nouveau_plat_image" accept="image/*">
            </div>

            <div class="form-group">
                <label for="associer_menu_id">Associer à un menu :</label>
                <select name="menu_id" id="associer_menu_id" required>
                    <option value="">-- Sélectionner un menu --</option>
                    <?php
                    if (isset($menus) && is_array($menus)) {
                        foreach ($menus as $menu_option) {
                            echo '<option value="' . htmlspecialchars($menu_option['id_menu']) . '">' . htmlspecialchars($menu_option['type_service']) . ' (ID: ' . htmlspecialchars($menu_option['id_menu']) . ')</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="associer_quota_max">Quota Maximum pour ce menu :</label>
                <input type="number" name="quota_max" id="associer_quota_max" required min="1">
            </div>

            <button type="submit">Ajouter le plat et associer</button>
        </form>
    </div>
</div>
