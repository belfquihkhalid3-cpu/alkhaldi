<?php echo form_open(get_uri("jawaz/save_redistribution"), ["id" => "jawaz-redistribution-form", "class" => "general-form", "role" => "form"]); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    
    <!-- Informations du badge -->
    <div class="alert alert-success">
        <h5><i data-feather="repeat" class="icon-16"></i> Redistribution Badge: <?php echo $model_info->numero_serie; ?></h5>
        <p class="mb-1"><strong>Dernière affectation:</strong> <?php echo format_to_date($model_info->date_affectation, false); ?></p>
        <p class="mb-1"><strong>Date de retour:</strong> <?php echo format_to_date($model_info->date_retour, false); ?></p>
        <p class="mb-0"><strong>Solde disponible:</strong> <?php echo to_currency($model_info->solde_retour ?? 0); ?></p>
    </div>

    <div class="form-group">
        <label for="vehicle_id" class="col-md-3">Nouveau véhicule *</label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("vehicle_id", $vehicles_dropdown, "", 
                "class='select2 validate-hidden' id='vehicle_id' data-rule-required='true' data-msg-required='" . lang("field_required") . "'");
            ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="chauffeur_id" class="col-md-3">Nouveau chauffeur *</label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("chauffeur_id", $chauffeurs_dropdown, "", 
                "class='select2 validate-hidden' id='chauffeur_id' data-rule-required='true' data-msg-required='" . lang("field_required") . "'");
            ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="date_affectation" class="col-md-3">Date d'affectation *</label>
        <div class="col-md-9">
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
    
    <div class="form-group">
        <label for="solde" class="col-md-3">Nouveau solde *</label>
        <div class="col-md-9">
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
                        <i data-feather="copy" class="icon-16"></i> Utiliser solde retour
                    </button>
                </div>
            </div>
            <small class="form-text text-muted">
                Solde à affecter au badge (peut être différent du solde de retour si rechargement)
            </small>
        </div>
    </div>

    <div class="form-group">
        <label for="evenement" class="col-md-3">Notes sur la redistribution</label>
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

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal">
        <span data-feather="x" class="icon-16"></span> Annuler
    </button>
    <button type="submit" class="btn btn-success">
        <span data-feather="repeat" class="icon-16"></span> Redistribuer le badge
    </button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#jawaz-redistribution-form").appForm({
            onSuccess: function (result) {
                $("#jawaz-table").appTable({newData: result.data, dataId: result.id});
                // Actualiser les statistiques si possible
                if (typeof updateStatistics === 'function') {
                    updateStatistics();
                }
            }
        });
        
        // Initialiser Select2 et Feather Icons
        $('.select2').select2();
        feather.replace();
        
        // Bouton pour utiliser le solde de retour
        $('#use_current_balance').on('click', function() {
            var soldeRetour = <?php echo $model_info->solde_retour ?? 0; ?>;
            $('#solde').val(soldeRetour);
        });
        
        // Validation pour éviter les conflits véhicule/chauffeur
        $('#vehicle_id, #chauffeur_id').on('change', function() {
            // Ici vous pourriez ajouter une validation AJAX pour vérifier
            // qu'aucun autre badge actif n'est assigné au même véhicule/chauffeur
            // selon vos règles métier
        });
    });
</script>