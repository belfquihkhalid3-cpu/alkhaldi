<div class="card">
    <div class="card-header">
        <div class="title-button-group">
            <i data-feather="file-text" class="icon-16"></i>&nbsp; Documents du chauffeur
        </div>
    </div>
    <div class="table-responsive">
        <table id="chauffeur-documents-table" class="display" cellspacing="0" width="100%">
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#chauffeur-documents-table").appTable({
            source: '<?php echo get_uri("chauffeur_documents/list_data/" . $chauffeur_id) ?>',
            order: [[0, "desc"]],
            columns: [
                {title: 'Fichier'},
                {title: 'Type'},
                {title: 'Taille du fichier'},
                {title: 'Date d\'upload'},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ]
        });
    });
</script>