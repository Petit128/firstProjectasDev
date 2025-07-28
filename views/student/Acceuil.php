<div class="container">
    <h2>Accueil Étudiant</h2>

    <h3>Suggestion du Jour</h3>
    <div class="suggestion">
        <?php
        // Display the most sold meal
        if (isset($recommended_plats) && count($recommended_plats) > 0) {
            $most_sold_plat = $recommended_plats[0]; // Assuming the first one is the most sold
            $full_plat_details = $repas_model->getById($most_sold_plat["id_plat"]);
            
            // Il faut aussi récupérer l'id_menu_plat pour le plat suggéré
            // On va chercher le premier id_menu_plat associé à ce plat
            $menu_plat_model_for_suggestion = new MenuPlat();
            $menu_plat_suggestion_details = $menu_plat_model_for_suggestion->getMenuPlatByPlatId($most_sold_plat["id_plat"]); // Nouvelle méthode à créer

            $id_menu_plat_for_suggestion = 0;
            if ($menu_plat_suggestion_details != null) {
                $id_menu_plat_for_suggestion = $menu_plat_suggestion_details["id_menu_plat"];
            }

            if ($full_plat_details != null && $id_menu_plat_for_suggestion > 0) { // Vérifier que les détails du plat et l'id_menu_plat sont valides
        ?>
                <div class="card">
                    <?php if (!empty($full_plat_details["image"])): ?>
                        <img src="public/assets/images/plats/<?php echo htmlspecialchars($full_plat_details["image"]); ?>" alt="<?php echo htmlspecialchars($full_plat_details["nom_plat"]); ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($full_plat_details["nom_plat"]); ?></h3>
                    <p>Prix: <?php echo htmlspecialchars($full_plat_details["prix"]); ?> Ar</p>
                    <p>Calories: <?php echo htmlspecialchars($full_plat_details["calories"]); ?></p>
                    <button class="add-to-cart" data-id="<?php echo htmlspecialchars($id_menu_plat_for_suggestion); ?>">Ajouter au Panier</button>
                </div>
            <?php
            } else {
                echo "<p>Aucune suggestion disponible pour le moment.</p>";
            }
        } else {
            echo "<p>Aucune suggestion disponible pour le moment.</p>";
        }
        ?>
    </div>

    <h3>Plats Disponibles</h3>
    <div class="board">
        <?php
        // Display all available plats
        if (isset($plats) && is_array($plats)) {
            $i = 0;
            while ($i < count($plats)) {
                $plat = $plats[$i];
                // Assurez-vous que $plat['id_menu_plat'] est bien défini ici grâce à RepasModel::getAllPlatsWithMenuPlatId()
                $id_menu_plat_for_cart = 0;
                if (isset($plat["id_menu_plat"])) {
                    $id_menu_plat_for_cart = $plat["id_menu_plat"];
                }
                
                if ($id_menu_plat_for_cart > 0) { // N'afficher le bouton que si un id_menu_plat valide est trouvé
                ?>
                    <div class="card" data-plat-id="<?php echo htmlspecialchars($plat["id_plat"]); ?>">
                        <?php if (!empty($plat["image"])): ?>
                            <img src="public/assets/images/plats/<?php echo htmlspecialchars($plat["image"]); ?>" alt="<?php echo htmlspecialchars($plat["nom_plat"]); ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($plat["nom_plat"]); ?></h3>
                        <p>Prix: <?php echo htmlspecialchars($plat["prix"]); ?> Ar</p>
                        <p>Calories: <?php echo htmlspecialchars($plat["calories"]); ?></p>
                        <button class="add-to-cart" data-id="<?php echo htmlspecialchars($id_menu_plat_for_cart); ?>">Ajouter au Panier</button>
                    </div>
                <?php
                }
                $i = $i + 1;
            }
        } else { ?>
            <p>Aucun plat disponible pour le moment.</p>
        <?php } ?>
    </div>
</div>
