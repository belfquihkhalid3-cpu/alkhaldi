<!-- ========================================
     FICHIER : Views/fuel_cards/view.php
     Page de détails d'une carte carburant
     ======================================== -->

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-credit-card text-primary"></i> 
                Carte <?= esc($fuel_card->numero_serie) ?>
            </h1>
            <div>
                <a href="<?= site_url('fuel_cards') ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
                <a href="<?= site_url('fuel_cards/edit/' . $fuel_card->id) ?>" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="<?= site_url('fuel_cards/delete/' . $fuel_card->id) ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette carte ?')">
                    <i class="fas fa-trash"></i> Supprimer
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Informations principales -->
            <div class="col-lg-8">
                <!-- Informations générales -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-credit-card"></i> Informations de la Carte
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Numéro de série :</strong></td>
                                        <td class="h6 text-primary"><?= esc($fuel_card->numero_serie) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Type de carte :</strong></td>
                                        <td>
                                            <span class="badge badge-info badge-lg">
                                                <?= ucfirst($fuel_card->type_carte) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Statut :</strong></td>
                                        <td>
                                            <span class="badge badge-<?= $fuel_card->statut == 'active' ? 'success' : ($fuel_card->statut == 'bloquee' ? 'danger' : 'secondary') ?> badge-lg">
                                                <?= ucfirst($fuel_card->statut) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date de création :</strong></td>
                                        <td><?= $fuel_card->created_at ? date('d/m/Y', strtotime($fuel_card->created_at)) : 'N/A' ?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Date d'expiration :</strong></td>
                                        <td>
                                            <?php if ($fuel_card->date_expiration): ?>
                                                <?php 
                                                $expiration_date = strtotime($fuel_card->date_expiration);
                                                $today = strtotime(date('Y-m-d'));
                                                $days_left = ceil(($expiration_date - $today) / (60*60*24));
                                                ?>
                                                <?= date('d/m/Y', $expiration_date) ?>
                                                
                                                <?php if ($days_left < 0): ?>
                                                    <span class="badge badge-danger ml-2">EXPIRÉE</span>
                                                <?php elseif ($days_left <= 30): ?>
                                                    <span class="badge badge-warning ml-2">Expire dans <?= $days_left ?> jour(s)</span>
                                                <?php else: ?>
                                                    <span class="badge badge-success ml-2">Valide (<?= $days_left ?> jours)</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">Pas d'expiration</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Solde dotation :</strong></td>
                                        <td>
                                            <?php if ($fuel_card->solde_dotation > 0): ?>
                                                <span class="h6 text-success">
                                                    <?= number_format($fuel_card->solde_dotation, 2, ',', ' ') ?> DH
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">0,00 DH</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Prix par litre :</strong></td>
                                        <td>
                                            <?php if ($fuel_card->prix_litre): ?>
                                                <?= number_format($fuel_card->prix_litre, 3, ',', ' ') ?> DH
                                            <?php else: ?>
                                                <span class="text-muted">Prix marché</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assignation -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-link"></i> Assignation
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if ($fuel_card->vehicle_id): ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <i class="fas fa-car fa-3x text-primary"></i>
                                </div>
                                <div class="col-md-10">
                                    <h5>Assignée au véhicule</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Véhicule :</strong></td>
                                            <td><?= esc($fuel_card->marque . ' ' . $fuel_card->modele) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Matricule :</strong></td>
                                            <td><?= esc($fuel_card->numero_matricule) ?></td>
                                        </tr>
                                    </table>
                                    <a href="<?= site_url('vehicles/view/' . $fuel_card->vehicle_id) ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> Voir le véhicule
                                    </a>
                                </div>
                            </div>
                        <?php elseif ($fuel_card->chauffeur_id): ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <i class="fas fa-user fa-3x text-info"></i>
                                </div>
                                <div class="col-md-10">
                                    <h5>Assignée au chauffeur</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Chauffeur :</strong></td>
                                            <td><?= esc($fuel_card->chauffeur_prenom . ' ' . $fuel_card->chauffeur_nom) ?></td>
                                        </tr>
                                        <?php if ($fuel_card->chauffeur_telephone): ?>
                                        <tr>
                                            <td><strong>Téléphone :</strong></td>
                                            <td>
                                                <a href="tel:<?= esc($fuel_card->chauffeur_telephone) ?>">
                                                    <i class="fas fa-phone text-success"></i> <?= esc($fuel_card->chauffeur_telephone) ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                    <a href="<?= site_url('chauffeurs/view/' . $fuel_card->chauffeur_id) ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Voir le chauffeur
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-circle fa-3x text-success mb-3"></i>
                                <h5 class="text-success">Carte Disponible</h5>
                                <p class="text-muted">Cette carte n'est assignée à aucun véhicule ou chauffeur</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal">
                                    <i class="fas fa-link"></i> Assigner maintenant
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Historique des consommations -->
                <?php if (!empty($fuel_card->consommations_recentes)): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-gas-pump"></i> Consommations Récentes
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Véhicule</th>
                                        <th>Chauffeur</th>
                                        <th>Litres</th>
                                        <th>Montant</th>
                                        <th>Station</th>
                                        <th>KM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($fuel_card->consommations_recentes as $consommation): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i', strtotime($consommation->date_plein)) ?></td>
                                            <td>
                                                <?php 
                                                if ($consommation->vehicle_id) {
                                                    $vehicleModel = model('App\Models\Vehicles_model');
                                                    $vehicle = $vehicleModel->find($consommation->vehicle_id);
                                                    echo $vehicle ? esc($vehicle->numero_matricule ?? $vehicle->marque . ' ' . $vehicle->modele) : 'N/A';
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                if ($consommation->chauffeur_id) {
                                                    $chauffeurModel = model('App\Models\Chauffeurs_model');
                                                    $chauffeur = $chauffeurModel->find($consommation->chauffeur_id);
                                                    echo $chauffeur ? esc($chauffeur->prenom . ' ' . $chauffeur->nom) : 'N/A';
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?>
                                            </td>
                                            <td><span class="text-info font-weight-bold"><?= number_format($consommation->quantite_litre, 2, ',', ' ') ?> L</span></td>
                                            <td><span class="text-success font-weight-bold"><?= number_format($consommation->montant, 2, ',', ' ') ?> DH</span></td>
                                            <td><?= esc($consommation->station_service ?? 'N/A') ?></td>
                                            <td><?= number_format($consommation->km_compteur, 0, ',', ' ') ?> km</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?= site_url('fuel_consumption?fuel_card_id=' . $fuel_card->id) ?>" class="btn btn-outline-primary">
                                <i class="fas fa-list"></i> Voir toutes les consommations
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Actions rapides -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-tools"></i> Actions Rapides
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?= site_url('fuel_consumption/add?fuel_card_id=' . $fuel_card->id) ?>" 
                               class="btn btn-success btn-sm">
                                <i class="fas fa-gas-pump"></i> Ajouter Consommation
                            </a>
                            
                            <button type="button" class="btn btn-info btn-sm" 
                                    data-bs-toggle="modal" data-bs-target="#updateBalanceModal">
                                <i class="fas fa-money-bill"></i> Mettre à jour Solde
                            </button>
                            
                            <?php if ($fuel_card->vehicle_id || $fuel_card->chauffeur_id): ?>
                            <a href="<?= site_url('fuel_cards/unassign/' . $fuel_card->id) ?>" 
                               class="btn btn-warning btn-sm"
                               onclick="return confirm('Libérer cette carte ?')">
                                <i class="fas fa-unlink"></i> Libérer Carte
                            </a>
                            <?php else: ?>
                            <button type="button" class="btn btn-primary btn-sm" 
                                    data-bs-toggle="modal" data-bs-target="#assignModal">
                                <i class="fas fa-link"></i> Assigner Carte
                            </button>
                            <?php endif; ?>

                            <?php if ($fuel_card->statut == 'active'): ?>
                            <button type="button" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-ban"></i> Bloquer Carte
                            </button>
                            <?php else: ?>
                            <button type="button" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-check"></i> Activer Carte
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-pie"></i> Statistiques
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php 
                        $total_consommations = count($fuel_card->consommations_recentes ?? []);
                        $total_litres = 0;
                        $total_montant = 0;
                        
                        if (!empty($fuel_card->consommations_recentes)) {
                            foreach ($fuel_card->consommations_recentes as $conso) {
                                $total_litres += $conso->quantite_litre;
                                $total_montant += $conso->montant;
                            }
                        }
                        ?>
                        
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border-right">
                                    <h4 class="text-primary"><?= $total_consommations ?></h4>
                                    <small class="text-muted">Pleins récents</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-right">
                                    <h4 class="text-info"><?= number_format($total_litres, 0) ?></h4>
                                    <small class="text-muted">Litres</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <h4 class="text-success"><?= number_format($total_montant, 0) ?></h4>
                                <small class="text-muted">DH dépensés</small>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <?php if ($fuel_card->solde_dotation > 0 && $fuel_card->prix_litre > 0): ?>
                        <div class="text-center">
                            <h5 class="text-warning">
                                <?= number_format($fuel_card->solde_dotation / $fuel_card->prix_litre, 1) ?> L
                            </h5>
                            <small class="text-muted">Litres possibles avec le solde</small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Informations système -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-secondary">
                            <i class="fas fa-info"></i> Informations Système
                        </h6>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            <strong>ID :</strong> #<?= $fuel_card->id ?><br>
                            <strong>Créée le :</strong> <?= $fuel_card->created_at ? date('d/m/Y H:i', strtotime($fuel_card->created_at)) : 'N/A' ?><br>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'assignation -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assigner la carte <?= esc($fuel_card->numero_serie) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Assigner à un véhicule</h6>
                        <?= form_open('fuel_cards/assign_to_vehicle/' . $fuel_card->id) ?>
                            <select name="vehicle_id" class="form-select mb-2">
                                <option value="">Choisir un véhicule...</option>
                                <?php 
                                $vehiclesModel = model('App\Models\Vehicles_model');
                                $vehicles = $vehiclesModel->get_available_vehicles();
                                foreach ($vehicles as $vehicle): ?>
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
                        <?= form_open('fuel_cards/assign_to_chauffeur/' . $fuel_card->id) ?>
                            <select name="chauffeur_id" class="form-select mb-2">
                                <option value="">Choisir un chauffeur...</option>
                                <?php 
                                $chauffeursModel = model('App\Models\Chauffeurs_model');
                                $chauffeurs = $chauffeursModel->get_active_chauffeurs();
                                foreach ($chauffeurs as $chauffeur): ?>
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

<!-- Modal mise à jour solde -->
<div class="modal fade" id="updateBalanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mettre à jour le solde</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?= form_open('fuel_cards/update_balance/' . $fuel_card->id) ?>
                    <div class="mb-3">
                        <label for="solde_dotation" class="form-label">Nouveau solde (DH)</label>
                        <input type="number" class="form-control" id="solde_dotation" name="solde_dotation" 
                               value="<?= $fuel_card->solde_dotation ?>" step="0.01" min="0" required>
                        <div class="form-text">Solde actuel: <?= number_format($fuel_card->solde_dotation, 2, ',', ' ') ?> DH</div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<style>
.badge-lg {
    font-size: 0.9em;
    padding: 0.5em 0.8em;
}

.border-right {
    border-right: 1px solid #dee2e6;
}

.table-borderless td {
    border: none;
    padding: 0.5rem 0.75rem;
}

.table-borderless td:first-child {
    font-weight: 500;
    color: #495057;
    width: 40%;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>