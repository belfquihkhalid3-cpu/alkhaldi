<?php echo form_open(get_uri("jawaz/save"), ["id" => "jawaz-add-form", "class" => "general-form", "role" => "form"]); ?>

<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="0" />

        <!-- Informations Badge -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">🎯 Informations Badge Jawaz</h6>
            </div>
            <div class="card-body">
                
                <!-- Numéro de série -->
                <div class="form-group">
                    <div class="row">
                        <label for="numero_serie" class="col-md-3 col-form-label">
                            🏷 Numéro Série <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_input([
                                "id" => "numero_serie",
                                "name" => "numero_serie",
                                "value" => "",
                                "class" => "form-control",
                                "placeholder" => "Numéro de série du badge Jawaz",
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ]);
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Véhicule -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vehicle_id">🚗 Véhicule</label>
                            <?php
                            echo form_dropdown("vehicle_id", $vehicles_dropdown ?? ["" => "- Aucun véhicule disponible -"], 
                                "", 
                                "class='select2 validate-hidden' id='vehicle_id'");
                            ?>
                        </div>
                    </div>

                    <!-- Chauffeur -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="chauffeur_id">👤 Chauffeur</label>
                            <?php
                            echo form_dropdown("chauffeur_id", $chauffeurs_dropdown ?? ["" => "- Aucun chauffeur disponible -"], 
                                "", 
                                "class='select2 validate-hidden' id='chauffeur_id'");
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Date affectation -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_affectation">📅 Date Affectation <span class="text-danger">*</span></label>
                            <?php
                            echo form_input([
                                "id" => "date_affectation",
                                "name" => "date_affectation",
                                "value" => date('Y-m-d'),
                                "class" => "form-control",
                                "type" => "date",
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ]);
                            ?>
                        </div>
                    </div>

                    <!-- Solde -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="solde">💳 Solde Initial</label>
                            <?php
                            echo form_input([
                                "id" => "solde",
                                "name" => "solde",
                                "value" => "",
                                "class" => "form-control",
                                "type" => "number",
                                "step" => "0.01",
                                "min" => "0",
                                "placeholder" => "0.00",
                            ]);
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Statut -->
                <div class="form-group">
                    <div class="row">
                        <label for="statut" class="col-md-3 col-form-label">
                            📊 Statut
                        </label>
                        <div class="col-md-9">
                            <?php
                            $statuts_options = [
                                'actif' => 'Actif (En circulation)',
                                'inactif' => 'Inactif',
                                'retourne' => 'Retourné',
                                'en_maintenance' => 'En maintenance'
                            ];
                            echo form_dropdown("statut", $statuts_options, "actif", "class='select2'");
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <div class="row">
                        <label for="evenement" class="col-md-3 col-form-label">
                            📝 Notes
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_textarea([
                                "id" => "evenement",
                                "name" => "evenement",
                                "value" => "",
                                "class" => "form-control",
                                "rows" => 3,
                                "placeholder" => "Notes sur ce badge..."
                            ]);
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
        💾 Enregistrer le Badge
    </button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#jawaz-add-form").appForm({
            onSuccess: function (result) {
                $("#jawaz-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('.select2').select2();
        feather.replace();
    });
</script>