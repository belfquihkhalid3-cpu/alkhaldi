<?php echo form_open(get_uri("chauffeurs/save"), array("id" => "chauffeur-form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix">
    <?php echo form_hidden("id", $model_info->id ?? ""); ?>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Colonne gauche -->
            <div class="col-md-6">
                <h6 class="text-primary mb-3">
                    <i data-feather="user" class="icon-16"></i> Informations Personnelles
                </h6>
                
                <div class="form-group">
                    <div class="row">
                        <label for="nom" class="col-3 col-form-label"><?php echo app_lang('nom'); ?> <span class="text-danger">*</span></label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "nom",
                                "name" => "nom",
                                "value" => $model_info->nom ?? "",
                                "class" => "form-control",
                                "placeholder" => app_lang('prenom'),
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
                        <label for="cnie" class="col-3 col-form-label"><?php echo app_lang('cnie'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "cnie",
                                "name" => "cnie",
                                "value" => $model_info->cnie ?? "",
                                "class" => "form-control",
                                "placeholder" => "AB123456",
                                "maxlength" => "8"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="telephone" class="col-3 col-form-label"><?php echo app_lang('telephone'); ?> <span class="text-danger">*</span></label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "telephone",
                                "name" => "telephone",
                                "value" => $model_info->telephone ?? "",
                                "class" => "form-control",
                                "placeholder" => "+212 6 XX XX XX XX",
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
                        <label for="telephone_urgence" class="col-3 col-form-label"><?php echo app_lang('telephone_urgence'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "telephone_urgence",
                                "name" => "telephone_urgence",
                                "value" => $model_info->telephone_urgence ?? "",
                                "class" => "form-control",
                                "placeholder" => "+212 6 XX XX XX XX"
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
                                "placeholder" => "email@exemple.com",
                                "type" => "email"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="adresse" class="col-3 col-form-label"><?php echo app_lang('adresse'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_textarea(array(
                                "id" => "adresse",
                                "name" => "adresse",
                                "value" => $model_info->adresse ?? "",
                                "class" => "form-control",
                                "placeholder" => app_lang('adresse'),
                                "rows" => 3
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="col-md-6">
                <h6 class="text-primary mb-3">
                    <i data-feather="briefcase" class="icon-16"></i> Informations Professionnelles
                </h6>

                <div class="form-group">
                    <div class="row">
                        <label for="date_naissance" class="col-3 col-form-label"><?php echo app_lang('date_naissance'); ?></label>
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

                <div class="form-group">
                    <div class="row">
                        <label for="date_embauche" class="col-3 col-form-label"><?php echo app_lang('date_embauche'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "date_embauche",
                                "name" => "date_embauche",
                                "value" => $model_info->date_embauche ?? date('Y-m-d'),
                                "class" => "form-control",
                                "type" => "date"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="numero_permis" class="col-3 col-form-label"><?php echo app_lang('numero_permis'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_input(array(
                                "id" => "numero_permis",
                                "name" => "numero_permis",
                                "value" => $model_info->numero_permis ?? "",
                                "class" => "form-control",
                                "placeholder" => "P123456789"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="date_expiration_permis" class="col-3 col-form-label"><?php echo app_lang('date_expiration_permis'); ?></label>
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
                        <label for="categorie_permis" class="col-3 col-form-label"><?php echo app_lang('categorie_permis'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_dropdown(
                                "categorie_permis",
                                array(
                                    "D" => "D - Transport de personnes",
                                    "B" => "B - Véhicules légers",
                                    "C" => "C - Poids lourds",
                                    "E" => "E - Avec remorque"
                                ),
                                $model_info->categorie_permis ?? "D",
                                "class='form-control'"
                            );
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="salaire_base" class="col-3 col-form-label"><?php echo app_lang('salaire_base'); ?></label>
                        <div class="col-9">
                            <div class="input-group">
                                <?php
                                echo form_input(array(
                                    "id" => "salaire_base",
                                    "name" => "salaire_base",
                                    "value" => $model_info->salaire_base ?? "",
                                    "class" => "form-control",
                                    "placeholder" => "3000",
                                    "type" => "number",
                                    "step" => "0.01"
                                ));
                                ?>
                                <span class="input-group-text">MAD</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="statut" class="col-3 col-form-label"><?php echo app_lang('statut'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_dropdown(
                                "statut",
                                array(
                                    "actif" => "Actif",
                                    "inactif" => "Inactif",
                                    "suspendu" => "Suspendu"
                                ),
                                $model_info->statut ?? "actif",
                                "class='form-control'"
                            );
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="observations" class="col-3 col-form-label"><?php echo app_lang('observations'); ?></label>
                        <div class="col-9">
                            <?php
                            echo form_textarea(array(
                                "id" => "observations",
                                "name" => "observations",
                                "value" => $model_info->observations ?? "",
                                "class" => "form-control",
                                "placeholder" => app_lang('observations'),
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
    <button type="button" class="btn btn-default" data-bs-dismiss="modal">
        <span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?>
    </button>
    <button type="submit" class="btn btn-primary">
        <span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?>
    </button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#chauffeur-form").appForm({
            onSuccess: function (result) {
                $("#chauffeur-table").appTable({newData: result.data, dataId: result.id});
                appAlert.success(result.message, {duration: 10000});
            }
        });
        
        setTimeout(function () {
            $("#nom").focus();
        }, 200);
        
        // Initialiser les icônes Feather
        feather.replace();
    });
</script>