<?php echo form_open(get_uri("mise_a_dispo/save"), array("id" => "mise-a-dispo-form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix">
    <div class="container-fluid">
        
        <!-- Informations Client -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">👤 Informations Client</h6>
            </div>
            <div class="card-body">
                
                <!-- Client existant -->
                <div class="form-group">
                    <div class="row">
                        <label for="client_id" class="col-md-3 col-form-label">
                            👥 Client
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown("client_id", $clients_dropdown ?? [], "", 
                                "class='form-control select2' id='client_id'");
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Nom du client -->
                <div class="form-group">
                    <div class="row">
                        <label for="nom_client" class="col-md-3 col-form-label">
                            🏷 Nom Client <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "nom_client",
                                "name" => "nom_client",
                                "value" => $model_info->nom_client ?? '',
                                "class" => "form-control",
                                "placeholder" => "Nom du client",
                                "autofocus" => true,
                                "data-rule-required" => true,
                                "data-msg-required" => "Le nom du client est requis"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Téléphone -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telephone_client">📞 Téléphone</label>
                            <?php
                            echo form_input(array(
                                "id" => "telephone_client",
                                "name" => "telephone_client",
                                "value" => $model_info->telephone_client ?? '',
                                "class" => "form-control",
                                "placeholder" => "Téléphone",
                                "type" => "tel"
                            ));
                            ?>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email_client">📧 Email</label>
                            <?php
                            echo form_input(array(
                                "id" => "email_client",
                                "name" => "email_client",
                                "value" => $model_info->email_client ?? '',
                                "class" => "form-control",
                                "placeholder" => "Email",
                                "type" => "email"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Hôtel partenaire -->
                <div class="form-group">
                    <div class="row">
                        <label for="hotel_partenaire" class="col-md-3 col-form-label">
                            🏨 Hôtel/Partenaire
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "hotel_partenaire",
                                "name" => "hotel_partenaire",
                                "value" => $model_info->hotel_partenaire ?? '',
                                "class" => "form-control",
                                "placeholder" => "Nom de l'hôtel ou partenaire"
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Demandé -->
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">🎯 Service Demandé</h6>
            </div>
            <div class="card-body">
                
                <!-- Type de service -->
                <div class="form-group">
                    <div class="row">
                        <label for="type_service" class="col-md-3 col-form-label">
                            🎯 Type <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php
                            $type_options = [
                                '' => '- Sélectionner le type -',
                                'chauffeur_seul' => '👨‍💼 Chauffeur seul',
                                'vehicule_seul' => '🚗 Véhicule seul',
                                'chauffeur_vehicule' => '👨‍💼🚗 Chauffeur + Véhicule'
                            ];
                            echo form_dropdown("type_service", $type_options, $model_info->type_service ?? '', 
                                "class='form-control select2' id='type_service' data-rule-required='true' data-msg-required='Le type de service est requis'");
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Période -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_debut">📅 Date/Heure Début <span class="text-danger">*</span></label>
                            <?php
                            $date_debut_value = '';
                            if (isset($model_info->date_debut)) {
                                $date_debut_value = date('Y-m-d\TH:i', strtotime($model_info->date_debut));
                            } else {
                                $date_debut_value = date('Y-m-d\TH:i');
                            }
                            echo form_input(array(
                                "id" => "date_debut",
                                "name" => "date_debut",
                                "value" => $date_debut_value,
                                "class" => "form-control",
                                "type" => "datetime-local",
                                "data-rule-required" => true,
                                "data-msg-required" => "La date de début est requise"
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_fin">📅 Date/Heure Fin <span class="text-danger">*</span></label>
                            <?php
                            $date_fin_value = '';
                            if (isset($model_info->date_fin)) {
                                $date_fin_value = date('Y-m-d\TH:i', strtotime($model_info->date_fin));
                            } else {
                                $date_fin_value = date('Y-m-d\TH:i', strtotime('+1 day'));
                            }
                            echo form_input(array(
                                "id" => "date_fin",
                                "name" => "date_fin",
                                "value" => $date_fin_value,
                                "class" => "form-control",
                                "type" => "datetime-local",
                                "data-rule-required" => true,
                                "data-msg-required" => "La date de fin est requise"
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignation -->
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">👥 Assignation</h6>
            </div>
            <div class="card-body">
                
                <div class="row">
                    <!-- Véhicule -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vehicle_id">🚗 Véhicule</label>
                            <?php
                            echo form_dropdown("vehicle_id", $vehicles_dropdown ?? [], $model_info->vehicle_id ?? '', 
                                "class='form-control select2' id='vehicle_id'");
                            ?>
                        </div>
                    </div>

                    <!-- Chauffeur -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="chauffeur_id">👨‍💼 Chauffeur</label>
                            <?php
                            echo form_dropdown("chauffeur_id", $chauffeurs_dropdown ?? [], $model_info->chauffeur_id ?? '', 
                                "class='form-control select2' id='chauffeur_id'");
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- Alerte disponibilité -->
                <div id="disponibilite_alert" class="alert alert-warning d-none">
                    ⚠️ <strong>Attention :</strong> Vérification de disponibilité en cours...
                </div>
            </div>
        </div>

        <!-- Lieux et Programme -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">📍 Lieux et Programme</h6>
            </div>
            <div class="card-body">
                
                <!-- Lieu de prise en charge -->
                <div class="form-group">
                    <div class="row">
                        <label for="lieu_prise_en_charge" class="col-md-3 col-form-label">
                            📍 Prise en charge <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "lieu_prise_en_charge",
                                "name" => "lieu_prise_en_charge",
                                "value" => $model_info->lieu_prise_en_charge ?? '',
                                "class" => "form-control",
                                "placeholder" => "Adresse de prise en charge",
                                "data-rule-required" => true,
                                "data-msg-required" => "Le lieu de prise en charge est requis"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Destination principale -->
                <div class="form-group">
                    <div class="row">
                        <label for="destination_principale" class="col-md-3 col-form-label">
                            🎯 Destination principale
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "destination_principale",
                                "name" => "destination_principale",
                                "value" => $model_info->destination_principale ?? '',
                                "class" => "form-control",
                                "placeholder" => "Destination principale ou zone de service"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Programme détaillé -->
                <div class="form-group">
                    <div class="row">
                        <label for="programme_detaille" class="col-md-3 col-form-label">
                            📝 Programme détaillé
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_textarea(array(
                                "id" => "programme_detaille",
                                "name" => "programme_detaille",
                                "value" => $model_info->programme_detaille ?? '',
                                "class" => "form-control",
                                "rows" => 3,
                                "placeholder" => "Programme détaillé de la mise à disposition..."
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="form-group">
                    <div class="row">
                        <label for="instructions_particulieres" class="col-md-3 col-form-label">
                            📝 Instructions
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_textarea(array(
                                "id" => "instructions_particulieres",
                                "name" => "instructions_particulieres",
                                "value" => $model_info->instructions_particulieres ?? '',
                                "class" => "form-control",
                                "rows" => 2,
                                "placeholder" => "Instructions particulières..."
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarification -->
        <div class="card mb-3">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0">💰 Tarification</h6>
            </div>
            <div class="card-body">
                
                <div class="row">
                    <!-- Type de tarif -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tarif_type">💳 Type de tarif</label>
                            <?php
                            $tarif_options = [
                                'journalier' => '📅 Journalier',
                                'horaire' => '⏰ Horaire',
                                'forfait' => '💰 Forfait'
                            ];
                            echo form_dropdown("tarif_type", $tarif_options, $model_info->tarif_type ?? 'journalier', 
                                "class='form-control select2' id='tarif_type'");
                            ?>
                        </div>
                    </div>

                    <!-- Prix unitaire -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="prix_unitaire">💰 Prix unitaire</label>
                            <div class="input-group">
                                <?php
                                echo form_input(array(
                                    "id" => "prix_unitaire",
                                    "name" => "prix_unitaire",
                                    "value" => $model_info->prix_unitaire ?? '',
                                    "class" => "form-control",
                                    "type" => "number",
                                    "step" => "0.01",
                                    "placeholder" => "Prix"
                                ));
                                ?>
                                <span class="input-group-text">MAD</span>
                            </div>
                        </div>
                    </div>

                    <!-- Prix total -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="prix_total">💵 Prix total</label>
                            <div class="input-group">
                                <?php
                                echo form_input(array(
                                    "id" => "prix_total",
                                    "name" => "prix_total",
                                    "value" => $model_info->prix_total ?? '',
                                    "class" => "form-control bg-light",
                                    "type" => "number",
                                    "step" => "0.01",
                                    "placeholder" => "Calculé automatiquement",
                                    "readonly" => true
                                ));
                                ?>
                                <span class="input-group-text">MAD</span>
                            </div>
                            <small class="text-muted">Calculé automatiquement</small>
                        </div>
                    </div>
                </div>

                <!-- Durée calculée -->
                <div class="alert alert-info" id="duree_info" style="display: none;">
                    ⏱️ <strong>Durée calculée :</strong> <span id="duree_text"></span>
                </div>
            </div>
        </div>

        <!-- Champs cachés -->
        <input type="hidden" id="id" name="id" value="<?php echo $model_info->id ?? ''; ?>">
        <input type="hidden" id="nombre_unites" name="nombre_unites" value="<?php echo $model_info->nombre_unites ?? ''; ?>">
        <input type="hidden" id="statut" name="statut" value="<?php echo $model_info->statut ?? 'demande'; ?>">
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        ❌ Annuler
    </button>
    <button type="submit" class="btn btn-primary">
        💾 Enregistrer la Mise à Disposition
    </button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        console.log('🚀 Initialisation modal mise à disposition...');
        
        $("#mise-a-dispo-form").appForm({
            onSuccess: function (result) {
                console.log('✅ Sauvegarde réussie:', result);
                if (result.success) {
                    // Mise à jour du tableau sur la page index
                    if ($("#mise-a-dispo-table").length > 0) {
                        $("#mise-a-dispo-table").appTable({newData: result.data, dataId: result.id});
                    }
                }
            }
        });
        
        // Initialiser Select2
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
                dropdownParent: $('.modal-body')
            });
        }
        
        // Gestion du client existant
        $('#client_id').on('change', function() {
            var clientId = $(this).val();
            console.log('👤 Client sélectionné:', clientId);
            if (clientId) {
                // Charger les infos du client via AJAX
                loadClientInfo(clientId);
            } else {
                // Vider les champs si aucun client sélectionné
                $('#nom_client, #telephone_client, #email_client').val('');
            }
        });
        
        function loadClientInfo(clientId) {
            // TODO: Implémenter le chargement AJAX des infos client
            console.log('📡 Chargement infos client:', clientId);
        }
        
        // Calcul automatique du prix total et de la durée
        $('#date_debut, #date_fin, #tarif_type, #prix_unitaire').on('change input', function() {
            console.log('🔄 Trigger calcul depuis:', $(this).attr('id'), '=', $(this).val());
            calculateAll();
        });
        
        function calculateAll() {
            var dateDebut = $('#date_debut').val();
            var dateFin = $('#date_fin').val();
            var tarifType = $('#tarif_type').val();
            var prixUnitaire = parseFloat($('#prix_unitaire').val()) || 0;
            
            console.log('🧮 Calcul - Début:', dateDebut, 'Fin:', dateFin, 'Type:', tarifType, 'Prix:', prixUnitaire);
            
            if (dateDebut && dateFin) {
                var debut = new Date(dateDebut);
                var fin = new Date(dateFin);
                
                if (fin > debut) {
                    var diffMs = fin - debut;
                    var heures = diffMs / (1000 * 60 * 60);
                    var jours = heures / 24;
                    
                    console.log('⏰ Durée calculée:', heures, 'heures', jours, 'jours');
                    
                    // Afficher la durée
                    var dureeText = '';
                    if (heures < 24) {
                        dureeText = Math.ceil(heures) + ' heures';
                    } else {
                        dureeText = Math.ceil(jours) + ' jour(s) (' + heures.toFixed(1) + ' heures)';
                    }
                    
                    $('#duree_text').text(dureeText);
                    $('#duree_info').show();
                    
                    // Calculer les unités et le prix
                    if (prixUnitaire > 0) {
                        var unites = 1;
                        switch (tarifType) {
                            case 'horaire':
                                unites = Math.ceil(heures);
                                break;
                            case 'journalier':
                                unites = Math.ceil(jours);
                                break;
                            case 'forfait':
                                unites = 1;
                                break;
                            default:
                                unites = Math.ceil(jours); // Par défaut journalier
                                break;
                        }
                        
                        console.log('📊 Calcul final:', unites, 'unités ×', prixUnitaire, 'MAD');
                        
                        var total = prixUnitaire * unites;
                        $('#prix_total').val(total.toFixed(2));
                        
                        // Mettre à jour l'affichage de la durée avec le calcul
                        var uniteText = {
                            'horaire': 'heures',
                            'journalier': 'jour(s)',
                            'forfait': 'forfait'
                        }[tarifType] || 'jour(s)';
                        
                        $('#duree_text').text(dureeText + ' → ' + unites + ' ' + uniteText + ' × ' + prixUnitaire + ' MAD = ' + total.toFixed(2) + ' MAD');
                        
                        // Mettre à jour le champ caché nombre_unites
                        $('#nombre_unites').val(unites);
                        
                    } else {
                        $('#prix_total').val('');
                        $('#duree_text').text(dureeText + ' → Saisir le prix unitaire pour calculer le total');
                    }
                } else {
                    $('#duree_info').hide();
                    $('#prix_total').val('');
                    console.warn('⚠️ Date de fin antérieure à la date de début');
                }
            } else {
                $('#duree_info').hide();
                $('#prix_total').val('');
                console.log('📅 Dates incomplètes');
            }
        }
        
        // Vérification de disponibilité en temps réel
        $('#date_debut, #date_fin, #chauffeur_id, #vehicle_id').on('change', function() {
            checkDisponibilite();
        });
        
        function checkDisponibilite() {
            var dateDebut = $('#date_debut').val();
            var dateFin = $('#date_fin').val();
            var chauffeurId = $('#chauffeur_id').val();
            var vehicleId = $('#vehicle_id').val();
            var excludeId = $('#id').val();
            
            if (dateDebut && dateFin && (chauffeurId || vehicleId)) {
                $('#disponibilite_alert').removeClass('d-none alert-danger alert-success')
                    .addClass('alert-warning')
                    .html('⏳ Vérification de disponibilité...');
                
                $.ajax({
                    url: '<?php echo get_uri("mise_a_dispo/check_disponibilite"); ?>',
                    type: 'POST',
                    data: {
                        date_debut: dateDebut,
                        date_fin: dateFin,
                        chauffeur_id: chauffeurId,
                        vehicle_id: vehicleId,
                        exclude_id: excludeId
                    },
                    success: function(response) {
                        try {
                            var result = JSON.parse(response);
                            if (result.success) {
                                $('#disponibilite_alert').removeClass('alert-warning alert-danger')
                                    .addClass('alert-success')
                                    .html('✅ <strong>Disponible</strong> pour cette période');
                            } else {
                                $('#disponibilite_alert').removeClass('alert-warning alert-success')
                                    .addClass('alert-danger')
                                    .html('❌ <strong>Non disponible</strong> - ' + result.message);
                            }
                        } catch (e) {
                            console.error('Erreur parsing disponibilité:', e);
                        }
                    },
                    error: function() {
                        $('#disponibilite_alert').removeClass('alert-warning alert-success')
                            .addClass('alert-danger')
                            .html('❌ Erreur lors de la vérification');
                    }
                });
            } else {
                $('#disponibilite_alert').addClass('d-none');
            }
        }
        
        // Validation des dates
        $('#date_fin').on('change', function() {
            var dateDebut = $('#date_debut').val();
            var dateFin = $(this).val();
            
            if (dateDebut && dateFin && new Date(dateFin) <= new Date(dateDebut)) {
                alert('⚠️ La date de fin doit être postérieure à la date de début');
                $(this).focus();
            }
        });
        
        // Calculer dès le chargement si des valeurs par défaut
        setTimeout(function() {
            calculateAll();
            console.log('✅ Calcul initial effectué');
        }, 500); // Délai pour s'assurer que le DOM est prêt
        
        console.log('✅ Modal mise à disposition complètement initialisé !');
    });
</script>

<style>
.card-header h6 {
    margin: 0;
    font-weight: 600;
}

.form-group {
    margin-bottom: 1rem;
}

.text-danger {
    color: #dc3545 !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

#duree_info {
    margin-top: 10px;
    font-size: 0.9em;
}

.select2-container--bootstrap4 .select2-selection {
    height: calc(1.5em + 0.75rem + 2px);
}
</style>