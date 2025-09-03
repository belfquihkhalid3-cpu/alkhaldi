<?php echo form_open(get_uri("jawaz/save"), ["id" => "jawaz-add-form", "class" => "general-form", "role" => "form"]); ?>
<div class="modal-body clearfix">
    <!-- ID fixé à 0 pour nouveau badge -->
    <input type="hidden" name="id" value="0" />

    <div class="form-group">
        <label for="numero_serie" class="col-md-3">
            <i data-feather="hash" class="icon-16"></i> Numéro de série *
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

    <div class="form-group">
        <label for="vehicle_id" class="col-md-3">
            <i data-feather="truck" class="icon-16"></i> Véhicule affecté
        </label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("vehicle_id", $vehicles_dropdown ?? ["" => "- Aucun véhicule disponible -"], 
                "", 
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
                "", 
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
            // Pour nouveau badge : toujours la date du jour
            $date_value = date('Y-m-d');
            
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
                "value" => "0",
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
            
            // Pour nouveau badge : toujours actif par défaut
            echo form_dropdown("statut", $statuts_options, "actif", "class='select2'");
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
                "value" => "",
                "class" => "form-control",
                "rows" => 3,
                "placeholder" => "Notes sur ce badge..."
            ]);
            ?>
        </div>
    </div>

    <!-- Pas d'affichage d'infos pour nouveau badge -->

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal">
        <span data-feather="x" class="icon-16"></span> Annuler
    </button>
    <button type="submit" class="btn btn-primary">
        <span data-feather="check-circle" class="icon-16"></span> 
        Ajouter
    </button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        // Debug pour vérifier l'état du formulaire
        var badgeId = $('input[name="id"]').val();
        console.log('Badge ID dans le formulaire:', badgeId);
        
        $("#jawaz-add-form").appForm({
            onSuccess: function (result) {
                $("#jawaz-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        // Initialiser Select2 et Feather Icons
        $('.select2').select2();
        feather.replace();
        
        // Toujours en mode ajout, focus sur le numéro de série
        $('#numero_serie').focus();
        console.log('Mode ajout: formulaire vide');
    });
</script>