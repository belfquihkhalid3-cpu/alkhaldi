<?php echo form_open(get_uri("chauffeur_payments/save"), array("id" => "payment-form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix">
    <div class="container-fluid">
        
        <!-- Champ ID caché -->
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        
        <div class="row">
            <!-- COLONNE GAUCHE - Informations de base -->
            <div class="col-md-6">
                <div class="form-group">
                    <div class="row">
                        <label for="chauffeur_id" class="col-md-3 col-form-label">
                            <i data-feather="user" class="icon-16"></i> Chauffeur <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown("chauffeur_id", $chauffeurs_dropdown, 
                                $model_info->chauffeur_id, 
                                "class='select2 form-control' id='chauffeur_id' data-rule-required='true' data-msg-required='" . lang('field_required') . "'"
                            );
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="type_paiement" class="col-md-3 col-form-label">
                            <i data-feather="tag" class="icon-16"></i> Type <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown("type_paiement", $types_dropdown, 
                                $model_info->type_paiement, 
                                "class='select2 form-control' id='type_paiement' data-rule-required='true' data-msg-required='" . lang('field_required') . "'"
                            );
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="montant" class="col-md-3 col-form-label">
                            <i data-feather="dollar-sign" class="icon-16"></i> Montant <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="number" id="montant" name="montant" step="0.01" min="0" 
                                       value="<?php echo $model_info->montant; ?>" 
                                       class="form-control" placeholder="0.00" 
                                       data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" />
                                <span class="input-group-text">DH</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="mois_concerne" class="col-md-3 col-form-label">
                            <i data-feather="calendar" class="icon-16"></i> Mois Concerné
                        </label>
                        <div class="col-md-9">
                            <input type="month" id="mois_concerne" name="mois_concerne" 
                                   value="<?php echo $model_info->mois_concerne; ?>" 
                                   class="form-control" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="date_paiement" class="col-md-3 col-form-label">
                            <i data-feather="clock" class="icon-16"></i> Date Paiement <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="date" id="date_paiement" name="date_paiement" 
                                   value="<?php echo $model_info->date_paiement; ?>" 
                                   class="form-control" 
                                   data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- COLONNE DROITE - Mode de paiement et gestion avances -->
            <div class="col-md-6">
                <div class="form-group">
                    <div class="row">
                        <label for="mode_paiement" class="col-md-3 col-form-label">
                            <i data-feather="credit-card" class="icon-16"></i> Mode
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown("mode_paiement", $modes_dropdown, 
                                $model_info->mode_paiement, 
                                "class='select2 form-control' id='mode_paiement'"
                            );
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="statut" class="col-md-3 col-form-label">
                            <i data-feather="check-circle" class="icon-16"></i> Statut
                        </label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown("statut", $statuts_dropdown, 
                                $model_info->statut, 
                                "class='select2 form-control' id='statut'"
                            );
                            ?>
                        </div>
                    </div>
                </div>

                <!-- SECTION GESTION DES AVANCES -->
                <div id="avances-section" class="mt-4" style="display: none;">
                    <div class="card bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i data-feather="arrow-down-circle" class="icon-16 text-warning"></i>
                                Gestion des Avances
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Solde actuel des avances -->
                            <div id="solde-avances-info" class="alert alert-info" style="display: none;">
                                <i data-feather="info" class="icon-16"></i>
                                <strong>Solde avances : </strong>
                                <span id="solde-amount">0.00 DH</span>
                            </div>

                            <!-- Sélection de l'avance à déduire -->
                            <div class="form-group" id="avance-selection" style="display: none;">
                                <label for="avance_reference_id" class="form-label">Avance à déduire :</label>
                                <select id="avance_reference_id" name="avance_reference_id" class="form-control">
                                    <option value="">-- Déduction automatique --</option>
                                </select>
                                <small class="form-text text-muted">
                                    Laissez vide pour déduction automatique par ordre chronologique
                                </small>
                            </div>

                            <!-- Montant de déduction -->
                            <div class="form-group" id="avance-montant" style="display: none;">
                                <label for="avance_deduite" class="form-label">Montant à déduire :</label>
                                <div class="input-group">
                                    <input type="number" id="avance_deduite" name="avance_deduite" 
                                           step="0.01" min="0" class="form-control" placeholder="0.00" />
                                    <span class="input-group-text">DH</span>
                                </div>
                                <small class="form-text text-muted">
                                    Maximum : <span id="max-deduction">0.00</span> DH
                                </small>
                            </div>

                            <!-- Liste des avances disponibles -->
                            <div id="avances-disponibles" style="display: none;">
                                <h6 class="mt-3">Avances disponibles :</h6>
                                <div id="avances-list" class="small">
                                    <!-- Sera rempli dynamiquement -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label for="description" class="col-md-3 col-form-label">
                            <i data-feather="file-text" class="icon-16"></i> Description
                        </label>
                        <div class="col-md-9">
                            <textarea id="description" name="description" rows="3" 
                                      class="form-control" placeholder="Description ou commentaire..."><?php echo $model_info->description; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal">
        <i data-feather="x" class="icon-16"></i> <?php echo lang('close'); ?>
    </button>
    <button type="submit" class="btn btn-primary">
        <i data-feather="check" class="icon-16"></i> <?php echo lang('save'); ?>
    </button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        // Initialiser les icônes Feather
        feather.replace();
        
        // Initialiser les select2
        $("#chauffeur_id, #type_paiement, #mode_paiement, #statut").select2();
        
        // Gestionnaire de changement de chauffeur
        $("#chauffeur_id").on('change', function() {
            loadChauffeurAvances($(this).val());
        });
        
        // Gestionnaire de changement de type de paiement
        $("#type_paiement").on('change', function() {
            toggleAvancesSection();
        });
        
        // Gestionnaire de changement d'avance sélectionnée
        $("#avance_reference_id").on('change', function() {
            updateDeductionAmount();
        });
        
        // Gestionnaire de changement du montant principal
        $("#montant").on('input', function() {
            updateMaxDeduction();
        });
        
        // Validation du formulaire
        $("#payment-form").appForm({
            onSuccess: function(result) {
                $("#payments-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        
        // Charger les avances si on est en mode édition
        <?php if (!empty($model_info->chauffeur_id)): ?>
            loadChauffeurAvances(<?php echo $model_info->chauffeur_id; ?>);
        <?php endif; ?>
        
        // Afficher la section avances si nécessaire
        toggleAvancesSection();
    });

    function loadChauffeurAvances(chauffeurId) {
        if (!chauffeurId) {
            $("#avances-section").hide();
            return;
        }
        
        $.ajax({
            url: '<?php echo get_uri("chauffeur_payments/get_chauffeur_avances"); ?>',
            type: 'POST',
            data: {chauffeur_id: chauffeurId},
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Mettre à jour le solde
                    $("#solde-amount").text(parseFloat(response.solde_avances).toFixed(2) + ' DH');
                    
                    if (response.solde_avances > 0) {
                        $("#solde-avances-info").show();
                        
                        // Remplir la liste des avances disponibles
                        let avancesHtml = '';
                        let avancesOptions = '<option value="">-- Déduction automatique --</option>';
                        
                        response.avances_disponibles.forEach(function(avance) {
                            avancesHtml += `
                                <div class="d-flex justify-content-between border-bottom py-1">
                                    <span>${avance.description || 'Avance du ' + avance.date_paiement}</span>
                                    <span class="text-success">${parseFloat(avance.solde_restant).toFixed(2)} DH</span>
                                </div>
                            `;
                            
                            avancesOptions += `<option value="${avance.id}" data-solde="${avance.solde_restant}">
                                ${avance.description || 'Avance du ' + avance.date_paiement} 
                                (${parseFloat(avance.solde_restant).toFixed(2)} DH)
                            </option>`;
                        });
                        
                        $("#avances-list").html(avancesHtml);
                        $("#avance_reference_id").html(avancesOptions);
                        $("#avances-disponibles").show();
                    } else {
                        $("#solde-avances-info").hide();
                        $("#avances-disponibles").hide();
                    }
                }
            },
            error: function() {
                console.log('Erreur lors du chargement des avances');
            }
        });
    }

    function toggleAvancesSection() {
        const typePaiement = $("#type_paiement").val();
        const chauffeurId = $("#chauffeur_id").val();
        
        if (chauffeurId && (typePaiement === 'salaire' || typePaiement === 'prime')) {
            $("#avances-section").show();
            $("#avance-selection").show();
            $("#avance-montant").show();
            updateMaxDeduction();
        } else {
            $("#avances-section").hide();
        }
    }

    function updateDeductionAmount() {
        const selectedAvance = $("#avance_reference_id option:selected");
        const soldeRestant = selectedAvance.data('solde');
        
        if (soldeRestant) {
            $("#avance_deduite").val(parseFloat(soldeRestant).toFixed(2));
            $("#max-deduction").text(parseFloat(soldeRestant).toFixed(2));
        } else {
            $("#avance_deduite").val('');
            updateMaxDeduction();
        }
    }

    function updateMaxDeduction() {
        const montantPaiement = parseFloat($("#montant").val()) || 0;
        const soldeTotal = parseFloat($("#solde-amount").text()) || 0;
        const maxDeduction = Math.min(montantPaiement, soldeTotal);
        
        if ($("#avance_reference_id").val() === '') {
            $("#max-deduction").text(maxDeduction.toFixed(2));
            
            // Suggestion automatique
            if (maxDeduction > 0 && montantPaiement > 0) {
                $("#avance_deduite").attr('placeholder', 'Suggestion: ' + maxDeduction.toFixed(2));
            }
        }
    }

    // Validation personnalisée
    $("#payment-form").on('submit', function(e) {
        const montant = parseFloat($("#montant").val()) || 0;
        const avanceDeduite = parseFloat($("#avance_deduite").val()) || 0;
        
        if (avanceDeduite > montant) {
            e.preventDefault();
            appAlert.error("Le montant de déduction ne peut pas être supérieur au montant du paiement");
            return false;
        }
        
        const selectedAvance = $("#avance_reference_id option:selected");
        const soldeRestant = parseFloat(selectedAvance.data('solde')) || 0;
        
        if (selectedAvance.val() && avanceDeduite > soldeRestant) {
            e.preventDefault();
            appAlert.error("Le montant de déduction ne peut pas être supérieur au solde de l'avance sélectionnée");
            return false;
        }
    });
</script>