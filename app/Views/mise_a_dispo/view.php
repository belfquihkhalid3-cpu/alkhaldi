<div id="page-content" class="page-wrapper clearfix">
    
    <!-- En-tête avec actions -->
    <div class="page-title clearfix">
        <h1>🎯 Mise à Disposition #<?php echo $mise_a_dispo->id; ?></h1>
        <div class="title-button-group">
            <?php echo anchor(get_uri("mise_a_dispo"), "⬅️ Retour à la liste", ["class" => "btn btn-outline-secondary"]); ?>
            
            <?php if ($mise_a_dispo->statut !== 'annule'): ?>
                <?php echo modal_anchor(get_uri("mise_a_dispo/modal_form"), "✏️ Modifier", 
                    ["class" => "btn btn-outline-primary", "data-post-id" => $mise_a_dispo->id]); ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>📋 Détails de la Mise à Disposition</h5>
                </div>
                <div class="card-body">
                    
                    <!-- Statut et Type -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>🏷️ Statut :</strong><br>
                            <?php 
                            $statut_class = $statut_classes[$mise_a_dispo->statut] ?? 'bg-secondary';
                            $statut_label = $statut_text[$mise_a_dispo->statut] ?? $mise_a_dispo->statut;
                            echo "<span class='badge $statut_class fs-6'>$statut_label</span>";
                            ?>
                        </div>
                        <div class="col-md-6">
                            <strong>🎯 Type de Service :</strong><br>
                            <?php echo $type_text[$mise_a_dispo->type_service] ?? $mise_a_dispo->type_service; ?>
                        </div>
                    </div>

                    <!-- Période -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>📅 Période :</strong><br>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <strong>🟢 Début :</strong> <?php echo format_to_datetime($mise_a_dispo->date_debut); ?><br>
                                    <strong>🔴 Fin :</strong> <?php echo format_to_datetime($mise_a_dispo->date_fin); ?><br>
                                    <?php if ($mise_a_dispo->duree_heures): ?>
                                        <strong>⏱️ Durée :</strong> <?php echo $mise_a_dispo->duree_heures; ?> heures
                                        (<?php echo round($mise_a_dispo->duree_heures / 24, 1); ?> jours)
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Client -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>👤 Informations Client :</strong><br>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6><?php echo $mise_a_dispo->nom_client; ?></h6>
                                    <?php if ($mise_a_dispo->telephone_client): ?>
                                        📞 <?php echo $mise_a_dispo->telephone_client; ?><br>
                                    <?php endif; ?>
                                    <?php if ($mise_a_dispo->email_client): ?>
                                        📧 <?php echo $mise_a_dispo->email_client; ?><br>
                                    <?php endif; ?>
                                    <?php if ($mise_a_dispo->hotel_partenaire): ?>
                                        🏨 <?php echo $mise_a_dispo->hotel_partenaire; ?>
                                    <?php endif; ?>
                                    <?php if ($mise_a_dispo->client_company): ?>
                                        <br>🏢 <?php echo $mise_a_dispo->client_company; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assignation -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>🚗 Véhicule :</strong><br>
                            <?php if ($mise_a_dispo->vehicle_name): ?>
                                <?php echo anchor(get_uri("vehicles/view/" . $mise_a_dispo->vehicle_id), $mise_a_dispo->vehicle_name); ?>
                            <?php else: ?>
                                <span class="text-muted">Non assigné</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <strong>👨‍💼 Chauffeur :</strong><br>
                            <?php if ($mise_a_dispo->chauffeur_name): ?>
                                <?php echo anchor(get_uri("chauffeurs/view/" . $mise_a_dispo->chauffeur_id), $mise_a_dispo->chauffeur_name); ?>
                            <?php else: ?>
                                <span class="text-muted">Non assigné</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Lieux et Programme -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>📍 Lieux et Programme :</strong><br>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <strong>📍 Lieu de prise en charge :</strong><br>
                                    <?php echo $mise_a_dispo->lieu_prise_en_charge; ?><br><br>
                                    
                                    <?php if ($mise_a_dispo->destination_principale): ?>
                                        <strong>🎯 Destination principale :</strong><br>
                                        <?php echo $mise_a_dispo->destination_principale; ?><br><br>
                                    <?php endif; ?>
                                    
                                    <?php if ($mise_a_dispo->programme_detaille): ?>
                                        <strong>📋 Programme détaillé :</strong><br>
                                        <?php echo nl2br($mise_a_dispo->programme_detaille); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarification -->
                    <?php if ($mise_a_dispo->prix_total || $mise_a_dispo->prix_unitaire): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>💰 Tarification :</strong><br>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <?php if ($mise_a_dispo->tarif_type): ?>
                                        <strong>Type :</strong> 
                                        <?php 
                                        $tarif_labels = [
                                            'horaire' => '⏰ Horaire',
                                            'journalier' => '📅 Journalier', 
                                            'forfait' => '💰 Forfait'
                                        ];
                                        echo $tarif_labels[$mise_a_dispo->tarif_type] ?? $mise_a_dispo->tarif_type;
                                        ?><br>
                                    <?php endif; ?>
                                    
                                    <?php if ($mise_a_dispo->prix_unitaire): ?>
                                        <strong>Prix unitaire :</strong> <?php echo to_currency($mise_a_dispo->prix_unitaire); ?><br>
                                    <?php endif; ?>
                                    
                                    <?php if ($mise_a_dispo->nombre_unites): ?>
                                        <strong>Nombre d'unités :</strong> <?php echo $mise_a_dispo->nombre_unites; ?><br>
                                    <?php endif; ?>
                                    
                                    <?php if ($mise_a_dispo->prix_total): ?>
                                        <strong>Prix total :</strong> 
                                        <span class="text-success fs-5"><?php echo to_currency($mise_a_dispo->prix_total); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Instructions -->
                    <?php if ($mise_a_dispo->instructions_particulieres): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>📝 Instructions Particulières :</strong><br>
                            <div class="alert alert-info">
                                <?php echo nl2br($mise_a_dispo->instructions_particulieres); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Informations de suivi (si en cours ou terminé) -->
                    <?php if ($mise_a_dispo->statut === 'en_cours' || $mise_a_dispo->statut === 'termine' || $mise_a_dispo->statut === 'facture'): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>📊 Suivi de l'Exécution :</strong><br>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <?php if ($mise_a_dispo->heure_debut_reelle): ?>
                                        <strong>⏰ Début réel :</strong> <?php echo format_to_datetime($mise_a_dispo->heure_debut_reelle); ?><br>
                                    <?php endif; ?>
                                    <?php if ($mise_a_dispo->heure_fin_reelle): ?>
                                        <strong>🏁 Fin réelle :</strong> <?php echo format_to_datetime($mise_a_dispo->heure_fin_reelle); ?><br>
                                    <?php endif; ?>
                                    <?php if ($mise_a_dispo->kilometres_parcourus): ?>
                                        <strong>🛣️ Kilomètres parcourus :</strong> <?php echo number_format($mise_a_dispo->kilometres_parcourus); ?> km<br>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Notes -->
                    <?php if ($mise_a_dispo->notes_chauffeur || $mise_a_dispo->remarques_client): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>💬 Notes et Remarques :</strong><br>
                            <?php if ($mise_a_dispo->notes_chauffeur): ?>
                                <div class="alert alert-warning">
                                    <strong>Notes du chauffeur :</strong><br>
                                    <?php echo nl2br($mise_a_dispo->notes_chauffeur); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($mise_a_dispo->remarques_client): ?>
                                <div class="alert alert-info">
                                    <strong>Remarques du client :</strong><br>
                                    <?php echo nl2br($mise_a_dispo->remarques_client); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Évaluation -->
                    <?php if ($mise_a_dispo->note_service): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>⭐ Évaluation du Service :</strong><br>
                            <div class="alert alert-success">
                                <strong>Note :</strong> <?php echo $mise_a_dispo->note_service; ?>/5<br>
                                <?php if ($mise_a_dispo->commentaire_evaluation): ?>
                                    <strong>Commentaire :</strong> <?php echo $mise_a_dispo->commentaire_evaluation; ?>
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
                    <?php if ($mise_a_dispo->statut === 'demande'): ?>
                        <?php echo modal_anchor(get_uri("mise_a_dispo/modal_confirmer"), "✅ Confirmer", 
                            ["class" => "btn btn-primary btn-sm mb-2 w-100", "data-post-id" => $mise_a_dispo->id]); ?>
                    <?php elseif ($mise_a_dispo->statut === 'confirme'): ?>
                        <?php echo modal_anchor(get_uri("mise_a_dispo/modal_demarrer"), "▶️ Démarrer", 
                            ["class" => "btn btn-info btn-sm mb-2 w-100", "data-post-id" => $mise_a_dispo->id]); ?>
                    <?php elseif ($mise_a_dispo->statut === 'en_cours'): ?>
                        <?php echo modal_anchor(get_uri("mise_a_dispo/modal_terminer"), "🏁 Terminer", 
                            ["class" => "btn btn-success btn-sm mb-2 w-100", "data-post-id" => $mise_a_dispo->id]); ?>
                    <?php elseif ($mise_a_dispo->statut === 'termine'): ?>
                        <?php echo modal_anchor(get_uri("mise_a_dispo/modal_facturer"), "💳 Facturer", 
                            ["class" => "btn btn-success btn-sm mb-2 w-100", "data-post-id" => $mise_a_dispo->id]); ?>
                    <?php endif; ?>
                    
                    <?php if (!in_array($mise_a_dispo->statut, ['facture', 'annule'])): ?>
                        <?php echo modal_anchor(get_uri("mise_a_dispo/modal_annuler"), "❌ Annuler", 
                            ["class" => "btn btn-danger btn-sm mb-2 w-100", "data-post-id" => $mise_a_dispo->id]); ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Informations système -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6>ℹ️ Informations</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <strong>📅 Créé le :</strong><br>
                        <?php echo format_to_datetime($mise_a_dispo->created_at); ?><br><br>
                        
                        <strong>🔄 Modifié le :</strong><br>
                        <?php echo format_to_datetime($mise_a_dispo->updated_at); ?><br><br>
                        
                        <strong>🆔 ID :</strong> #<?php echo $mise_a_dispo->id; ?>
                    </small>
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