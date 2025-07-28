<div class="admin-container">
    <h2>Modifier le Menu</h2>
    <?php
    if (isset($message) && $message != '') {
        echo "<p class='message'>" . htmlspecialchars($message) . "</p>";
    }
    if (isset($error) && $error != '') {
        echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
    }
    ?>
    <form method="POST" action="index.php?page=admin_update_menu">
        <input type="hidden" name="id_menu" value="<?php echo htmlspecialchars($menu["id_menu"]); ?>">
        <label>Type de Service:</label>
        <select name="type_service" required>
            <option value="dejeuner" <?php if (isset($menu["type_service"]) && $menu["type_service"] == "dejeuner") { echo "selected"; } ?>>Déjeuner</option>
            <option value="diner" <?php if (isset($menu["type_service"]) && $menu["type_service"] == "diner") { echo "selected"; } ?>>Dîner</option>
            <option value="petit-dej" <?php if (isset($menu["type_service"]) && $menu["type_service"] == "petit-dej") { echo "selected"; } ?>>Petit-déjeuner</option>
            <option value="dessert" <?php if (isset($menu["type_service"]) && $menu["type_service"] == "dessert") { echo "selected"; } ?>>Dessert</option>
        </select>
        <label>Statut:</label>
        <select name="statut" required>
            <option value="draft" <?php if (isset($menu["statut"]) && $menu["statut"] == "draft") { echo "selected"; } ?>>Brouillon</option>
            <option value="publie" <?php if (isset($menu["statut"]) && $menu["statut"] == "publie") { echo "selected"; } ?>>Publié</option>
            <option value="archive" <?php if (isset($menu["statut"]) && $menu["statut"] == "archive") { echo "selected"; } ?>>Archivé</option>
        </select>
        <label>Date de Publication:</label>
        <input type="datetime-local" name="date_publication" value="<?php
        if (isset($menu["date_publication"])) {
            echo htmlspecialchars(str_replace(' ', 'T', $menu["date_publication"]));
        }
        ?>" required>
        <button type="submit">Modifier</button>
    </form>
</div>
