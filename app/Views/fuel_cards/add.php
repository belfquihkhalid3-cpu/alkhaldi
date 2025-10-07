<?php echo form_open(get_uri("fuel_cards/save"), array("id" => "fuel-card-form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix">
    <div class="container-fluid">
        
        <!-- Informations Carte -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">💳 Informations Carte</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="row">
                        <label for="numero_serie" class="col-md-3 col-form-label">
                            🔢 Numéro Série <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "numero_serie",
                                "name" => "numero_serie",
                                "value" => $model_info->numero_serie ?? '',
                                "class" => "form-control",
                                "placeholder" => "Numéro de série",
                                "autofocus" => true,
                                "data-rule-required" => true,
                                "data-msg-required" => "Numéro requis"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="type_carte" class="col-md-3 col-form-label">
                            🏷 Type Carte
                        </label>
                        <div class="col-md-9">
                            <?php
                            $types = ['easyone' => 'EasyOne', 'shell' => 'Shell', 'total' => 'Total'];
                            echo form_dropdown("type_carte", $types, $model_info->type_carte ?? 'easyone', 
                                "class='form-control select2' id='type_carte'");
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="solde_dotation">💰 Solde</label>
                            <div class="input-group">
                                <?php
                                echo form_input(array(
                                    "id" => "solde_dotation",
                                    "name" => "solde_dotation",
                                    "value" => $model_info->solde_dotation ?? '',
                                    "class" => "form-control",
                                    "type" => "number",
                                    "step" => "0.01",
                                    "placeholder" => "0.00"
                                ));
                                ?>
                                <span class="input-group-text">MAD</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prix_litre">⛽ Prix/L</label>
                            <div class="input-group">
                                <?php
                                echo form_input(array(
                                    "id" => "prix_litre",
                                    "name" => "prix_litre",
                                    "value" => $model_info->prix_litre ?? '',
                                    "class" => "form-control",
                                    "type" => "number",
                                    "step" => "0.01",
                                    "placeholder" => "0.00"
                                ));
                                ?>
                                <span class="input-group-text">MAD</span>
                            </div>
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
                            <label for="vehicle_id">🚗 Véhicule</label>
                            <?php
                            echo form_dropdown("vehicle_id", $vehicles_dropdown ?? [], $model_info->vehicle_id ?? '', 
                                "class='form-control select2' id='vehicle_id'");
                            ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="chauffeur_id">👨‍💼 Chauffeur</label>
                            <?php
                            echo form_dropdown("chauffeur_id", $chauffeurs_dropdown ?? [], $model_info->chauffeur_id ?? '', 
                                "class='form-control select2' id='chauffeur_id'");
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dates -->
        <div class="card mb-3">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0">📅 Dates</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_creation">📅 Création</label>
                            <?php
                            echo form_input(array(
                                "id" => "date_creation",
                                "name" => "date_creation",
                                "value" => $model_info->date_creation ?? date('Y-m-d'),
                                "class" => "form-control",
                                "type" => "date"
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_expiration">📅 Expiration</label>
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

                <div class="form-group">
                    <div class="row">
                        <label for="statut" class="col-md-3 col-form-label">
                            🔴 Statut
                        </label>
                        <div class="col-md-9">
                            <?php
                            $statuts = ['active' => 'Active', 'bloquee' => 'Bloquée', 'expiree' => 'Expirée'];
                            echo form_dropdown("statut", $statuts, $model_info->statut ?? 'active', 
                                "class='form-control select2' id='statut'");
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
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        ❌ Annuler
    </button>
    <button type="submit" class="btn btn-primary">
        💾 Enregistrer
    </button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#fuel-card-form").appForm({
            onSuccess: function (result) {
                if (result.success && $("#fuel-cards-table").length > 0) {
                    $("#fuel-cards-table").appTable({newData: result.data, dataId: result.id});
                }
            }
        });
        
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            dropdownParent: $('.modal-body')
        });
    });
</script>