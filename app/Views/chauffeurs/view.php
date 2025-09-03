

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-tie text-primary"></i> 
                <?= esc($chauffeur->prenom . ' ' . $chauffeur->nom) ?>
            </h1>
            <div>
                <a href="<?= site_url('chauffeurs') ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour ï¿½ la liste
                </a>
                <a href="<?= site_url('chauffeurs/edit/' . $chauffeur->id) ?>" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="<?= site_url('chauffeurs/delete/' . $chauffeur->id) ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('ï¿½tes-vous sï¿½r de vouloir supprimer ce chauffeur ?')">
                    <i class="fas fa-trash"></i> Supprimer
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Informations principales -->
            <div class="col-lg-8">
                <!-- Informations personnelles -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user"></i> Informations Personnelles
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Nom complet :</strong></td>
                                        <td><?= esc($chauffeur->prenom . ' ' . $chauffeur->nom) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>CNIE :</strong></td>
                                        <td><?= esc($chauffeur->cnie) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date de naissance :</strong></td>
                                        <td>
                                            <?= date('d/m/Y', strtotime($chauffeur->date_naissance)) ?>
                                            <span class="text-muted">(<?= $chauffeur->age ?> ans)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tï¿½lï¿½phone :</strong></td>
                                        <td>
                                            <a href="tel:<?= esc($chauffeur->telephone) ?>" class="text-decoration-none">
                                                <i class="fas fa-phone text-success"></i> <?= esc($chauffeur->telephone) ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php if ($chauffeur->telephone_urgence): ?>
                                    <tr>
                                        <td><strong>Tï¿½l. urgence :</strong></td>
                                        <td>
                                            <a href="tel:<?= esc($chauffeur->telephone_urgence) ?>" class="text-decoration-none">
                                                <i class="fas fa-phone text-warning"></i> <?= esc($chauffeur->telephone_urgence) ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($chauffeur->email): ?>
                                    <tr>
                                        <td><strong>Email :</strong></td>
                                        <td>
                                            <a href="mailto:<?= esc($chauffeur->email) ?>" class="text-decoration-none">
                                                <i class="fas fa-envelope text-info"></i> <?= esc($chauffeur->email) ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Date d'embauche :</strong></td>
                                        <td>
                                            <?= date('d/m/Y', strtotime($chauffeur->date_embauche)) ?>
                                            <div class="text-muted small">
                                                Anciennetï¿½: <?= $chauffeur->anciennete['years'] ?> an(s), <?= $chauffeur->anciennete['months'] ?> mois
                                            </div>
                                        </td>
                                    </tr>
                                    <?php if ($chauffeur->salaire_base): ?>
                                    <tr>
                                        <td><strong>Salaire de base :</strong></td>
                                        <td>
                                            <span class="h6 text-success">
                                                <?= number_format($chauffeur->salaire_base, 0, ',', ' ') ?> DH/mois
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td><strong>Statut :</strong></td>
                                        <td>
                                            <span class="badge badge-<?= $chauffeur->statut == 'actif' ? 'success' : ($chauffeur->statut == 'suspendu' ? 'danger' : 'secondary') ?> badge-lg">
                                                <?= ucfirst($chauffeur->statut) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php if ($chauffeur->adresse): ?>
                                    <tr>
                                        <td><strong>Adresse :</strong></td>
                                        <td><?= nl2br(esc($chauffeur->adresse)) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations permis -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-id-card"></i> Informations Permis de Conduire
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Numï¿½ro de permis :</strong></td>
                                        <td><?= esc($chauffeur->numero_permis) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Catï¿½gorie :</strong></td>
                                        <td>
                                            <span class="badge badge-info badge-lg">
                                                Catï¿½gorie <?= esc($chauffeur->categorie_permis) ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Date d'expiration :</strong></td>
                                        <td>
                                            <?php 
                                            $expiration_date = strtotime($chauffeur->date_expiration_permis);
                                            $today = strtotime(date('Y-m-d'));
                                            $days_left = ceil(($expiration_date - $today) / (60*60*24));
                                            ?>
                                            <?= date('d/m/Y', $expiration_date) ?>
                                            
                                            <?php if ($days_left < 0): ?>
                                                <span class="badge badge-danger ml-2">EXPIRï¿½</span>
                                            <?php elseif ($days_left <= 30): ?>
                                                <span class="badge badge-warning ml-2">Expire dans <?= $days_left ?> jour(s)</span>
                                            <?php else: ?>
                                                <span class="badge badge-success ml-2">Valide (<?= $days_left ?> jours)</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Statut permis :</strong></td>
                                        <td>
                                            <?php if ($chauffeur->permis_valide): ?>
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle"></i> Valide
                                                </span>
                                            <?php else: ?>
                                                <span class="text-danger">
                                                    <i class="fas fa-times-circle"></i> Expirï¿½
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observations -->
                <?php if ($chauffeur->observations): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-sticky-note"></i> Observations
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?= nl2br(esc($chauffeur->observations)) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Documents du chauffeur -->
                <?php if (!empty($chauffeur->documents)): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-folder"></i> Documents
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($chauffeur->documents as $document): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body p-3">
                                            <h6 class="card-title">
                                                <i class="fas fa-file-alt"></i> 
                                                <?= ucfirst($document->type_document) ?>
                                            </h6>
                                            <p class="card-text small text-muted mb-2">
                                                <?= esc($document->nom_fichier) ?>
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <?= date('d/m/Y', strtotime($document->date_upload)) ?>
                                                </small>
                                                <a href="<?= base_url($document->chemin_fichier) ?>" 
                                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-download"></i> Tï¿½lï¿½charger
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Locations rï¿½centes -->
                <?php if (!empty($chauffeur->locations_recentes)): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-route"></i> Locations Rï¿½centes
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Titre</th>
                                        <th>Client</th>
                                        <th>Statut</th>
                                        <th>Prix</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($chauffeur->locations_recentes as $location): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($location->date_debut)) ?></td>
                                            <td><?= esc($location->titre) ?></td>
                                            <td>
                                                <?php 
                                                // Rï¿½cupï¿½rer le nom du client
                                                $clientModel = model('App\Models\Clients_model');
                                                $client = $clientModel->find($location->client_id);
                                                echo $client ? esc($client->company_name ?? $client->contact_firstname . ' ' . $client->contact_lastname) : 'N/A';
                                                ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= $location->statut == 'terminee' ? 'success' : ($location->statut == 'en_cours' ? 'primary' : 'warning') ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $location->statut)) ?>
                                                </span>
                                            </td>
                                            <td><?= number_format($location->prix_total, 2, ',', ' ') ?> DH</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Photo du chauffeur -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-camera"></i> Photo
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <?php if ($chauffeur->photo): ?>
                            <img src="<?= base_url('writable/uploads/chauffeurs/' . $chauffeur->photo) ?>" 
                                 class="img-fluid rounded-circle mb-3" alt="Photo de <?= esc($chauffeur->prenom) ?>"
                                 style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="placeholder-avatar mb-3">
                                <i class="fas fa-user-circle fa-5x text-gray-300"></i>
                            </div>
                            <p class="text-muted">Aucune photo</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-tools"></i> Actions Rapides
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?= site_url('locations/add?chauffeur_id=' . $chauffeur->id) ?>" 
                               class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Nouvelle Location
                            </a>
                            
                            <a href="<?= site_url('chauffeur_payments/add?chauffeur_id=' . $chauffeur->id) ?>" 
                               class="btn btn-info btn-sm">
                                <i class="fas fa-money-bill"></i> Ajouter Paiement
                            </a>
                            
                            <a href="<?= site_url('chauffeur_documents/add?chauffeur_id=' . $chauffeur->id) ?>" 
                               class="btn btn-warning btn-sm">
                                <i class="fas fa-file-upload"></i> Ajouter Document
                            </a>

                            <?php if ($chauffeur->telephone): ?>
                            <a href="tel:<?= esc($chauffeur->telephone) ?>" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-phone"></i> Appeler
                            </a>
                            <?php endif; ?>

                            <?php if ($chauffeur->email): ?>
                            <a href="mailto:<?= esc($chauffeur->email) ?>" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-envelope"></i> Envoyer Email
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Statistiques personnelles -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-pie"></i> Statistiques
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-right">
                                    <h4 class="text-primary"><?= count($chauffeur->locations_recentes) ?></h4>
                                    <small class="text-muted">Locations rï¿½centes</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success"><?= count($chauffeur->documents) ?></h4>
                                <small class="text-muted">Documents</small>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row text-center">
                            <div class="col-12">
                                <h5 class="text-info"><?= $chauffeur->age ?> ans</h5>
                                <small class="text-muted">ï¿½ge du chauffeur</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations systï¿½me -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-secondary">
                            <i class="fas fa-info"></i> Informations Systï¿½me
                        </h6>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            <strong>ID :</strong> #<?= $chauffeur->id ?><br>
                            <strong>Crï¿½ï¿½ le :</strong> <?= date('d/m/Y H:i', strtotime($chauffeur->created_at)) ?><br>
                            <?php if ($chauffeur->updated_at): ?>
                            <strong>Modifiï¿½ le :</strong> <?= date('d/m/Y H:i', strtotime($chauffeur->updated_at)) ?><br>
                            <?php endif; ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.badge-lg {
    font-size: 0.9em;
    padding: 0.5em 0.8em;
}

.placeholder-avatar {
    color: #6c757d;
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
</style>