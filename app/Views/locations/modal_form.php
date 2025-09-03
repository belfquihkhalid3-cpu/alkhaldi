<?= form_open(get_uri("locations/save"), array("id" => "location-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?= $model_info->id ?? ''; ?>" />
        
        <div class="form-group">
            <div class="row">
                <label for="titre" class="col-md-3"><?= app_lang('titre'); ?> <span class="help" title="<?= app_lang('required'); ?>">*</span></label>
                <div class="col-md-9">
                    <?= form_input(array(
                        "id" => "titre",
                        "name" => "titre",
                        "value" => $model_info->titre ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('titre'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    )); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="client_id" class="col-md-3"><?= app_lang('client'); ?> <span class="help" title="<?= app_lang('required'); ?>">*</span></label>
                <div class="col-md-9">
                    <?= form_dropdown("client_id", $clients_dropdown, $model_info->client_id ?? '', "class='form-control select2' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="type_service" class="col-md-3"><?= app_lang('type_service'); ?> <span class="help" title="<?= app_lang('required'); ?>">*</span></label>
                <div class="col-md-9">
                    <?= form_dropdown("type_service", array(
                        "" => "-",
                        "transfert" => "Transfert",
                        "location_journee" => "Location journée",
                        "location_longue" => "Location longue durée",
                        "evenement" => "Événement"
                    ), $model_info->type_service ?? '', "class='form-control' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="date_debut" class="col-md-3"><?= app_lang('date_debut'); ?> <span class="help" title="<?= app_lang('required'); ?>">*</span></label>
                <div class="col-md-9">
                    <?= form_input(array(
                        "id" => "date_debut",
                        "name" => "date_debut",
                        "value" => $model_info->date_debut ?? '',
                        "class" => "form-control datetime",
                        "placeholder" => app_lang('date_debut'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    )); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="date_fin" class="col-md-3"><?= app_lang('date_fin'); ?> <span class="help" title="<?= app_lang('required'); ?>">*</span></label>
                <div class="col-md-9">
                    <?= form_input(array(
                        "id" => "date_fin",
                        "name" => "date_fin",
                        "value" => $model_info->date_fin ?? '',
                        "class" => "form-control datetime",
                        "placeholder" => app_lang('date_fin'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    )); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="lieu_depart" class="col-md-3"><?= app_lang('lieu_depart'); ?> <span class="help" title="<?= app_lang('required'); ?>">*</span></label>
                <div class="col-md-9">
                    <?= form_input(array(
                        "id" => "lieu_depart",
                        "name" => "lieu_depart",
                        "value" => $model_info->lieu_depart ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('lieu_depart'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    )); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="lieu_arrivee" class="col-md-3"><?= app_lang('lieu_arrivee'); ?> <span class="help" title="<?= app_lang('required'); ?>">*</span></label>
                <div class="col-md-9">
                    <?= form_input(array(
                        "id" => "lieu_arrivee",
                        "name" => "lieu_arrivee",
                        "value" => $model_info->lieu_arrivee ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('lieu_arrivee'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    )); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="vehicle_id" class="col-md-3"><?= app_lang('vehicule'); ?></label>
                <div class="col-md-9">
                    <?= form_dropdown("vehicle_id", $vehicles_dropdown, $model_info->vehicle_id ?? '', "class='form-control select2'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="chauffeur_id" class="col-md-3"><?= app_lang('chauffeur'); ?></label>
                <div class="col-md-9">
                    <?= form_dropdown("chauffeur_id", $chauffeurs_dropdown, $model_info->chauffeur_id ?? '', "class='form-control select2'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="prix_total" class="col-md-3"><?= app_lang('prix_total'); ?></label>
                <div class="col-md-9">
                    <?= form_input(array(
                        "id" => "prix_total",
                        "name" => "prix_total",
                        "value" => $model_info->prix_total ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('prix_total'),
                        "type" => "number",
                        "step" => "0.01"
                    )); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="statut" class="col-md-3"><?= app_lang('statut'); ?></label>
                <div class="col-md-9">
                    <?= form_dropdown("statut", array(
                        "en_attente" => "En attente",
                        "confirmee" => "Confirmée",
                        "en_cours" => "En cours",
                        "terminee" => "Terminée",
                        "annulee" => "Annulée"
                    ), $model_info->statut ?? 'en_attente', "class='form-control'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="observations" class="col-md-3"><?= app_lang('observations'); ?></label>
                <div class="col-md-9">
                    <?= form_textarea(array(
                        "id" => "observations",
                        "name" => "observations",
                        "value" => $model_info->observations ?? '',
                        "class" => "form-control",
                        "placeholder" => app_lang('observations')
                    )); ?>
                </div>
            </div>
        </div>

    </div>
     <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?= app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?= app_lang('save'); ?></button>
</div>


<?= form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#location-form").appForm({
            onSuccess: function (result) {
                $("#locations-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        
        $('.select2').select2({dropdownParent: $("#location-form .modal-body")});
        setDatePicker(".date, .datetime");
        
        // Initialiser Feather Icons
        feather.replace();
        
        // Vérification des disponibilités lors du changement de dates
        $("#date_debut, #date_fin").change(function() {
            checkAvailability();
        });
        
        function checkAvailability() {
            const dateDebut = $("#date_debut").val();
            const dateFin = $("#date_fin").val();
            const excludeId = $("input[name='id']").val();
            
            if (dateDebut && dateFin) {
                $.ajax({
                    url: '<?= get_uri("locations/check_availability"); ?>',
                    method: 'POST',
                    data: {
                        date_debut: dateDebut,
                        date_fin: dateFin,
                        exclude_id: excludeId
                    },
                    success: function(data) {
                        if (data.success) {
                            updateDropdowns(data);
                        }
                    }
                });
            }
        }
        
        function updateDropdowns(data) {
            // Mettre à jour les dropdowns avec les éléments disponibles
            // Code à adapter selon votre système de dropdown
        }
    });
</script>