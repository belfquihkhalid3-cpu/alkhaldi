<?php echo form_open(get_uri("jawaz/save_redistribution"), ["id" => "jawaz-redistribution-form", "class" => "general-form", "role" => "form"]); ?>

<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        
        <!-- Informations Badge -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">🔄 Redistribution Badge: <?php echo $model_info->numero_serie; ?></h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <p class="mb-1"><strong>📅 Dernière affectation:</strong> <?php echo format_to_date($model_info->date_affectation, false); ?></p>
                    <p class="mb-1"><strong>📅 Date de retour:</strong> <?php echo format_to_date($model_info->date_retour, false); ?></p>
                    <p class="mb-0"><strong>💰 Solde disponible:</strong> <?php echo to_currency($model_info->solde_retour ?? 0); ?></p>
                </div>

                <div class="row">
                    <!-- Véhicule -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vehicle_id">🚗 Nouveau Véhicule <span class="text-danger">*</span></label>
                            <?php
                            echo form_dropdown("vehicle_id", $vehicles_dropdown, "", 
                                "class='select2 validate-hidden' id='vehicle_id' data-rule-required='true' data-msg-required='" . lang("field_required") . "'");
                            ?>
                        </div>
                    </div>

                    <!-- Chauffeur -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="chauffeur_id">👤 Nouveau Chauffeur <span class="text-danger">*</span></label>
                            <?php
                            echo form_dropdown("chauffeur_id", $chauffeurs_dropdown, "", 
                                "class='select2 validate-hidden' id='chauffeur_id' data-rule-required='true' data-msg-required='" . lang("field_required") . "'");
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Date affectation -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_affectation">📅 Date d'Affectation <span class="text-danger">*</span></label>
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
                            <label for="solde">💳 Nouveau Solde <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <?php
                                echo form_input([
                                    "id" => "solde",
                                    "name" => "solde",
                                    "value" => $model_info->solde_retour ?? 0,
                                    "class" => "form-control",
                                    "type" => "number",
                                    "step" => "0.01",
                                    "min" => "0",
                                    "data-rule-required" => true,
                                    "data-msg-required" => lang("field_required"),
                                ]);
                                ?>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="use_current_balance" title="Utiliser le solde de retour">
                                        📋 Solde Retour
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                Solde à affecter au badge (peut être différent du solde de retour si rechargement)
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <div class="row">
                        <label for="evenement" class="col-md-3 col-form-label">
                            📝 Notes Redistribution
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_textarea([
                                "id" => "evenement",
                                "name" => "evenement",
                                "value" => "",
                                "class" => "form-control",
                                "rows" => 3,
                                "placeholder" => "Notes sur cette nouvelle affectation..."
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
    <button type="submit" class="btn btn-success">
        🔄 Redistribuer le Badge
    </button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#jawaz-redistribution-form").appForm({
            onSuccess: function (result) {
                $("#jawaz-table").appTable({newData: result.data, dataId: result.id});
                if (typeof updateStatistics === 'function') {
                    updateStatistics();
                }
            }
        });
        
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            dropdownParent: $('.modal-body')
        });
        
        $('#use_current_balance').on('click', function() {
            var soldeRetour = <?php echo $model_info->solde_retour ?? 0; ?>;
            $('#solde').val(soldeRetour);
        });
    });
</script>