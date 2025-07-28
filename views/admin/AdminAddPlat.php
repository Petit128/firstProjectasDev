<div class="admin-container">
    <h2>Ajouter un Plat</h2>
    <?php if (isset($message) && $message != '') { echo "<p class='message'>$message</p>"; } ?>
    <?php if (isset($error) && $error != '') { echo "<p class='error'>$error</p>"; } ?>
    <form method="POST" action="index.php?page=admin_add_plat">
        <label>Nom du Plat:</label>
        <input type="text" name="nom_plat" required>
        <label>Description:</label>
        <textarea name="description"></textarea>
        <label>Type de Plat:</label>
        <select name="type_plat" required>
            <option value="entree">Entrée</option>
            <option value="plat_principal">Plat Principal</option>
            <option value="dessert">Dessert</option>
        </select>
        <label>Catégorie:</label>
        <select name="categorie" required>
            <option value="viande">Viande</option>
            <option value="poisson">Poisson</option>
            <option value="vegetarien">Végétarien</option>
            <option value="vegan">Vegan</option>
        </select>
        <label>Prix (Ar):</label>
        <input type="number" step="0.01" name="prix" required>
        <label>Calories:</label>
        <input type="number" name="calories" required>
        <label>Stock:</label>
        <input type="number" name="stock">
        <label>Statut:</label>
        <select name="statut" required>
            <option value="actif">Actif</option>
            <option value="inactif">Inactif</option>
        </select>
        <button type="submit">Ajouter</button>
    </form>
</div>
