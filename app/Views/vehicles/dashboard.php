
<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt text-primary"></i> Tableau de bord véhicules
            </h1>
            <div>
                <button class="btn btn-primary btn-sm me-2" onclick="refreshStats()">
                    <i class="fas fa-sync-alt"></i> Actualiser
                </button>
                <a href="<?= site_url('vehicles/add') ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Nouveau véhicule
                </a>
            </div>
        </div>

        <!-- Statistiques avancées -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Valeur totale du stock
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-value">
                                    <?= number_format($total_value ?? 0, 0, ',', ' ') ?> €
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Prix moyen
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="average-price">
                                    <?= number_format($average_price ?? 0, 0, ',', ' ') ?> €
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calculator fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Véhicules récents
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="recent-vehicles">
                                    <?= $recent_count ?? 0 ?>
                                </div>
                                <div class="text-xs text-gray-500">Cette semaine</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-plus fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Marques différentes
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="brands-count">
                                    <?= $brands_count ?? 0 ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tags fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques et analyses -->
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Répartition par statut</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top marques</h6>
                    </div>
                    <div class="card-body">
                        <div id="brands-list">
                            <!-- Liste des marques populaires -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Véhicules récents -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Véhicules ajoutés récemment</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Véhicule</th>
                                <th>Prix</th>
                                <th>Statut</th>
                                <th>Date d'ajout</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="recent-vehicles-table">
                            <!-- Contenu généré dynamiquement -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des statuts
const ctx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Disponible', 'Réservé', 'Vendu'],
        datasets: [{
            data: [
                <?= $statistics['disponible'] ?? 0 ?>,
                <?= $statistics['reserve'] ?? 0 ?>,
                <?= $statistics['vendu'] ?? 0 ?>
            ],
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#dc3545'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Fonction pour actualiser les statistiques
function refreshStats() {
    fetch('<?= site_url('vehicles/statistics') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour les statistiques
                updateDashboard(data.data);
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'actualisation:', error);
        });
}

function updateDashboard(stats) {
    // Mettre à jour les compteurs
    document.getElementById('total-value').textContent = 
        new Intl.NumberFormat('fr-FR').format(stats.total_value) + ' €';
    document.getElementById('average-price').textContent = 
        new Intl.NumberFormat('fr-FR').format(stats.average_price) + ' €';
    
    // Mettre à jour le graphique
    statusChart.data.datasets[0].data = [
        stats.disponible,
        stats.reserve,
        stats.vendu
    ];
    statusChart.update();
}