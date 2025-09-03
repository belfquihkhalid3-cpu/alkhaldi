<!-- Fichier: app/Views/clients_contrat/index.php -->
<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1>Gestion des Clients</h1>
            <div class="title-button-group">
                <?php
                echo modal_anchor(get_uri("clients_contrat/modal_form"), "<i class='fa fa-plus-circle'></i> Ajouter un client", ["class" => "btn btn-primary", "title" => "Ajouter un client"]);
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="client-contrat-table" class="display" cellspacing="0" width="100%">
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#client-contrat-table").appTable({
            source: '<?php echo_uri("clients_contrat/list_data") ?>',
            columns: [
                {title: 'ID', "class": "text-center w50", "visible": false},
                {title: 'Prénom'},
                {title: 'Nom'},
                {title: 'Téléphone'},
                {title: 'Pièce d\'identité'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            createdRow: function (row, data, index) {
                $(row).attr('data-id', data[0]);
            }
        });
    });
</script>