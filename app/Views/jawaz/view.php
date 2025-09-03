<div id="page-content" class="page-wrapper clearfix">
    
    <!-- En-tête avec informations principales -->
    <div class="card">
        <div class="page-title clearfix">
            <h1>
                <i data-feather="credit-card" class="icon-24 me-2"></i>
                Badge Jawaz: <?php echo $badge->numero_serie; ?>
            </h1>
            <div class="title-button-group">
                <a href="<?php echo get_uri('jawaz'); ?>" class="btn btn-default">
                    <i data-feather="arrow-left" class="icon-16"></i> Retour à la liste
                </a>
                
                <?php if ($badge->statut === 'actif'): ?>
                    <?php echo modal_anchor(get_uri("jawaz/modal_form"), "<i data-feather='edit' class='icon-16'></i> Modifier", 
                        ["class" => "btn btn-primary", "data-post-id" => $badge->id]); ?>
                    <?php echo modal_anchor(get_uri("jawaz/modal_retour"), "<i data-feather='corner-up-left' class='icon-16'></i> Retourner", 
                        ["class" => "btn btn-warning", "data-post-id" => $badge->id]); ?>
                <?php elseif ($badge->statut === 'retourne' && $badge->peut_redistribuer): ?>
                    <?php echo modal_anchor(get_uri("jawaz/modal_redistribution"), "<i data-feather='repeat' class='icon-16'></i> Redistribuer", 
                        ["class" => "btn btn-success", "data-post-id" => $badge->id]); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i data-feather="info" class="icon-16"></i> Informations du Badge</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Numéro de série:</label>
                                <p class="h5 text-primary"><?php echo $badge->numero_serie; ?></p>
                            </div>
                            
                            <div class="form-group">
                                <label class="font-weight-bold">Véhicule affecté:</label>
                                <p>
                                    <?php if ($badge->vehicle_name): ?>
                                        <?php echo anchor(get_uri("vehicles/view/" . $badge->vehicle_id), $badge->vehicle_name, "class='text-decoration-none'"); ?>
                                    <?php else: ?>
                                        <span class="text-muted">Non affecté</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <div class="form-group">
                                <label class="font-weight-bold">Chauffeur affecté:</label>
                                <p>
                                    <?php if ($badge->chauffeur_name): ?>
                                        <?php echo anchor(get_uri("chauffeurs/view/" . $badge->chauffeur_id), $badge->chauffeur_name, "class='text-decoration-none'"); ?>
                                    <?php else: ?>
                                        <span class="text-muted">Non affecté</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <div class="form-group">
                                <label class="font-weight-bold">Date d'affectation:</label>
                                <p><?php echo format_to_date($badge->date_affectation, false); ?></p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Statut:</label>
                                <p>
                                    <?php
                                    $statut_classes = [
                                        'actif' => 'bg-success',
                                        'retourne' => 'bg-primary', 
                                        'inactif' => 'bg-secondary',
                                        'en_maintenance' => 'bg-warning'
                                    ];
                                    $statut_text = [
                                        'actif' => 'En circulation',
                                        'retourne' => 'Retourné',
                                        'inactif' => 'Inactif',
                                        'en_maintenance' => 'En maintenance'
                                    ][$badge->statut] ?? $badge->statut;
                                    $statut_class = $statut_classes[$badge->statut] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?php echo $statut_class; ?> badge-lg"><?php echo $statut_text; ?></span>
                                </p>
                            </div>
                            
                            <div class="form-group">
                                <label class="font-weight-bold">Solde actuel:</label>
                                <p class="h5 <?php echo $badge->solde > 100 ? 'text-success' : ($badge->solde > 50 ? 'text-warning' : 'text-danger'); ?>">
                                    <?php echo to_currency($badge->solde); ?>
                                </p>
                            </div>
                            
                            <?php if ($badge->date_retour): ?>
                                <div class="form-group">
                                    <label class="font-weight-bold">Date de retour:</label>
                                    <p><?php echo format_to_date($badge->date_retour, false); ?></p>
                                </div>
                                
                                <div class="form-group">
                                    <label class="font-weight-bold">Solde au retour:</label>
                                    <p class="text-info"><?php echo to_currency($badge->solde_retour); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($badge->jours_utilisation): ?>
                                <div class="form-group">
                                    <label class="font-weight-bold">Durée d'utilisation:</label>
                                    <p class="text-info"><?php echo $badge->jours_utilisation; ?> jours</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($badge->motif_retour): ?>
                        <div class="form-group">
                            <label class="font-weight-bold">Motif du retour:</label>
                            <div class="alert alert-info">
                                <?php echo nl2br(htmlspecialchars($badge->motif_retour)); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($badge->evenement): ?>
                        <div class="form-group">
                            <label class="font-weight-bold">Notes/Événements:</label>
                            <div class="alert alert-light">
                                <?php echo nl2br(htmlspecialchars($badge->evenement)); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Statistiques rapides -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4><i data-feather="bar-chart-2" class="icon-16"></i> Résumé</h4>
                </div>
                <div class="card-body">
                    <?php if ($badge->statut === 'actif'): ?>
                        <div class="alert alert-success">
                            <i data-feather="activity" class="icon-16"></i>
                            <strong>Badge en circulation</strong><br>
                            Depuis le <?php echo format_to_date($badge->date_affectation, false); ?>
                        </div>
                    <?php elseif ($badge->statut === 'retourne'): ?>
                        <div class="alert alert-primary">
                            <i data-feather="corner-up-left" class="icon-16"></i>
                            <strong>Badge retourné</strong><br>
                            Le <?php echo format_to_date($badge->date_retour, false); ?>
                            <?php if ($badge->peut_redistribuer): ?>
                                <br><small class="text-success">✓ Redistribuable</small>
                            <?php else: ?>
                                <br><small class="text-danger">✗ Non redistribuable</small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Calculs de consommation -->
                    <?php if ($badge->date_retour && $badge->solde_retour !== null): ?>
                        <?php 
                        $consommation = $badge->solde - $badge->solde_retour;
                        $jours = $badge->jours_utilisation ?: 1;
                        $consommation_journaliere = $consommation / $jours;
                        ?>
                        <div class="mb-3">
                            <h6><i data-feather="trending-down" class="icon-16"></i> Consommation</h6>
                            <p class="mb-1"><strong>Total:</strong> <?php echo to_currency($consommation); ?></p>
                            <p class="mb-1"><strong>Par jour:</strong> <?php echo to_currency($consommation_journaliere); ?></p>
                            <p class="mb-0"><strong>Période:</strong> <?php echo $jours; ?> jours</p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Informations techniques -->
                    <div class="small text-muted">
                        <p class="mb-1"><strong>ID:</strong> #<?php echo $badge->id; ?></p>
                        <p class="mb-1"><strong>Créé:</strong> <?php echo format_to_datetime($badge->created_at ?? ''); ?></p>
                        <?php if ($badge->updated_at): ?>
                            <p class="mb-0"><strong>Modifié:</strong> <?php echo format_to_datetime($badge->updated_at); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Historique des mouvements -->
    <div class="card">
        <div class="card-header">
            <h4><i data-feather="clock" class="icon-16"></i> Historique des Mouvements</h4>
        </div>
        <div class="card-body">
            <?php if (empty($historique)): ?>
                <div class="alert alert-info">
                    <i data-feather="info" class="icon-16"></i>
                    Aucun historique disponible pour ce badge.
                </div>
            <?php else: ?>
                <div class="timeline">
                    <?php foreach ($historique as $mouvement): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <?php
                                $icons = [
                                    'affectation' => 'plus-circle',
                                    'retour' => 'corner-up-left',
                                    'redistribution' => 'repeat'
                                ];
                                $colors = [
                                    'affectation' => 'text-success',
                                    'retour' => 'text-warning',
                                    'redistribution' => 'text-primary'
                                ];
                                ?>
                                <i data-feather="<?php echo $icons[$mouvement->type_mouvement] ?? 'circle'; ?>" 
                                   class="icon-16 <?php echo $colors[$mouvement->type_mouvement] ?? 'text-secondary'; ?>"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <h6 class="mb-1">
                                        <?php
                                        $types = [
                                            'affectation' => 'Affectation initiale',
                                            'retour' => 'Retour au bureau',
                                            'redistribution' => 'Redistribution'
                                        ];
                                        echo $types[$mouvement->type_mouvement] ?? $mouvement->type_mouvement;
                                        ?>
                                    </h6>
                                    <small class="text-muted">
                                        <?php echo format_to_datetime($mouvement->date_mouvement); ?>
                                    </small>
                                </div>
                                <div class="timeline-body">
                                    <?php if ($mouvement->vehicle_name): ?>
                                        <p class="mb-1"><strong>Véhicule:</strong> <?php echo $mouvement->vehicle_name; ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if ($mouvement->chauffeur_name): ?>
                                        <p class="mb-1"><strong>Chauffeur:</strong> <?php echo $mouvement->chauffeur_name; ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if ($mouvement->solde_avant !== null || $mouvement->solde_apres !== null): ?>
                                        <p class="mb-1">
                                            <strong>Solde:</strong>
                                            <?php if ($mouvement->solde_avant !== null): ?>
                                                <?php echo to_currency($mouvement->solde_avant); ?> →
                                            <?php endif; ?>
                                            <?php echo to_currency($mouvement->solde_apres); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if ($mouvement->motif): ?>
                                        <p class="mb-0"><strong>Motif:</strong> <?php echo htmlspecialchars($mouvement->motif); ?></p>
                                    <?php endif; ?>
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
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 30px;
    height: 30px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.timeline-content {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-left: 20px;
}

.timeline-header {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 8px;
    margin-bottom: 8px;
}

.badge-lg {
    font-size: 0.9em;
    padding: 0.5em 1em;
}
</style>

<script type="text/javascript">
    $(document).ready(function () {
        // Initialiser les icônes Feather
        feather.replace();
        
        // Gérer les modals depuis cette page
        $(document).on('hidden.bs.modal', function () {
            setTimeout(function() {
                feather.replace();
                // Recharger la page après modification pour voir les changements
                location.reload();
            }, 100);
        });
    });
</script>