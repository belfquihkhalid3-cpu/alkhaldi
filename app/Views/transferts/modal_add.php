<div id="page-content" class="page-wrapper clearfix">
    
    <!-- En-tête de la page -->
    <div class="page-title clearfix">
        <h1>🚚 <?php echo ($model_info->id ?? false) ? 'Modifier le Transfert #' . $model_info->id : 'Nouveau Transfert'; ?></h1>
        <div class="title-button-group">
            <?php echo anchor(get_uri("transferts"), "⬅️ Retour à la liste", ["class" => "btn btn-outline-secondary"]); ?>
        </div>
    </div>

    <!-- Formulaire principal -->
    <div class="row">
        <div class="col-md-12">
            <?php echo form_open(get_uri("transferts/save"), array("id" => "transfert-form", "class" => "general-form", "role" => "form")); ?>
            
            <input type="hidden" name="id" value="<?php echo $model_info->id ?? ''; ?>" />
            
            <div class="row">
                <!-- Colonne de gauche -->
                <div class="col-md-8">
                    
                    <!-- Informations Client -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">👤 Informations Client</h5>
                        </div>
                        <div class="card-body">
                            
                            <div class="row">
                                <!-- Client existant ou nouveau -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client_id">
                                            👤 Client
                                        </label>
                                        <?php
                                        echo form_dropdown("client_id", $clients_dropdown ?? [], $model_info->client_id ?? "", 
                                            "class='form-control select2' id='client_id'");
                                        ?>
                                    </div>
                                </div>

                                <!-- Nombre de passagers -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre_passagers">
                                            👥 Nombre de Passagers
                                        </label>
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

                            <!-- Nom du client -->
                            <div class="form-group">
                                <label for="nom_client">
                                    📝 Nom du Client <span class="text-danger">*</span>
                                </label>
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

                            <div class="row">
                                <!-- Téléphone -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telephone_client">
                                            📞 Téléphone
                                        </label>
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

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email_client">
                                            📧 Email
                                        </label>
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
                        </div>
                    </div>

                    <!-- Détails du Transfert -->
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">🚚 Détails du Transfert</h5>
                        </div>
                        <div class="card-body">
                            
                            <div class="row">
                                <!-- Type de transfert -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type_transfert">
                                            🔄 Type de Transfert <span class="text-danger">*</span>
                                        </label>
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

                                <!-- Type de service -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="service_type">
                                            🎯 Type de Service <span class="text-danger">*</span>
                                        </label>
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
                                <label for="date_transfert">
                                    📅 Date du Transfert <span class="text-danger">*</span>
                                </label>
                                <?php
                                // Formater la date pour le champ HTML5
                                $date_value = '';
                                if (isset($model_info->date_transfert)) {
                                    $date_value = date('Y-m-d', strtotime($model_info->date_transfert));
                                } else {
                                    $date_value = date('Y-m-d');
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

                    <!-- Informations Vol -->
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">✈️ Informations Vol (optionnel)</h5>
                        </div>
                        <div class="card-body">
                            
                            <div class="row">
                                <!-- Numéro de vol -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="numero_vol">
                                            ✈️ Numéro de Vol
                                        </label>
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

                                <!-- Compagnie -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="compagnie">
                                            🏢 Compagnie
                                        </label>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="heure_arrivee_prevue">
                                            🛬 Heure d'Arrivée Prévue
                                        </label>
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
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="heure_depart_prevue">
                                            🛫 Heure de Départ Prévue
                                        </label>
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
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">📍 Itinéraire</h5>
                        </div>
                        <div class="card-body">
                            
                            <!-- Lieu de prise en charge -->
                            <div class="form-group">
                                <label for="lieu_prise_en_charge">
                                    🟢 Lieu de Prise en Charge <span class="text-danger">*</span>
                                </label>
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

                            <!-- Destination -->
                            <div class="form-group">
                                <label for="lieu_destination">
                                    🔴 Destination <span class="text-danger">*</span>
                                </label>
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

                            <!-- Adresse complète -->
                            <div class="form-group">
                                <label for="adresse_complete">
                                    📍 Adresse Complète / Détails
                                </label>
                                <?php
                                echo form_textarea(array(
                                    "id" => "adresse_complete",
                                    "name" => "adresse_complete",
                                    "value" => $model_info->adresse_complete ?? '',
                                    "class" => "form-control",
                                    "rows" => 3,
                                    "placeholder" => "Détails d'adresse supplémentaires, étage, appartement..."
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne de droite -->
                <div class="col-md-4">
                    
                    <!-- Assignation -->
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">👥 Assignation</h5>
                        </div>
                        <div class="card-body">
                            
                            <!-- Véhicule -->
                            <div class="form-group">
                                <label for="vehicle_id">
                                    🚗 Véhicule
                                </label>
                                <?php
                                echo form_dropdown("vehicle_id", $vehicles_dropdown ?? [], $model_info->vehicle_id ?? "", 
                                    "class='form-control select2' id='vehicle_id'");
                                ?>
                            </div>

                            <!-- Chauffeur -->
                            <div class="form-group">
                                <label for="chauffeur_id">
                                    👨‍💼 Chauffeur
                                </label>
                                <?php
                                echo form_dropdown("chauffeur_id", $chauffeurs_dropdown ?? [], $model_info->chauffeur_id ?? "", 
                                    "class='form-control select2' id='chauffeur_id'");
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Prix et Statut -->
                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">💰 Prix et Statut</h5>
                        </div>
                        <div class="card-body">
                            
                            <!-- Prix prévu -->
                            <div class="form-group">
                                <label for="prix_prevu">
                                    💰 Prix Prévu
                                </label>
                                <div class="input-group">
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
                                    <span class="input-group-text">MAD</span>
                                </div>
                            </div>

                            <!-- Statut -->
                            <div class="form-group">
                                <label for="statut">
                                    🏷️ Statut
                                </label>
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

                    <!-- Instructions -->
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">📝 Instructions</h5>
                        </div>
                        <div class="card-body">
                            
                            <!-- Instructions particulières -->
                            <div class="form-group">
                                <label for="instructions_particulieres">
                                    📝 Instructions Particulières
                                </label>
                                <?php
                                echo form_textarea(array(
                                    "id" => "instructions_particulieres",
                                    "name" => "instructions_particulieres",
                                    "value" => $model_info->instructions_particulieres ?? '',
                                    "class" => "form-control",
                                    "rows" => 4,
                                    "placeholder" => "Instructions spéciales pour le chauffeur, remarques importantes..."
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">⚡ Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    💾 <?php echo ($model_info->id ?? false) ? 'Modifier le Transfert' : 'Enregistrer le Transfert'; ?>
                                </button>
                                
                                <?php if (($model_info->id ?? false)): ?>
                                    <a href="<?php echo get_uri('transferts/view/' . $model_info->id); ?>" class="btn btn-outline-info">
                                        👁️ Voir les Détails
                                    </a>
                                <?php endif; ?>
                                
                                <a href="<?php echo get_uri('transferts'); ?>" class="btn btn-outline-secondary">
                                    ❌ Annuler
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#transfert-form").appForm({
            onSuccess: function (result) {
                if (result.success) {
                    if (result.id) {
                        // Rediriger vers la page de détails du transfert créé/modifié
                        window.location.href = '<?php echo get_uri("transferts/view/"); ?>' + result.id;
                    } else {
                        // Ou vers la liste
                        window.location.href = '<?php echo get_uri("transferts"); ?>';
                    }
                }
            }
        });
        
        // Initialiser Select2
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        }
        
        // Gestion du client existant vs nouveau
        $('#client_id').on('change', function() {
            var clientId = $(this).val();
            if (clientId) {
                // Client existant sélectionné - charger ses infos via AJAX
                loadClientInfo(clientId);
            }
        });
        
        // Fonction pour charger les infos client
        function loadClientInfo(clientId) {
            // TODO: Implémenter le chargement AJAX des infos client
            console.log('Chargement client:', clientId);
            /*
            $.ajax({
                url: '<?php echo get_uri("transferts/get_client_info"); ?>',
                type: 'POST',
                data: {client_id: clientId},
                success: function(response) {
                    if (response.success) {
                        $('#nom_client').val(response.data.name);
                        $('#telephone_client').val(response.data.phone);
                        $('#email_client').val(response.data.email);
                    }
                }
            });
            */
        }
        
        // Auto-complétion pour les lieux fréquents
        var lieuxFrequents = [
            'Aéroport Mohammed V - Casablanca',
            'Gare Casa Voyageurs',
            'Marina de Casablanca',
            'Hotel Hyatt Regency Casablanca',
            'Twin Center - Casablanca'
        ];
        
        $('#lieu_prise_en_charge, #lieu_destination').on('input', function() {
            // TODO: Implémenter l'auto-complétion
        });
        
        // Validation en temps réel
        $('#nom_client').on('blur', function() {
            validateField($(this), function(val) {
                return val.trim().length >= 3;
            }, 'Le nom doit contenir au moins 3 caractères');
        });
        
        $('#email_client').on('blur', function() {
            validateField($(this), function(val) {
                return !val || isValidEmail(val);
            }, 'Format d\'email invalide');
        });
        
        $('#telephone_client').on('blur', function() {
            validateField($(this), function(val) {
                return !val || val.replace(/\s/g, '').length >= 10;
            }, 'Numéro de téléphone trop court');
        });
        
        function validateField(field, validator, errorMsg) {
            var value = field.val();
            if (validator(value)) {
                field.removeClass('is-invalid').addClass('is-valid');
                field.siblings('.invalid-feedback').remove();
            } else {
                field.removeClass('is-valid').addClass('is-invalid');
                if (!field.siblings('.invalid-feedback').length) {
                    field.after('<div class="invalid-feedback">' + errorMsg + '</div>');
                }
            }
        }
        
        function isValidEmail(email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        // Calculateur de prix automatique basé sur la distance (optionnel)
        $('#lieu_prise_en_charge, #lieu_destination').on('blur', function() {
            if ($('#lieu_prise_en_charge').val() && $('#lieu_destination').val()) {
                // TODO: Calculer le prix estimé
                console.log('Calcul du prix...');
            }
        });
        
        console.log('Page transfert initialisée avec design amélioré');
    });
</script>

<style>
.card-header h5 {
    margin: 0;
    font-weight: 600;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.text-danger {
    color: #dc3545 !important;
}

.is-valid {
    border-color: #28a745;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='m2.3 6.73.8-.77-.76-.77-.8.77.76.77zm2.4-4.74L1.33 5.37l-.74-.76L4.93 1.26l.77.77zM7.93 1.26L4.59 4.59l-.76-.76 3.34-3.34.76.77z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.is-invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6.4.4.4-.4M5.8 7.4l.4-.4.4.4'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #dc3545;
}

.select2-container--bootstrap4 .select2-selection {
    height: calc(1.5em + 0.75rem + 2px);
    border: 1px solid #ced4da;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.input-group-text {
    background-color: #e9ecef;
    border: 1px solid #ced4da;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}

.d-grid .btn + .btn {
    margin-top: 0.5rem;
}

.page-title h1 {
    color: #495057;
    font-weight: 600;
}

@media (max-width: 768px) {
    .col-md-4 {
        margin-top: 2rem;
    }
}
</style>