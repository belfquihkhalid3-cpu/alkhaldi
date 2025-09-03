<?php echo form_open(get_uri("jawaz/save_retour"), ["id" => "jawaz-retour-form", "class" => "general-form", "role" => "form"]); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    
    <!-- Informations du badge -->
    <div class="alert alert-info">
        <h5><i data-feather="credit-card" class="icon-16"></i> Badge: <?php echo $model_info->numero_serie; ?></h5>
        <p class="mb-1"><strong>Véhicule:</strong> <?php echo $model_info->vehicle_name ?? 'Non affecté'; ?></p>
        <p class="mb-1"><strong>Chauffeur:</strong> <?php echo $model_info->chauffeur_name ?? 'Non affecté'; ?></p>
        <p class="mb-0"><strong>Solde actuel:</strong> <?php echo to_currency($model_info->solde ?? 0); ?></p>
    </div>

    <div class="form-group">
        <label for="date_retour" class="col-md-3">Date de retour *</label>
        <div class="col-md-9">
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

    <div class="form-group">
        <label for="solde_retour" class="col-md-3">Solde restant *</label>
        <div class="col-md-9">
            <?php
            echo form_input([
                "id" => "solde_retour",
                "name" => "solde_retour",
                "value" => $model_info->solde ?? 0,
                "class" => "form-control",
                "type" => "number",
                "step" => "0.01",
                "min" => "0",
                "placeholder" => "Montant restant sur le badge",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ]);
            ?>
            <small class="form-text text-muted">
                Montant restant sur le badge au moment du retour
            </small>
        </div>
    </div>

    <div class="form-group">
        <label for="motif_retour" class="col-md-3">Motif du retour</label>
        <div class="col-md-9">
            <?php
            echo form_textarea([
                "id" => "motif_retour",
                "name" => "motif_retour",
                "value" => "",
                "class" => "form-control",
                "rows" => 3,
                "placeholder" => "Raison du retour du badge (optionnel)..."
            ]);
            ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-9 col-md-offset-3">
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
                    <i data-feather="repeat" class="icon-16"></i> 
                    Autoriser la redistribution de ce badge
                </label>
                <small class="form-text text-muted">
                    Si décoché, ce badge ne pourra pas être redistribué automatiquement
                </small>
            </div>
        </div>
    </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal">
        <span data-feather="x" class="icon-16"></span> Annuler
    </button>
    <button type="submit" class="btn btn-warning">
        <span data-feather="corner-up-left" class="icon-16"></span> Confirmer le retour
    </button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#jawaz-retour-form").appForm({
            onSuccess: function (result) {
                $("#jawaz-table").appTable({newData: result.data, dataId: result.id});
                // Actualiser les statistiques si possible
                if (typeof updateStatistics === 'function') {
                    updateStatistics();
                }
            }
        });
        
        // Initialiser les icônes Feather
        feather.replace();
        
        // Validation côté client
        $('#solde_retour').on('input', function() {
            var soldeActuel = <?php echo $model_info->solde ?? 0; ?>;
            var soldeRetour = parseFloat($(this).val()) || 0;
            
            if (soldeRetour > soldeActuel) {
                $(this).addClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
                $(this).after('<div class="invalid-feedback">Le solde de retour ne peut pas être supérieur au solde actuel</div>');
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
        });
    });
</script>