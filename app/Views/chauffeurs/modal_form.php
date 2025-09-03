<?php echo form_open(get_uri("chauffeurs/save"), array("id" => "chauffeur-form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix">
    <?php echo form_hidden("id", $model_info->id ?? ""); ?>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Informations personnelles -->
            <div class="col-md-6">
                <div class="form-group">
                    <div class="row">
                        <label for="nom" class="col-3 col-form-label"><?php echo app_lang('last_name'); ?> *</label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "nom",
                                "name" => "nom",
                                "value" => $model_info->nom ?? "",
                                "class" => "form-control",
                                "placeholder" => app_lang('last_name'),
                                "required" => true,
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="prenom" class="col-3 col-form-label"><?php echo app_lang('first_name'); ?> *</label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "prenom",
                                "name" => "prenom",
                                "value" => $model_info->prenom ?? "",
                                "class" => "form-control",
                                "placeholder" => app_lang('first_name'),
                                "required" => true,
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="cnie" class="col-3 col-form-label"><?php echo app_lang('cnie'); ?> *</label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "cnie",
                                "name" => "cnie",
                                "value" => $model_info->cnie ?? "",
                                "class" => "form-control",
                                "placeholder" => "D123456789",
                                "required" => true,
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="telephone" class="col-3 col-form-label"><?php echo app_lang('phone'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "telephone",
                                "name" => "telephone",
                                "value" => $model_info->telephone ?? "",
                                "class" => "form-control",
                                "placeholder" => "+212661234567"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="telephone_urgence" class="col-3 col-form-label"><?php echo app_lang('emergency_phone'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "telephone_urgence",
                                "name" => "telephone_urgence",
                                "value" => $model_info->telephone_urgence ?? "",
                                "class" => "form-control",
                                "placeholder" => "+212661111111"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="email" class="col-3 col-form-label"><?php echo app_lang('email'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "email",
                                "name" => "email",
                                "value" => $model_info->email ?? "",
                                "class" => "form-control",
                                "placeholder" => "chauffeur@elkhaldicar.com",
                                "type" => "email"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="date_naissance" class="col-3 col-form-label"><?php echo app_lang('birth_date'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "date_naissance",
                                "name" => "date_naissance",
                                "value" => $model_info->date_naissance ?? "",
                                "class" => "form-control",
                                "type" => "date"
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations professionnelles -->
            <div class="col-md-6">
                <div class="form-group">
                    <div class="row">
                        <label for="numero_permis" class="col-3 col-form-label"><?php echo app_lang('permit_number'); ?> *</label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "numero_permis",
                                "name" => "numero_permis",
                                "value" => $model_info->numero_permis ?? "",
                                "class" => "form-control",
                                "placeholder" => "P123456789",
                                "required" => true,
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="date_expiration_permis" class="col-3 col-form-label"><?php echo app_lang('permit_expiry'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "date_expiration_permis",
                                "name" => "date_expiration_permis",
                                "value" => $model_info->date_expiration_permis ?? "",
                                "class" => "form-control",
                                "type" => "date"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="categorie_permis" class="col-3 col-form-label"><?php echo app_lang('permit_category'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_dropdown(
                                "categorie_permis",
                                array(
                                    "" => "- " . app_lang('select') . " -",
                                    "A" => "A - Motocycles",
                                    "B" => "B - Véhicules légers",
                                    "C" => "C - Poids lourds",
                                    "D" => "D - Transport en commun",
                                    "E" => "E - Remorques"
                                ),
                                $model_info->categorie_permis ?? "",
                                "class='form-control select2'"
                            );
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="date_embauche" class="col-3 col-form-label"><?php echo app_lang('hire_date'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "date_embauche",
                                "name" => "date_embauche",
                                "value" => $model_info->date_embauche ?? "",
                                "class" => "form-control",
                                "type" => "date"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="salaire_base" class="col-3 col-form-label"><?php echo app_lang('base_salary'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "salaire_base",
                                "name" => "salaire_base",
                                "value" => $model_info->salaire_base ?? "",
                                "class" => "form-control",
                                "placeholder" => "7500.00",
                                "type" => "number",
                                "step" => "0.01"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="statut" class="col-3 col-form-label"><?php echo app_lang('status'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_dropdown(
                                "statut",
                                array(
                                    "actif" => app_lang('active'),
                                    "inactif" => app_lang('inactive'),
                                    "suspendu" => app_lang('suspended')
                                ),
                                $model_info->statut ?? "actif",
                                "class='form-control select2'"
                            );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adresse -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label for="adresse" class="col-2 col-form-label"><?php echo app_lang('address'); ?></label>
                        <div class="col-10">
                            <?php
                            echo form_textarea(array(
                                "id" => "adresse",
                                "name" => "adresse",
                                "value" => $model_info->adresse ?? "",
                                "class" => "form-control",
                                "placeholder" => app_lang('address'),
                                "rows" => 2
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Observations -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label for="observations" class="col-2 col-form-label"><?php echo app_lang('notes'); ?></label>
                        <div class="col-10">
                            <?php
                            echo form_textarea(array(
                                "id" => "observations",
                                "name" => "observations",
                                "value" => $model_info->observations ?? "",
                                "class" => "form-control",
                                "placeholder" => app_lang('notes'),
                                "rows" => 3
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#chauffeur-form").appForm({
            onSuccess: function (result) {
                $("#chauffeurs-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        
        $('.select2').select2();
    });
</script>