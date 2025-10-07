<?php echo form_open(get_uri("fuel_cards/save"), array("id" => "fuel-card-form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix">
    <div class="container-fluid">
        
        <!-- Informations de base -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">📋 Informations de base</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="numero_serie">Numéro de série <span class="text-danger">*</span></label>
                    <?php
                    echo form_input(array(
                        "id" => "numero_serie",
                        "name" => "numero_serie",
                        "value" => $model_info->numero_serie ?? '',
                        "class" => "form-control",
                        "placeholder" => "ex: EASY123456",
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => "Ce champ est requis"
                    ));
                    ?>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type_carte">Type de carte</label>
                            <?php
                            echo form_dropdown("type_carte", 
                                ['easyone' => 'EasyOne', 'autre' => 'Autre'], 
                                $model_info->type_carte ?? 'easyone', 
                                "class='form-control select2' id='type_carte'");
                            ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="statut">Statut</label>
                            <?php
                            echo form_dropdown("statut", 
                                ['active' => 'Active', 'inactive' => 'Inactive', 'bloquee' => 'Bloquée'], 
                                $model_info->statut ?? 'active', 
                                "class='form-control select2' id='statut'");
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="created_at">Date de création</label>
                           <?php
echo form_input(array(
    "id" => "created_at",
    "name" => "created_at",
    "value" => isset($model_info->created_at) ? date('Y-m-d', strtotime($model_info->created_at)) : date('Y-m-d'),
    "class" => "form-control",
    "type" => "date",
    "readonly" => "readonly"
));
?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_expiration">Date d'expiration</label>
                            <?php
                            echo form_input(array(
                                "id" => "date_expiration",
                                "name" => "date_expiration",
                                "value" => $model_info->date_expiration ?? '',
                                "class" => "form-control",
                                "type" => "date"
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dotations -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">💰 Dotations</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="solde_dotation">Solde dotation</label>
                            <?php
                            echo form_input(array(
                                "id" => "solde_dotation",
                                "name" => "solde_dotation",
                                "value" => $model_info->solde_dotation ?? '',
                                "class" => "form-control",
                                "type" => "number",
                                "step" => "0.01"
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prix_litre">Prix/L</label>
                            <?php
                            echo form_input(array(
                                "id" => "prix_litre",
                                "name" => "prix_litre",
                                "value" => $model_info->prix_litre ?? '',
                                "class" => "form-control",
                                "type" => "number",
                                "step" => "0.01"
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignation -->
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">👥 Assignation</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vehicle_id">Véhicule</label>
                            <?php
                            echo form_dropdown("vehicle_id", $vehicles_dropdown ?? [], $model_info->vehicle_id ?? '', 
                                "class='form-control select2' id='vehicle_id'");
                            ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="chauffeur_id">Chauffeur</label>
                            <?php
                            echo form_dropdown("chauffeur_id", $chauffeurs_dropdown ?? [], $model_info->chauffeur_id ?? '', 
                                "class='form-control select2' id='chauffeur_id'");
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="id" name="id" value="<?php echo $model_info->id ?? ''; ?>">
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Fermer</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> Sauvegarder</button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#fuel-card-form").appForm({
            onSuccess: function (result) {
                location.reload();
            }
        });
        
        $('.select2').select2();
    });
</script>