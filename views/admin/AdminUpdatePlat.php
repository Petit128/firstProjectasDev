<div class="admin-container">
    <h2>Modifier un Plat</h2>
    <?php
    if (isset($message) && $message != '') {
        echo "<p class='message'>" . htmlspecialchars($message) . "</p>";
    }
    if (isset($error) && $error != '') {
        echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
    }
    ?>
    <form method="POST" action="index.php?page=admin_update_plat">
        <input type="hidden" name="id_plat" value="<?php echo htmlspecialchars($plat["id_plat"]); ?>">
        <label>Nom du Plat:</label>
        <input type="text" name="nom_plat" value="<?php echo htmlspecialchars($plat["nom_plat"]); ?>" required>
        <label>Description:</label>
        <textarea name="description"><?php echo htmlspecialchars($plat["description"]); ?></textarea>
        <label>Type de Plat:</label>
        <select name="type_plat" required>
            <option value="entree" <?php if (isset($plat["type_plat"]) && $plat["type_plat"] == "entree") { echo "selected"; } ?>>Entrée</option>
            <option value="plat_principal" <?php if (isset($plat["type_plat"]) && $plat["type_plat"] == "plat_principal") { echo "selected"; } ?>>Plat Principal</option>
            <option value="dessert" <?php if (isset($plat["type_plat"]) && $plat["type_plat"] == "dessert") { echo "selected"; } ?>>Dessert</option>
        </select>
        <label>Catégorie:</label>
        <select name="categorie" required>
            <option value="viande" <?php if (isset($plat["categorie"]) && $plat["categorie"] == "viande") { echo "selected"; } ?>>Viande</option>
            <option value="poisson" <?php if (isset($plat["categorie"]) && $plat["categorie"] == "poisson") { echo "selected"; } ?>>Poisson</option>
            <option value="vegetarien" <?php if (isset($plat["categorie"]) && $plat["categorie"] == "vegetarien") { echo "selected"; } ?>>Végétarien</option>
            <option value="vegan" <?php if (isset($plat["categorie"]) && $plat["categorie"] == "vegan") { echo "selected"; } ?>>Vegan</option>
        </select>
        <label>Prix (Ar):</label>
        <input type="number" step="0.01" name="prix" value="<?php echo htmlspecialchars($plat["prix"]); ?>" required>
        <label>Calories:</label>
        <input type="number" name="calories" value="<?php echo htmlspecialchars($plat["calories"]); ?>" required>
        <label>Stock:</label>
        <input type="number" name="stock" value="<?php echo htmlspecialchars($plat["stock"]); ?>" required>
        <label>Statut:</label>
        <select name="statut" required>
            <option value="actif" <?php if (isset($plat["statut"]) && $plat["statut"] == "actif") { echo "selected"; } ?>>Actif</option>
            <option value="inactif" <?php if (isset($plat["statut"]) && $plat["statut"] == "inactif") { echo "selected"; } ?>>Inactif</option>
        </select>
        <button type="submit">Modifier</button>
    </form>
</div>
