<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérification si l'utilisateur est connecté comme étudiant
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'etudiant') {
    header("Location: index.php?page=login");
    exit();
}
?>

<div class="student-menu-container">
    <div class="menu-header">
        <h1>Menu <?php echo htmlspecialchars($type_service); ?></h1>
        <p class="menu-subtitle">Découvrez nos délicieux plats pour votre <?php echo htmlspecialchars(strtolower($type_service)); ?></p>
    </div>

    <?php if (isset($plats) && is_array($plats) && count($plats) > 0): ?>
        <div class="plats-grid">
            <?php 
            // Organiser les plats par type de plat
            $plats_organises = array(
                'Entree' => array(),
                'Principale' => array(),
                'Resistance' => array(),
                'dessert' => array()
            );
            
            foreach ($plats as $plat) {
                $type_plat_key = $plat['type_plat'];
                // Assurez-vous que la clé existe avant d'ajouter
                if (array_key_exists($type_plat_key, $plats_organises)) {
                    $plats_organises[$type_plat_key][] = $plat;
                } else {
                    // Si le type n'existe pas, l'ajouter à "Principale" par défaut
                    $plats_organises['Principale'][] = $plat;
                }
            }
            
            // Afficher les plats par catégorie
            foreach ($plats_organises as $type_plat_category => $plats_type):
                if (count($plats_type) > 0):
            ?>
                <div class="type-plat-section">
                    <h2 class="type-plat-title">
                        <?php 
                        if ($type_plat_category == 'Entree') {
                            echo 'Entrées';
                        } elseif ($type_plat_category == 'Principale') {
                            echo 'Plats Principaux';
                        } elseif ($type_plat_category == 'Resistance') {
                            echo 'Plats de Résistance';
                        } elseif ($type_plat_category == 'dessert') {
                            echo 'Desserts';
                        } else {
                            echo htmlspecialchars(ucfirst($type_plat_category));
                        }
                        ?>
                    </h2>
                    
                    <div class="plats-row">
                        <?php foreach ($plats_type as $plat): ?>
                            <div class="plat-card">
                                <div class="plat-image">
                                    <?php 
                                    $image_path = 'public/assets/images/default_plats.jpg';
                                    if (!empty($plat['image']) && $plat['image'] != 'default_plats.jpg') {
                                        $image_path = 'public/assets/images/plats/' . htmlspecialchars($plat['image']);
                                    }
                                    ?>
                                    <img src="<?php echo $image_path; ?>" 
                                         alt="<?php echo htmlspecialchars($plat['nom_plat']); ?>">
                                </div>
                                
                                <div class="plat-content">
                                    <h3 class="plat-nom"><?php echo htmlspecialchars($plat['nom_plat']); ?></h3>
                                    
                                    <?php if (!empty($plat['description'])): ?>
                                        <p class="plat-description"><?php echo htmlspecialchars($plat['description']); ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="plat-details">
                                        <div class="plat-info">
                                            <span class="plat-prix"><?php echo number_format($plat['prix'], 0); ?> Ar</span>
                                            <?php if (isset($plat['calories']) && $plat['calories'] > 0): ?>
                                                <span class="plat-calories"><?php echo htmlspecialchars($plat['calories']); ?> cal</span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="plat-badges">
                                            <span class="badge badge-categorie badge-<?php echo htmlspecialchars($plat['categorie']); ?>">
                                                <?php echo htmlspecialchars(ucfirst($plat['categorie'])); ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <?php 
                                    // Calculer les places disponibles sans opérateur ternaire ni coalesce
                                    $quota_max_display = 0;
                                    $quota_reserve_display = 0;
                                    
                                    if (isset($plat['quota_max_menu']) && $plat['quota_max_menu'] > 0) {
                                        $quota_max_display = $plat['quota_max_menu'];
                                        if (isset($plat['quota_reserve'])) {
                                            $quota_reserve_display = $plat['quota_reserve'];
                                        }
                                    } elseif (isset($plat['quota_max']) && $plat['quota_max'] > 0) {
                                        $quota_max_display = $plat['quota_max'];
                                        if (isset($plat['quota_reserver'])) {
                                            $quota_reserve_display = $plat['quota_reserver'];
                                        }
                                    }
                                    
                                    $places_disponibles = $quota_max_display - $quota_reserve_display;
                                    ?>
                                    
                                    <?php if ($quota_max_display > 0): ?>
                                        <div class="plat-quota">
                                            <span class="quota-info">
                                                Places disponibles: <?php echo htmlspecialchars($places_disponibles); ?>/<?php echo htmlspecialchars($quota_max_display); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="plat-actions">
                                        <?php 
                                        $is_available = false;
                                        if ($quota_max_display == 0) { // Si pas de quota défini, toujours disponible
                                            $is_available = true;
                                        } elseif ($places_disponibles > 0) { // Si quota défini et places disponibles
                                            $is_available = true;
                                        }
                                        
                                        if ($is_available): ?>
                                            <!-- Formulaire pour ajouter au panier -->
                                            <form method="post" action="index.php?page=add_to_cart" class="add-cart-form">
                                                <?php 
                                                $id_to_use = 0;
                                                if (isset($plat['id_menu_plat']) && $plat['id_menu_plat'] > 0) {
                                                    $id_to_use = $plat['id_menu_plat'];
                                                } else {
                                                    $id_to_use = $plat['id_plat'];
                                                }
                                                ?>
                                                <input type="hidden" name="id_menu_plat" value="<?php echo htmlspecialchars($id_to_use); ?>">
                                                <div class="quantity-selector">
                                                    <label for="quantite_<?php echo htmlspecialchars($plat['id_plat']); ?>">Quantité:</label>
                                                    <select name="quantite" id="quantite_<?php echo htmlspecialchars($plat['id_plat']); ?>">
                                                        <?php 
                                                        $max_qty_selection = 5; // Quantité maximale par défaut pour la sélection
                                                        if ($quota_max_display > 0) {
                                                            if ($places_disponibles < $max_qty_selection) {
                                                                $max_qty_selection = $places_disponibles;
                                                            }
                                                        }
                                                        
                                                        // S'assurer que la quantité minimale est 1 si des places sont disponibles
                                                        if ($max_qty_selection < 1 && $places_disponibles > 0) {
                                                            $max_qty_selection = 1;
                                                        } elseif ($places_disponibles <= 0) {
                                                            $max_qty_selection = 0; // Aucune option si pas de place
                                                        }

                                                        for ($i = 1; $i <= $max_qty_selection; $i++):
                                                        ?>
                                                            <option value="<?php echo htmlspecialchars($i); ?>"><?php echo htmlspecialchars($i); ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                                <?php if ($max_qty_selection > 0): ?>
                                                    <button type="submit" class="btn btn-ajouter-panier">
                                                        Ajouter au panier
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-indisponible" disabled>
                                                        Complet
                                                    </button>
                                                <?php endif; ?>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-indisponible" disabled>
                                                Complet
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php 
                endif;
            endforeach; 
            ?>
        </div>
    <?php else: ?>
        <div class="no-plats">
            <div class="no-plats-content">
                <h3>Aucun plat disponible</h3>
                <p>Il n'y a actuellement aucun plat disponible pour le <?php echo htmlspecialchars(strtolower($type_service)); ?>.</p>
                <p>Veuillez revenir plus tard ou choisir un autre type de menu.</p>
                
                <!-- Debug info -->
                <div class="debug-info" style="margin-top: 20px; padding: 10px; background: #f8f9fa; border-radius: 5px; font-size: 12px;">
                    <strong>Debug Info:</strong><br>
                    Type service affiché: <?php echo isset($type_service) ? htmlspecialchars($type_service) : 'non défini'; ?><br>
                    Type menu DB utilisé: <?php echo isset($type_menu_db) ? htmlspecialchars($type_menu_db) : 'non défini'; ?><br>
                    Nombre de plats reçus: <?php echo isset($plats) ? count($plats) : 'non défini'; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* ... (votre CSS existant) ... */
