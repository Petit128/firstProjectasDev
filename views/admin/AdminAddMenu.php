<div class="admin-container">
    <h2>Ajouter un Menu</h2>
    <?php if (isset($message) && $message != '') { echo "<p class='message'>$message</p>"; } ?>
    <?php if (isset($error) && $error != '') { echo "<p class='error'>$error</p>"; } ?>
    <form method="POST" action="index.php?page=admin_add_menu">
        <label>Type de Service:</label>
        <select name="type_service" required>
            <option value="dejeuner">Déjeuner</option>
            <option value="diner">Dîner</option>
            <option value="petit-dej">Petit-déjeuner</option>
            <option value="dessert">Dessert</option>
        </select>
        <label>Statut:</label>
        <select name="statut" required>
            <option value="draft">Brouillon</option>
            <option value="publie">Publié</option>
            <option value="archive">Archivé</option>
        </select>
        <label>Date de Publication:</label>
        <input type="datetime-local" name="date_publication" required>
        <button type="submit">Ajouter</button>
    </form>
</div>
