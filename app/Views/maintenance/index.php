<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo lang("maintenance"); ?></h1>
            <div class="title-button-group">
                <?php
                echo modal_anchor(get_uri("maintenance/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . lang('add_maintenance'), ["class" => "btn btn-primary", "title" => lang('add_maintenance')]);
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="maintenance-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Véhicule</th>
                        <th>Type</th>
                        <th>Kilométrage</th>
                        <th>Coût</th>
                        <th>Garage</th>
                        <th class="text-center w100">Actions</th>
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
        $("#maintenance-table").appTable({
            source: '<?php echo get_uri("maintenance/list_data") ?>',
            order: [[0, "desc"]],
            columns: [
                {title: 'ID', "visible": false}, // On cache la colonne ID
                {title: 'Date'},
                {title: 'Véhicule'},
                {title: 'Type'},
                {title: 'Kilométrage'},
                {title: 'Coût'},
                {title: 'Garage'},
                {title: 'Actions', "class": "text-center option w100"}
            ],
            printColumns: [1, 2, 3, 4, 5, 6], // On retire la colonne 0 (ID) de l'impression

            // On force la création de l'attribut 'data-id' sur chaque ligne <tr>
            createdRow: function (row, data, index) {
                $(row).attr('data-id', data[0]);
            }
        });
    });
</script>