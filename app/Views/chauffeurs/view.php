<?php
/**
 * Vue détaillée d'un chauffeur
 * app/Views/chauffeurs/view.php
 */
?>

<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
      

        <div class="col-sm-9 col-lg-12">
            <!-- En-tête -->
            <div class="page-title clearfix">
                <h4>
                    <i data-feather="user" class="icon-24"></i>
                    <?= esc($chauffeur->prenom . ' ' . $chauffeur->nom) ?>
                </h4>
                <div class="title-button-group">
                    <a href="<?= get_uri('chauffeurs') ?>" class="btn btn-default">
                        <i data-feather="arrow-left" class="icon-16"></i> Retour
                    </a>
                    <?php echo modal_anchor(get_uri("chauffeurs/modal_form"), "<i data-feather='edit-2' class='icon-16'></i> Modifier", array("class" => "btn btn-info", "data-post-id" => $chauffeur->id)); ?>
                    <button onclick="deleteChauffeur(<?= $chauffeur->id ?>)" class="btn btn-danger">
                        <i data-feather="trash-2" class="icon-16"></i> Supprimer
                    </button>
                </div>
            </div>

            <div class="row">
                <!-- Colonne gauche - Informations personnelles -->
                <div class="col-lg-8">
                    <!-- Informations personnelles -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i data-feather="user" class="icon-20"></i>
                                Informations Personnelles
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Nom complet :</td>
                                            <td><?= esc($chauffeur->prenom . ' ' . $chauffeur->nom) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">CNIE :</td>
                                            <td><?= esc($chauffeur->cnie ?: 'Non renseigné') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Téléphone :</td>
                                            <td>
                                                <?php if ($chauffeur->telephone): ?>
                                                    <a href="tel:<?= $chauffeur->telephone ?>"><?= esc($chauffeur->telephone) ?></a>
                                                <?php else: ?>
                                                    Non renseigné
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Téléphone urgence :</td>
                                            <td>
                                                <?php if ($chauffeur->telephone_urgence): ?>
                                                    <a href="tel:<?= $chauffeur->telephone_urgence ?>"><?= esc($chauffeur->telephone_urgence) ?></a>
                                                <?php else: ?>
                                                    Non renseigné
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Email :</td>
                                            <td>
                                                <?php if ($chauffeur->email): ?>
                                                    <a href="mailto:<?= $chauffeur->email ?>"><?= esc($chauffeur->email) ?></a>
                                                <?php else: ?>
                                                    Non renseigné
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Date de naissance :</td>
                                            <td>
                                                <?php if ($chauffeur->date_naissance): ?>
                                                    <?= date('d/m/Y', strtotime($chauffeur->date_naissance)) ?>
                                                    <small class="text-muted">(<?= date_diff(date_create($chauffeur->date_naissance), date_create('today'))->y ?> ans)</small>
                                                <?php else: ?>
                                                    Non renseigné
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Date d'embauche :</td>
                                            <td>
                                                <?php if ($chauffeur->date_embauche): ?>
                                                    <?= date('d/m/Y', strtotime($chauffeur->date_embauche)) ?>
                                                    <small class="text-muted">(<?= date_diff(date_create($chauffeur->date_embauche), date_create('today'))->y ?> ans d'ancienneté)</small>
                                                <?php else: ?>
                                                    Non renseigné
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Statut :</td>
                                            <td>
                                                <span class="status-badge status-<?= $chauffeur->statut ?>">
                                                    <?= ucfirst($chauffeur->statut) ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Salaire base :</td>
                                            <td>
                                                <?php if ($chauffeur->salaire_base): ?>
                                                    <?= number_format($chauffeur->salaire_base, 2) ?> MAD
                                                <?php else: ?>
                                                    Non renseigné
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <?php if ($chauffeur->adresse): ?>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="fw-bold">Adresse :</label>
                                            <p class="text-muted"><?= nl2br(esc($chauffeur->adresse)) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Informations permis -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i data-feather="credit-card" class="icon-20"></i>
                                Permis de Conduire
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Numéro de permis :</td>
                                            <td><?= esc($chauffeur->numero_permis ?: 'Non renseigné') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Catégorie :</td>
                                            <td>
                                                <?php if ($chauffeur->categorie_permis): ?>
                                                    <span class="badge bg-info"><?= esc($chauffeur->categorie_permis) ?></span>
                                                <?php else: ?>
                                                    Non renseigné
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Date d'expiration :</td>
                                            <td>
                                                <?php if ($chauffeur->date_expiration_permis): ?>
                                                    <?php
                                                    $expiration = strtotime($chauffeur->date_expiration_permis);
                                                    $aujourdhui = time();
                                                    $jours_restants = floor(($expiration - $aujourdhui) / 86400);
                                                    ?>
                                                    <?= date('d/m/Y', $expiration) ?>
                                                    <?php if ($jours_restants < 0): ?>
                                                        <span class="badge bg-danger">Expiré</span>
                                                    <?php elseif ($jours_restants < 30): ?>
                                                        <span class="badge bg-warning">Expire dans <?= $jours_restants ?> jours</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Valide</span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    Non renseigné
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
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i data-feather="message-square" class="icon-20"></i>
                                    Observations
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted"><?= nl2br(esc($chauffeur->observations)) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Colonne droite - Statistiques et actions -->
                <div class="col-lg-4">
                    <!-- Statistiques -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i data-feather="bar-chart-2" class="icon-20"></i>
                                Statistiques
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($performance)): ?>
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="stat-item">
                                            <h3 class="text-primary mb-1"><?= $performance->total_locations ?? 0 ?></h3>
                                            <small class="text-muted">Total Locations</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="stat-item">
                                            <h3 class="text-success mb-1"><?= $performance->locations_terminees ?? 0 ?></h3>
                                            <small class="text-muted">Terminées</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="stat-item">
                                            <h3 class="text-info mb-1"><?= $performance->locations_actives ?? 0 ?></h3>
                                            <small class="text-muted">En cours</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="stat-item">
                                            <h3 class="text-warning mb-1"><?= number_format($performance->revenus_generes ?? 0, 0) ?></h3>
                                            <small class="text-muted">MAD Générés</small>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center">Aucune statistique disponible</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i data-feather="settings" class="icon-20"></i>
                                Actions Rapides
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <select class="form-select" onchange="changeStatut(<?= $chauffeur->id ?>, this.value)">
                                    <option value="">Changer le statut...</option>
                                    <option value="actif" <?= $chauffeur->statut == 'actif' ? 'disabled' : '' ?>>Actif</option>
                                    <option value="inactif" <?= $chauffeur->statut == 'inactif' ? 'disabled' : '' ?>>Inactif</option>
                                    <option value="suspendu" <?= $chauffeur->statut == 'suspendu' ? 'disabled' : '' ?>>Suspendu</option>
                                </select>
                                
                                <a href="<?= get_uri('locations?chauffeur_id=' . $chauffeur->id) ?>" class="btn btn-outline-primary">
                                    <i data-feather="map-pin" class="icon-16"></i>
                                    Voir les locations
                                </a>
                                
                                <button class="btn btn-outline-info" onclick="printChauffeur()">
                                    <i data-feather="printer" class="icon-16"></i>
                                    Imprimer la fiche
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Informations système -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i data-feather="info" class="icon-20"></i>
                                Informations Système
                            </h5>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">
                                <div class="mb-2">
                                    <strong>ID:</strong> <?= $chauffeur->id ?>
                                </div>
                                <?php if (isset($chauffeur->created_at)): ?>
                                    <div class="mb-2">
                                        <strong>Créé le:</strong> <?= date('d/m/Y H:i', strtotime($chauffeur->created_at)) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($chauffeur->updated_at) && $chauffeur->updated_at): ?>
                                    <div class="mb-2">
                                        <strong>Modifié le:</strong> <?= date('d/m/Y H:i', strtotime($chauffeur->updated_at)) ?>
                                    </div>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        // Initialiser les icônes Feather
        feather.replace();
    });

    // Fonction pour supprimer le chauffeur
    function deleteChauffeur(id) {
        appAlert.confirm("Êtes-vous sûr de vouloir supprimer ce chauffeur ?", function() {
            appLoader.show();
            $.post('<?= get_uri("chauffeurs/delete") ?>', {id: id}, function(result) {
                if(result.success) {
                    appAlert.success(result.message);
                    setTimeout(function() {
                        window.location.href = '<?= get_uri("chauffeurs") ?>';
                    }, 1000);
                } else {
                    appAlert.error(result.message);
                }
                appLoader.hide();
            });
        });
    }

    // Fonction pour changer le statut
    function changeStatut(id, newStatut) {
    if (!newStatut) return;
    
    appLoader.show();
    
    $.ajax({
        url: '<?php echo get_uri("chauffeurs/change_status") ?>',
        type: 'POST',
        data: {
            id: id, 
            statut: newStatut
        },
        dataType: 'json',
        success: function(result) {
            appLoader.hide();
            if(result.success) {
                appAlert.success(result.message);
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                appAlert.error(result.message);
            }
        },
        error: function(xhr, status, error) {
            appLoader.hide();
            console.log('Erreur AJAX:', error);
            console.log('Response:', xhr.responseText);
            appAlert.error('Erreur lors du changement de statut');
        }
    });
}

    // Fonction pour imprimer la fiche
    function printChauffeur() {
        window.print();
    }
</script>

<style>
/* Styles pour les badges de statut */
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.status-actif {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-inactif {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.status-suspendu {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

/* Styles pour les statistiques */
.stat-item h3 {
    font-size: 1.8rem;
    font-weight: 700;
}

/* Styles pour l'impression */
@media print {
    .title-button-group,
    .card:last-child {
        display: none !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        break-inside: avoid;
    }
}

/* Animation pour les cartes */
.card {
    transition: transform 0.2s ease-in-out;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}
</style>