.student-menu-container {
    max-width: 1200px;
    padding: 20px;
}

.menu-header {
    text-align: center;
    margin-bottom: 40px;
}

.menu-header h1 {
    color: #333;
    margin-bottom: 10px;
    font-size: 2.5rem;
}

.menu-subtitle {
    color: #666;
    font-size: 1.1rem;
}

.type-plat-section {
    margin-bottom: 50px;
}

.type-plat-title {
    color: #2c3e50;
    border-bottom: 3px solid #3498db;
    padding-bottom: 10px;
    margin-bottom: 30px;
    font-size: 1.8rem;
}

.plats-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 25px;
}

.plat-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.plat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.plat-image {
    height: 200px;
    overflow: hidden;
}

.plat-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.plat-content {
    padding: 20px;
}

.plat-nom {
    color: #2c3e50;
    margin-bottom: 10px;
    font-size: 1.3rem;
    font-weight: 600;
}

.plat-description {
    color: #666;
    margin-bottom: 15px;
    line-height: 1.5;
    font-size: 0.95rem;
}

.plat-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.plat-info {
    display: flex;
    gap: 15px;
    align-items: center;
}

.plat-prix {
    font-weight: bold;
    color: #27ae60;
    font-size: 1.1rem;
}

.plat-calories {
    color: #f39c12;
    font-size: 0.9rem;
}

.plat-badges {
    display: flex;
    gap: 5px;
}

.badge {
    padding: 4px 8px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.badge-viande { 
    background-color: #e74c3c; 
    color: white; 
}

.badge-poisson { 
    background-color: #3498db; 
    color: white; 
}

.badge-vegetarien { 
    background-color: #27ae60; 
    color: white; 
}

.badge-vegan { 
    background-color: #9b59b6; 
    color: white; 
}

.plat-quota {
    margin-bottom: 15px;
}

.quota-info {
    font-size: 0.9rem;
    color: #7f8c8d;
}

.plat-actions {
    text-align: center;
}

.add-cart-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.quantity-selector {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.quantity-selector label {
    font-size: 0.9rem;
    color: #666;
}

.quantity-selector select {
    padding: 5px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.3s ease;
    font-size: 0.9rem;
}

.btn-ajouter-panier {
    background-color: #3498db;
    color: white;
}

.btn-ajouter-panier:hover {
    background-color: #2980b9;
}

.btn-indisponible {
    background-color: #95a5a6;
    color: white;
    cursor: not-allowed;
}

.no-plats {
    text-align: center;
    padding: 60px 20px;
}

.no-plats-content h3 {
    color: #7f8c8d;
    margin-bottom: 15px;
    font-size: 1.5rem;
}

.no-plats-content p {
    color: #95a5a6;
    margin-bottom: 10px;
}

.debug-info {
    text-align: left;
    max-width: 400px;
    margin: 0 auto;
}

@media (max-width: 768px) {
    .plats-row {
        grid-template-columns: 1fr;
    }
    
    .menu-header h1 {
        font-size: 2rem;
    }
    
    .type-plat-title {
        font-size: 1.5rem;
    }
    
    .plat-details {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .quantity-selector {
        flex-direction: column;
        gap: 5px;
    }
}
</style>
