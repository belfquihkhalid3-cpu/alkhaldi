<?php echo form_open(get_uri("transferts/save"), array("id" => "transfert-form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id ?? ''; ?>" />
        
        <!-- Informations Client -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">👤 Informations Client</h6>
            </div>
            <div class="card-body">
                
                <!-- Client existant ou nouveau -->
                <div class="form-group">
                    <div class="row">
                        <label for="client_id" class="col-md-3 col-form-label">
                            👤 Client
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown("client_id", $clients_dropdown ?? [], $model_info->client_id ?? "", 
                                "class='form-control select2' id='client_id'");
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Nom du client -->
                <div class="form-group">
                    <div class="row">
                        <label for="nom_client" class="col-md-3 col-form-label">
                            📝 Nom Client <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "nom_client",
                                "name" => "nom_client",
                                "value" => $model_info->nom_client ?? '',
                                "class" => "form-control",
                                "placeholder" => "Nom complet du client",
                                "autofocus" => true,
                                "data-rule-required" => true,
                                "data-msg-required" => "Le nom du client est requis"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Téléphone -->
                <div class="form-group">
                    <div class="row">
                        <label for="telephone_client" class="col-md-3 col-form-label">
                            📞 Téléphone
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "telephone_client",
                                "name" => "telephone_client",
                                "value" => $model_info->telephone_client ?? '',
                                "class" => "form-control",
                                "placeholder" => "Téléphone du client",
                                "type" => "tel"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <div class="row">
                        <label for="email_client" class="col-md-3 col-form-label">
                            📧 Email
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "email_client",
                                "name" => "email_client",
                                "value" => $model_info->email_client ?? '',
                                "class" => "form-control",
                                "placeholder" => "Email du client",
                                "type" => "email"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Nombre de passagers -->
                <div class="form-group">
                    <div class="row">
                        <label for="nombre_passagers" class="col-md-3 col-form-label">
                            👥 Passagers
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "nombre_passagers",
                                "name" => "nombre_passagers",
                                "value" => $model_info->nombre_passagers ?? '1',
                                "class" => "form-control",
                                "type" => "number",
                                "min" => "1",
                                "max" => "50"
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails du Transfert -->
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">🚚 Détails du Transfert</h6>
            </div>
            <div class="card-body">
                
                <!-- Type de transfert -->
                <div class="form-group">
                    <div class="row">
                        <label for="type_transfert" class="col-md-3 col-form-label">
                            🔄 Type <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php
                            $type_options = [
                                '' => '- Sélectionner le type -',
                                'arrivee' => '✈️ Arrivée',
                                'depart' => '🛫 Départ',
                                'aller_retour' => '🔄 Aller-retour'
                            ];
                            echo form_dropdown("type_transfert", $type_options, $model_info->type_transfert ?? "", 
                                "class='form-control select2' id='type_transfert' data-rule-required='true' data-msg-required='Le type de transfert est requis'");
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Type de service -->
                <div class="form-group">
                    <div class="row">
                        <label for="service_type" class="col-md-3 col-form-label">
                            🎯 Service <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php
                            $service_options = [
                                '' => '- Sélectionner le service -',
                                'aeroport_hotel' => '🛬 Aéroport → Hôtel',
                                'hotel_aeroport' => '🛫 Hôtel → Aéroport',
                                'gare_hotel' => '🚄 Gare → Hôtel',
                                'autre' => '🎯 Autre'
                            ];
                            echo form_dropdown("service_type", $service_options, $model_info->service_type ?? "", 
                                "class='form-control select2' id='service_type' data-rule-required='true' data-msg-required='Le type de service est requis'");
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Date du transfert -->
                <div class="form-group">
                    <div class="row">
                        <label for="date_transfert" class="col-md-3 col-form-label">
                            📅 Date <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php
                            // Formater la date pour le champ HTML5
                            $date_value = '';
                            if (isset($model_info->date_transfert)) {
                                $date_value = date('Y-m-d', strtotime($model_info->date_transfert));
                            }
                            echo form_input(array(
                                "id" => "date_transfert",
                                "name" => "date_transfert",
                                "value" => $date_value,
                                "class" => "form-control",
                                "type" => "date",
                                "data-rule-required" => true,
                                "data-msg-required" => "La date du transfert est requise"
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations Vol (optionnel) -->
        <div class="card mb-3">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">✈️ Informations Vol (optionnel)</h6>
            </div>
            <div class="card-body">
                
                <!-- Numéro de vol -->
                <div class="form-group">
                    <div class="row">
                        <label for="numero_vol" class="col-md-3 col-form-label">
                            ✈️ N° Vol
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "numero_vol",
                                "name" => "numero_vol",
                                "value" => $model_info->numero_vol ?? '',
                                "class" => "form-control",
                                "placeholder" => "Ex: AT123"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Compagnie -->
                <div class="form-group">
                    <div class="row">
                        <label for="compagnie" class="col-md-3 col-form-label">
                            🏢 Compagnie
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "compagnie",
                                "name" => "compagnie",
                                "value" => $model_info->compagnie ?? '',
                                "class" => "form-control",
                                "placeholder" => "Ex: Royal Air Maroc"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Heures prévues -->
                <div class="form-group">
                    <div class="row">
                        <label for="heure_arrivee_prevue" class="col-md-3 col-form-label">
                            🛬 Arrivée prévue
                        </label>
                        <div class="col-md-4">
                            <?php
                            // Formater l'heure pour le champ HTML5
                            $heure_arrivee = '';
                            if (isset($model_info->heure_arrivee_prevue)) {
                                $heure_arrivee = date('H:i', strtotime($model_info->heure_arrivee_prevue));
                            }
                            echo form_input(array(
                                "id" => "heure_arrivee_prevue",
                                "name" => "heure_arrivee_prevue",
                                "value" => $heure_arrivee,
                                "class" => "form-control",
                                "type" => "time"
                            ));
                            ?>
                        </div>
                        <label for="heure_depart_prevue" class="col-md-2 col-form-label">
                            🛫 Départ
                        </label>
                        <div class="col-md-3">
                            <?php
                            // Formater l'heure pour le champ HTML5
                            $heure_depart = '';
                            if (isset($model_info->heure_depart_prevue)) {
                                $heure_depart = date('H:i', strtotime($model_info->heure_depart_prevue));
                            }
                            echo form_input(array(
                                "id" => "heure_depart_prevue",
                                "name" => "heure_depart_prevue",
                                "value" => $heure_depart,
                                "class" => "form-control",
                                "type" => "time"
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Itinéraire -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">📍 Itinéraire</h6>
            </div>
            <div class="card-body">
                
                <!-- Lieu de prise en charge -->
                <div class="form-group">
                    <div class="row">
                        <label for="lieu_prise_en_charge" class="col-md-3 col-form-label">
                            🟢 Prise en charge <span class="text-danger">*</span>
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

                <!-- Destination -->
                <div class="form-group">
                    <div class="row">
                        <label for="lieu_destination" class="col-md-3 col-form-label">
                            🔴 Destination <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "lieu_destination",
                                "name" => "lieu_destination",
                                "value" => $model_info->lieu_destination ?? '',
                                "class" => "form-control",
                                "placeholder" => "Adresse de destination",
                                "data-rule-required" => true,
                                "data-msg-required" => "La destination est requise"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Adresse complète -->
                <div class="form-group">
                    <div class="row">
                        <label for="adresse_complete" class="col-md-3 col-form-label">
                            📍 Adresse complète
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_textarea(array(
                                "id" => "adresse_complete",
                                "name" => "adresse_complete",
                                "value" => $model_info->adresse_complete ?? '',
                                "class" => "form-control",
                                "rows" => 3,
                                "placeholder" => "Détails d'adresse supplémentaires..."
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
                
                <!-- Véhicule -->
                <div class="form-group">
                    <div class="row">
                        <label for="vehicle_id" class="col-md-3 col-form-label">
                            🚗 Véhicule
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown("vehicle_id", $vehicles_dropdown ?? [], $model_info->vehicle_id ?? "", 
                                "class='form-control select2' id='vehicle_id'");
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Chauffeur -->
                <div class="form-group">
                    <div class="row">
                        <label for="chauffeur_id" class="col-md-3 col-form-label">
                            👨‍💼 Chauffeur
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown("chauffeur_id", $chauffeurs_dropdown ?? [], $model_info->chauffeur_id ?? "", 
                                "class='form-control select2' id='chauffeur_id'");
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prix et Instructions -->
        <div class="card mb-3">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0">💰 Prix et Instructions</h6>
            </div>
            <div class="card-body">
                
                <!-- Prix prévu -->
                <div class="form-group">
                    <div class="row">
                        <label for="prix_prevu" class="col-md-3 col-form-label">
                            💰 Prix prévu
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "prix_prevu",
                                "name" => "prix_prevu",
                                "value" => $model_info->prix_prevu ?? '',
                                "class" => "form-control",
                                "type" => "number",
                                "step" => "0.01",
                                "placeholder" => "Prix estimé"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Statut -->
                <div class="form-group">
                    <div class="row">
                        <label for="statut" class="col-md-3 col-form-label">
                            🏷️ Statut
                        </label>
                        <div class="col-md-9">
                            <?php
                            $statut_options = [
                                'reserve' => '📝 Réservé',
                                'confirme' => '✅ Confirmé',
                                'en_cours' => '🔄 En cours',
                                'termine' => '🏁 Terminé',
                                'annule' => '❌ Annulé'
                            ];
                            echo form_dropdown("statut", $statut_options, $model_info->statut ?? 'reserve', 
                                "class='form-control select2' id='statut'");
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Instructions particulières -->
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
                                "rows" => 3,
                                "placeholder" => "Instructions spéciales pour le chauffeur..."
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        ❌ Annuler
    </button>
    <button type="submit" class="btn btn-primary">
        💾 <?php echo ($model_info->id ?? false) ? 'Modifier' : 'Enregistrer'; ?>
    </button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#transfert-form").appForm({
            onSuccess: function (result) {
                if (result.success) {
                    // Mise à jour du tableau sur la page index
                    if ($("#transferts-table").length > 0) {
                        $("#transferts-table").appTable({newData: result.data, dataId: result.id});
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
        
        // Gestion du client existant vs nouveau
        $('#client_id').on('change', function() {
            var clientId = $(this).val();
            if (clientId) {
                // Client existant sélectionné - charger ses infos
                // TODO: Implémenter le chargement AJAX des infos client
                console.log('Client sélectionné:', clientId);
            }
        });
        
        // Validation en temps réel
        $('#nom_client').on('blur', function() {
            if ($(this).val().trim().length < 3) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
            }
        });
        
        $('#email_client').on('blur', function() {
            var email = $(this).val();
            if (email && !isValidEmail(email)) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
            }
        });
        
        function isValidEmail(email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        console.log('Modal transfert initialisé avec emojis');
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

.col-form-label {
    font-weight: 500;
}

.text-danger {
    color: #dc3545 !important;
}

.is-valid {
    border-color: #28a745;
}

.is-invalid {
    border-color: #dc3545;
}

.select2-container--bootstrap4 .select2-selection {
    height: calc(1.5em + 0.75rem + 2px);
}
</style>