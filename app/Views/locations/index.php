<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i data-feather="map" class="icon-20"></i> Gestion des Locations
            </h1>
            <div>
                <a href="<?= site_url('locations/add') ?>" class="btn btn-primary btn-sm">
                    <i data-feather="plus" class="icon-16"></i> Nouvelle location
                </a>
                <a href="<?= site_url('locations/calendar') ?>" class="btn btn-info btn-sm">
                    <i data-feather="calendar" class="icon-16"></i> Planning
                </a>
                <button type="button" class="btn btn-success btn-sm" onclick="refreshStatistics()">
                    <i data-feather="refresh-cw" class="icon-16"></i> Actualiser
                </button>
            </div>
        </div>

        <!-- Statistiques avec bouton debug -->
        <div class="row mb-4">
            <div class="col-12 mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tableau de Bord</h5>
                    <a href="<?= site_url('locations/debug_statistics') ?>" class="btn btn-outline-secondary btn-sm" target="_blank">
                        <i data-feather="info" class="icon-16"></i> Debug Stats
                    </a>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-primary shadow h-100 py-2 cursor-pointer" onclick="filterByStatus('')">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-total"><?= $statistics['total'] ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i data-feather="map" class="icon-32 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-warning shadow h-100 py-2 cursor-pointer" onclick="filterByStatus('en_attente')">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En Attente</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-en-attente"><?= $statistics['en_attente'] ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i data-feather="clock" class="icon-32 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-success shadow h-100 py-2 cursor-pointer" onclick="filterByStatus('confirmee')">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Confirmées</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-confirmee"><?= $statistics['confirmee'] ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i data-feather="check" class="icon-32 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-info shadow h-100 py-2 cursor-pointer" onclick="filterByStatus('en_cours')">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">En Cours</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-en-cours"><?= $statistics['en_cours'] ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i data-feather="play" class="icon-32 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Aujourd'hui</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-today"><?= $statistics['today_locations'] ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i data-feather="calendar" class="icon-32 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">CA Mois</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-ca">
                                    <?= number_format($statistics['ca_mois'] ?? 0, 0, ',', ' ') ?> DH
                                </div>
                            </div>
                            <div class="col-auto">
                                <i data-feather="dollar-sign" class="icon-32 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres de recherche avancés -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i data-feather="filter" class="icon-16"></i> Critères de Recherche
                </h6>
                <div>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="toggleAdvancedFilters()">
                        <i data-feather="settings" class="icon-16"></i> Filtres Avancés
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetAllFilters()">
                        <i data-feather="x" class="icon-16"></i> Reset
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?= form_open('locations', ['method' => 'GET', 'id' => 'filterForm']) ?>
                
                <!-- Filtres de base -->
                <div class="row g-3 mb-3">
                    <div class="col-md-2">
                        <label for="client_id" class="form-label">Client</label>
                        <select class="form-select" id="client_id" name="client_id">
                            <option value="">Tous les clients</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= $client->id ?>" 
                                        <?= ($filters['client_id'] ?? '') == $client->id ? 'selected' : '' ?>>
                                    <?= esc($client->company_name ?: 'Client #' . $client->id) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="statut" class="form-label">Statut</label>
                        <select class="form-select" id="statut" name="statut">
                            <option value="">Tous</option>
                            <option value="en_attente" <?= ($filters['statut'] ?? '') == 'en_attente' ? 'selected' : '' ?>>En attente</option>
                            <option value="confirmee" <?= ($filters['statut'] ?? '') == 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                            <option value="en_cours" <?= ($filters['statut'] ?? '') == 'en_cours' ? 'selected' : '' ?>>En cours</option>
                            <option value="terminee" <?= ($filters['statut'] ?? '') == 'terminee' ? 'selected' : '' ?>>Terminée</option>
                            <option value="annulee" <?= ($filters['statut'] ?? '') == 'annulee' ? 'selected' : '' ?>>Annulée</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="type_service" class="form-label">Type Service</label>
                        <select class="form-select" id="type_service" name="type_service">
                            <option value="">Tous</option>
                            <option value="transfert" <?= ($filters['type_service'] ?? '') == 'transfert' ? 'selected' : '' ?>>Transfert</option>
                            <option value="location_journee" <?= ($filters['type_service'] ?? '') == 'location_journee' ? 'selected' : '' ?>>Location journée</option>
                            <option value="location_longue" <?= ($filters['type_service'] ?? '') == 'location_longue' ? 'selected' : '' ?>>Location longue</option>
                            <option value="evenement" <?= ($filters['type_service'] ?? '') == 'evenement' ? 'selected' : '' ?>>Événement</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="date_debut" class="form-label">Date début</label>
                        <input type="date" class="form-control" id="date_debut" name="date_debut" 
                               value="<?= $filters['date_debut'] ?? '' ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="date_fin" class="form-label">Date fin</label>
                        <input type="date" class="form-control" id="date_fin" name="date_fin" 
                               value="<?= $filters['date_fin'] ?? '' ?>">
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i data-feather="search" class="icon-16"></i> Rechercher
                        </button>
                    </div>
                </div>
                
                <!-- Filtres avancés (masqués par défaut) -->
                <div id="advanced-filters" class="row g-3" style="display: none;">
                    <div class="col-md-3">
                        <label for="vehicle_id" class="form-label">Véhicule</label>
                        <select class="form-select" id="vehicle_id" name="vehicle_id">
                            <option value="">Tous les véhicules</option>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <option value="<?= $vehicle->id ?>" 
                                        <?= ($filters['vehicle_id'] ?? '') == $vehicle->id ? 'selected' : '' ?>>
                                    <?= esc($vehicle->numero_matricule ?? $vehicle->marque . ' ' . $vehicle->modele) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="chauffeur_id" class="form-label">Chauffeur</label>
                        <select class="form-select" id="chauffeur_id" name="chauffeur_id">
                            <option value="">Tous les chauffeurs</option>
                            <?php foreach ($chauffeurs as $chauffeur): ?>
                                <option value="<?= $chauffeur->id ?>" 
                                        <?= ($filters['chauffeur_id'] ?? '') == $chauffeur->id ? 'selected' : '' ?>>
                                    <?= esc($chauffeur->prenom . ' ' . $chauffeur->nom) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="prix_min" class="form-label">Prix min (DH)</label>
                        <input type="number" class="form-control" id="prix_min" name="prix_min" 
                               value="<?= $filters['prix_min'] ?? '' ?>" step="0.01" placeholder="0.00">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="prix_max" class="form-label">Prix max (DH)</label>
                        <input type="number" class="form-control" id="prix_max" name="prix_max" 
                               value="<?= $filters['prix_max'] ?? '' ?>" step="0.01" placeholder="9999.99">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="keyword" class="form-label">Mot-clé</label>
                        <input type="text" class="form-control" id="keyword" name="keyword" 
                               value="<?= $filters['keyword'] ?? '' ?>" placeholder="Titre, lieu...">
                    </div>
                </div>
                
                <?= form_close() ?>
            </div>
        </div>

        <!-- Liste des locations (votre code existant) -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Liste des locations (<span id="results-count"><?= count($locations) ?></span> résultat<?= count($locations) > 1 ? 's' : '' ?>)
                </h6>
                <div>
                    <a href="<?= site_url('locations/export_csv?' . http_build_query($filters)) ?>" class="btn btn-success btn-sm">
                        <i data-feather="download" class="icon-16"></i> Export CSV
                    </a>
                    <a href="<?= site_url('locations/export_pdf?' . http_build_query($filters)) ?>" class="btn btn-danger btn-sm">
                        <i data-feather="file-text" class="icon-16"></i> Export PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Votre tableau existant ici -->
                <?php if (empty($locations)): ?>
                    <div class="text-center py-5">
                        <i data-feather="map" class="icon-48 text-gray-300 mb-3"></i>
                        <h5 class="text-gray-500">Aucune location trouvée</h5>
                        <p class="text-gray-400">Modifiez vos critères de recherche ou créez une nouvelle location</p>
                        <a href="<?= site_url('locations/add') ?>" class="btn btn-primary">
                            <i data-feather="plus" class="icon-16"></i> Nouvelle location
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Votre tableau existant avec les locations -->
                  <div class="table-responsive">
                        <table class="table table-bordered" id="locationsTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Client</th>
                                    <th>Date/Heure</th>
                                    <th>Trajet</th>
                                    <th>Prix</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($locations as $location): ?>
                                    <tr>
                                        <td>#<?= $location->id ?></td>
                                        <td>
                                            <strong><?= esc($location->titre) ?></strong>
                                            <br><small class="text-muted"><?= ucfirst($location->type_service) ?></small>
                                        </td>
                                        <td><?= esc($location->company_name ?: 'Client #' . $location->client_id) ?></td>
                                        <td>
                                            <div><?= date('d/m/Y', strtotime($location->date_debut)) ?></div>
                                            <small class="text-muted">
                                                <?= date('H:i', strtotime($location->date_debut)) ?> - 
                                                <?= date('H:i', strtotime($location->date_fin)) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div><i data-feather="map-pin" class="icon-14 text-success"></i> <?= esc($location->lieu_depart) ?></div>
                                                <div><i data-feather="flag" class="icon-14 text-danger"></i> <?= esc($location->lieu_arrivee) ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($location->prix_total): ?>
                                                <span class="font-weight-bold text-success">
                                                    <?= number_format($location->prix_total, 2, ',', ' ') ?> DH
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">À définir</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?= $location->statut == 'en_attente' ? 'warning' : ($location->statut == 'confirmee' ? 'success' : ($location->statut == 'en_cours' ? 'primary' : ($location->statut == 'terminee' ? 'secondary' : 'danger'))) ?>">
                                                <?= ucfirst(str_replace('_', ' ', $location->statut)) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= site_url('locations/view/' . $location->id) ?>" 
                                                   class="btn btn-outline-primary btn-sm" title="Voir">
                                                    <i data-feather="eye" class="icon-16"></i>
                                                </a>
                                                <a href="<?= site_url('locations/edit/' . $location->id) ?>" 
                                                   class="btn btn-outline-warning btn-sm" title="Modifier">
                                                    <i data-feather="edit" class="icon-16"></i>
                                                </a>
                                                <a href="<?= site_url('locations/download_pdf/' . $location->id) ?>" 
                                                   class="btn btn-outline-info btn-sm" title="PDF" target="_blank">
                                                    <i data-feather="file-text" class="icon-16"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Actualiser les statistiques
