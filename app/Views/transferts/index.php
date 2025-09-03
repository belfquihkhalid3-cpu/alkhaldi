<div id="page-content" class="page-wrapper clearfix">
    <!-- Statistiques Dashboard -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i data-feather="calendar" class="icon-32 mb-2"></i>
                    <h4 class="mb-0"><?php echo $statistics['transferts_aujourdhui'] ?? 0; ?></h4>
                    <small>Aujourd'hui</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i data-feather="clock" class="icon-32 mb-2"></i>
                    <h4 class="mb-0"><?php echo $statistics['transferts_demain'] ?? 0; ?></h4>
                    <small>Demain</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i data-feather="activity" class="icon-32 mb-2"></i>
                    <h4 class="mb-0"><?php echo $statistics['transferts_en_cours'] ?? 0; ?></h4>
                    <small>En cours</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i data-feather="truck" class="icon-32 mb-2"></i>
                    <h4 class="mb-0"><?php echo $statistics['total_transferts'] ?? 0; ?></h4>
                    <small>Total</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <i data-feather="dollar-sign" class="icon-32 mb-2"></i>
                    <h4 class="mb-0"><?php echo isset($statistics['ca_mois']) ? to_currency($statistics['ca_mois']) : '0 MAD'; ?></h4>
                    <small>CA Mois</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-dark text-white">
                <div class="card-body text-center">
                    <i data-feather="star" class="icon-32 mb-2"></i>
                    <h4 class="mb-0"><?php echo $statistics['note_moyenne'] ?? 0; ?>/5</h4>
                    <small>Note Moy.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau principal -->
    <div class="card">
        <div class="page-title clearfix">
            <h1><i data-feather="map" class="icon-24 me-2"></i>Gestion des Transferts</h1>
            <div class="title-button-group">
                <?php
                echo modal_anchor(get_uri("transferts/modal_add"), 
                    "<i data-feather='plus-circle' class='icon-16'></i> Nouveau Transfert", 
                    ["class" => "btn btn-primary", "title" => "Créer un nouveau transfert"]);
                ?>
            </div>
        </div>
        
        <!-- Filtres -->
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="statut_filter"><i data-feather="filter" class="icon-16"></i> Statut :</label>
                    <?php
                    echo form_dropdown("statut_filter", $statuts_dropdown ?? [], "", 
                        "class='form-control select2' id='statut_filter'");
                    ?>
                </div>
                <div class="col-md-3">
                    <label for="type_filter"><i data-feather="arrow-right" class="icon-16"></i> Type :</label>
                    <?php
                    echo form_dropdown("type_filter", $types_dropdown ?? [], "", 
                        "class='form-control select2' id='type_filter'");
                    ?>
                </div>
                <div class="col-md-4">
                    <label for="search_filter"><i data-feather="search" class="icon-16"></i> Recherche :</label>
                    <input type="text" class="form-control" id="search_filter" 
                           placeholder="Nom client, n° vol, lieu...">
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <div class="d-grid">
                        <button type="button" class="btn btn-outline-secondary" id="reset_filters">
                            <i data-feather="refresh-ccw" class="icon-16"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table id="transferts-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th><i data-feather="calendar" class="icon-16"></i> Date</th>
                        <th><i data-feather="arrow-right" class="icon-16"></i> Type</th>
                        <th><i data-feather="user" class="icon-16"></i> Client</th>
                        <th><i data-feather="plane" class="icon-16"></i> Vol</th>
                        <th><i data-feather="map-pin" class="icon-16"></i> Itinéraire</th>
                        <th><i data-feather="users" class="icon-16"></i> Assignation</th>
                        <th><i data-feather="dollar-sign" class="icon-16"></i> Prix</th>
                        <th><i data-feather="info" class="icon-16"></i> Statut</th>
                        <th class="text-center w200"><i data-feather="settings" class="icon-16"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal pour changement de statut -->
