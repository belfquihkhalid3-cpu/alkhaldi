<!-- ========================================
     FICHIER : Views/chauffeurs/index.php
     Page principale - Liste des chauffeurs
     ======================================== -->

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-tie text-primary"></i> Gestion des Chauffeurs
            </h1>
            <div>
                <a href="<?= site_url('chauffeurs/add') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Ajouter un chauffeur
                </a>
                <a href="<?= site_url('chauffeurs/expiring_licenses') ?>" class="btn btn-warning btn-sm">
                    <i class="fas fa-exclamation-triangle"></i> Permis expirant (<?= count($expiring_licenses) ?>)
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
                                    Total Chauffeurs
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $statistics['total'] ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                    Actifs
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $statistics['actif'] ?>
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
                                    Permis Expirant
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= $statistics['permis_expire_soon'] ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                                    Salaire Moyen
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?= number_format($statistics['salaire_moyen'], 0, ',', ' ') ?> DH
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
                <?= form_open('chauffeurs', ['method' => 'GET', 'class' => 'row g-3']) ?>
                    <div class="col-md-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Nom, prénom, CNIE..." value="<?= $filters['search'] ?? '' ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="statut" class="form-label">Statut</label>
                        <select class="form-select" id="statut" name="statut">
                            <option value="">Tous</option>
                            <option value="actif" <?= ($filters['statut'] ?? '') == 'actif' ? 'selected' : '' ?>>Actif</option>
                            <option value="inactif" <?= ($filters['statut'] ?? '') == 'inactif' ? 'selected' : '' ?>>Inactif</option>
                            <option value="suspendu" <?= ($filters['statut'] ?? '') == 'suspendu' ? 'selected' : '' ?>>Suspendu</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="categorie_permis" class="form-label">Catégorie Permis</label>
                        <select class="form-select" id="categorie_permis" name="categorie_permis">
                            <option value="">Toutes</option>
                            <?php foreach ($license_categories as $category): ?>
                                <option value="<?= esc($category->categorie_permis) ?>" 
                                        <?= ($filters['categorie_permis'] ?? '') == $category->categorie_permis ? 'selected' : '' ?>>
                                    Catégorie <?= esc($category->categorie_permis) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="salaire_min" class="form-label">Salaire Min (DH)</label>
                        <input type="number" class="form-control" id="salaire_min" name="salaire_min" 
                               placeholder="5000" value="<?= $filters['salaire_min'] ?? '' ?>">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" id="permis_expire_soon" 
                                   name="permis_expire_soon" value="1" 
                                   <?= !empty($filters['permis_expire_soon']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="permis_expire_soon">
                                Permis expirant bientôt
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                        <a href="<?= site_url('chauffeurs') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                <?= form_close() ?>
            </div>
        </div>

        <!-- Liste des chauffeurs -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Liste des chauffeurs (<?= count($chauffeurs) ?> résultat<?= count($chauffeurs) > 1 ? 's' : '' ?>)
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($chauffeurs)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-user-tie fa-3x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-500">Aucun chauffeur trouvé</h5>
                        <p class="text-gray-400">Commencez par ajouter votre premier chauffeur</p>
                        <a href="<?= site_url('chauffeurs/add') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un chauffeur
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($chauffeurs as $chauffeur): ?>
                            <div class="col-xl-4 col-lg-6 mb-4">
                                <div class="card chauffeur-card shadow-sm h-100">
                                    <!-- Photo du chauffeur -->
                                    <div class="chauffeur-photo">
                                        <?php if ($chauffeur->photo): ?>
                                          <img src="<?= base_url('uploads/chauffeurs/' . $chauffeur->photo) ?>" 
                                                 class="card-img-top" alt="<?= esc($chauffeur->prenom . ' ' . $chauffeur->nom) ?>">
                                        <?php else: ?>
                                            <div class="placeholder-photo">
                                                <i class="fas fa-user fa-3x"></i>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Badge statut -->
                                        <span class="badge badge-<?= $chauffeur->statut == 'actif' ? 'success' : ($chauffeur->statut == 'suspendu' ? 'danger' : 'secondary') ?> position-absolute top-0 end-0 m-2">
                                            <?= ucfirst($chauffeur->statut) ?>
                                        </span>

                                        <!-- Badge permis expirant -->
                                        <?php 
                                        $days_to_expire = (strtotime($chauffeur->date_expiration_permis) - strtotime(date('Y-m-d'))) / (60*60*24);
                                        if ($days_to_expire <= 30 && $days_to_expire >= 0): 
                                        ?>
                                            <span class="badge badge-warning position-absolute top-0 start-0 m-2">
                                                <i class="fas fa-exclamation-triangle"></i> Permis expire bientôt
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <?= esc($chauffeur->prenom . ' ' . $chauffeur->nom) ?>
                                        </h5>
                                        
                                        <div class="chauffeur-details mb-3">
                                            <div class="row text-sm">
                                                <div class="col-12 mb-1">
                                                    <i class="fas fa-id-card"></i> <?= esc($chauffeur->cnie) ?>
                                                </div>
                                                <div class="col-12 mb-1">
                                                    <i class="fas fa-phone"></i> <?= esc($chauffeur->telephone) ?>
                                                </div>
                                                <div class="col-6">
                                                    <i class="fas fa-certificate"></i> Cat. <?= esc($chauffeur->categorie_permis) ?>
                                                </div>
                                                <div class="col-6">
                                                    <i class="fas fa-calendar"></i> 
                                                    <?php
                                                    $age = (new DateTime())->diff(new DateTime($chauffeur->date_naissance))->y;
                                                    echo $age . ' ans';
                                                    ?>
                                                </div>
                                                <div class="col-12 mt-1">
                                                    <i class="fas fa-clock"></i> 
                                                    Embauché le <?= date('d/m/Y', strtotime($chauffeur->date_embauche)) ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <?php if ($chauffeur->salaire_base): ?>
                                            <div class="salary mb-3">
                                                <h6 class="text-success font-weight-bold">
                                                    <?= number_format($chauffeur->salaire_base, 0, ',', ' ') ?> DH/mois
                                                </h6>
                                            </div>
                                        <?php endif; ?>

                                        <div class="permis-info mb-2">
                                            <small class="text-muted">
                                                Permis expire le: 
                                                <span class="<?= $days_to_expire <= 30 ? 'text-danger font-weight-bold' : '' ?>">
                                                    <?= date('d/m/Y', strtotime($chauffeur->date_expiration_permis)) ?>
                                                </span>
                                            </small>
                                        </div>

                                        <?php if ($chauffeur->observations): ?>
                                            <p class="card-text text-muted small">
                                                <?= strlen($chauffeur->observations) > 80 ? substr(esc($chauffeur->observations), 0, 80) . '...' : esc($chauffeur->observations) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="card-footer bg-transparent">
                                        <div class="btn-group w-100" role="group">
                                            <a href="<?= site_url('chauffeurs/view/' . $chauffeur->id) ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                            <a href="<?= site_url('chauffeurs/edit/' . $chauffeur->id) ?>" 
                                               class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            <a href="<?= site_url('chauffeurs/delete/' . $chauffeur->id) ?>" 
                                               class="btn btn-outline-danger btn-sm"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce chauffeur ?')">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </a>
                                        </div>
                                        
                                        <!-- Changement de statut rapide -->
                                        <div class="mt-2">
                                            <?= form_open('chauffeurs/change_status/' . $chauffeur->id, ['class' => 'd-flex']) ?>
                                                <select name="statut" class="form-select form-select-sm me-1" onchange="this.form.submit()">
                                                    <option value="actif" <?= $chauffeur->statut == 'actif' ? 'selected' : '' ?>>Actif</option>
                                                    <option value="inactif" <?= $chauffeur->statut == 'inactif' ? 'selected' : '' ?>>Inactif</option>
                                                    <option value="suspendu" <?= $chauffeur->statut == 'suspendu' ? 'selected' : '' ?>>Suspendu</option>
                                                </select>
                                            <?= form_close() ?>
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
.chauffeur-card {
    transition: transform 0.2s ease-in-out;
}

.chauffeur-card:hover {
    transform: translateY(-5px);
}

.chauffeur-photo {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.chauffeur-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-photo {
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.chauffeur-details {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
}

.salary {
    text-align: center;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    padding: 10px 0;
}

.permis-info {
    background: #fff3cd;
    padding: 5px 10px;
    border-radius: 3px;
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
</style>