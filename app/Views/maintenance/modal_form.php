<?php echo form_open(get_uri("maintenance/save"), ["id" => "maintenance-form", "class" => "general-form", "role" => "form"]); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id ?? 0; ?>" />

    <div class="form-group">
        <label for="vehicle_id" class=" col-md-3">Véhicule</label>
        <div class="col-md-9">
            <?php
            // Utilise l'opérateur ?? pour gérer le cas où on ajoute une nouvelle entrée ($model_info est null)
            echo form_dropdown("vehicle_id", $vehicles_dropdown, $model_info->vehicle_id ?? "", "class='select2 validate-hidden' id='vehicle_id' data-rule-required='true' data-msg-required='" . lang("field_required") . "'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="date_maintenance" class=" col-md-3">Date de maintenance</label>
        <div class="col-md-9">
            <?php
            echo form_input([
                "id" => "date_maintenance",
                "name" => "date_maintenance",
                "value" => $model_info->date_maintenance ?? "",
                "class" => "form-control datepicker",
                "placeholder" => "YYYY-MM-DD",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ]);
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="km_maintenance" class=" col-md-3">Kilométrage</label>
        <div class="col-md-9">
            <?php
            echo form_input([
                "id" => "km_maintenance",
                "name" => "km_maintenance",
                "value" => $model_info->km_maintenance ?? "",
                "class" => "form-control",
                "type" => "number",
                "placeholder" => "Kilométrage au moment de la maintenance",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ]);
            ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="type_maintenance" class=" col-md-3">Type de maintenance</label>
        <div class="col-md-9">
            <?php
            $type_options = [
                "vidange" => "Vidange",
                "filtre_huile" => "Filtre à huile",
                "filtre_air" => "Filtre à air",
                "filtre_carburant" => "Filtre à carburant",
                "autre" => "Autre"
            ];
            echo form_dropdown("type_maintenance", $type_options, $model_info->type_maintenance ?? "autre", "class='select2'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="cout" class=" col-md-3">Coût</label>
        <div class="col-md-9">
            <?php
            echo form_input([
                "id" => "cout",
                "name" => "cout",
                "value" => $model_info->cout ?? "",
                "class" => "form-control",
                "type" => "number",
                "step" => "0.01",
                "placeholder" => "Coût de l'opération"
            ]);
            ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="garage" class=" col-md-3">Garage / Prestataire</label>
        <div class="col-md-9">
            <?php
            echo form_input([
                "id" => "garage",
                "name" => "garage",
                "value" => $model_info->garage ?? "",
                "class" => "form-control",
                "placeholder" => "Nom du garage"
            ]);
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="description" class=" col-md-3">Description</label>
        <div class="col-md-9">
            <?php
            echo form_textarea([
                "id" => "description",
                "name" => "description",
                "value" => $model_info->description ?? "",
                "class" => "form-control",
                "placeholder" => "Détails supplémentaires sur la maintenance..."
            ]);
            ?>
        </div>
    </div>
    
    <hr />
    <p class="text-center">Informations sur la prochaine maintenance (optionnel)</p>
    
    <div class="form-group">
        <label for="prochaine_maintenance_date" class=" col-md-3">Prochaine date</label>
        <div class="col-md-9">
            <?php
            echo form_input([
                "id" => "prochaine_maintenance_date",
                "name" => "prochaine_maintenance_date",
                "value" => $model_info->prochaine_maintenance_date ?? "",
                "class" => "form-control datepicker",
                "placeholder" => "YYYY-MM-DD"
            ]);
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="prochaine_maintenance_km" class=" col-md-3">Prochain kilométrage</label>
        <div class="col-md-9">
            <?php
            echo form_input([
                "id" => "prochaine_maintenance_km",
                "name" => "prochaine_maintenance_km",
                "value" => $model_info->prochaine_maintenance_km ?? "",
                "class" => "form-control",
                "type" => "number",
                "placeholder" => "Kilométrage de la prochaine maintenance"
            ]);
            ?>
        </div>
    </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Annuler</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> Enregistrer</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        // C'est la version simple et correcte qui fonctionne avec notre table maintenant réparée
        $("#maintenance-form").appForm({
            onSuccess: function (result) {
                $("#maintenance-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        // Initialisation des menus déroulants et des sélecteurs de date
        $('.select2').select2();
        setDatePicker(".datepicker");
    });
</script>