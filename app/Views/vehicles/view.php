<!-- ========================================
     FICHIER : Views/vehicles/view.php
     Dï¿½tails d'un vï¿½hicule
     ======================================== -->

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-car text-primary"></i> 
                <?= esc($vehicle->marque . ' ' . $vehicle->modele) ?>
            </h1>
            <div>
                <a href="<?= site_url('vehicles') ?>" class="btn btn-secondary btn-sm me-2">
                    <i class="fas fa-arrow-left"></i> Retour ï¿½ la liste
                </a>
                <a href="<?= site_url('vehicles/edit/' . $vehicle->id) ?>" class="btn btn-warning btn-sm me-2">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="<?= site_url('vehicles/delete/' . $vehicle->id) ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('ï¿½tes-vous sï¿½r de vouloir supprimer ce vï¿½hicule ?')">
                    <i class="fas fa-trash"></i> Supprimer
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Image principale -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-body p-0">
                        <?php if ($vehicle->image): ?>
                            <img src="<?= base_url('uploads/vehicles/' . $vehicle->image) ?>" 
                                 class="img-fluid rounded" alt="<?= esc($vehicle->marque . ' ' . $vehicle->modele) ?>">
                        <?php else: ?>
                            <div class="placeholder-image-large">
                                <i class="fas fa-car fa-5x"></i>
                                <p class="mt-3">Aucune image disponible</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Informations dï¿½taillï¿½es -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Informations Générales
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Prix :</div>
                            <div class="col-sm-8">
                                <span class="h4 text-primary font-weight-bold">
                                    <?= number_format($vehicle->prix, 0, ',', ' ') ?> EUR
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Kilométrage :</div>
                            <div class="col-sm-8"><?= number_format($vehicle->kilometrage) ?> km</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Carburant :</div>
                            <div class="col-sm-8">
                                <?php
                                $carburant_icons = [
                                    'essence' => '?',
                                    'diesel' => '?',
                                    'hybride' => '?',
                                    'electrique' => '?'
                                ];
                                ?>
                                <?= $carburant_icons[$vehicle->carburant] ?? '' ?> <?= ucfirst($vehicle->carburant) ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Transmission :</div>
                            <div class="col-sm-8">
                                <?= $vehicle->transmission == 'manuelle' ? '?' : '?' ?> 
                                <?= ucfirst($vehicle->transmission) ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Couleur :</div>
                            <div class="col-sm-8"><?= esc($vehicle->couleur) ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Statut :</div>
                            <div class="col-sm-8">
                                <span class="badge badge-<?= $vehicle->statut == 'disponible' ? 'success' : ($vehicle->statut == 'vendu' ? 'danger' : 'warning') ?>">
                                    <?php
                                    $statut_icons = [
                                        'disponible' => '?',
                                        'vendu' => '?',
                                        'reserve' => '?'
                                    ];
                                    ?>
                                    <?= $statut_icons[$vehicle->statut] ?? '' ?> <?= ucfirst($vehicle->statut) ?>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Ajouté le :</div>
                            <div class="col-sm-8"><?= date('d/m/Y a  H:i', strtotime($vehicle->date_ajout)) ?></div>
                        </div>
                        <?php if ($vehicle->date_modification != $vehicle->date_ajout): ?>
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Modifié le :</div>
                            <div class="col-sm-8"><?= date('d/m/Y a H:i', strtotime($vehicle->date_modification)) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Description -->
                <?php if ($vehicle->description): ?>
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Description</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?= nl2br(esc($vehicle->description)) ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Actions rapides</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-info btn-block" onclick="printVehicle()">
                                    <i class="fas fa-print"></i> Imprimer la fiche
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-success btn-block" onclick="shareVehicle()">
                                    <i class="fas fa-share-alt"></i> Partager
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-warning btn-block" onclick="duplicateVehicle()">
                                    <i class="fas fa-copy"></i> Dupliquer
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-secondary btn-block" onclick="exportVehicle()">
                                    <i class="fas fa-download"></i> Exporter PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.placeholder-image-large {
    height: 400px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    border-radius: 0.375rem;
}

.badge-success {
    background-color: #28a745;
}

.badge-danger {
    background-color: #dc3545;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

@media print {
    .btn, .card-header, .page-header {
        display: none !important;
    }
}
</style>

<script>
function printVehicle() {
    window.print();
}

function shareVehicle() {
    if (navigator.share) {
        navigator.share({
            title: '<?= esc($vehicle->marque . ' ' . $vehicle->modele) ?>',
            text: 'Dï¿½couvrez ce vï¿½hicule : <?= esc($vehicle->marque . ' ' . $vehicle->modele) ?> - <?= number_format($vehicle->prix) ?> EUR',
            url: window.location.href
        });
    } else {
        // Fallback pour les navigateurs qui ne supportent pas l'API Web Share
        navigator.clipboard.writeText(window.location.href).then(function() {
            alert('Lien copiï¿½ dans le presse-papiers !');
        });
    }
}

function duplicateVehicle() {
    if (confirm('Voulez-vous crï¿½er un nouveau vï¿½hicule basï¿½ sur celui-ci ?')) {
        window.location.href = '<?= site_url('vehicles/add') ?>?duplicate=<?= $vehicle->id ?>';
    }
}

function exportVehicle() {
    // Fonction pour exporter en PDF (ï¿½ implï¿½menter selon vos besoins)
    alert('Fonctionnalitï¿½ d\'export PDF ï¿½ implï¿½menter');
}
</script>
