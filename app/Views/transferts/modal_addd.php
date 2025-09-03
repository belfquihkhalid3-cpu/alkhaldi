<?php echo form_open(get_uri("transferts/save"), ["id" => "transferts-add-form", "class" => "general-form", "role" => "form"]); ?>
<div class="modal-body clearfix">
    <!-- ID caché pour nouveau transfert -->
    <input type="hidden" name="id" value="0" />
    
    <!-- En-tête du modal -->
    <div class="alert alert-primary mb-3">
        <h6><i data-feather="plus-circle" class="icon-16"></i> Nouveau Transfert</h6>
        <p class="mb-0">Créer une nouvelle réservation de transfert</p>
    </div>

    <!-- Informations Client -->
    <div class="card mb-3">
        <div class="card-header">
            <h6><i data-feather="user" class="icon-16"></i> Informations Client</h6>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="client_id" class="col-md-3">Client existant</label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("client_id", $clients_dropdown ?? ["" => "- Nouveau client -"], 
                        "", "class='select2' id='client_id'");
                    ?>
                    <small class="form-text text-muted">Sélectionnez un client existant ou laissez vide pour un nouveau</small>
                </div>
            </div>

            <div class="form-group">
                <label for="nom_client" class="col-md-3">Nom du client *</label>
                <div class="col-md-9">
                    <?php
                    echo form_input([
                        "id" => "nom_client",
                        "name" => "nom_client",
                        "value" => "",
                        "class" => "form-control",
                        "placeholder" => "Nom complet du client",
                        "data-rule-required" => true,
                        "data-msg-required" => "Ce champ est requis",
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="telephone_client">Téléphone</label>
                        <?php
                        echo form_input([
                            "id" => "telephone_client",
                            "name" => "telephone_client",
                            "value" => "",
                            "class" => "form-control",
                            "placeholder" => "+212 6XX XXX XXX"
                        ]);
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email_client">Email</label>
                        <?php
                        echo form_input([
                            "id" => "email_client",
                            "name" => "email_client",
                            "type" => "email",
                            "value" => "",
                            "class" => "form-control",
                            "placeholder" => "email@exemple.com"
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="nombre_passagers" class="col-md-3">Nombre de passagers</label>
                <div class="col-md-9">
                    <?php
                    echo form_input([
                        "id" => "nombre_passagers",
                        "name" => "nombre_passagers",
                        "type" => "number",
                        "value" => "1",
                        "class" => "form-control",
                        "min" => "1",
                        "max" => "8"
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Type de Transfert -->
    <div class="card mb-3">
        <div class="card-header">
            <h6><i data-feather="arrow-right" class="icon-16"></i> Type de Service</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type_transfert">Type de transfert *</label>
                        <?php
                        $types_transfert = [
                            "arrivee" => "Arrivée (Aéroport → Destination)",
                            "depart" => "Départ (Origine → Aéroport)",
                            "aller_retour" => "Aller-retour"
                        ];
                        echo form_dropdown("type_transfert", $types_transfert, "", 
                            "class='select2' id='type_transfert' data-rule-required='true' data-msg-required='Ce champ est requis'");
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="service_type">Type de service *</label>
                        <?php
                        $services_types = [
                            "aeroport_hotel" => "Aéroport ↔ Hôtel",
                            "hotel_aeroport" => "Hôtel ↔ Aéroport",
                            "gare_hotel" => "Gare ↔ Hôtel",
                            "autre" => "Autre"
                        ];
                        echo form_dropdown("service_type", $services_types, "", 
                            "class='select2' id='service_type' data-rule-required='true' data-msg-required='Ce champ est requis'");
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations Vol -->
    <div class="card mb-3" id="vol_info_card">
        <div class="card-header">
            <h6><i data-feather="plane" class="icon-16"></i> Informations Vol (Optionnel)</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="numero_vol">Numéro de vol</label>
                        <?php
                        echo form_input([
                            "id" => "numero_vol",
                            "name" => "numero_vol",
                            "value" => "",
                            "class" => "form-control",
                            "placeholder" => "Ex: AT1234"
                        ]);
                        ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="compagnie">Compagnie</label>
                        <?php
                        echo form_input([
                            "id" => "compagnie",
                            "name" => "compagnie",
                            "value" => "",
                            "class" => "form-control",
                            "placeholder" => "Ex: Royal Air Maroc"
                        ]);
                        ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="heure_arrivee_prevue">Heure d'arrivée</label>
                        <?php
                        echo form_input([
                            "id" => "heure_arrivee_prevue",
                            "name" => "heure_arrivee_prevue",
                            "type" => "time",
                            "value" => "",
                            "class" => "form-control"
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Itinéraire -->
    <div class="card mb-3">
        <div class="card-header">
            <h6><i data-feather="map-pin" class="icon-16"></i> Itinéraire</h6>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="date_transfert">Date du transfert *</label>
                <div class="col-md-12">
                    <?php
                    echo form_input([
                        "id" => "date_transfert",
                        "name" => "date_transfert",
                        "type" => "date",
                        "value" => date('Y-m-d'),
                        "class" => "form-control",
                        "data-rule-required" => true,
                        "data-msg-required" => "Ce champ est requis"
                    ]);
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="lieu_prise_en_charge">Lieu de prise en charge *</label>
                <div class="col-md-12">
                    <?php
                    echo form_input([
                        "id" => "lieu_prise_en_charge",
                        "name" => "lieu_prise_en_charge",
                        "value" => "",
                        "class" => "form-control",
                        "placeholder" => "Ex: Aéroport Mohammed V, Hôtel XYZ...",
                        "data-rule-required" => true,
                        "data-msg-required" => "Ce champ est requis"
                    ]);
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="lieu_destination">Destination *</label>
                <div class="col-md-12">
                    <?php
                    echo form_input([
                        "id" => "lieu_destination",
                        "name" => "lieu_destination",
                        "value" => "",
                        "class" => "form-control",
                        "placeholder" => "Ex: Hôtel ABC, Aéroport...",
                        "data-rule-required" => true,
                        "data-msg-required" => "Ce champ est requis"
                    ]);
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="adresse_complete">Adresse complète</label>
                <div class="col-md-12">
                    <?php
                    echo form_textarea([
                        "id" => "adresse_complete",
                        "name" => "adresse_complete",
                        "value" => "",
                        "class" => "form-control",
                        "rows" => 2,
                        "placeholder" => "Adresse détaillée, numéro de terminal, instructions spéciales..."
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignation et Prix -->
    <div class="card mb-3">
        <div class="card-header">
            <h6><i data-feather="settings" class="icon-16"></i> Assignation et Tarification</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vehicle_id">Véhicule</label>
                        <?php
                        echo form_dropdown("vehicle_id", $vehicles_dropdown ?? ["" => "- Assigner plus tard -"], 
                            "", "class='select2' id='vehicle_id'");
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="chauffeur_id">Chauffeur</label>
                        <?php
                        echo form_dropdown("chauffeur_id", $chauffeurs_dropdown ?? ["" => "- Assigner plus tard -"], 
                            "", "class='select2' id='chauffeur_id'");
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="prix_prevu">Prix prévu (MAD)</label>
                <div class="col-md-12">
                    <div class="input-group">
                        <?php
                        echo form_input([
                            "id" => "prix_prevu",
                            "name" => "prix_prevu",
                            "type" => "number",
                            "step" => "0.01",
                            "min" => "0",
                            "value" => "",
                            "class" => "form-control",
                            "placeholder" => "0.00"
                        ]);
                        ?>
                        <div class="input-group-append">
                            <span class="input-group-text">MAD</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="form-group">
        <label for="instructions_particulieres">Instructions particulières</label>
        <div class="col-md-12">
            <?php
            echo form_textarea([
                "id" => "instructions_particulieres",
                "name" => "instructions_particulieres",
                "value" => "",
                "class" => "form-control",
                "rows" => 3,
                "placeholder" => "Instructions spéciales, besoins particuliers, contacts supplémentaires..."
            ]);
            ?>
        </div>
    </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal">
        <span data-feather="x" class="icon-16"></span> Annuler
    </button>
    <button type="submit" class="btn btn-primary">
        <span data-feather="check-circle" class="icon-16"></span> Créer le transfert
    </button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#transferts-add-form").appForm({
            onSuccess: function (result) {
                $("#transferts-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        // Initialiser Select2 et Feather Icons
        $('.select2').select2();
        feather.replace();
        
        // Focus sur le nom du client
        $('#nom_client').focus();
        
        // Auto-remplissage depuis client existant
        $('#client_id').on('change', function() {
            var clientId = $(this).val();
            if (clientId) {
                // Ici vous pouvez faire un appel AJAX pour récupérer les infos du client
                // et pré-remplir nom, téléphone, email
                console.log('Client sélectionné:', clientId);
            } else {
                // Vider les champs si "nouveau client"
                $('#nom_client, #telephone_client, #email_client').val('');
            }
        });
        
        // Logique pour masquer/afficher les infos vol selon le type
        $('#type_transfert, #service_type').on('change', function() {
            var type = $('#type_transfert').val();
            var service = $('#service_type').val();
            
            if (service === 'aeroport_hotel' || service === 'hotel_aeroport') {
                $('#vol_info_card').show();
            } else {
                $('#vol_info_card').hide();
                $('#numero_vol, #compagnie, #heure_arrivee_prevue').val('');
            }
        });
        
        // Suggestions de lieux selon le service
        $('#service_type').on('change', function() {
            var service = $(this).val();
            var suggestions = {
                'aeroport_hotel': {
                    prise: 'Aéroport Mohammed V - Casablanca',
                    dest: 'Hôtel'
                },
                'hotel_aeroport': {
                    prise: 'Hôtel',
                    dest: 'Aéroport Mohammed V - Casablanca'
                },
                'gare_hotel': {
                    prise: 'Gare Casa-Port',
                    dest: 'Hôtel'
                }
            };
            
            if (suggestions[service]) {
                if (!$('#lieu_prise_en_charge').val()) {
                    $('#lieu_prise_en_charge').attr('placeholder', suggestions[service].prise);
                }
                if (!$('#lieu_destination').val()) {
                    $('#lieu_destination').attr('placeholder', suggestions[service].dest);
                }
            }
        });
        // Auto-remplissage depuis client existant
$('#client_id').on('change', function() {
    var clientId = $(this).val();
    if (clientId) {
        // AJAX pour récupérer les infos du client
        $.ajax({
            url: '<?php echo get_uri("transferts/get_client_info") ?>',
            type: 'POST',
            data: {client_id: clientId},
            success: function(result) {
                try {
                    result = JSON.parse(result);
                    if (result.success) {
                        $('#nom_client').val(result.company_name || '');
                        $('#telephone_client').val(result.phone || '');
                        $('#email_client').val(''); // Pas d'email dans votre structure
                    }
                } catch(e) {
                    console.log('Erreur parsing client info');
                }
            }
        });
    } else {
        // Vider les champs si "nouveau client"
        $('#nom_client, #telephone_client, #email_client').val('');
    }
});
        // Validation du nombre de passagers
        $('#nombre_passagers').on('change', function() {
            var nb = parseInt($(this).val());
            if (nb > 4) {
                $(this).after('<small class="text-warning">Véhicule grande capacité recommandé</small>');
            } else {
                $(this).next('small').remove();
            }
        });
        
        console.log('Modal ajout transfert initialisé');
    });
</script>