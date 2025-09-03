<div id="page-content" class="page-wrapper clearfix">
    <!-- Statistiques Dashboard -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0"><?php echo $statistics['en_cours_aujourdhui'] ?? 0; ?></h4>
                    <small>En cours</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0"><?php echo $statistics['confirmees_a_venir'] ?? 0; ?></h4>
                    <small>Confirmées</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0"><?php echo $statistics['en_attente'] ?? 0; ?></h4>
                    <small>En attente</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0"><?php echo $statistics['total_mises_a_dispo'] ?? 0; ?></h4>
                    <small>Total</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0"><?php echo isset($statistics['ca_mois']) ? to_currency($statistics['ca_mois']) : '0 MAD'; ?></h4>
                    <small>CA Mois</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card bg-dark text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0"><?php echo $statistics['note_moyenne'] ?? 0; ?>/5</h4>
                    <small>Note Moy.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau principal -->
    <div class="card">
        <div class="page-title clearfix">
            <h1>🎯 Gestion des Mises à Disposition</h1>
            <div class="title-button-group">
                <?php
                echo modal_anchor(get_uri("mise_a_dispo/modal_add"), 
                    "➕ Nouvelle Mise à Disposition", 
                    ["class" => "btn btn-primary", "title" => "Créer une nouvelle mise à disposition"]);
                ?>
            </div>
        </div>
        
        <!-- Filtres -->
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="statut_filter">🏷️ Statut :</label>
                    <?php
                    echo form_dropdown("statut_filter", $statuts_dropdown ?? [], "", 
                        "class='form-control select2' id='statut_filter'");
                    ?>
                </div>
                <div class="col-md-3">
                    <label for="type_filter">🎯 Type :</label>
                    <?php
                    echo form_dropdown("type_filter", $types_dropdown ?? [], "", 
                        "class='form-control select2' id='type_filter'");
                    ?>
                </div>
                <div class="col-md-4">
                    <label for="search_filter">🔍 Recherche :</label>
                    <input type="text" class="form-control" id="search_filter" 
                           placeholder="Nom client, hôtel, lieu...">
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <div class="d-grid">
                        <button type="button" class="btn btn-outline-secondary" id="reset_filters">
                            🔄 Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table id="mise-a-dispo-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>📅 Période</th>
                        <th>🎯 Type Service</th>
                        <th>👤 Client</th>
                        <th>📍 Lieux</th>
                        <th>👥 Assignation</th>
                        <th>💰 Prix</th>
                        <th>ℹ️ Statut</th>
                        <th class="text-center w250">⚙️ Actions</th>
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
        console.log('🚀 Initialisation du module mise à disposition...');
        
        // Debug des statistiques
        <?php if (isset($statistics)): ?>
            console.log('📊 Statistiques chargées:', <?php echo json_encode($statistics); ?>);
        <?php else: ?>
            console.error('❌ Statistiques non chargées!');
        <?php endif; ?>

        var table = $("#mise-a-dispo-table").appTable({
            source: '<?php echo get_uri("mise_a_dispo/list_data") ?>',
            order: [[1, "desc"]], // Trier par période
            columns: [
                {title: 'ID', "visible": false},
                {title: 'Période', "class": "text-center"},
                {title: 'Type', "class": "text-center"},
                {title: 'Client'},
                {title: 'Lieux'},
                {title: 'Assignation', "class": "text-center"},
                {title: 'Prix', "class": "text-end"},
                {title: 'Statut', "class": "text-center"},
                {title: 'Actions', "class": "text-center option w250"}
            ],
            printColumns: [1, 2, 3, 4, 5, 6, 7],
            createdRow: function (row, data, index) {
                $(row).attr('data-id', data[0]);
            },
            onInitComplete: function () {
                console.log('✅ Table initialisée avec emojis');
            }
        });

        // Gestion des filtres
        $('#statut_filter, #type_filter').on('change', function() {
            console.log('🔄 Filtre changé:', $(this).attr('id'), '=', $(this).val());
            updateTable();
        });
        
        $('#search_filter').on('keyup', debounce(function() {
            console.log('🔍 Recherche:', $(this).val());
            updateTable();
        }, 500));
        
        $('#reset_filters').on('click', function() {
            console.log('🔄 Reset des filtres');
            $('#statut_filter, #type_filter').val('').trigger('change');
            $('#search_filter').val('');
            updateTable();
        });
        
        function updateTable() {
            if ($.fn.DataTable.isDataTable('#mise-a-dispo-table')) {
                $('#mise-a-dispo-table').DataTable().destroy();
            }
            
            table = $("#mise-a-dispo-table").appTable({
                source: '<?php echo get_uri("mise_a_dispo/list_data") ?>',
                serverMethod: 'POST',
                postData: {
                    statut: $('#statut_filter').val(),
                    type_service: $('#type_filter').val(),
                    search: $('#search_filter').val()
                },
                order: [[1, "desc"]],
                columns: [
                    {title: 'ID', "visible": false},
                    {title: 'Période', "class": "text-center"},
                    {title: 'Type', "class": "text-center"},
                    {title: 'Client'},
                    {title: 'Lieux'},
                    {title: 'Assignation', "class": "text-center"},
                    {title: 'Prix', "class": "text-end"},
                    {title: 'Statut', "class": "text-center"},
                    {title: 'Actions', "class": "text-center option w250"}
                ],
                createdRow: function (row, data, index) {
                    $(row).attr('data-id', data[0]);
                }
            });
        }

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
        
        console.log('🎉 Module mise à disposition complètement initialisé !');
    });
</script>