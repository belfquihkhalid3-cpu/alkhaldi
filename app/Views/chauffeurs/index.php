<?php
/**
 * Vue Index des Chauffeurs - Style direct sans AJAX
 * Suivant le pattern du module mise_a_dispo
 */
?>

<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        

        <div class="col-sm-11 col-lg-12">
            <div class="page-title clearfix">
                <h4><?php echo app_lang('chauffeurs'); ?></h4>
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("chauffeurs/modal_add"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_chauffeur'), array("class" => "btn btn-default", "title" => app_lang('add_chauffeur'))); ?>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h4 class="card-title mb-0"><?= $statistics->total ?? 0 ?></h4>
                                    <p class="card-text">Total Chauffeurs</p>
                                </div>
                                <div class="ms-auto">
                                    <i data-feather="users" class="icon-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h4 class="card-title mb-0"><?= $statistics->actifs ?? 0 ?></h4>
                                    <p class="card-text">Actifs</p>
                                </div>
                                <div class="ms-auto">
                                    <i data-feather="check-circle" class="icon-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h4 class="card-title mb-0"><?= $statistics->inactifs ?? 0 ?></h4>
                                    <p class="card-text">Inactifs</p>
                                </div>
                                <div class="ms-auto">
                                    <i data-feather="pause-circle" class="icon-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h4 class="card-title mb-0"><?= $statistics->suspendus ?? 0 ?></h4>
                                    <p class="card-text">Suspendus</p>
                                </div>
                                <div class="ms-auto">
                                    <i data-feather="alert-circle" class="icon-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-body">
                    <form id="filtres-form" class="row g-3">
                        <div class="col-md-3">
                            <?php
                            echo form_dropdown("statut_filter", array(
                                "" => "- Tous les statuts -",
                                "actif" => "Actif",
                                "inactif" => "Inactif",
                                "suspendu" => "Suspendu"
                            ), "", "class='form-control' id='statut-filter'");
                            ?>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="search-filter" placeholder="Rechercher...">
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary" id="apply-filters">
                                <i data-feather="filter" class="icon-16"></i> Filtrer
                            </button>
                            <button type="button" class="btn btn-secondary" id="reset-filters">
                                <i data-feather="refresh-cw" class="icon-16"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des chauffeurs -->
            <div class="row" id="chauffeurs-container">
                <?php if (!empty($chauffeurs)): ?>
                    <?php foreach ($chauffeurs as $chauffeur): ?>
                        <div class="col-md-6 col-lg-4 mb-4 chauffeur-item" 
                             data-statut="<?= $chauffeur->statut ?>"
                             data-search="<?= strtolower($chauffeur->prenom . ' ' . $chauffeur->nom . ' ' . $chauffeur->cnie . ' ' . $chauffeur->telephone) ?>">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-title rounded-circle bg-primary text-white">
                                                <?= strtoupper(substr($chauffeur->prenom, 0, 1) . substr($chauffeur->nom, 0, 1)) ?>
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?= esc($chauffeur->prenom . ' ' . $chauffeur->nom) ?></h6>
                                            <small class="text-muted">ID: <?= $chauffeur->id ?></small>
                                        </div>
                                    </div>
                                    <span class="status-badge status-<?= $chauffeur->statut ?>"><?= ucfirst($chauffeur->statut) ?></span>
                                </div>

                                <div class="card-body">
                                    <div class="row text-sm">
                                        <div class="col-6">
                                            <strong>CNIE:</strong><br>
                                            <span><?= esc($chauffeur->cnie ?: 'Non renseigné') ?></span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Téléphone:</strong><br>
                                            <span><?= esc($chauffeur->telephone ?: 'Non renseigné') ?></span>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($chauffeur->email)): ?>
                                        <div class="mt-2">
                                            <strong>Email:</strong><br>
                                            <small><?= esc($chauffeur->email) ?></small>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($chauffeur->numero_permis)): ?>
                                        <div class="mt-2">
                                            <strong>Permis:</strong><br>
                                            <small><?= esc($chauffeur->numero_permis) ?></small>
                                            <?php if (!empty($chauffeur->date_expiration_permis)): ?>
                                                <br><small class="text-muted">Expire: <?= date('d/m/Y', strtotime($chauffeur->date_expiration_permis)) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($chauffeur->observations)): ?>
                                        <div class="mt-2">
                                            <strong>Observations:</strong><br>
                                            <p class="text-muted small mb-0">
                                                <?= strlen($chauffeur->observations) > 80 ? 
                                                    substr(esc($chauffeur->observations), 0, 80) . '...' : esc($chauffeur->observations) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="card-footer bg-transparent">
                                    <div class="btn-group w-100" role="group">
                                        <a href="<?= site_url('chauffeurs/view/' . $chauffeur->id) ?>" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i data-feather="eye" class="icon-14"></i> Voir
                                        </a>
                                        <?php echo modal_anchor(get_uri("chauffeurs/modal_form"), "<i data-feather='edit-2' class='icon-14'></i> Modifier", array("class" => "btn btn-outline-warning btn-sm", "data-post-id" => $chauffeur->id)); ?>
                                        <button onclick="deleteChauffeur(<?= $chauffeur->id ?>)" 
                                               class="btn btn-outline-danger btn-sm">
                                            <i data-feather="trash-2" class="icon-14"></i> Supprimer
                                        </button>
                                    </div>
                                    
                                    <!-- Changement de statut rapide -->
                                    <div class="mt-2">
                                        <select class="form-select form-select-sm" onchange="changeStatut(<?= $chauffeur->id ?>, this.value)">
                                            <option value="">Changer statut...</option>
                                            <option value="actif" <?= $chauffeur->statut == 'actif' ? 'disabled' : '' ?>>Actif</option>
                                            <option value="inactif" <?= $chauffeur->statut == 'inactif' ? 'disabled' : '' ?>>Inactif</option>
                                            <option value="suspendu" <?= $chauffeur->statut == 'suspendu' ? 'disabled' : '' ?>>Suspendu</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <div class="empty-state">
                                    <i data-feather="users" class="icon-48 text-muted mb-3"></i>
                                    <h4>Aucun chauffeur trouvé</h4>
                                    <p class="text-muted">Commencez par ajouter un chauffeur à votre flotte.</p>
                                    <?php echo modal_anchor(get_uri("chauffeurs/modal_add"), "<i data-feather='plus-circle' class='icon-16'></i> Ajouter un chauffeur", array("class" => "btn btn-primary")); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        // Gestion des filtres
        $("#apply-filters").click(function() {
            var statutFilter = $("#statut-filter").val();
            var searchFilter = $("#search-filter").val().toLowerCase();
            
            $(".chauffeur-item").each(function() {
                var item = $(this);
                var showItem = true;
                
                // Filtre par statut
                if (statutFilter && item.data('statut') !== statutFilter) {
                    showItem = false;
                }
                
                // Filtre par recherche
                if (searchFilter && item.data('search').indexOf(searchFilter) === -1) {
                    showItem = false;
                }
                
                if (showItem) {
                    item.show();
                } else {
                    item.hide();
                }
            });
        });

        $("#reset-filters").click(function() {
            $("#statut-filter").val("");
            $("#search-filter").val("");
            $(".chauffeur-item").show();
        });

        // Recherche en temps réel
        $("#search-filter").on('input', function() {
            $("#apply-filters").click();
        });

        // Initialiser les icônes Feather
        feather.replace();
    });

    // Fonction pour supprimer un chauffeur
    function deleteChauffeur(id) {
        appAlert.confirm("<?php echo app_lang('delete_confirm'); ?>", function() {
            appLoader.show();
            $.post('<?php echo get_uri("chauffeurs/delete") ?>', {id: id}, function(result) {
                if(result.success) {
                    appAlert.success(result.message);
                    location.reload();
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
        
        $.post('<?php echo get_uri("chauffeurs/change_status") ?>', {
            id: id, 
            statut: newStatut
        }, function(result) {
            if(result.success) {
                appAlert.success(result.message);
                location.reload();
            } else {
                appAlert.error(result.message);
            }
        });
    }
</script>

<style>
.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
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

.avatar {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
}

.empty-state .icon-48 {
    width: 48px;
    height: 48px;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}
</style>