<div class="modal fade" id="statutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i data-feather="edit-3" class="icon-16"></i> Changer le statut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="transfert_id">
                <input type="hidden" id="nouveau_statut">
                <div class="form-group">
                    <label for="commentaire_statut"><i data-feather="message-circle" class="icon-16"></i> Commentaire (optionnel) :</label>
                    <textarea class="form-control" id="commentaire_statut" rows="3" 
                              placeholder="Ajoutez un commentaire sur ce changement de statut..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i data-feather="x" class="icon-16"></i> Annuler
                </button>
                <button type="button" class="btn btn-primary" id="confirm_statut">
                    <i data-feather="check" class="icon-16"></i> Confirmer
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        console.log('Initialisation du module transferts...');
        
        // Debug des statistiques
        <?php if (isset($statistics)): ?>
            console.log('Statistiques chargées:', <?php echo json_encode($statistics); ?>);
        <?php else: ?>
            console.error('Statistiques non chargées!');
        <?php endif; ?>

        // Debug des dropdowns
        <?php if (isset($statuts_dropdown)): ?>
            console.log('Statuts dropdown:', <?php echo json_encode($statuts_dropdown); ?>);
        <?php else: ?>
            console.error('Statuts dropdown non défini!');
        <?php endif; ?>

        var table = $("#transferts-table").appTable({
            source: '<?php echo get_uri("transferts/list_data") ?>',
            order: [[1, "desc"]], // Trier par date
            columns: [
                {title: 'ID', "visible": false},
                {title: 'Date', "class": "text-center"},
                {title: 'Type', "class": "text-center"},
                {title: 'Client'},
                {title: 'Vol', "class": "text-center"},
                {title: 'Itinéraire'},
                {title: 'Assignation', "class": "text-center"},
                {title: 'Prix', "class": "text-end"},
                {title: 'Statut', "class": "text-center"},
                {title: 'Actions', "class": "text-center option w200"}
            ],
            printColumns: [1, 2, 3, 4, 5, 6, 7, 8],
            createdRow: function (row, data, index) {
                $(row).attr('data-id', data[0]);
            },
            onInitComplete: function () {
                console.log('Table initialisée, activation des icônes Feather...');
                feather.replace();
            },
            onReloadComplete: function () {
                feather.replace();
            },
            drawCallback: function() {
                // ? Appelé à chaque redraw du tableau (comme dans Jawaz)
                feather.replace();
            }
        });

        // Gestion des filtres
        $('#statut_filter, #type_filter').on('change', function() {
            console.log('Filtre changé:', $(this).attr('id'), '=', $(this).val());
            updateTable();
        });
        
        $('#search_filter').on('keyup', debounce(function() {
            console.log('Recherche:', $(this).val());
            updateTable();
        }, 500));
        
        $('#reset_filters').on('click', function() {
            console.log('Reset des filtres');
            $('#statut_filter, #type_filter').val('').trigger('change');
            $('#search_filter').val('');
            updateTable();
        });
        
        function updateTable() {
            if ($.fn.DataTable.isDataTable('#transferts-table')) {
                $('#transferts-table').DataTable().destroy();
            }
            
            table = $("#transferts-table").appTable({
                source: '<?php echo get_uri("transferts/list_data") ?>',
                serverMethod: 'POST',
                postData: {
                    statut: $('#statut_filter').val(),
                    type_transfert: $('#type_filter').val(),
                    search: $('#search_filter').val()
                },
                order: [[1, "desc"]],
                columns: [
                    {title: 'ID', "visible": false},
                    {title: 'Date', "class": "text-center"},
                    {title: 'Type', "class": "text-center"},
                    {title: 'Client'},
                    {title: 'Vol', "class": "text-center"},
                    {title: 'Itinéraire'},
                    {title: 'Assignation', "class": "text-center"},
                    {title: 'Prix', "class": "text-end"},
                    {title: 'Statut', "class": "text-center"},
                    {title: 'Actions', "class": "text-center option w200"}
                ],
                createdRow: function (row, data, index) {
                    $(row).attr('data-id', data[0]);
                },
                onInitComplete: function () {
                    feather.replace();
                },
                onReloadComplete: function () {
                    feather.replace();
                }
            });
        }

        // Changement de statut
        $(document).on('click', '.change-statut', function() {
            var id = $(this).data('id');
            var statut = $(this).data('statut');
            
            console.log('Changement statut demandé:', id, '->', statut);
            
            $('#transfert_id').val(id);
            $('#nouveau_statut').val(statut);
            $('#commentaire_statut').val('');
            
            // Afficher le modal avec titre dynamique
            var titres = {
                'confirme': 'Confirmer le transfert',
                'en_cours': 'Démarrer le transfert', 
                'termine': 'Terminer le transfert',
                'annule': 'Annuler le transfert'
            };
            
            $('.modal-title').html('<i data-feather="edit-3" class="icon-16"></i> ' + (titres[statut] || 'Changer le statut'));
            $('#statutModal').modal('show');
            
            // Réactiver les icônes dans le modal
            setTimeout(function() {
                feather.replace();
            }, 100);
        });
        
        $('#confirm_statut').on('click', function() {
            var id = $('#transfert_id').val();
            var statut = $('#nouveau_statut').val();
            var commentaire = $('#commentaire_statut').val();
            
            console.log('Confirmation changement statut:', {id, statut, commentaire});
            
            // Désactiver le bouton pendant le traitement
            $(this).prop('disabled', true).html('<i data-feather="loader" class="icon-16 me-1"></i>Traitement...');
            feather.replace();
            
            $.ajax({
                url: '<?php echo get_uri("transferts/change_statut") ?>',
                type: 'POST',
                data: {
                    id: id,
                    statut: statut,
                    commentaire: commentaire
                },
                success: function(result) {
                    try {
                        result = JSON.parse(result);
                        console.log('Réponse serveur:', result);
                        
                        if (result.success) {
                            $("#transferts-table").appTable({newData: result.data, dataId: result.id});
                            $('#statutModal').modal('hide');
                            
                            if (typeof appAlert !== 'undefined') {
                                appAlert.success(result.message);
                            } else {
                                alert(result.message);
                            }
                        } else {
                            if (typeof appAlert !== 'undefined') {
                                appAlert.error(result.message);
                            } else {
                                alert('Erreur: ' + result.message);
                            }
                        }
                    } catch (e) {
                        console.error('Erreur parsing JSON:', e, result);
                        alert('Erreur de communication avec le serveur');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur AJAX:', {xhr, status, error});
                    alert('Erreur de connexion au serveur');
                },
                complete: function() {
                    // Réactiver le bouton
                    $('#confirm_statut').prop('disabled', false).html('<i data-feather="check" class="icon-16"></i> Confirmer');
                    feather.replace();
                }
            });
        });

        // Fonctions utilitaires
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Initialiser les composants
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        }
        
        console.log('Module transferts complètement initialisé - SANS Feather replace');
        
        // PAS d'observer - Feather est cassé
        console.log('Emojis utilisés à la place des icônes Feather cassées');
    });
</script>