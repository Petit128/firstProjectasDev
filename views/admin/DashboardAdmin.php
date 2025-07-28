<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérification des droits admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header("Location: index.php?page=login");
    exit();
}
?>

<div class="container">
    <h1>Dashboard Admin - Gestion des Plats</h1>
    
    <?php if (isset($message) && $message != ''): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php if (isset($error) && $error != ''): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Formulaire d'ajout de plat -->
    <div class="form-section">
        <h2>Ajouter un Plat</h2>
        <form method="POST" action="index.php?page=dashboard_admin&action=ajouter" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nom_plat">Nom du Plat:</label>
                <input type="text" id="nom_plat" name="nom_plat" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label for="type_plat">Type de Plat:</label>
                <select id="type_plat" name="type_plat" required>
                    <option value="">Sélectionner...</option>
                    <option value="Entree">Entrée</option>
                    <option value="Resistance">Résistance</option>
                    <option value="Principale">Principale</option>
                    <option value="dessert">Dessert</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="type_menu">Type de Menu:</label>
                <select id="type_menu" name="type_menu" required>
                    <option value="">Sélectionner...</option>
                    <option value="PETIT-DEJ">Petit-déjeuner</option>
                    <option value="DEJEUNER">Déjeuner</option>
                    <option value="DINER">Dîner</option>
                    <option value="DESSERT">Dessert</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="categorie">Catégorie:</label>
                <select id="categorie" name="categorie" required>
                    <option value="">Sélectionner...</option>
                    <option value="viande">Viande</option>
                    <option value="poisson">Poisson</option>
                    <option value="vegetarien">Végétarien</option>
                    <option value="vegan">Vegan</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="quota_max">Quota Maximum:</label>
                <input type="number" id="quota_max" name="quota_max" min="0" value="0">
            </div>

            <div class="form-group">
                <label for="stock">stock:</label>
                <input type="number" id="stock" name="stock" min="0" value="0">
            </div>
            
            <div class="form-group">
                <label for="prix">Prix (Ar):</label>
                <input type="number" id="prix" name="prix" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="calories">Calories:</label>
                <input type="number" id="calories" name="calories" min="0" value="0">
            </div>
            
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            
            <div class="form-group">
                <label for="statut">Statut:</label>
                <select id="statut" name="statut" required>
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Ajouter le Plat</button>
        </form>
    </div>

    <!-- Formulaire de modification (affiché si modification en cours) -->
    <?php if (isset($plat_modifier) && $plat_modifier != null): ?>
    <div class="form-section">
        <h2>Modifier le Plat</h2>
        <form method="POST" action="index.php?page=dashboard_admin&action=modifier" enctype="multipart/form-data">
            <input type="hidden" name="id_plat" value="<?php echo htmlspecialchars($plat_modifier['id_plat']); ?>">
            
            <div class="form-group">
                <label for="nom_plat_modif">Nom du Plat:</label>
                <input type="text" id="nom_plat_modif" name="nom_plat" value="<?php echo htmlspecialchars($plat_modifier['nom_plat']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description_modif">Description:</label>
                <textarea id="description_modif" name="description" rows="3"><?php echo htmlspecialchars($plat_modifier['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="type_plat_modif">Type de Plat:</label>
                <select id="type_plat_modif" name="type_plat" required>
                    <option value="Entree" <?php if($plat_modifier['type_plat'] == 'Entree') echo 'selected'; ?>>Entrée</option>
                    <option value="Resistance" <?php if($plat_modifier['type_plat'] == 'Resistance') echo 'selected'; ?>>Résistance</option>
                    <option value="Principale" <?php if($plat_modifier['type_plat'] == 'Principale') echo 'selected'; ?>>Principale</option>
                    <option value="dessert" <?php if($plat_modifier['type_plat'] == 'dessert') echo 'selected'; ?>>Dessert</option>
                </select>
            </div>
            
            
        <div class="form-group">
            <label for="type_menu">Type de Menu :</label>
            <select name="type_menu" id="type_menu" required>
                <option value="">Sélectionner un type de menu</option>
                <option value="DEJEUNER">Déjeuner</option>
                <option value="DINER">Dîner</option>
                <option value="PETIT-DEJ">Petit-déjeuner</option>
                <option value="DESSERT">Dessert</option>
            </select>
        </div>

        <div class="form-group">
                <label for="categorie_modif">Catégorie:</label>
                <select id="categorie_modif" name="categorie">
                    <option value="viande" <?php if($plat_modifier['categorie'] == 'viande') echo 'selected'; ?>>Viande</option>
                    <option value="poisson" <?php if($plat_modifier['categorie'] == 'poisson') echo 'selected'; ?>>Poisson</option>
                    <option value="vegetarien" <?php if($plat_modifier['categorie'] == 'vegetarien') echo 'selected'; ?>>Végétarien</option>
                    <option value="vegan" <?php if($plat_modifier['categorie'] == 'vegan') echo 'selected'; ?>>Vegan</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="quota_max_modif">Quota Maximum:</label>
                <input type="number" id="quota_max_modif" name="quota_max" min="0" value="<?php echo htmlspecialchars($plat_modifier['quota_max']); ?>">
            </div>
            
            <div class="form-group">
                <label for="stock_modif">Stock :</label>
                <input type="number" id="stock_modif" name="stock" step="0.01" min="0" value="<?php echo htmlspecialchars($plat_modifier['stock']); ?>" required>
            </div>
            <div class="form-group">
                <label for="prix_modif">Prix (Ar):</label>
                <input type="number" id="prix_modif" name="prix" step="0.01" min="0" value="<?php echo htmlspecialchars($plat_modifier['prix']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="calories_modif">Calories:</label>
                <input type="number" id="calories_modif" name="calories" min="0" value="<?php echo htmlspecialchars($plat_modifier['calories']); ?>">
            </div>
            
            <div class="form-group">
                <label for="image_modif">Image:</label>
                <input type="file" id="image_modif" name="image" accept="image/*">
                <?php if (!empty($plat_modifier['image'])): ?>
                    <p>Image actuelle: <?php echo htmlspecialchars($plat_modifier['image']); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="statut_modif">Statut:</label>
                <select id="statut_modif" name="statut" required>
                    <option value="actif" <?php if($plat_modifier['statut'] == 'actif') echo 'selected'; ?>>Actif</option>
                    <option value="inactif" <?php if($plat_modifier['statut'] == 'inactif') echo 'selected'; ?>>Inactif</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Modifier le Plat</button>
            <a href="index.php?page=dashboard_admin" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
    <?php endif; ?>

    <!-- Tableau des plats -->
    <div class="table-section">
        <h2>Liste des Plats</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Plat</th>
                    <th>Nom Plat</th>
                    <th>Description</th>
                    <th>Type de Plat</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Calories</th>
                    <th>Stock</th>
                    <th>Statut</th>
                    <th>Quota Max</th>
                    <th>Menu Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (isset($plats) && is_array($plats) && count($plats) > 0) {
                    foreach ($plats as $plat) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($plat['id_plat']); ?></td>
                            <td><?php echo htmlspecialchars($plat['nom_plat']); ?></td>
                            <td><?php echo htmlspecialchars($plat['description']); ?></td>
                            <td><?php echo htmlspecialchars($plat['type_plat']); ?></td>
                            <td><?php echo htmlspecialchars($plat['categorie']); ?></td>
                            <td><?php echo htmlspecialchars($plat['prix']); ?> Ar</td>
                            <td><?php echo htmlspecialchars($plat['calories']); ?></td>
                            <td><?php echo isset($plat['stock']) ? htmlspecialchars($plat['stock']) : 'N/A'; ?></td>
                            <td><?php echo htmlspecialchars($plat['statut']); ?></td>
                            <td><?php echo htmlspecialchars($plat['quota_max']); ?></td>
                            <td><?php echo htmlspecialchars($plat['type_menu']); ?></td>
                            <td>
                                <a href="index.php?page=dashboard_admin&action=modifier_form&id=<?php echo $plat['id_plat']; ?>" class="btn btn-edit">Modifier</a>
                                <a href="index.php?page=dashboard_admin&action=supprimer&id=<?php echo $plat['id_plat']; ?>" 
                                   class="btn btn-delete" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce plat ?')">
                                   Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="12">Aucun plat trouvé.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .form-section {
        background: #f9f9f9;
        padding: 20px;
        margin: 20px 0;
        border-radius: 5px;
    }
    
    .form-group {
        margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.btn {
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 4px;
    cursor: pointer;
    border: none;
    margin-right: 5px;
}

.btn-primary {
    background-color:rgb(25, 92, 4);
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-edit {
    background-color:  #007bff;
    color: white;
}

.btn-delete {
    background-color: #dc3545;
    color: white;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.table th,
.table td {
    position: relative;
    height : 110px;
    border: 1px solid #ddd;
    padding: 12px 0px 12px 12px;
    text-align: left;
}

.table th {
    background-color: #f2f2f2;
}

.alert {
    padding: 15px;
    margin: 20px 0;
    border-radius: 4px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>
<!-- Script de débogage à ajouter temporairement pour vérifier les valeurs -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action*="ajouter"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const typeMenu = document.getElementById('type_menu').value;
            console.log('Type menu sélectionné:', typeMenu);
            
            // Pour déboguer, vous pouvez temporairement empêcher la soumission
            // e.preventDefault();
            // alert('Type menu: ' + typeMenu);
        });
    }
});
</script>