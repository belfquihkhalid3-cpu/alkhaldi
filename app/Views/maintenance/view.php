<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo "Détails de la maintenance #" . $model_info->id; ?></h1>
            <div class="title-button-group">
                <?php
                // Bouton pour modifier, qui ouvre le même formulaire modal que nous avons déjà créé
                echo modal_anchor(get_uri("maintenance/modal_form"), "<i data-feather='edit' class='icon-16'></i> " . lang('edit'), ["class" => "btn btn-default", "title" => "Modifier la maintenance", "data-post-id" => $model_info->id]);
                ?>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="p-2">
                        <strong class="font-bold">Véhicule :</strong> <?php echo anchor(get_uri("vehicles/view/" . $model_info->vehicle_id), $model_info->vehicle_marque . " " . $model_info->vehicle_modele); ?><br>
                        <strong>N° Matricule :</strong> <?php echo $model_info->vehicle_matricule; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-2">
                        <strong class="font-bold">Date de maintenance :</strong> <?php echo format_to_date($model_info->date_maintenance, false); ?><br>
                        <strong>Kilométrage :</strong> <?php echo $model_info->km_maintenance; ?> km
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <div class="p-2 border-top">
                        <strong class="font-bold">Type de maintenance :</strong> <?php echo lang($model_info->type_maintenance) ? lang($model_info->type_maintenance) : $model_info->type_maintenance; ?><br>
                        <strong>Coût :</strong> <?php echo to_currency($model_info->cout); ?><br>
                        <strong>Garage / Prestataire :</strong> <?php echo $model_info->garage ? $model_info->garage : "N/A"; ?><br>
                    </div>
                </div>
                 <div class="col-md-12 mt-4">
                    <div class="p-2 border-top">
                        <strong class="font-bold">Description :</strong><br>
                        <p><?php echo nl2br($model_info->description ? $model_info->description : "Aucune description."); ?></p>
                    </div>
                </div>
                
                <?php if ($model_info->prochaine_maintenance_date || $model_info->prochaine_maintenance_km) { ?>
                <div class="col-md-12 mt-4">
                    <div class="p-2 border-top bg-light">
                        <strong classs="font-bold">Prochaine Maintenance Prévue :</strong><br>
                        <?php if ($model_info->prochaine_maintenance_date) { ?>
                            <strong>Date :</strong> <?php echo format_to_date($model_info->prochaine_maintenance_date, false); ?><br>
                        <?php } ?>
                         <?php if ($model_info->prochaine_maintenance_km) { ?>
                            <strong>Kilométrage :</strong> <?php echo $model_info->prochaine_maintenance_km; ?> km
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>