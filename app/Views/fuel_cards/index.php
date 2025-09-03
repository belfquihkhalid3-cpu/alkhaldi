<!-- ========================================
     FICHIER : Views/fuel_cards/index.php
     Page principale - Liste des cartes carburant
     ======================================== -->

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-credit-card text-primary"></i> Gestion des Cartes Carburant
            </h1>
            <div>
                <a href="<?= site_url('fuel_cards/add') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Ajouter une carte
                </a>
                <a href="<?= site_url('fuel_cards/expiring_cards') ?>" class="btn btn-warning btn-sm">
                    <i class="fas fa-exclamation-triangle"></i> Cartes expirant (<?= count($expiring_cards) ?>)
                </a>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Cartes
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $statistics['total'] ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Actives
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $statistics['active'] ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Disponibles
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $statistics['available'] ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Solde Total
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($statistics['total_dotation'], 2, ',', ' ') ?> DH
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres de recherche -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filtres de recherche</h6>
            </div>
            <div class="card-body">
                <?= form_open('fuel_cards', ['method' => 'GET', 'class' => 'row g-3']) ?>
                    <div class="col-md-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Numéro série, véhicule..." value="<?= $filters['search'] ?? '' ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="type_carte" class="form-label">Type</label>
                        <select class="form-select" id="type_carte" name="type_carte">
                            <option value="">Tous</option>
                            <option value="easyone" <?= ($filters['type_carte'] ?? '') == 'easyone' ? 'selected' : '' ?>>EasyOne</option>
                            <option value="autre" <?= ($filters['type_carte'] ?? '') == 'autre' ? 'selected' : '' ?>>Autre</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="statut" class="form-label">Statut</label>
                        <select class="form-select" id="statut" name="statut">
                            <option value="">Tous</option>
                            <option value="active" <?= ($filters['statut'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($filters['statut'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            <option value="bloquee" <?= ($filters['statut'] ?? '') == 'bloquee' ? 'selected' : '' ?>>Bloquée</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="vehicle_id" class="form-label">Véhicule</label>
                        <select class="form-select" id="vehicle_id" name="vehicle_id">
                            <option value="">Tous</option>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <option value="<?= $vehicle->id ?>" 
                                        <?= ($filters['vehicle_id'] ?? '') == $vehicle->id ? 'selected' : '' ?>>
                                    <?= esc($vehicle->numero_matricule ?? $vehicle->marque . ' ' . $vehicle->modele) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" id="expire_soon" 
                                   name="expire_soon" value="1" 
                                   <?= !empty($filters['expire_soon']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="expire_soon">
                                Expire bientôt
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                        <a href="<?= site_url('fuel_cards') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                <?= form_close() ?>
            </div>
        </div>

        <!-- Liste des cartes carburant -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Liste des cartes carburant (<?= count($fuel_cards) ?> résultat<?= count($fuel_cards) > 1 ? 's' : '' ?>)
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($fuel_cards)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-credit-card fa-3x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-500">Aucune carte carburant trouvée</h5>
                        <p class="text-gray-400">Commencez par ajouter votre première carte</p>
                        <a href="<?= site_url('fuel_cards/add') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter une carte
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($fuel_cards as $card): ?>
                            <div class="col-xl-4 col-lg-6 mb-4">
                                <div class="card fuel-card shadow-sm h-100">
                                    <!-- Header carte -->
                                    <div class="card-header bg-gradient-primary text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">
                                                <i class="fas fa-credit-card"></i>
                                                <?= esc($card->numero_serie) ?>
                                            </h6>
                                            <span class="badge badge-<?= $card->statut == 'active' ? 'success' : ($card->statut == 'bloquee' ? 'danger' : 'secondary') ?>">
                                                <?= ucfirst($card->statut) ?>
                                            </span>
                                        </div>
                                        <small><?= ucfirst($card->type_carte) ?></small>
                                    </div>

                                    <div class="card-body">
                                        <!-- Informations d'assignation -->
                                        <div class="assignment-info mb-3">
                                            <?php if ($card->vehicle_id): ?>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-car text-primary me-2"></i>
                                                    <span class="text-sm">
                                                        <strong>Véhicule:</strong> 
                                                        <?= esc($card->numero_matricule ?? $card->marque . ' ' . $card->modele) ?>
                                                    </span>
                                                </div>
                                            <?php elseif ($card->chauffeur_id): ?>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-user text-info me-2"></i>
                                                    <span class="text-sm">
                                                        <strong>Chauffeur:</strong> 
                                                        <?= esc($card->chauffeur_prenom . ' ' . $card->chauffeur_nom) ?>
                                                    </span>
                                                </div>
                                            <?php else: ?>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-circle text-success me-2"></i>
                                                    <span class="text-success text-sm"><strong>Disponible</strong></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Informations financières -->
                                        <div class="financial-info mb-3">
                                            <?php if ($card->solde_dotation > 0): ?>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Solde:</span>
                                                    <span class="h6 text-success mb-0">
                                                        <?= number_format($card->solde_dotation, 2, ',', ' ') ?> DH
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($card->prix_litre): ?>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Prix/L:</span>
                                                    <span><?= number_format($card->prix_litre, 3, ',', ' ') ?> DH</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Date d'expiration -->
                                        <?php if ($card->date_expiration): ?>
                                            <div class="expiration-info mb-3">
                                                <?php 
                                                $expiration_date = strtotime($card->date_expiration);
                                                $today = strtotime(date('Y-m-d'));
                                                $days_left = ceil(($expiration_date - $today) / (60*60*24));
                                                ?>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Expire le:</span>
                                                    <span class="<?= $days_left <= 30 ? 'text-danger font-weight-bold' : '' ?>">
                                                        <?= date('d/m/Y', $expiration_date) ?>
                                                    </span>
                                                </div>
                                                <?php if ($days_left <= 30 && $days_left >= 0): ?>
                                                    <div class="alert alert-warning mt-2 py-1 px-2 text-center">
                                                        <small><i class="fas fa-exclamation-triangle"></i> Expire dans <?= $days_left ?> jour(s)</small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Actions -->
                                    <div class="card-footer bg-transparent">
                                        <div class="btn-group w-100 mb-2" role="group">
                                            <a href="<?= site_url('fuel_cards/view/' . $card->id) ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                            <a href="<?= site_url('fuel_cards/edit/' . $card->id) ?>" 
                                               class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            <button type="button" class="btn btn-outline-info btn-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#assignModal<?= $card->id ?>">
                                                <i class="fas fa-link"></i> Assigner
                                            </button>
                                        </div>

                                        <!-- Actions rapides -->
                                        <div class="d-flex justify-content-between">
                                            <?php if ($card->vehicle_id || $card->chauffeur_id): ?>
                                                <a href="<?= site_url('fuel_cards/unassign/' . $card->id) ?>" 
                                                   class="btn btn-outline-secondary btn-sm"
                                                   onclick="return confirm('Libérer cette carte ?')">
                                                    <i class="fas fa-unlink"></i> Libérer
                                                </a>
                                            <?php else: ?>
                                                <span></span>
                                            <?php endif; ?>

                                            <a href="<?= site_url('fuel_cards/delete/' . $card->id) ?>" 
                                               class="btn btn-outline-danger btn-sm"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette carte ?')">
                                                <i class="fas fa-trash"></i>Supprimer
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal d'assignation -->
                                <div class="modal fade" id="assignModal<?= $card->id ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Assigner la carte <?= esc($card->numero_serie) ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Assigner à un véhicule</h6>
                                                        <?= form_open('fuel_cards/assign_to_vehicle/' . $card->id) ?>
                                                            <select name="vehicle_id" class="form-select mb-2">
                                                                <option value="">Choisir un véhicule...</option>
                                                                <?php foreach ($vehicles as $vehicle): ?>
                                                                    <option value="<?= $vehicle->id ?>">
                                                                        <?= esc($vehicle->numero_matricule ?? $vehicle->marque . ' ' . $vehicle->modele) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                                <i class="fas fa-car"></i> Assigner au véhicule
                                                            </button>
                                                        <?= form_close() ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Assigner à un chauffeur</h6>
                                                        <?= form_open('fuel_cards/assign_to_chauffeur/' . $card->id) ?>
                                                            <select name="chauffeur_id" class="form-select mb-2">
                                                                <option value="">Choisir un chauffeur...</option>
                                                                <?php foreach ($chauffeurs as $chauffeur): ?>
                                                                    <option value="<?= $chauffeur->id ?>">
                                                                        <?= esc($chauffeur->prenom . ' ' . $chauffeur->nom) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <button type="submit" class="btn btn-info btn-sm w-100">
                                                                <i class="fas fa-user"></i> Assigner au chauffeur
                                                            </button>
                                                        <?= form_close() ?>
                                                    </div>
                                                </div>
                                            </div>
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
</div>

<style>
.fuel-card {
    transition: transform 0.2s ease-in-out;
    border-left: 4px solid #007bff;
}

.fuel-card:hover {
    transform: translateY(-5px);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.assignment-info {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    border-left: 3px solid #007bff;
}

.financial-info {
    background: #e8f5e8;
    padding: 10px;
    border-radius: 5px;
}

.expiration-info {
    background: #fff3cd;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ffeaa7;
}

.card-footer .btn-group .btn {
    border-radius: 0;
}

.card-footer .btn-group .btn:first-child {
    border-radius: 0.25rem 0 0 0.25rem;
}

.card-footer .btn-group .btn:last-child {
    border-radius: 0 0.25rem 0.25rem 0;
}

.text-sm {
    font-size: 0.875rem;
}
</style>