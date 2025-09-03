<div id="page-content" class="page-wrapper clearfix">
    
    <!-- En-tête avec actions -->
    <div class="page-title clearfix">
        <h1>🚚 Transfert #<?php echo $transfert->id; ?></h1>
        <div class="title-button-group">
            <?php echo anchor(get_uri("transferts"), "⬅️ Retour à la liste", ["class" => "btn btn-outline-secondary"]); ?>
            
            <?php if ($transfert->statut !== 'annule'): ?>
                <?php echo modal_anchor(get_uri("transferts/modal_form"), "✏️ Modifier", 
                    ["class" => "btn btn-outline-primary", "data-post-id" => $transfert->id]); ?>
            <?php endif; ?>
            
            <?php echo anchor(get_uri("transferts/download_pdf/" . $transfert->id), "📄 PDF", 
                ["class" => "btn btn-outline-info", "target" => "_blank"]); ?>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>📋 Détails du Transfert</h5>
                </div>
                <div class="card-body">
                    
                    <!-- Statut et Type -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>🏷️ Statut :</strong><br>
                            <?php 
                            $statut_class = $statut_classes[$transfert->statut] ?? 'bg-secondary';
                            $statut_label = $statut_text[$transfert->statut] ?? $transfert->statut;
                            echo "<span class='badge $statut_class fs-6'>$statut_label</span>";
                            ?>
                        </div>
                        <div class="col-md-6">
                            <strong>🔄 Type de Transfert :</strong><br>
                            <?php echo $type_text[$transfert->type_transfert] ?? $transfert->type_transfert; ?>
                        </div>
                    </div>

                    <!-- Date et Service -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>📅 Date du Transfert :</strong><br>
                            <?php echo format_to_date($transfert->date_transfert, false); ?>
                        </div>
                        <div class="col-md-6">
                            <strong>🎯 Type de Service :</strong><br>
                            <?php echo $transfert->service_type; ?>
                        </div>
                    </div>

                    <!-- Client -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>👤 Informations Client :</strong><br>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6><?php echo $transfert->nom_client; ?></h6>
                                    <?php if ($transfert->telephone_client): ?>
                                        📞 <?php echo $transfert->telephone_client; ?><br>
                                    <?php endif; ?>
                                    <?php if ($transfert->email_client): ?>
                                        📧 <?php echo $transfert->email_client; ?><br>
                                    <?php endif; ?>
                                    <?php if ($transfert->nombre_passagers > 1): ?>
                                        👥 <?php echo $transfert->nombre_passagers; ?> passagers<br>
                                    <?php endif; ?>
                                    <?php if ($transfert->client_company): ?>
                                        🏢 <?php echo $transfert->client_company; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vol -->
                    <?php if ($transfert->numero_vol): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>✈️ Informations Vol :</strong><br>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <strong>Vol :</strong> <?php echo $transfert->numero_vol; ?><br>
                                    <?php if ($transfert->compagnie): ?>
                                        <strong>Compagnie :</strong> <?php echo $transfert->compagnie; ?><br>
                                    <?php endif; ?>
                                    <?php if ($transfert->heure_arrivee_prevue): ?>
                                        <strong>Heure d'arrivée prévue :</strong> <?php echo date('H:i', strtotime($transfert->heure_arrivee_prevue)); ?><br>
                                    <?php endif; ?>
                                    <?php if ($transfert->heure_depart_prevue): ?>
                                        <strong>Heure de départ prévue :</strong> <?php echo date('H:i', strtotime($transfert->heure_depart_prevue)); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Itinéraire -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>📍 Itinéraire :</strong><br>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <strong>🟢 Lieu de prise en charge :</strong><br>
                                    <?php echo $transfert->lieu_prise_en_charge; ?><br><br>
                                    
                                    <strong>🔴 Destination :</strong><br>
                                    <?php echo $transfert->lieu_destination; ?><br>
                                    
                                    <?php if ($transfert->adresse_complete): ?>
                                        <br><strong>Adresse complète :</strong><br>
                                        <?php echo nl2br($transfert->adresse_complete); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assignation -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>🚗 Véhicule :</strong><br>
                            <?php if ($transfert->vehicle_name): ?>
                                <?php echo anchor(get_uri("vehicles/view/" . $transfert->vehicle_id), $transfert->vehicle_name); ?>
                            <?php else: ?>
                                <span class="text-muted">Non assigné</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <strong>👨‍💼 Chauffeur :</strong><br>
                            <?php if ($transfert->chauffeur_name): ?>
                                <?php echo anchor(get_uri("chauffeurs/view/" . $transfert->chauffeur_id), $transfert->chauffeur_name); ?>
                            <?php else: ?>
                                <span class="text-muted">Non assigné</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Prix -->
                    <?php if ($transfert->prix_prevu || $transfert->prix_facture): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>💰 Prix Prévu :</strong><br>
                            <?php echo $transfert->prix_prevu ? to_currency($transfert->prix_prevu) : '-'; ?>
                        </div>
                        <div class="col-md-6">
                            <strong>💳 Prix Facturé :</strong><br>
                            <?php echo $transfert->prix_facture ? to_currency($transfert->prix_facture) : '-'; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Instructions -->
                    <?php if ($transfert->instructions_particulieres): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>📝 Instructions Particulières :</strong><br>
                            <div class="alert alert-info">
                                <?php echo nl2br($transfert->instructions_particulieres); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Heures réelles si en cours ou terminé -->
                    <?php if ($transfert->statut === 'en_cours' || $transfert->statut === 'termine'): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>⏰ Heures Réelles :</strong><br>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <?php if ($transfert->heure_prise_en_charge_reelle): ?>
                                        <strong>Prise en charge :</strong> <?php echo format_to_datetime($transfert->heure_prise_en_charge_reelle); ?><br>
                                    <?php endif; ?>
                                    <?php if ($transfert->heure_arrivee_reelle): ?>
                                        <strong>Arrivée :</strong> <?php echo format_to_datetime($transfert->heure_arrivee_reelle); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Notes -->
                    <?php if ($transfert->notes_chauffeur || $transfert->remarques_client): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>💬 Notes et Remarques :</strong><br>
                            <?php if ($transfert->notes_chauffeur): ?>
                                <div class="alert alert-warning">
                                    <strong>Notes du chauffeur :</strong><br>
                                    <?php echo nl2br($transfert->notes_chauffeur); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($transfert->remarques_client): ?>
                                <div class="alert alert-info">
                                    <strong>Remarques du client :</strong><br>
                                    <?php echo nl2br($transfert->remarques_client); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Évaluation -->
                    <?php if ($transfert->note_service): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>⭐ Évaluation du Service :</strong><br>
                            <div class="alert alert-success">
                                <strong>Note :</strong> <?php echo $transfert->note_service; ?>/5<br>
                                <?php if ($transfert->commentaire_evaluation): ?>
                                    <strong>Commentaire :</strong> <?php echo $transfert->commentaire_evaluation; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <!-- Actions et Historique -->
        <div class="col-md-4">
            
            <!-- Actions rapides -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6>⚡ Actions Rapides</h6>
                </div>
                <div class="card-body">
                    <?php if ($transfert->statut === 'reserve'): ?>
                        <?php echo modal_anchor(get_uri("transferts/modal_confirmer"), "✅ Confirmer", 
                            ["class" => "btn btn-success btn-sm mb-2 w-100", "data-post-id" => $transfert->id]); ?>
                    <?php elseif ($transfert->statut === 'confirme'): ?>
                        <?php echo modal_anchor(get_uri("transferts/modal_demarrer"), "▶️ Démarrer", 
                            ["class" => "btn btn-warning btn-sm mb-2 w-100", "data-post-id" => $transfert->id]); ?>
                    <?php elseif ($transfert->statut === 'en_cours'): ?>
                        <?php echo modal_anchor(get_uri("transferts/modal_terminer"), "🏁 Terminer", 
                            ["class" => "btn btn-success btn-sm mb-2 w-100", "data-post-id" => $transfert->id]); ?>
                    <?php endif; ?>
                    
                    <?php if (!in_array($transfert->statut, ['termine', 'annule'])): ?>
                        <?php echo modal_anchor(get_uri("transferts/modal_annuler"), "❌ Annuler", 
                            ["class" => "btn btn-danger btn-sm mb-2 w-100", "data-post-id" => $transfert->id]); ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Historique des changements -->
            <div class="card">
                <div class="card-header">
                    <h6>📜 Historique</h6>
                </div>
                <div class="card-body">
                    <?php if ($historique && count($historique) > 0): ?>
                        <div class="timeline">
                            <?php foreach ($historique as $h): ?>
                                <div class="timeline-item mb-3">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <small class="text-muted">
                                            <?php echo format_to_datetime($h->changed_at); ?>
                                        </small><br>
                                        <strong>
                                            <?php echo ($h->ancien_statut ?: 'Nouveau') . ' → ' . $h->nouveau_statut; ?>
                                        </strong><br>
                                        <?php if ($h->first_name || $h->last_name): ?>
                                            <small>Par : <?php echo trim($h->first_name . ' ' . $h->last_name); ?></small><br>
                                        <?php endif; ?>
                                        <?php if ($h->commentaire): ?>
                                            <small class="text-info"><?php echo $h->commentaire; ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Aucun historique disponible</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
    padding-bottom: 15px;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #007bff;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #007bff;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -21px;
    top: 10px;
    bottom: 10px;
    width: 2px;
    background: #dee2e6;
}

.timeline-item:last-child::after {
    content: '';
    position: absolute;
    left: -22px;
    bottom: 0;
    width: 4px;
    height: 20px;
    background: #fff;
}
</style>