<div id="page-content" class="page-wrapper clearfix">
    
    <!-- En-tête avec statistiques -->
    <div class="page-title clearfix">
        <h1>🚗 Gestion des Véhicules</h1>
        <div class="title-button-group">
            <a href="<?= site_url('vehicles/add') ?>" class="btn btn-primary">
                ➕ Nouveau Véhicule
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0"><?php echo $statistics['total'] ?? 0; ?></h4>
                    <small>Total</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0"><?php echo $statistics['disponible'] ?? 0; ?></h4>
                    <small>Disponibles</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0"><?php echo $statistics['reserve'] ?? 0; ?></h4>
                    <small>Réservés</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0"><?php echo $statistics['vendu'] ?? 0; ?></h4>
                    <small>Vendus</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">15</h4>
                    <small>Valeur Totale</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-dark text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0"><?php echo $statistics['moyenne_km'] ?? 0; ?> km</h4>
                    <small>Km Moyen</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">🔍 Filtres de Recherche</h6>
        </div>
        <div class="card-body">
            <?php echo form_open(get_uri('vehicles'), ['method' => 'GET', 'class' => 'row g-3']); ?>
                <div class="col-md-2">
                    <label for="marque_filter">🏷️ Marque :</label>
                    <?php
                    $marques_dropdown = ["" => "- Toutes -"];
                    if (isset($marques_list)) {
                        foreach ($marques_list as $marque) {
                            $marques_dropdown[$marque] = $marque;
                        }
                    }
                    echo form_dropdown("marque", $marques_dropdown, $filters['marque'] ?? "", 
                        "class='form-control select2' id='marque_filter'");
                    ?>
                </div>
                
                <div class="col-md-2">
                    <label for="carburant_filter">⛽ Carburant :</label>
                    <?php
                    echo form_dropdown("carburant", [
                        "" => "- Tous -",
                        "essence" => "⛽ Essence",
                        "diesel" => "🛢️ Diesel",
                        "hybride" => "🔋 Hybride",
                        "electrique" => "⚡ Électrique"
                    ], $filters['carburant'] ?? "", "class='form-control select2' id='carburant_filter'");
                    ?>
                </div>
                
                <div class="col-md-2">
                    <label for="statut_filter">📊 Statut :</label>
                    <?php
                    echo form_dropdown("statut", [
                        "" => "- Tous -",
                        "disponible" => "✅ Disponible",
                        "reserve" => "⏳ Réservé",
                        "vendu" => "✔️ Vendu"
                    ], $filters['statut'] ?? "", "class='form-control select2' id='statut_filter'");
                    ?>
                </div>
                
                <div class="col-md-2">
                    <label for="prix_min">💰 Prix Min :</label>
                    <?php
                    echo form_input([
                        "id" => "prix_min",
                        "name" => "prix_min",
                        "value" => $filters['prix_min'] ?? '',
                        "class" => "form-control",
                        "type" => "number",
                        "placeholder" => "0"
                    ]);
                    ?>
                </div>
                
                <div class="col-md-2">
                    <label for="prix_max">💰 Prix Max :</label>
                    <?php
                    echo form_input([
                        "id" => "prix_max",
                        "name" => "prix_max",
                        "value" => $filters['prix_max'] ?? '',
                        "class" => "form-control",
                        "type" => "number",
                        "placeholder" => "999999"
                    ]);
                    ?>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">🔍 Filtrer</button>
                    <a href="<?php echo get_uri('vehicles'); ?>" class="btn btn-secondary">🔄 Reset</a>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <!-- Liste des véhicules -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">📋 Liste des Véhicules (<?php echo count($vehicles ?? []); ?> résultat<?php echo count($vehicles ?? []) > 1 ? 's' : ''; ?>)</h6>
        </div>
        <div class="card-body">
            <?php if (empty($vehicles)): ?>
                <div class="text-center py-5">
                    <i class="fa fa-car fa-5x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun véhicule trouvé</h5>
                    <p class="text-muted">Commencez par ajouter votre premier véhicule</p>
                    <a href="<?= site_url('vehicles/add') ?>" class="btn btn-primary">
                        ➕ Ajouter un véhicule
                    </a>
                </div>
            <?php else: ?>
            <div class="row">
    <?php foreach ($vehicles as $vehicle): ?>
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card vehicle-card shadow-sm h-100 border-0">
                <!-- Image du véhicule avec overlay -->
                <div class="vehicle-image-container position-relative overflow-hidden">
                    <?php if (!empty($vehicle->image)): ?>
                        <img src="<?= base_url('uploads/vehicles/' . $vehicle->image) ?>" 
                             class="vehicle-img" 
                             alt="<?= esc($vehicle->marque . ' ' . $vehicle->modele) ?>"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="placeholder-image d-none">
                            <i class="fas fa-car fa-4x"></i>
                            <p class="mt-2 mb-0">Image non disponible</p>
                        </div>
                    <?php else: ?>
                        <div class="placeholder-image">
                            <i class="fas fa-car fa-4x"></i>
                            <p class="mt-2 mb-0">Aucune image</p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Badge statut avec animation -->
                    <?php
                    $statut_config = [
                        'disponible' => ['class' => 'success', 'icon' => '✅', 'text' => 'Disponible'],
                        'reserve' => ['class' => 'warning', 'icon' => '⏳', 'text' => 'Réservé'],
                        'vendu' => ['class' => 'danger', 'icon' => '✔️', 'text' => 'Vendu']
                    ];
                    $config = $statut_config[$vehicle->statut] ?? ['class' => 'secondary', 'icon' => '❓', 'text' => 'Inconnu'];
                    ?>
                    <div class="statut-badge badge-<?= $config['class'] ?>">
                        <?= $config['icon'] ?> <?= $config['text'] ?>
                    </div>
                    
                    <!-- Overlay pour les actions rapides -->
                    <div class="image-overlay">
                        <div class="overlay-actions">
                            <a href="<?= site_url('vehicles/view/' . $vehicle->id) ?>" 
                               class="btn btn-light btn-sm rounded-circle" title="Voir détails">
                                <i class="fas fa-eye">🔍</i>
                            </a>
                            <a href="<?= site_url('vehicles/edit/' . $vehicle->id) ?>" 
                               class="btn btn-light btn-sm rounded-circle" title="Modifier">
                                <i class="fas fa-edit">🔄</i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 d-flex flex-column">
                    <!-- En-tête avec marque/modèle et année -->
                    <div class="vehicle-header mb-3">
                        <h5 class="vehicle-title mb-1">
                            <strong><?= esc($vehicle->marque) ?></strong>
                            <?= esc($vehicle->modele) ?>
                        </h5>
                        <div class="vehicle-year text-muted">
                            <i class="fas fa-calendar-alt"></i> Année <?= $vehicle->annee ?>
                        </div>
                    </div>
                    
                    <!-- Prix avec mise en valeur -->
                    <div class="vehicle-price mb-3">
                        <div class="price-tag">
                            <span class="price-amount"><?= number_format($vehicle->prix, 0, ',', ' ') ?></span>
                            <span class="price-currency">€</span>
                        </div>
                    </div>
                    
                    <!-- Caractéristiques principales -->
                    <div class="vehicle-features mb-3 flex-grow-1">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="feature-item">
                                    <i class="fas fa-road feature-icon"></i>
                                    <div class="feature-content">
                                        <small class="feature-label">Kilométrage</small>
                                        <div class="feature-value"><?= number_format($vehicle->kilometrage) ?> km</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="feature-item">
                                    <?php
                                    $carburant_config = [
                                        'essence' => ['icon' => 'fas fa-gas-pump', 'color' => '#ff6b6b'],
                                        'diesel' => ['icon' => 'fas fa-oil-can', 'color' => '#4ecdc4'],
                                        'hybride' => ['icon' => 'fas fa-leaf', 'color' => '#45b7d1'],
                                        'electrique' => ['icon' => 'fas fa-bolt', 'color' => '#f9ca24']
                                    ];
                                    $carb_config = $carburant_config[$vehicle->carburant] ?? ['icon' => 'fas fa-gas-pump', 'color' => '#6c757d'];
                                    ?>
                                    <i class="<?= $carb_config['icon'] ?> feature-icon" style="color: <?= $carb_config['color'] ?>"></i>
                                    <div class="feature-content">
                                        <small class="feature-label">Carburant</small>
                                        <div class="feature-value"><?= ucfirst($vehicle->carburant) ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="feature-item">
                                    <i class="fas fa-cog feature-icon"></i>
                                    <div class="feature-content">
                                        <small class="feature-label">Transmission</small>
                                        <div class="feature-value"><?= ucfirst($vehicle->transmission) ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="feature-item">
                                    <i class="fas fa-palette feature-icon"></i>
                                    <div class="feature-content">
                                        <small class="feature-label">Couleur</small>
                                        <div class="feature-value"><?= ucfirst($vehicle->couleur) ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description si disponible -->
                    <?php if (!empty($vehicle->description)): ?>
                        <div class="vehicle-description mb-3">
                            <p class="description-text">
                                <?= strlen($vehicle->description) > 80 ? 
                                    substr(esc($vehicle->description), 0, 80) . '...' : 
                                    esc($vehicle->description) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Actions principales -->
                <div class="card-footer vehicle-actions">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group flex-grow-1" role="group">
                            <a href="<?= site_url('vehicles/view/' . $vehicle->id) ?>" 
                               class="btn btn-primary btn-action flex-fill">
                                <i class="fas fa-eye me-1"></i> Détails
                            </a>
                            <a href="<?= site_url('vehicles/edit/' . $vehicle->id) ?>" 
                               class="btn btn-outline-primary btn-action flex-fill">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                        </div>
                        
                        <button type="button" class="btn btn-outline-danger btn-action ms-2" 
                                onclick="confirmDelete(<?= $vehicle->id ?>, '<?= addslashes(esc($vehicle->marque . ' ' . $vehicle->modele)) ?>')"
                                title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Card principale */
