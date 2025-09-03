<!-- ========================================
     FICHIER : Views/vehicles/index.php
     Page principale - Liste des véhicules
     ======================================== -->

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-car text-primary"></i> Gestion des Véhicules
            </h1>
            <a href="<?= site_url('vehicles/add') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Ajouter un véhicule
            </a>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Véhicules
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $statistics['total'] ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-car fa-2x text-gray-300"></i>
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
                                    Disponibles
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $statistics['disponible'] ?>
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
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Réservés
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $statistics['reserve'] ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                    Vendus
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $statistics['vendu'] ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                <?= form_open('vehicles', ['method' => 'GET', 'class' => 'row g-3']) ?>
                    <div class="col-md-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Marque, modèle..." value="<?= $filters['search'] ?? '' ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="marque" class="form-label">Marque</label>
                        <select class="form-select" id="marque" name="marque">
                            <option value="">Toutes</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= esc($brand->marque) ?>" 
                                        <?= ($filters['marque'] ?? '') == $brand->marque ? 'selected' : '' ?>>
                                    <?= esc($brand->marque) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="carburant" class="form-label">Carburant</label>
                        <select class="form-select" id="carburant" name="carburant">
                            <option value="">Tous</option>
                            <option value="essence" <?= ($filters['carburant'] ?? '') == 'essence' ? 'selected' : '' ?>>Essence</option>
                            <option value="diesel" <?= ($filters['carburant'] ?? '') == 'diesel' ? 'selected' : '' ?>>Diesel</option>
                            <option value="hybride" <?= ($filters['carburant'] ?? '') == 'hybride' ? 'selected' : '' ?>>Hybride</option>
                            <option value="electrique" <?= ($filters['carburant'] ?? '') == 'electrique' ? 'selected' : '' ?>>Électrique</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="statut" class="form-label">Statut</label>
                        <select class="form-select" id="statut" name="statut">
                            <option value="">Tous</option>
                            <option value="disponible" <?= ($filters['statut'] ?? '') == 'disponible' ? 'selected' : '' ?>>Disponible</option>
                            <option value="reserve" <?= ($filters['statut'] ?? '') == 'reserve' ? 'selected' : '' ?>>Réservé</option>
                            <option value="vendu" <?= ($filters['statut'] ?? '') == 'vendu' ? 'selected' : '' ?>>Vendu</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                        <a href="<?= site_url('vehicles') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                <?= form_close() ?>
            </div>
        </div>

        <!-- Liste des véhicules -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Liste des véhicules (<?= count($vehicles) ?> résultat<?= count($vehicles) > 1 ? 's' : '' ?>)
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($vehicles)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-car fa-3x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-500">Aucun véhicule trouvé</h5>
                        <p class="text-gray-400">Commencez par ajouter votre premier véhicule</p>
                        <a href="<?= site_url('vehicles/add') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un véhicule
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($vehicles as $vehicle): ?>
                            <div class="col-xl-4 col-lg-6 mb-4">
                                <div class="card vehicle-card shadow-sm h-100">
                                    <!-- Image du véhicule -->
                                    <div class="vehicle-image">
                                        <?php if ($vehicle->image): ?>
                                            <img src="<?= base_url('uploads/vehicles/' . $vehicle->image) ?>" 
                                                 class="card-img-top" alt="<?= esc($vehicle->marque . ' ' . $vehicle->modele) ?>">
                                        <?php else: ?>
                                            <div class="placeholder-image">
                                                <i class="fas fa-car fa-3x"></i>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Badge statut -->
                                        <span class="badge badge-<?= $vehicle->statut == 'disponible' ? 'success' : ($vehicle->statut == 'vendu' ? 'danger' : 'warning') ?> position-absolute top-0 end-0 m-2">
                                            <?= ucfirst($vehicle->statut) ?>
                                        </span>
                                    </div>

                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <?= esc($vehicle->marque . ' ' . $vehicle->modele) ?>
                                        </h5>
                                        
                                        <div class="vehicle-details mb-3">
                                            <div class="row text-sm">
                                                <div class="col-6">
                                                    <i class="fas fa-calendar"></i> <?= $vehicle->annee ?>
                                                </div>
                                                <div class="col-6">
                                                    <i class="fas fa-tachometer-alt"></i> <?= number_format($vehicle->kilometrage) ?> km
                                                </div>
                                                <div class="col-6">
                                                    <i class="fas fa-gas-pump"></i> <?= ucfirst($vehicle->carburant) ?>
                                                </div>
                                                <div class="col-6">
                                                    <i class="fas fa-cog"></i> <?= ucfirst($vehicle->transmission) ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="price mb-3">
                                            <h4 class="text-primary font-weight-bold">
                                                <?= number_format($vehicle->prix, 0, ',', ' ') ?> €
                                            </h4>
                                        </div>

                                        <?php if ($vehicle->description): ?>
                                            <p class="card-text text-muted small">
                                                <?= strlen($vehicle->description) > 100 ? substr(esc($vehicle->description), 0, 100) . '...' : esc($vehicle->description) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="card-footer bg-transparent">
                                        <div class="btn-group w-100" role="group">
                                            <a href="<?= site_url('vehicles/view/' . $vehicle->id) ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                            <a href="<?= site_url('vehicles/edit/' . $vehicle->id) ?>" 
                                               class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            <a href="<?= site_url('vehicles/delete/' . $vehicle->id) ?>" 
                                               class="btn btn-outline-danger btn-sm"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?')">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </a>
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
.vehicle-card {
    transition: transform 0.2s ease-in-out;
}

.vehicle-card:hover {
    transform: translateY(-5px);
}

.vehicle-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.vehicle-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-image {
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.vehicle-details {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
}

.price {
    text-align: center;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    padding: 10px 0;
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
</style>