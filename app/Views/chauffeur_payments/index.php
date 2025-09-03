<div id="page-content" class="page-wrapper clearfix">
    
    <!-- STATISTIQUES EN HAUT -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card dashboard-icon-widget">
                <div class="card-body">
                    <div class="widget-icon bg-primary">
                        <i data-feather="credit-card" class="icon-24"></i>
                    </div>
                    <div class="widget-details">
                        <h1 id="total-paiements" class="text-primary">
                            <span class="counter" data-value="0">0</span>
                        </h1>
                        <span class="bg-transparent-white">Total Paiements ce Mois</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card dashboard-icon-widget">
                <div class="card-body">
                    <div class="widget-icon bg-success">
                        <i data-feather="trending-up" class="icon-24"></i>
                    </div>
                    <div class="widget-details">
                        <h1 id="total-avances" class="text-success">
                            <span class="counter" data-value="0">0</span>
                        </h1>
                        <span class="bg-transparent-white">Avances en Cours</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card dashboard-icon-widget">
                <div class="card-body">
                    <div class="widget-icon bg-warning">
                        <i data-feather="clock" class="icon-24"></i>
                    </div>
                    <div class="widget-details">
                        <h1 id="paiements-attente" class="text-warning">
                            <span class="counter" data-value="0">0</span>
                        </h1>
                        <span class="bg-transparent-white">Paiements en Attente</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card dashboard-icon-widget">
                <div class="card-body">
                    <div class="widget-icon bg-info">
                        <i data-feather="users" class="icon-24"></i>
                    </div>
                    <div class="widget-details">
                        <h1 id="chauffeurs-avances" class="text-info">
                            <span class="counter" data-value="0">0</span>
                        </h1>
                        <span class="bg-transparent-white">Chauffeurs avec Avances</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GRAPHIQUES ET RÉSUMÉS -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i data-feather="bar-chart-2" class="icon-16"></i>
                        Évolution des Paiements (6 derniers mois)
                    </h4>
                </div>
                <div class="card-body">
                    <canvas id="payments-chart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i data-feather="pie-chart" class="icon-16"></i>
                        Répartition par Type
                    </h4>
                </div>
                <div class="card-body">
                    <canvas id="types-chart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- TOP CHAUFFEURS AVEC AVANCES -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i data-feather="arrow-up-circle" class="icon-16 text-warning"></i>
                        Top Chauffeurs - Soldes Avances
                    </h4>
                </div>
                <div class="card-body">
                    <div id="top-avances-list">
                        <div class="text-center p-3">
                            <i data-feather="loader" class="icon-24 text-muted"></i>
                            <p class="text-muted">Chargement...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i data-feather="activity" class="icon-16 text-success"></i>
                        Activité Récente
                    </h4>
                </div>
                <div class="card-body">
                    <div id="recent-activity">
                        <div class="text-center p-3">
                            <i data-feather="loader" class="icon-24 text-muted"></i>
                            <p class="text-muted">Chargement...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTRES RAPIDES -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-0">
                        <i data-feather="filter" class="icon-16"></i>
                        Filtres Rapides
                    </h5>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary btn-sm filter-btn active" data-filter="all">
                            Tous
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm filter-btn" data-filter="avance">
                            Avances
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm filter-btn" data-filter="salaire">
                            Salaires
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm filter-btn" data-filter="en_attente">
                            En Attente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE PRINCIPALE -->
    <div class="card">
        <div class="page-title clearfix">
            <h1>
                <i data-feather="credit-card" class="icon-24"></i>
                Paiements des Chauffeurs
            </h1>
            <div class="title-button-group">
                <a href="<?php echo get_uri('chauffeur_payments/solde_avances'); ?>" class="btn btn-info btn-sm">
                    <i data-feather="eye" class="icon-16"></i> Consulter Soldes Avances
                </a>
                <?php
                echo modal_anchor(get_uri("chauffeur_payments/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> Ajouter un paiement", ["class" => "btn btn-primary", "title" => "Ajouter un paiement"]);
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="payments-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th> 
                        <th>Chauffeur</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Mois Concerné</th>
                        <th>Date de Paiement</th>
                        <th>Statut</th>
                        <th class="text-center w100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- STYLES ADDITIONNELS -->
<style>
.dashboard-icon-widget {
    margin-bottom: 20px;
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.dashboard-icon-widget:hover {
    transform: translateY(-5px);
}

.widget-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    float: left;
    margin-right: 15px;
}

.widget-details {
    overflow: hidden;
    padding-top: 5px;
}

.widget-details h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.widget-details span {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
}

.counter {
    display: inline-block;
}

.avance-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.avance-item:last-child {
    border-bottom: none;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
}

.filter-btn.active {
    background-color: var(--bs-primary);
    color: white;
    border-color: var(--bs-primary);
}
</style>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    // Variables globales
    let paymentsTable;
    let paymentsChart;
    let typesChart;

    $(document).ready(function() {
        // Initialiser la table
        initializeTable();
        
        // Charger les statistiques
        loadStatistics();
        
        // Charger les graphiques
        loadCharts();
        
        // Charger les données supplémentaires
        loadTopAvances();
        loadRecentActivity();
        
        // Initialiser les icônes Feather
        feather.replace();
        
        // Gestionnaires d'événements
        setupEventHandlers();
    });

    function initializeTable() {
        paymentsTable = $("#payments-table").appTable({
            source: '<?php echo get_uri("chauffeur_payments/list_data") ?>',
            order: [[0, "desc"]],
            columns: [
                {title: 'ID', "visible": false},
                {title: 'Chauffeur'},
                {title: 'Type'},
                {title: 'Montant', "class": "text-end"},
                {title: 'Mois Concerné'},
                {title: 'Date de Paiement'},
                {title: 'Statut', "class": "text-center"},
                {title: 'Actions', "class": "text-center option w100"}
            ],
            printColumns: [1, 2, 3, 4, 5, 6],
            createdRow: function(row, data, index) {
                $(row).attr('data-id', data[0]);
                
                // Ajouter des classes pour les filtres
                if (data[2].includes('Avance')) {
                    $(row).addClass('filter-avance');
                }
                if (data[2].includes('Salaire')) {
                    $(row).addClass('filter-salaire');
                }
                if (data[6].includes('en_attente')) {
                    $(row).addClass('filter-en_attente');
                }
            }
        });
    }

    function loadStatistics() {
        $.ajax({
            url: '<?php echo get_uri("chauffeur_payments/get_statistics") ?>',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Animer les compteurs
                    animateCounter('#total-paiements .counter', response.data.total_paiements_mois);
                    animateCounter('#total-avances .counter', response.data.total_avances, 'currency');
                    animateCounter('#paiements-attente .counter', response.data.paiements_attente);
                    animateCounter('#chauffeurs-avances .counter', response.data.chauffeurs_avec_avances);
                }
            },
            error: function() {
                console.log('Erreur lors du chargement des statistiques');
            }
        });
    }

    function loadCharts() {
        // Graphique des paiements par mois
        $.ajax({
            url: '<?php echo get_uri("chauffeur_payments/get_payments_chart_data") ?>',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    createPaymentsChart(response.data);
                }
            }
        });

        // Graphique des types
        $.ajax({
            url: '<?php echo get_uri("chauffeur_payments/get_types_chart_data") ?>',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    createTypesChart(response.data);
                }
            }
        });
    }

    function createPaymentsChart(data) {
        const ctx = document.getElementById('payments-chart').getContext('2d');
        paymentsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Montant Total',
                    data: data.amounts,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Nombre de Paiements',
                    data: data.counts,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    }

    function createTypesChart(data) {
        const ctx = document.getElementById('types-chart').getContext('2d');
        typesChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: [
                        '#007bff',
                        '#28a745',
                        '#ffc107',
                        '#17a2b8',
                        '#dc3545'
                    ]
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
    }

    function loadTopAvances() {
        $.ajax({
            url: '<?php echo get_uri("chauffeur_payments/get_top_avances") ?>',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let html = '';
                    if (response.data.length > 0) {
                        response.data.forEach(function(item) {
                            html += `
                                <div class="avance-item">
                                    <div>
                                        <strong>${item.chauffeur_name}</strong>
                                        <br><small class="text-muted">${item.nb_avances} avance(s)</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-warning">${item.solde}</span>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html = '<div class="text-center text-muted p-3">Aucune avance en cours</div>';
                    }
                    $('#top-avances-list').html(html);
                }
            }
        });
    }

    function loadRecentActivity() {
        $.ajax({
            url: '<?php echo get_uri("chauffeur_payments/get_recent_activity") ?>',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let html = '';
                    response.data.forEach(function(item) {
                        let iconClass = 'bg-primary';
                        let icon = 'credit-card';
                        
                        if (item.type_paiement === 'avance') {
                            iconClass = 'bg-warning';
                            icon = 'arrow-up-circle';
                        } else if (item.type_paiement === 'salaire') {
                            iconClass = 'bg-success';
                            icon = 'dollar-sign';
                        }
                        
                        html += `
                            <div class="activity-item">
                                <div class="activity-icon ${iconClass}">
                                    <i data-feather="${icon}" class="icon-16 text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong>${item.chauffeur_name}</strong>
                                    <br><small class="text-muted">${item.type_paiement} - ${item.montant}</small>
                                    <br><small class="text-muted">${item.date_relative}</small>
                                </div>
                            </div>
                        `;
                    });
                    $('#recent-activity').html(html);
                    feather.replace();
                }
            }
        });
    }

    function setupEventHandlers() {
        // Filtres rapides
        $('.filter-btn').click(function() {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            
            const filter = $(this).data('filter');
            
            if (filter === 'all') {
                paymentsTable.DataTable().columns(2).search('').draw();
            } else {
                paymentsTable.DataTable().columns(2).search(filter).draw();
            }
        });
    }

    function animateCounter(selector, targetValue, format = 'number') {
        const element = $(selector);
        const startValue = 0;
        const duration = 2000;
        const startTime = Date.now();
        
        function updateCounter() {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const currentValue = Math.floor(startValue + (targetValue - startValue) * progress);
            
            if (format === 'currency') {
                element.text(new Intl.NumberFormat('fr-FR', {
                    style: 'currency',
                    currency: 'MAD'
                }).format(currentValue));
            } else {
                element.text(currentValue.toLocaleString());
            }
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            }
        }
        
        updateCounter();
    }

    // Rafraîchir les données toutes les 5 minutes
    setInterval(function() {
        loadStatistics();
        loadTopAvances();
        loadRecentActivity();
    }, 300000);
</script>