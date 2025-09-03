
<?php echo form_open(get_uri("contrats/save"), ["id" => "contrat-form", "class" => "general-form", "role" => "form"]); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id ?? ''; ?>" />

        <div class="form-group mb-3">
            <label for="numero_contrat">Numéro de Contrat</label>
            <?php echo form_input(["id" => "numero_contrat", "name" => "numero_contrat", "value" => $model_info->numero_contrat ?? '', "class" => "form-control", "readonly" => true]); ?>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="client_id" class="fw-bold">Client</label>
                        <?php echo form_dropdown("client_id", $clients_dropdown, $model_info->client_id ?? '', "class='select2 validate-hidden' id='client_id' data-rule-required='true'"); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="voiture_id" class="fw-bold">Voiture</label>
                        <?php echo form_dropdown("voiture_id", $voitures_dropdown, $model_info->voiture_id ?? '', "class='select2 validate-hidden' id='voiture_id' data-rule-required='true'"); ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="date_depart">Date & Heure de Départ</label>
                        <?php echo form_input(["id" => "date_depart", "name" => "date_depart", "value" => $model_info->date_depart ?? '', "class" => "form-control datetimepicker", "placeholder" => "YYYY-MM-DD HH:MM", "data-rule-required" => true]); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="date_retour_prevue">Date & Heure de Retour Prévue</label>
                        <?php echo form_input(["id" => "date_retour_prevue", "name" => "date_retour_prevue", "value" => $model_info->date_retour_prevue ?? '', "class" => "form-control datetimepicker", "placeholder" => "YYYY-MM-DD HH:MM", "data-rule-required" => true]); ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="lieu_depart">Lieu de Départ</label>
                        <?php echo form_input(["id" => "lieu_depart", "name" => "lieu_depart", "value" => $model_info->lieu_depart ?? 'Aéroport', "class" => "form-control"]); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="lieu_retour">Lieu de Retour</label>
                        <?php echo form_input(["id" => "lieu_retour", "name" => "lieu_retour", "value" => $model_info->lieu_retour ?? 'Aéroport', "class" => "form-control"]); ?>
                    </div>
                </div>
            </div>

            <hr />

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="km_depart">Kilométrage de Départ</label>
                        <?php echo form_input(["id" => "km_depart", "name" => "km_depart", "value" => $model_info->km_depart ?? '', "class" => "form-control", "type" => "number", "data-rule-required" => true]); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="carburant_depart">Niveau Carburant Départ</label>
                        <?php echo form_input(["id" => "carburant_depart", "name" => "carburant_depart", "value" => $model_info->carburant_depart ?? '1/2', "class" => "form-control"]); ?>
                    </div>
                </div>
            </div>

            <hr />
            <h5 class="text-center mb-3">Détails Financiers</h5>

            <div class="row">
                 <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="prix_jour">Prix / Jour (Dhs)</label>
                        <?php echo form_input(["id" => "prix_jour", "name" => "prix_jour", "value" => $model_info->prix_jour ?? '', "class" => "form-control", "type" => "number", "data-rule-required" => true]); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="nombre_jours">Nombre de Jours</label>
                        <?php echo form_input(["id" => "nombre_jours", "name" => "nombre_jours", "value" => $model_info->nombre_jours ?? '', "class" => "form-control", "type" => "number", "readonly" => true]); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="total_final">Total (Dhs)</label>
                        <?php echo form_input(["id" => "total_final", "name" => "total_final", "value" => $model_info->total_final ?? '', "class" => "form-control", "type" => "number", "readonly" => true]); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="caution">Caution (Dhs)</label>
                        <?php echo form_input(["id" => "caution", "name" => "caution", "value" => $model_info->caution ?? '0', "class" => "form-control", "type" => "number"]); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="avance">Avance (Dhs)</label>
                        <?php echo form_input(["id" => "avance", "name" => "avance", "value" => $model_info->avance ?? '0', "class" => "form-control", "type" => "number"]); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="mode_paiement">Mode de Paiement</label>
                        <?php echo form_input(["id" => "mode_paiement", "name" => "mode_paiement", "value" => $model_info->mode_paiement ?? 'Espèces', "class" => "form-control"]); ?>
                    </div>
                </div>
            </div>
            
            <hr />
            <h5 class="text-center mb-3">Informations Additionnelles</h5>

            <div class="form-group mb-3">
                <label for="notes_etat_vehicule_depart">Notes sur l'état du véhicule au départ</label>
                <?php echo form_textarea(["id" => "notes_etat_vehicule_depart", "name" => "notes_etat_vehicule_depart", "value" => $model_info->notes_etat_vehicule_depart ?? '', "class" => "form-control", "placeholder" => "Ex: Rayure porte avant droite..."]); ?>
            </div>

            <!-- CORRECTION : Ajout de la section pour le deuxième conducteur -->
            <hr />
            <h5 class="text-center mb-3">2ème Conducteur (Optionnel)</h5>

            <div class="form-group mb-3">
                <label for="deuxieme_conducteur_nom">Nom et Prénom</label>
                <?php echo form_input(["id" => "deuxieme_conducteur_nom", "name" => "deuxieme_conducteur_nom", "value" => $model_info->deuxieme_conducteur_nom ?? '', "class" => "form-control", "placeholder" => "Nom complet du deuxième conducteur"]); ?>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group mb-3">
                        <label for="deuxieme_conducteur_permis_numero">N° Permis</label>
                        <?php echo form_input(["id" => "deuxieme_conducteur_permis_numero", "name" => "deuxieme_conducteur_permis_numero", "value" => $model_info->deuxieme_conducteur_permis_numero ?? '', "class" => "form-control", "placeholder" => "Numéro du permis"]); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="deuxieme_conducteur_permis_delivre_le">Délivré le</label>
                        <?php echo form_input(["id" => "deuxieme_conducteur_permis_delivre_le", "name" => "deuxieme_conducteur_permis_delivre_le", "value" => $model_info->deuxieme_conducteur_permis_delivre_le ?? '', "class" => "form-control datepicker", "placeholder" => "YYYY-MM-DD"]); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#contrat-form").appForm({
            onSuccess: function (result) {
                $("#contrat-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('.select2').select2();
        setDateTimePicker(".datetimepicker");

        function calculateTotal() {
            const startDate = moment($("#date_depart").val(), "YYYY-MM-DD HH:mm");
            const endDate = moment($("#date_retour_prevue").val(), "YYYY-MM-DD HH:mm");
            const pricePerDay = parseFloat($("#prix_jour").val()) || 0;

            if (startDate.isValid() && endDate.isValid() && endDate.isAfter(startDate)) {
                const duration = moment.duration(endDate.diff(startDate));
                const days = Math.ceil(duration.asDays());
                
                $("#nombre_jours").val(days);
                $("#total_final").val(days * pricePerDay);
            } else {
                $("#nombre_jours").val(0);
                $("#total_final").val(0);
            }
        }

        $("#date_depart, #date_retour_prevue, #prix_jour").on("change", function() {
            calculateTotal();
        });

    });
</script>
