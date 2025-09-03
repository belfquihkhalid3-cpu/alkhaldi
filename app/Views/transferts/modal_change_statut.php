<?php echo form_open(get_uri("transferts/change_statut"), array("id" => "change-statut-form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $transfert_id; ?>" />
        <input type="hidden" name="statut" value="<?php echo $nouveau_statut; ?>" />
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <label for="commentaire" class="col-md-3 col-form-label">
                            ? Commentaire
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_textarea(array(
                                "id" => "commentaire",
                                "name" => "commentaire",
                                "class" => "form-control",
                                "placeholder" => "Commentaire sur ce changement de statut (optionnel)...",
                                "rows" => 3,
                                "data-rule-required" => false,
                                "data-msg-required" => ""
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Champs supplémentaires selon le statut -->
        <?php if ($nouveau_statut === 'en_cours'): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="row">
                            <label for="heure_prise_en_charge_reelle" class="col-md-3 col-form-label">
                                ? Heure prise en charge
                            </label>
                            <div class="col-md-9">
                                <?php
                                echo form_input(array(
                                    "id" => "heure_prise_en_charge_reelle",
                                    "name" => "heure_prise_en_charge_reelle",
                                    "class" => "form-control",
                                    "type" => "datetime-local",
                                    "value" => date('Y-m-d\TH:i'),
                                    "placeholder" => "Heure réelle de prise en charge"
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($nouveau_statut === 'termine'): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="row">
                            <label for="heure_arrivee_reelle" class="col-md-3 col-form-label">
                                ? Heure d'arrivée
                            </label>
                            <div class="col-md-9">
                                <?php
                                echo form_input(array(
                                    "id" => "heure_arrivee_reelle",
                                    "name" => "heure_arrivee_reelle",
                                    "class" => "form-control",
                                    "type" => "datetime-local",
                                    "value" => date('Y-m-d\TH:i'),
                                    "placeholder" => "Heure réelle d'arrivée"
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="row">
                            <label for="prix_facture" class="col-md-3 col-form-label">
                                ? Prix facturé
                            </label>
                            <div class="col-md-9">
                                <?php
                                echo form_input(array(
                                    "id" => "prix_facture",
                                    "name" => "prix_facture",
                                    "class" => "form-control",
                                    "type" => "number",
                                    "step" => "0.01",
                                    "placeholder" => "Prix final facturé au client"
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="row">
                            <label for="notes_chauffeur" class="col-md-3 col-form-label">
                                ? Notes chauffeur
                            </label>
                            <div class="col-md-9">
                                <?php
                                echo form_textarea(array(
                                    "id" => "notes_chauffeur",
                                    "name" => "notes_chauffeur",
                                    "class" => "form-control",
                                    "placeholder" => "Notes du chauffeur sur le transfert...",
                                    "rows" => 2
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        ? Annuler
    </button>
    <button type="submit" class="btn <?php echo $action_class; ?>">
        ? <?php echo $action_title; ?>
    </button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#change-statut-form").appForm({
            onSuccess: function (result) {
                if (result.success) {
                    // Si on est sur la page index, mettre à jour le tableau
                    if ($("#transferts-table").length > 0) {
                        $("#transferts-table").appTable({newData: result.data, dataId: result.id});
                    } else {
                        // Si on est sur la page view, recharger la page
                        location.reload();
                    }
                }
            }
        });
        
        console.log('Modal change statut initialisé avec emojis');
    });
</script>