function refreshStatistics() {
    fetch('<?= site_url("locations/get_statistics") ?>')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('stat-total').textContent = data.statistics.total;
            document.getElementById('stat-en-attente').textContent = data.statistics.en_attente;
            document.getElementById('stat-confirmee').textContent = data.statistics.confirmee;
            document.getElementById('stat-en-cours').textContent = data.statistics.en_cours;
            document.getElementById('stat-today').textContent = data.statistics.today_locations;
            document.getElementById('stat-ca').textContent = new Intl.NumberFormat('fr-FR').format(data.statistics.ca_mois) + ' DH';
        }
    });
}

// Filtrer par statut en cliquant sur une carte statistique
function filterByStatus(status) {
    document.getElementById('statut').value = status;
    document.getElementById('filterForm').submit();
}

// Toggle filtres avancés
function toggleAdvancedFilters() {
    const advanced = document.getElementById('advanced-filters');
    if (advanced.style.display === 'none') {
        advanced.style.display = 'flex';
    } else {
        advanced.style.display = 'none';
    }
}

// Reset tous les filtres
function resetAllFilters() {
    window.location.href = '<?= site_url("locations") ?>';
}

// Auto-submit sur changement de filtre
document.addEventListener('DOMContentLoaded', function() {
    const filters = ['client_id', 'statut', 'type_service', 'vehicle_id', 'chauffeur_id'];
    filters.forEach(filterId => {
        document.getElementById(filterId).addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
    
    feather.replace();
});
</script>

<style>
.cursor-pointer { cursor: pointer; }
.cursor-pointer:hover { transform: translateY(-2px); transition: transform 0.2s; }
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
.border-left-secondary { border-left: 0.25rem solid #858796 !important; }
.text-xs { font-size: 0.7rem; }
.icon-48 { width: 48px; height: 48px; }
.icon-32 { width: 32px; height: 32px; }
.icon-16 { width: 16px; height: 16px; }
</style>