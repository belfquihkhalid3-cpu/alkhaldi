<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1>Gestion des Voitures</h1>
            <div class="title-button-group">
                <?php
                // CORRECTION: Utilisation de l'icône Feather
                echo modal_anchor(get_uri("voitures_contrat/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> Ajouter une voiture", ["class" => "btn btn-primary", "title" => "Ajouter une voiture"]);
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="voiture-contrat-table" class="display" cellspacing="0" width="100%">
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#voiture-contrat-table").appTable({
            source: '<?php echo_uri("voitures_contrat/list_data") ?>',
            columns: [
                {title: 'ID', "class": "text-center w50", "visible": false},
                {title: 'Marque'},
                {title: 'Modèle'},
                {title: 'Immatriculation'},
                {title: 'Statut', "class": "text-center w100"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            createdRow: function (row, data, index) {
                $(row).attr('data-id', data[0]);
            }
        });
    });
</script>