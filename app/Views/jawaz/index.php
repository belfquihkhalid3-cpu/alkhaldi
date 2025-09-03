<div id="page-content" class="page-wrapper clearfix">
    <!-- Statistiques en haut de page -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i data-feather="credit-card" class="icon-32"></i>
                        </div>
                        <div>
                            <h4 class="mb-0"><?php echo $statistics['total_badges'] ?? 0; ?></h4>
                            <p class="mb-0">Total Badges</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i data-feather="activity" class="icon-32"></i>
                        </div>
                        <div>
                            <h4 class="mb-0"><?php echo $statistics['badges_actifs'] ?? 0; ?></h4>
                            <p class="mb-0">En circulation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i data-feather="corner-up-left" class="icon-32"></i>
                        </div>
                        <div>
                            <h4 class="mb-0"><?php echo $statistics['badges_retournes'] ?? 0; ?></h4>
                            <p class="mb-0">Retournés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i data-feather="dollar-sign" class="icon-32"></i>
                        </div>
                        <div>
                            <h4 class="mb-0"><?php echo to_currency($statistics['solde_total'] ?? 0); ?></h4>
                            <p class="mb-0">Solde Total</p>
                            <small>Moyenne: <?php echo to_currency($statistics['solde_moyen'] ?? 0); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau principal -->
    <div class="card">
        <div class="page-title clearfix">
            <h1><i data-feather="credit-card" class="icon-24 me-2"></i>Gestion des Badges Jawaz</h1>
            <div class="title-button-group">
                <?php
                // CORRECTION: Modal pour ajout avec méthode dédiée
                echo modal_anchor(get_uri("jawaz/modal_add"), "<i data-feather='plus-circle' class='icon-16'></i> Nouveau Badge", 
                    ["class" => "btn btn-primary", "title" => "Ajouter un nouveau badge Jawaz"]);
                ?>
            </div>
        </div>
        
        <!-- Filtres -->
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="statut_filter">Filtrer par statut :</label>
                    <?php
                    echo form_dropdown("statut_filter", $statuts_dropdown ?? [], "", "class='form-control select2' id='statut_filter'");
                    ?>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table id="jawaz-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th><i data-feather="hash" class="icon-16"></i> N° Série</th>
                        <th><i data-feather="truck" class="icon-16"></i> Véhicule</th>
                        <th><i data-feather="user" class="icon-16"></i> Chauffeur</th>
                        <th><i data-feather="dollar-sign" class="icon-16"></i> Solde</th>
                        <th><i data-feather="calendar" class="icon-16"></i> Date</th>
                        <th><i data-feather="info" class="icon-16"></i> Statut</th>
                        <th class="text-center w150"><i data-feather="settings" class="icon-16"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var table = $("#jawaz-table").appTable({
            source: '<?php echo get_uri("jawaz/list_data") ?>',
            order: [[0, "desc"]],
            columns: [
                {title: 'ID', "visible": false},
                {title: 'N° Série', "class": "text-center"},
                {title: 'Véhicule'},
                {title: 'Chauffeur'},
                {title: 'Solde', "class": "text-end"},
                {title: 'Date', "class": "text-center"},
                {title: 'Statut', "class": "text-center"},
                {title: 'Actions', "class": "text-center option w150"}
            ],
            printColumns: [1, 2, 3, 4, 5, 6],
            createdRow: function (row, data, index) {
                $(row).attr('data-id', data[0]);
            },
            onInitComplete: function () {
                feather.replace();
            }
        });

        // Gestion du filtre par statut
        $('#statut_filter').on('change', function() {
            var statut = $(this).val();
            table.fnDestroy();
            
            $("#jawaz-table").appTable({
                source: '<?php echo get_uri("jawaz/list_data") ?>',
                serverMethod: 'POST',
                postData: {statut: statut},
                order: [[0, "desc"]],
                columns: [
                    {title: 'ID', "visible": false},
                    {title: 'N° Série', "class": "text-center"},
                    {title: 'Véhicule'},
                    {title: 'Chauffeur'},
                    {title: 'Solde', "class": "text-end"},
                    {title: 'Date', "class": "text-center"},
                    {title: 'Statut', "class": "text-center"},
                    {title: 'Actions', "class": "text-center option w150"}
                ],
                printColumns: [1, 2, 3, 4, 5, 6],
                createdRow: function (row, data, index) {
                    $(row).attr('data-id', data[0]);
                },
                onInitComplete: function () {
                    feather.replace();
                }
            });
        });

        // Initialiser Select2 et Feather Icons
        $('.select2').select2();
        feather.replace();
    });
</script>