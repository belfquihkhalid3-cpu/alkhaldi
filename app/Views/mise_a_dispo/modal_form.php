<?php echo form_open(get_uri("mise_a_dispo/save"), array("id" => "mise-a-dispo-form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix" style="max-height: 80vh; overflow-y: auto;">
    <?php echo form_hidden("id", $model_info->id ?? ""); ?>
    
    <div class="container-fluid">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h4 class="text-primary">
                    🚗 <?= ($model_info->id ?? false) ? 'Modifier la Mise à Disposition' : 'Nouvelle Mise à Disposition' ?>
                </h4>
                <p class="text-muted">Gestion complète des services de mise à disposition</p>
            </div>
        </div>

        <div class="row">
            <!-- Section Client -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">👤 Informations Client</h5>
                    </div>
                    <div class="card-body">
                        <!-- Client existant -->
                        <div class="form-group mb-3">
                            <label for="client_id" class="form-label">🏢 Client Existant</label>
                            <?php
                            echo form_dropdown(
                                "client_id",
                                $clients_dropdown ?? ["" => "- Nouveau client -"],
                                $model_info->client_id ?? "",
                                "class='form-control select2' id='client_id'"
                            );
                            ?>
                            <small class="text-muted">Sélectionnez un client existant ou laissez vide pour un nouveau</small>
                        </div>

                        <!-- Nom client -->
                        <div class="form-group mb-3">
                            <label for="nom_client" class="form-label">📝 Nom du Client <span class="text-danger">*</span></label>
                            <?php
                            echo form_input(array(
                                "id" => "nom_client",
                                "name" => "nom_client",
                                "value" => $model_info->nom_client ?? "",
                                "class" => "form-control",
                                "placeholder" => "Nom complet ou raison sociale",
                                "required" => true
                            ));
                            ?>
                        </div>

                        <!-- Téléphone -->
                        <div class="form-group mb-3">
                            <label for="telephone_client" class="form-label">📞 Téléphone</label>
                            <?php
                            echo form_input(array(
                                "id" => "telephone_client",
                                "name" => "telephone_client",
                                "value" => $model_info->telephone_client ?? "",
                                "class" => "form-control",
                                "placeholder" => "+212 6XX XX XX XX"
                            ));
                            ?>
                        </div>

                        <!-- Email -->
                        <div class="form-group mb-3">
                            <label for="email_client" class="form-label">📧 Email</label>
                            <?php
                            echo form_input(array(
                                "id" => "email_client",
                                "name" => "email_client",
                                "value" => $model_info->email_client ?? "",
                                "class" => "form-control",
                                "type" => "email",
                                "placeholder" => "client@email.com"
                            ));
                            ?>
                        </div>

                        <!-- Hôtel partenaire -->
                        <div class="form-group mb-3">
                            <label for="hotel_partenaire" class="form-label">🏨 Hôtel Partenaire</label>
                            <?php
                            echo form_input(array(
                                "id" => "hotel_partenaire",
                                "name" => "hotel_partenaire",
                                "value" => $model_info->hotel_partenaire ?? "",
                                "class" => "form-control",
                                "placeholder" => "Nom de l'hôtel (optionnel)"
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Service -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">⚙️ Type de Service</h5>
                    </div>
                    <div class="card-body">
                        <!-- Type de service -->
                        <div class="form-group mb-4">
                            <label for="type_service" class="form-label">🔧 Service Demandé <span class="text-danger">*</span></label>
                            <?php
                            echo form_dropdown(
                                "type_service",
                                array(
                                    "" => "- Sélectionner le type -",
                                    "chauffeur_seul" => "👨‍✈️ Chauffeur uniquement",
                                    "vehicule_seul" => "🚗 Véhicule uniquement",
                                    "chauffeur_vehicule" => "🚗👨‍✈️ Chauffeur + Véhicule"
                                ),
                                $model_info->type_service ?? "",
                                "class='form-control' required id='type_service'"
                            );
                            ?>
                            <small class="text-muted">Le type de service détermine les ressources nécessaires</small>
                        </div>

                        <!-- Assignations conditionnelles -->
                        <div id="assignation-section">
                            <!-- Chauffeur -->
                            <div class="form-group mb-3" id="chauffeur-group">
                                <label for="chauffeur_id" class="form-label">👨‍✈️ Chauffeur Assigné</label>
                                <?php
                                echo form_dropdown(
                                    "chauffeur_id",
                                    $chauffeurs_dropdown ?? ["" => "- Aucun chauffeur -"],
                                    $model_info->chauffeur_id ?? "",
                                    "class='form-control select2'"
                                );
                                ?>
                            </div>

                            <!-- Véhicule -->
                            <div class="form-group mb-3" id="vehicule-group">
                                <label for="vehicle_id" class="form-label">🚗 Véhicule Assigné</label>
                                <?php
                                echo form_dropdown(
                                    "vehicle_id",
                                    $vehicles_dropdown ?? ["" => "- Aucun véhicule -"],
                                    $model_info->vehicle_id ?? "",
                                    "class='form-control select2'"
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Planning -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">📅 Planification</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="date_debut" class="form-label">📅 Date & Heure de Début <span class="text-danger">*</span></label>
                                    <?php
                                    echo form_input(array(
                                        "id" => "date_debut",
                                        "name" => "date_debut",
                                        "value" => $model_info->date_debut ?? "",
                                        "class" => "form-control",
                                        "type" => "datetime-local",
                                        "required" => true
                                    ));
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="date_fin" class="form-label">📅 Date & Heure de Fin <span class="text-danger">*</span></label>
                                    <?php
                                    echo form_input(array(
                                        "id" => "date_fin",
                                        "name" => "date_fin",
                                        "value" => $model_info->date_fin ?? "",
                                        "class" => "form-control",
                                        "type" => "datetime-local",
                                        "required" => true
                                    ));
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="lieu_prise_en_charge" class="form-label">📍 Lieu de Prise en Charge <span class="text-danger">*</span></label>
                                    <?php
                                    echo form_input(array(
                                        "id" => "lieu_prise_en_charge",
                                        "name" => "lieu_prise_en_charge",
                                        "value" => $model_info->lieu_prise_en_charge ?? "",
                                        "class" => "form-control",
                                        "placeholder" => "Adresse complète de prise en charge",
                                        "required" => true
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Tarification -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">💰 Tarification</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="tarif_type" class="form-label">📊 Type de Tarif</label>
                                    <?php
                                    echo form_dropdown(
                                        "tarif_type",
                                        array(
                                            "journalier" => "🗓️ Journalier",
                                            "horaire" => "⏰ Horaire",
                                            "forfait" => "💼 Forfait"
                                        ),
                                        $model_info->tarif_type ?? "journalier",
                                        "class='form-control'"
                                    );
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="prix_unitaire" class="form-label">💵 Prix Unitaire</label>
                                    <div class="input-group">
                                        <?php
                                        echo form_input(array(
                                            "id" => "prix_unitaire",
                                            "name" => "prix_unitaire",
                                            "value" => $model_info->prix_unitaire ?? "",
                                            "class" => "form-control",
                                            "type" => "number",
                                            "step" => "0.01",
                                            "placeholder" => "0.00"
                                        ));
                                        ?>
                                        <span class="input-group-text">MAD</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="nombre_unites" class="form-label">🔢 Nombre d'Unités</label>
                                    <?php
                                    echo form_input(array(
                                        "id" => "nombre_unites",
                                        "name" => "nombre_unites",
                                        "value" => $model_info->nombre_unites ?? "1",
                                        "class" => "form-control",
                                        "type" => "number",
                                        "min" => "1"
                                    ));
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="prix_total" class="form-label">💰 <strong>Prix Total</strong></label>
                                    <div class="input-group">
                                        <?php
                                        echo form_input(array(
                                            "id" => "prix_total",
                                            "name" => "prix_total",
                                            "value" => $model_info->prix_total ?? "",
                                            "class" => "form-control fw-bold",
                                            "type" => "number",
                                            "step" => "0.01",
                                            "placeholder" => "0.00"
                                        ));
                                        ?>
                                        <span class="input-group-text bg-success text-white">MAD</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Instructions -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">📝 Instructions Spéciales</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="instructions_speciales" class="form-label">💬 Remarques et Instructions</label>
                            <?php
                            echo form_textarea(array(
                                "id" => "instructions_speciales",
                                "name" => "instructions_speciales",
                                "value" => $model_info->instructions_speciales ?? "",
                                "class" => "form-control",
                                "rows" => 4,
                                "placeholder" => "Instructions spéciales pour le chauffeur, remarques importantes..."
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
    <button type="submit" class="btn btn-primary btn-lg">
        💾 <?= ($model_info->id ?? false) ? 'Modifier la Mise à Disposition' : 'Enregistrer la Mise à Disposition' ?>
    </button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#mise-a-dispo-form").appForm({
            onSuccess: function (result) {
                if (result.success) {
                    appAlert.success(result.message, {duration: 3000});
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
        });
        
        // Initialiser Select2 si disponible
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
                dropdownParent: $('.modal-body')
            });
        }
        
        // Gestion des assignations selon le type de service
        $('#type_service').change(function() {
            var typeService = $(this).val();
            
            if (typeService === 'chauffeur_seul') {
                $('#chauffeur-group').show();
                $('#vehicule-group').hide();
            } else if (typeService === 'vehicule_seul') {
                $('#chauffeur-group').hide();
                $('#vehicule-group').show();
            } else if (typeService === 'chauffeur_vehicule') {
                $('#chauffeur-group').show();
                $('#vehicule-group').show();
            } else {
                $('#chauffeur-group').show();
                $('#vehicule-group').show();
            }
        });
        
        // Déclencher le changement initial
        $('#type_service').trigger('change');
        
        // Calcul automatique du prix total
        $('#prix_unitaire, #nombre_unites').on('input', function() {
            var prix_unitaire = parseFloat($('#prix_unitaire').val()) || 0;
            var nombre_unites = parseInt($('#nombre_unites').val()) || 1;
            var prix_total = prix_unitaire * nombre_unites;
            $('#prix_total').val(prix_total.toFixed(2));
        });
        
        setTimeout(function () {
            $("#nom_client").focus();
        }, 200);
    });
</script>