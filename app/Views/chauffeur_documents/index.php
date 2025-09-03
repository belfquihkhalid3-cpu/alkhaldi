<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1>Documents des Chauffeurs</h1>
        </div>
        <div class="table-responsive">
            <table id="all-documents-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fichier</th>
                        <th>Chauffeur</th>
                        <th>Type</th>
                        <th>Date d'upload</th>
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
        $("#all-documents-table").appTable({
            source: '<?php echo get_uri("chauffeur_documents/list_data") ?>',
            order: [[0, "desc"]],
            columns: [
                {title: 'ID', "visible": false},
                {title: 'Fichier'},
                {title: 'Chauffeur'},
                {title: 'Type'},
                {title: 'Date d\'upload'},
                {title: 'Actions', "class": "text-center option w100"}
            ],
            createdRow: function (row, data, index) {
                $(row).attr('data-id', data[0]);
            }
        });
    });
</script>