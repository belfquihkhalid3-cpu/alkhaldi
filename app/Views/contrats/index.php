<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1>Gestion des Contrats</h1>
            <div class="title-button-group">
                <?php
                echo modal_anchor(get_uri("contrats/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> Ajouter un contrat", ["class" => "btn btn-primary", "title" => "Ajouter un contrat"]);
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="contrat-table" class="display" cellspacing="0" width="100%">
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#contrat-table").appTable({
            source: '<?php echo_uri("contrats/list_data") ?>',
            columns: [
                {title: 'ID', "class": "text-center w50", "visible": false},
                {title: 'N° Contrat'},
                {title: 'Client'},
                {title: 'Voiture'},
                {title: 'Date de départ'},
                {title: 'Date de retour'},
                {title: 'Total'},
                {title: 'Statut'},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            createdRow: function (row, data, index) {
                $(row).attr('data-id', data[0]);
            }
        });
    });
</script>