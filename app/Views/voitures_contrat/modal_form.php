<?php echo form_open(get_uri("voitures_contrat/save"), ["id" => "voiture-contrat-form", "class" => "general-form", "role" => "form"]); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id ?? ''; ?>" />

        <div class="form-group">
            <div class="row">
                <label for="marque" class=" col-md-3">Marque</label>
                <div class=" col-md-9">
                    <?php
                    echo form_input([
                        "id" => "marque",
                        "name" => "marque",
                        "value" => $model_info->marque ?? '',
                        "class" => "form-control",
                        "placeholder" => "Marque de la voiture",
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="modele" class=" col-md-3">Modèle</label>
                <div class=" col-md-9">
                    <?php
                    echo form_input([
                        "id" => "modele",
                        "name" => "modele",
                        "value" => $model_info->modele ?? '',
                        "class" => "form-control",
                        "placeholder" => "Modèle de la voiture",
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="immatriculation" class=" col-md-3">Immatriculation</label>
                <div class=" col-md-9">
                    <?php
                    echo form_input([
                        "id" => "immatriculation",
                        "name" => "immatriculation",
                        "value" => $model_info->immatriculation ?? '',
                        "class" => "form-control",
                        "placeholder" => "Numéro d'immatriculation",
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="statut" class=" col-md-3">Statut</label>
                <div class=" col-md-9">
                    <?php
                    $statut_options = [
                        "disponible" => "Disponible",
                        "en_location" => "En location",
                        "en_maintenance" => "En maintenance",
                    ];
                    echo form_dropdown("statut", $statut_options, $model_info->statut ?? 'disponible', "class='select2'");
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        // Initialisation de appForm
        $("#voiture-contrat-form").appForm({
            onSuccess: function (result) {
                // Mise à jour de la table après sauvegarde, comme convenu
                $("#voiture-contrat-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        // Initialisation du select2
        $("#voiture-contrat-form .select2").select2();
    });
</script>
