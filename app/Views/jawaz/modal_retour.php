<?php echo form_open(get_uri("jawaz/save_retour"), ["id" => "jawaz-retour-form", "class" => "general-form", "role" => "form"]); ?>

<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        
        <!-- Informations Badge -->
        <div class="card mb-3">
            <div class="card-header bg-warning text-white">
                <h6 class="mb-0">↩️ Retour Badge: <?php echo $model_info->numero_serie; ?></h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <p class="mb-1"><strong>🚗 Véhicule:</strong> <?php echo $model_info->vehicle_name ?? 'Non affecté'; ?></p>
                    <p class="mb-1"><strong>👤 Chauffeur:</strong> <?php echo $model_info->chauffeur_name ?? 'Non affecté'; ?></p>
                    <p class="mb-0"><strong>💰 Solde actuel:</strong> <?php echo to_currency($model_info->solde ?? 0); ?></p>
                </div>

                <div class="row">
                    <!-- Date de retour -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_retour">📅 Date de Retour <span class="text-danger">*</span></label>
                            <?php
                            echo form_input([
                                "id" => "date_retour",
                                "name" => "date_retour",
                                "value" => date('Y-m-d'),
                                "class" => "form-control",
                                "type" => "date",
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ]);
                            ?>
                        </div>
                    </div>

                    <!-- Solde restant -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="solde_retour">💳 Solde Restant <span class="text-danger">*</span></label>
                            <?php
                            echo form_input([
                                "id" => "solde_retour",
                                "name" => "solde_retour",
                                "value" => $model_info->solde ?? 0,
                                "class" => "form-control",
                                "type" => "number",
                                "step" => "0.01",
                                "min" => "0",
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ]);
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Motif de retour -->
                <div class="form-group">
                    <div class="row">
                        <label for="motif_retour" class="col-md-3 col-form-label">
                            📝 Motif du Retour
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_textarea([
                                "id" => "motif_retour",
                                "name" => "motif_retour",
                                "value" => "",
                                "class" => "form-control",
                                "rows" => 3,
                                "placeholder" => "Raison du retour du badge..."
                            ]);
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Peut redistribuer -->
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <div class="form-check">
                                <?php
                                echo form_checkbox([
                                    "id" => "peut_redistribuer",
                                    "name" => "peut_redistribuer",
                                    "value" => "1",
                                    "checked" => true,
                                    "class" => "form-check-input"
                                ]);
                                ?>
                                <label class="form-check-label" for="peut_redistribuer">
                                    🔄 Peut être redistribué
                                </label>
                                <small class="form-text text-muted">
                                    Cochez si ce badge peut être réaffecté à un autre véhicule/chauffeur
                                </small>
                            </div>
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
    <button type="submit" class="btn btn-warning">
        ↩️ Retourner le Badge
    </button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#jawaz-retour-form").appForm({
            onSuccess: function (result) {
                $("#jawaz-table").appTable({newData: result.data, dataId: result.id});
                if (typeof updateStatistics === 'function') {
                    updateStatistics();
                }
            }
        });
    });
</script>