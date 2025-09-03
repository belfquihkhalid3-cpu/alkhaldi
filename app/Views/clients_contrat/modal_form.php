<?php echo form_open(get_uri("clients_contrat/save"), ["id" => "client-contrat-form", "class" => "general-form", "role" => "form"]); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id ?? ''; ?>" />

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <?php echo form_input(["id" => "prenom", "name" => "prenom", "value" => $model_info->prenom ?? '', "class" => "form-control", "placeholder" => "Prénom", "autofocus" => true, "data-rule-required" => true, "data-msg-required" => lang("field_required")]); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <?php echo form_input(["id" => "nom", "name" => "nom", "value" => $model_info->nom ?? '', "class" => "form-control", "placeholder" => "Nom", "data-rule-required" => true, "data-msg-required" => lang("field_required")]); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="telephone">Téléphone</label>
            <?php echo form_input(["id" => "telephone", "name" => "telephone", "value" => $model_info->telephone ?? '', "class" => "form-control", "placeholder" => "Numéro de téléphone"]); ?>
        </div>

        <hr />
        <p class="text-center"><strong>Pièce d'identité</strong></p>

        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="cin_numero">N° CIN</label>
                    <?php echo form_input(["id" => "cin_numero", "name" => "cin_numero", "value" => $model_info->cin_numero ?? '', "class" => "form-control", "placeholder" => "Numéro de la carte d'identité"]); ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cin_delivre_le">Délivrée le</label>
                    <?php echo form_input(["id" => "cin_delivre_le", "name" => "cin_delivre_le", "value" => $model_info->cin_delivre_le ?? '', "class" => "form-control datepicker", "placeholder" => "YYYY-MM-DD"]); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="passeport_numero">N° Passeport</label>
                    <?php echo form_input(["id" => "passeport_numero", "name" => "passeport_numero", "value" => $model_info->passeport_numero ?? '', "class" => "form-control", "placeholder" => "Numéro du passeport"]); ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="passeport_delivre_le">Délivré le</label>
                    <?php echo form_input(["id" => "passeport_delivre_le", "name" => "passeport_delivre_le", "value" => $model_info->passeport_delivre_le ?? '', "class" => "form-control datepicker", "placeholder" => "YYYY-MM-DD"]); ?>
                </div>
            </div>
        </div>
        
        <hr />
        <p class="text-center"><strong>Permis de Conduire</strong></p>

        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="permis_numero">N° Permis</label>
                    <?php echo form_input(["id" => "permis_numero", "name" => "permis_numero", "value" => $model_info->permis_numero ?? '', "class" => "form-control", "placeholder" => "Numéro du permis"]); ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="permis_delivre_le">Délivré le</label>
                    <?php echo form_input(["id" => "permis_delivre_le", "name" => "permis_delivre_le", "value" => $model_info->permis_delivre_le ?? '', "class" => "form-control datepicker", "placeholder" => "YYYY-MM-DD"]); ?>
                </div>
            </div>
        </div>

        <hr />

        <div class="form-group">
            <label for="nationalite">Nationalité</label>
            <?php echo form_input(["id" => "nationalite", "name" => "nationalite", "value" => $model_info->nationalite ?? '', "class" => "form-control", "placeholder" => "Nationalité"]); ?>
        </div>

        <div class="form-group">
            <label for="adresse_maroc">Adresse au Maroc</label>
            <?php echo form_textarea(["id" => "adresse_maroc", "name" => "adresse_maroc", "value" => $model_info->adresse_maroc ?? '', "class" => "form-control", "placeholder" => "Adresse complète au Maroc"]); ?>
        </div>
        
        <div class="form-group">
            <label for="adresse_etranger">Adresse à l'étranger</label>
            <?php echo form_textarea(["id" => "adresse_etranger", "name" => "adresse_etranger", "value" => $model_info->adresse_etranger ?? '', "class" => "form-control", "placeholder" => "Adresse complète à l'étranger"]); ?>
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
        $("#client-contrat-form").appForm({
            onSuccess: function (result) {
                $("#client-contrat-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        // Initialisation des sélecteurs de date
        setDatePicker(".datepicker");
    });
</script>
