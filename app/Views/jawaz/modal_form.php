<?php echo form_open(get_uri("jawaz/save"), ["id" => "jawaz-form", "class" => "general-form", "role" => "form"]); ?>
<div class="modal-body clearfix">
    <!-- CORRECTION: S'assurer que l'ID est 0 pour nouveau badge -->
    <input type="hidden" name="id" value="<?php echo (isset($model_info->id) && $model_info->id > 0) ? $model_info->id : 0; ?>" />

    <div class="form-group">
        <label for="numero_serie" class="col-md-3">
            <i data-feather="hash" class="icon-16"></i> Numéro de série *
        </label>
        <div class="col-md-9">
            <?php
            echo form_input([
                "id" => "numero_serie",
                "name" => "numero_serie",
                "value" => (isset($model_info->numero_serie)) ? $model_info->numero_serie : "",
                "class" => "form-control",
                "placeholder" => "Numéro de série du badge Jawaz",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ]);
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="vehicle_id" class="col-md-3">
            <i data-feather="truck" class="icon-16"></i> Véhicule affecté
        </label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("vehicle_id", $vehicles_dropdown ?? ["" => "- Aucun véhicule disponible -"], 
                (isset($model_info->vehicle_id)) ? $model_info->vehicle_id : "", 
                "class='select2 validate-hidden' id='vehicle_id'");
            ?>
            <small class="form-text text-muted">Laissez vide si badge non affecté</small>
        </div>
    </div>
    
    <div class="form-group">
        <label for="chauffeur_id" class="col-md-3">
            <i data-feather="user" class="icon-16"></i> Chauffeur affecté
        </label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("chauffeur_id", $chauffeurs_dropdown ?? ["" => "- Aucun chauffeur disponible -"], 
                (isset($model_info->chauffeur_id)) ? $model_info->chauffeur_id : "", 
                "class='select2 validate-hidden' id='chauffeur_id'");
            ?>
            <small class="form-text text-muted">Laissez vide si badge non affecté</small>
        </div>
    </div>
    
    <div class="form-group">
        <label for="date_affectation" class="col-md-3">
            <i data-feather="calendar" class="icon-16"></i> Date d'affectation *
        </label>
        <div class="col-md-9">
            <?php
            // CORRECTION: Valeur par défaut plus robuste
            $date_value = date('Y-m-d'); // Par défaut : aujourd'hui
            
            if (isset($model_info->date_affectation) && !empty($model_info->date_affectation)) {
                // Badge existant : convertir datetime vers date
                $date_value = date('Y-m-d', strtotime($model_info->date_affectation));
            }
            
            echo form_input([
                "id" => "date_affectation",
                "name" => "date_affectation",
                "value" => $date_value,
                "class" => "form-control",
                "type" => "date",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ]);
            ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="solde" class="col-md-3">
            <i data-feather="dollar-sign" class="icon-16"></i> Solde initial
        </label>
        <div class="col-md-9">
            <?php
            echo form_input([
                "id" => "solde",
                "name" => "solde",
                "value" => (isset($model_info->solde)) ? $model_info->solde : "0",
                "class" => "form-control",
                "type" => "number",
                "step" => "0.01",
                "min" => "0",
                "placeholder" => "Montant initial sur le badge"
            ]);
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="statut" class="col-md-3">
            <i data-feather="info" class="icon-16"></i> Statut
        </label>
        <div class="col-md-9">
            <?php
            $statuts_options = [
                "actif" => "Actif (En circulation)", 
                "inactif" => "Inactif", 
                "en_maintenance" => "En maintenance"
            ];
            
            // Si c'est un badge retourné, permettre de le garder retourné
            if (isset($model_info->statut) && $model_info->statut === 'retourne') {
                $statuts_options["retourne"] = "Retourné";
            }
            
            $selected_statut = (isset($model_info->statut)) ? $model_info->statut : "actif";
            echo form_dropdown("statut", $statuts_options, $selected_statut, "class='select2'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="evenement" class="col-md-3">
            <i data-feather="file-text" class="icon-16"></i> Notes / Événement
        </label>
        <div class="col-md-9">
            <?php
            echo form_textarea([
                "id" => "evenement",
                "name" => "evenement",
                "value" => (isset($model_info->evenement)) ? $model_info->evenement : "",
                "class" => "form-control",
                "rows" => 3,
                "placeholder" => "Notes sur ce badge..."
            ]);
            ?>
        </div>
    </div>

    <?php if (isset($model_info->id) && $model_info->id > 0): ?>
        <!-- Afficher les infos supplémentaires seulement pour badge existant -->
        <div class="alert alert-info">
            <h6><i data-feather="info" class="icon-16"></i> Informations du badge</h6>
            <p class="mb-1"><strong>ID:</strong> #<?php echo $model_info->id; ?></p>
            <?php if (isset($model_info->created_at) && !empty($model_info->created_at)): ?>
                <p class="mb-0"><strong>Créé le:</strong> <?php echo format_to_datetime($model_info->created_at); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal">
        <span data-feather="x" class="icon-16"></span> Annuler
    </button>
    <button type="submit" class="btn btn-primary">
        <span data-feather="check-circle" class="icon-16"></span> 
        <?php echo (isset($model_info->id) && $model_info->id > 0) ? 'Modifier' : 'Ajouter'; ?>
    </button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        // Debug pour vérifier l'état du formulaire
        var badgeId = $('input[name="id"]').val();
        console.log('Badge ID dans le formulaire:', badgeId);
        
        $("#jawaz-form").appForm({
            onSuccess: function (result) {
                $("#jawaz-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        // Initialiser Select2 et Feather Icons
        $('.select2').select2();
        feather.replace();
        
        // Si nouveau badge, focus sur le numéro de série
        if (!badgeId || badgeId == '0') {
            $('#numero_serie').focus();
            console.log('Mode ajout: formulaire vide');
        } else {
            console.log('Mode modification: badge ID', badgeId);
        }
    });
</script>