.vehicle-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
    background: #fff;
}

.vehicle-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

/* Container image */
.vehicle-image-container {
    height: 220px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
}

.vehicle-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.vehicle-card:hover .vehicle-img {
    transform: scale(1.05);
}

.placeholder-image {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: rgba(255, 255, 255, 0.8);
    text-align: center;
}

/* Badge statut */
.statut-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
    animation: pulse 2s infinite;
}

.badge-success {
    background: rgba(40, 167, 69, 0.9);
    color: white;
}

.badge-warning {
    background: rgba(255, 193, 7, 0.9);
    color: #212529;
}

.badge-danger {
    background: rgba(220, 53, 69, 0.9);
    color: white;
}

/* Overlay pour actions rapides */
.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.vehicle-card:hover .image-overlay {
    opacity: 1;
}

.overlay-actions {
    display: flex;
    gap: 10px;
}

.overlay-actions .btn {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    border: none;
}

/* En-tête véhicule */
.vehicle-header {
    border-bottom: 1px solid #f8f9fa;
    padding-bottom: 10px;
}

.vehicle-title {
    font-size: 1.1rem;
    color: #2c3e50;
    margin-bottom: 5px;
    line-height: 1.3;
}

.vehicle-year {
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Prix */
.vehicle-price {
    text-align: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    margin: 0 -1rem;
    padding: 15px;
    border-radius: 10px;
    color: white;
}

.price-tag {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 5px;
}

.price-amount {
    font-size: 1.8rem;
    font-weight: 700;
}

.price-currency {
    font-size: 1.2rem;
    font-weight: 500;
    opacity: 0.9;
}

/* Caractéristiques */
.vehicle-features {
    padding: 10px 0;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
}

.feature-item:last-child {
    border-bottom: none;
}

.feature-icon {
    width: 20px;
    color: #6c757d;
    text-align: center;
}

.feature-content {
    flex: 1;
    min-width: 0;
}

.feature-label {
    color: #6c757d;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: block;
    margin-bottom: 2px;
}

.feature-value {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.85rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Description */
.vehicle-description {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
    margin: 0 -0.5rem;
}

.description-text {
    margin: 0;
    font-size: 0.85rem;
    color: #6c757d;
    line-height: 1.4;
    font-style: italic;
}

/* Actions */
.vehicle-actions {
    background: #fff;
    border-top: 1px solid #e9ecef;
    padding: 15px 20px;
}

.btn-action {
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.85rem;
    padding: 8px 12px;
    transition: all 0.2s ease;
}

.btn-action:hover {
    transform: translateY(-1px);
}

.btn-group .btn-action:not(:last-child) {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.btn-group .btn-action:not(:first-child) {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-left: 1px solid rgba(0, 0, 0, 0.125);
}

/* Animations */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.8; }
    100% { opacity: 1; }
}

/* Responsive */
@media (max-width: 768px) {
    .vehicle-price {
        margin: 0 -0.5rem;
    }
    
    .price-amount {
        font-size: 1.5rem;
    }
    
    .vehicle-actions {
        padding: 10px 15px;
    }
    
    .btn-action {
        font-size: 0.8rem;
        padding: 6px 8px;
    }
}
</style>



<script>
function confirmDelete(vehicleId, vehicleName) {
    if (confirm('Êtes-vous sûr de vouloir supprimer le véhicule "' + vehicleName + '" ?')) {
        window.location.href = "<?= site_url('vehicles/delete/') ?>" + vehicleId;
    }
}

// Initialiser Select2 si disponible
$(document).ready(function() {
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    }
});
</script>
