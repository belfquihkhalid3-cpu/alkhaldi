 <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i data-feather="map" class="icon-20 text-primary"></i> 
                Détails Location #<?= $location_info->id ?>
            </h1>
            <div>
                <a href="<?= site_url('locations') ?>" class="btn btn-secondary btn-sm">
                    <i data-feather="arrow-left" class="icon-16"></i> Retour à la liste
                </a>
                <a href="<?= site_url('locations/edit/' . $location_info->id) ?>" class="btn btn-warning btn-sm">
                    <i data-feather="edit" class="icon-16"></i> Modifier
                </a>
                <a href="<?= site_url('locations/download_pdf/' . $location_info->id) ?>" class="btn btn-info btn-sm" target="_blank">
                    <i data-feather="file-text" class="icon-16"></i> PDF
                </a>
                <a href="<?= site_url('locations/duplicate/' . $location_info->id) ?>" class="btn btn-success btn-sm">
                    <i data-feather="copy" class="icon-16"></i> Dupliquer
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Informations principales -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Informations de la location</h6>
                        <span class="badge badge-<?= $location_info->statut == 'en_attente' ? 'warning' : ($location_info->statut == 'confirmee' ? 'success' : ($location_info->statut == 'en_cours' ? 'primary' : ($location_info->statut == 'terminee' ? 'secondary' : 'danger'))) ?> badge-lg">
                            <?= ucfirst(str_replace('_', ' ', $location_info->statut)) ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i data-feather="info" class="icon-16"></i> Informations Générales
                                </h6>
                                
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Titre :</label>
                                    <p class="mb-2"><?= esc($location_info->titre) ?></p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Type de Service :</label>
                                    <p class="mb-2">
                                        <span class="badge badge-info">
                                            <?= ucfirst(str_replace('_', ' ', $location_info->type_service)) ?>
                                        </span>
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Client :</label>
                                    <p class="mb-2">
                                        <i data-feather="user" class="icon-16 text-muted"></i> 
                                        <?= esc($location_info->company_name ?: 'Client #' . $location_info->client_id) ?>
                                        <?php if ($location_info->phone): ?>
                                            <br><small class="text-muted">
                                                <i data-feather="phone" class="icon-14"></i> <?= $location_info->phone ?>
                                            </small>
                                        <?php endif; ?>
                                    </p>
                                </div>

                                <?php if ($location_info->prix_total): ?>
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Prix Total :</label>
                                    <p class="mb-2">
                                        <span class="h4 text-success">
                                            <?= number_format($location_info->prix_total, 2, ',', ' ') ?> DH
                                        </span>
                                    </p>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i data-feather="clock" class="icon-16"></i> Dates & Horaires
                                </h6>

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Date/Heure de Début :</label>
                                    <p class="mb-2">
                                        <i data-feather="calendar" class="icon-16 text-success"></i> 
                                        <?= date('d/m/Y à H:i', strtotime($location_info->date_debut)) ?>
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Date/Heure de Fin :</label>
                                    <p class="mb-2">
                                        <i data-feather="calendar" class="icon-16 text-danger"></i> 
                                        <?= date('d/m/Y à H:i', strtotime($location_info->date_fin)) ?>
                                    </p>
                                </div>

                                <?php 
                                $debut = new DateTime($location_info->date_debut);
                                $fin = new DateTime($location_info->date_fin);
                                $duree = $debut->diff($fin);
                                ?>
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Durée Totale :</label>
                                    <p class="mb-2">
                                        <span class="badge badge-secondary">
                                            <?= $duree->days ?> jour(s), <?= $duree->h ?> heure(s), <?= $duree->i ?> minute(s)
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Trajet -->
                        <h6 class="text-primary mb-3">
                            <i data-feather="map" class="icon-16"></i> Trajet
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-left-success">
                                    <div class="card-body py-3">
                                        <div class="d-flex align-items-center">
                                            <i data-feather="map-pin" class="icon-24 text-success mr-3"></i>
                                            <div>
                                                <div class="font-weight-bold">Lieu de Départ</div>
                                                <div class="text-muted"><?= esc($location_info->lieu_depart) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-left-danger">
                                    <div class="card-body py-3">
                                        <div class="d-flex align-items-center">
                                            <i data-feather="flag" class="icon-24 text-danger mr-3"></i>
                                            <div>
                                                <div class="font-weight-bold">Lieu d'Arrivée</div>
                                                <div class="text-muted"><?= esc($location_info->lieu_arrivee) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Assignations -->
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-users"></i> Assignations
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-car text-primary"></i> Véhicule Assigné
                                        </h6>
                                        <?php if ($location_info->vehicle_id): ?>
                                            <p class="mb-1">
                                                <strong>Matricule :</strong> <?= esc($location_info->numero_matricule) ?>
                                            </p>
                                            <p class="mb-0">
                                                <strong>Modèle :</strong> <?= esc($location_info->marque . ' ' . $location_info->modele) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i data-feather="user" class="icon-16 text-secondary"></i> Chauffeur Assigné
                                        </h6>
                                        <?php if ($location_info->chauffeur_id): ?>
                                            <p class="mb-1">
                                                <strong>Nom :</strong> <?= esc($location_info->chauffeur_prenom . ' ' . $location_info->chauffeur_nom) ?>
                                            </p>
                                            <?php if ($location_info->chauffeur_telephone): ?>
                                            <p class="mb-0">
                                                <strong>Téléphone :</strong> 
                                                <a href="tel:<?= $location_info->chauffeur_telephone ?>"><?= $location_info->chauffeur_telephone ?></a>
                                            </p>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <p class="text-muted mb-0">Aucun chauffeur assigné</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description et Observations -->
                        <?php if ($location_info->description || $location_info->observations): ?>
                        <hr>
                        <h6 class="text-primary mb-3">
                            <i data-feather="file-text" class="icon-16"></i> Notes et Observations
                        </h6>

                        <?php if ($location_info->description): ?>
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Description :</label>
                            <div class="border p-3 bg-light rounded">
                                <?= nl2br(esc($location_info->description)) ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($location_info->observations): ?>
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Observations :</label>
                            <div class="border p-3 bg-warning-light rounded">
                                <?= nl2br(esc($location_info->observations)) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar avec informations techniques -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i data-feather="info" class="icon-16"></i> Informations Système
                        </h6>
                    </div>
                    <div class="card-body">
                        <p><strong>ID Location :</strong> #<?= $location_info->id ?></p>
                        <p><strong>Créée le :</strong><br>
                           <?= date('d/m/Y à H:i', strtotime($location_info->created_at)) ?>
                        </p>
                        <?php if (isset($location_info->updated_at) && $location_info->updated_at): ?>
                        <p><strong>Modifiée le :</strong><br>
                           <?= date('d/m/Y à H:i', strtotime($location_info->updated_at)) ?>
                        </p>
                        <?php endif; ?>
                        
                        <?php if (isset($location_info->created_by_name)): ?>
                        <p><strong>Créée par :</strong><br>
                           <?= esc($location_info->created_by_name . ' ' . $location_info->created_by_lastname) ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i data-feather="settings" class="icon-16"></i> Actions Rapides
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?php if ($location_info->statut == 'en_attente'): ?>
                            <a href="#" class="btn btn-success btn-sm" onclick="changeStatus('confirmee')">
                                <i data-feather="check" class="icon-16"></i> Confirmer
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($location_info->statut == 'confirmee'): ?>
                            <a href="#" class="btn btn-primary btn-sm" onclick="changeStatus('en_cours')">
                                <i data-feather="play" class="icon-16"></i> Démarrer
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($location_info->statut == 'en_cours'): ?>
                            <a href="#" class="btn btn-secondary btn-sm" onclick="changeStatus('terminee')">
                                <i data-feather="stop-circle" class="icon-16"></i> Terminer
                            </a>
                            <?php endif; ?>
                            
                            <?php if (!in_array($location_info->statut, ['terminee', 'annulee'])): ?>
                            <a href="#" class="btn btn-danger btn-sm" onclick="changeStatus('annulee')">
                                <i data-feather="x" class="icon-16"></i> Annuler
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeStatus(newStatus) {
    if (confirm('Changer le statut de cette location ?')) {
        $.post('<?= site_url("locations/change_status/" . $location_info->id) ?>', 
               {statut: newStatus}, 
               function(response) {
                   if (response.success) {
                       location.reload();
                   } else {
                       alert('Erreur lors du changement de statut');
                   }
               }, 'json');
    }
}

// Initialiser Feather Icons
$(document).ready(function() {
    feather.replace();
});
</script>

<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
.badge-lg { font-size: 0.9em; padding: 0.5rem 0.75rem; }
.bg-warning-light { background-color: #fff3cd; }
.bg-light { background-color: #f8f9fc !important; }
</style>