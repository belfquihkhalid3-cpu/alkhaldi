
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Location de Voitures</title>
    
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    
    <!-- Feather Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
    
    <style>
        /* ===============================================
           RESET ET BASE
        =============================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #ffffff;
            color: #1a202c;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ===============================================
           LAYOUT PRINCIPAL
        =============================================== */
        .dashboard-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 24px;
            background: #ffffff;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 32px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="60" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
            pointer-events: none;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .header-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .btn-header {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-header:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            color: white;
        }

        /* ===============================================
           SECTION STATISTIQUES
        =============================================== */
        .stats-section {
            margin-bottom: 32px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: 16px 16px 0 0;
            background: var(--accent-gradient);
        }

        .revenue-card::before { --accent-gradient: linear-gradient(90deg, #10b981, #059669); }
        .locations-card::before { --accent-gradient: linear-gradient(90deg, #3b82f6, #2563eb); }
        .vehicles-card::before { --accent-gradient: linear-gradient(90deg, #f59e0b, #d97706); }
        .drivers-card::before { --accent-gradient: linear-gradient(90deg, #8b5cf6, #7c3aed); }
        .maintenance-card::before { --accent-gradient: linear-gradient(90deg, #ef4444, #dc2626); }
        .fuel-card::before { --accent-gradient: linear-gradient(90deg, #06b6d4, #0891b2); }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            background: var(--icon-gradient);
            box-shadow: 0 4px 12px var(--icon-shadow);
        }

        .revenue-card .stat-icon { --icon-gradient: linear-gradient(135deg, #10b981, #059669); --icon-shadow: rgba(16, 185, 129, 0.3); }
        .locations-card .stat-icon { --icon-gradient: linear-gradient(135deg, #3b82f6, #2563eb); --icon-shadow: rgba(59, 130, 246, 0.3); }
        .vehicles-card .stat-icon { --icon-gradient: linear-gradient(135deg, #f59e0b, #d97706); --icon-shadow: rgba(245, 158, 11, 0.3); }
        .drivers-card .stat-icon { --icon-gradient: linear-gradient(135deg, #8b5cf6, #7c3aed); --icon-shadow: rgba(139, 92, 246, 0.3); }
        .maintenance-card .stat-icon { --icon-gradient: linear-gradient(135deg, #ef4444, #dc2626); --icon-shadow: rgba(239, 68, 68, 0.3); }
        .fuel-card .stat-icon { --icon-gradient: linear-gradient(135deg, #06b6d4, #0891b2); --icon-shadow: rgba(6, 182, 212, 0.3); }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a202c;
            line-height: 1.2;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 12px;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
            width: fit-content;
        }

        .trend-positive {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .trend-negative {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .trend-neutral {
            background: rgba(100, 116, 139, 0.1);
            color: #475569;
        }

        /* ===============================================
           SECTION GRAPHIQUES
        =============================================== */
        .charts-section {
            margin-bottom: 32px;
        }

        .charts-row {
            display: grid;
            gap: 20px;
            margin-bottom: 20px;
        }

        .charts-row-2 {
            grid-template-columns: 2fr 1fr;
        }

        .charts-row-3 {
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        }

        .chart-container {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 0;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .chart-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .chart-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #f1f5f9;
            background: linear-gradient(135deg, #f8fafc, #ffffff);
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chart-body {
            padding: 20px 24px;
            height: 300px;
            position: relative;
        }

        .chart-body.large {
            height: 400px;
        }

        .chart-body.small {
            height: 250px;
        }

        /* ===============================================
           SECTION TABLEAUX
        =============================================== */
        .tables-section {
            margin-bottom: 32px;
        }

        .tables-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .table-container {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            padding: 20px 24px;
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }

        .modern-table th {
            background: #f8fafc;
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
        }

        .modern-table td {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            color: #374151;
            font-size: 14px;
        }

        .modern-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background: #f8fafc;
        }

        /* ===============================================
           ÉLÉMENTS DE TABLEAU
        =============================================== */
        .item-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .item-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            color: white;
        }

        .vehicle-avatar {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .driver-avatar {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .client-avatar {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
        }

        .item-details {
            flex: 1;
            min-width: 0;
        }

        .item-name {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 2px;
        }

        .item-meta {
            font-size: 12px;
            color: #64748b;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-disponible { background: #dcfce7; color: #166534; }
        .status-en-service { background: #dbeafe; color: #1d4ed8; }
        .status-maintenance { background: #fef3c7; color: #92400e; }
        .status-actif { background: #dcfce7; color: #166534; }
        .status-inactif { background: #fee2e2; color: #991b1b; }
        .status-confirmee { background: #dcfce7; color: #166534; }
        .status-en-cours { background: #dbeafe; color: #1d4ed8; }
        .status-terminee { background: #ede9fe; color: #6b21a8; }

        .metric-value {
            font-weight: 700;
            color: #1a202c;
        }

        .revenue-amount {
            font-weight: 700;
            color: #059669;
        }

        .rating-stars {
            display: flex;
            gap: 2px;
        }

        .star {
            color: #fbbf24;
            font-size: 14px;
        }

        .star.empty {
            color: #e5e7eb;
        }

        /* ===============================================
           BOUTONS ET ACTIONS
        =============================================== */
        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid #e5e7eb;
            color: #6b7280;
        }

        .btn-outline:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .action-buttons {
            display: flex;
            gap: 6px;
        }

        /* ===============================================
           ICÔNES
        =============================================== */
        .icon-16 { width: 16px; height: 16px; }
        .icon-20 { width: 20px; height: 20px; }
        .icon-24 { width: 24px; height: 24px; }

        /* ===============================================
           RESPONSIVE
        =============================================== */
        @media (max-width: 1200px) {
            .charts-row-2 {
                grid-template-columns: 1fr;
            }
            
            .tables-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 16px;
            }
            
            .dashboard-header {
                padding: 24px 20px;
                text-align: center;
            }
            
            .header-content {
                flex-direction: column;
                gap: 16px;
            }
            
            .header-title {
                font-size: 1.5rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .stat-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .stat-value {
                font-size: 2rem;
            }
            
            .charts-row-3 {
                grid-template-columns: 1fr;
            }
            
            .table-body {
                overflow-x: auto;
            }
        }

        /* ===============================================
           ANIMATIONS
        =============================================== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.6s ease forwards;
        }

        .slide-in {
            animation: slideIn 0.8s ease forwards;
        }

        /* ===============================================
           SCROLL PERSONNALISÉ
        =============================================== */
        .table-body::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .table-body::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .table-body::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .table-body::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- En-tête du Dashboard -->
        <div class="dashboard-header fade-in">
            <div class="header-content">
                <div>
                    <h1 class="header-title">
                        <i data-feather="bar-chart-2"></i>
                        Dashboard Location de Voitures
                    </h1>
                    <p class="header-subtitle">Tableau de bord avec statistiques en temps réel</p>
                </div>
                <div class="header-actions">
                    <button class="btn-header" onclick="refreshDashboard()">
                        <i data-feather="refresh-cw"></i>
                        Actualiser
                    </button>
                    <button class="btn-header" onclick="exportReport()">
                        <i data-feather="download"></i>
                        Exporter
                    </button>
                </div>
            </div>
        </div>

        <!-- Section Statistiques -->
        <div class="stats-section">
            <div class="stats-grid">
                <!-- Revenus -->
                <div class="stat-card revenue-card slide-in">
                    <div class="stat-header">
                        <div class="stat-content">
                            <div class="stat-value" id="revenueValue">125,350</div>
                            <div class="stat-label">Revenus Mensuels (MAD)</div>
                            <div class="stat-trend trend-positive">
                                <i data-feather="trending-up"></i>
                                <span>+12.5%</span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i data-feather="dollar-sign"></i>
                        </div>
                    </div>
                </div>

                <!-- Locations -->
                <div class="stat-card locations-card slide-in">
                    <div class="stat-header">
                        <div class="stat-content">
                            <div class="stat-value" id="locationsValue">147</div>
                            <div class="stat-label">Locations Actives</div>
                            <div class="stat-trend trend-positive">
                                <i data-feather="arrow-up"></i>
                                <span>+8.3%</span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i data-feather="map-pin"></i>
                        </div>
                    </div>
                </div>

                <!-- Véhicules -->
                <div class="stat-card vehicles-card slide-in">
                    <div class="stat-header">
                        <div class="stat-content">
                            <div class="stat-value" id="vehiclesValue">28</div>
                            <div class="stat-label">Véhicules Disponibles</div>
                            <div class="stat-trend trend-neutral">
                                <i data-feather="truck"></i>
                                <span>Taux: 78%</span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i data-feather="truck"></i>
                        </div>
                    </div>
                </div>

                <!-- Chauffeurs -->
                <div class="stat-card drivers-card slide-in">
                    <div class="stat-header">
                        <div class="stat-content">
                            <div class="stat-value" id="driversValue">15</div>
                            <div class="stat-label">Chauffeurs Actifs</div>
                            <div class="stat-trend trend-positive">
                                <i data-feather="users"></i>
                                <span>Disponibles</span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i data-feather="users"></i>
                        </div>
                    </div>
                </div>

                <!-- Maintenance -->
                <div class="stat-card maintenance-card slide-in">
                    <div class="stat-header">
                        <div class="stat-content">
                            <div class="stat-value" id="maintenanceValue">5</div>
                            <div class="stat-label">Maintenances Dues</div>
                            <div class="stat-trend trend-negative">
                                <i data-feather="alert-triangle"></i>
                                <span>À planifier</span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i data-feather="tool"></i>
                        </div>
                    </div>
                </div>

                <!-- Carburant -->
                <div class="stat-card fuel-card slide-in">
                    <div class="stat-header">
                        <div class="stat-content">
                            <div class="stat-value" id="fuelValue">9,240</div>
                            <div class="stat-label">Coûts Carburant (MAD)</div>
                            <div class="stat-trend trend-positive">
                                <i data-feather="trending-down"></i>
                                <span>-3.2%</span>
                            </div>
                        </div>
                        <div class="stat-icon">
                            <i data-feather="zap"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Graphiques -->
        <div class="charts-section">
            <!-- Première ligne -->
            <div class="charts-row charts-row-2">
                <div class="chart-container fade-in">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i data-feather="trending-up"></i>
                            Évolution des Revenus
                        </h3>
                    </div>
                    <div class="chart-body large">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <div class="chart-container fade-in">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i data-feather="pie-chart"></i>
                            Types de Services
                        </h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="servicesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Deuxième ligne -->
            <div class="charts-row charts-row-3">
                <div class="chart-container fade-in">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i data-feather="bar-chart-2"></i>
                            Top Véhicules
                        </h3>
                    </div>
                    <div class="chart-body small">
                        <canvas id="vehiclesChart"></canvas>
                    </div>
                </div>

                <div class="chart-container fade-in">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i data-feather="calendar"></i>
                            Occupation Hebdo
                        </h3>
                    </div>
                    <div class="chart-body small">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>

                <div class="chart-container fade-in">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i data-feather="clock"></i>
                            Tendances Horaires
                        </h3>
                    </div>
                    <div class="chart-body small">
                        <canvas id="hourlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Tableaux -->
        <div class="tables-section">
            <!-- Première ligne de tableaux -->
            <div class="tables-row">
                <!-- Tableau Véhicules -->
                <div class="table-container fade-in">
                    <div class="table-header">
                        <h3 class="table-title">
                            <i data-feather="truck"></i>
                            Performance Véhicules
                        </h3>
                        <button class="btn btn-outline btn-sm">
                            <i data-feather="download"></i>
                            Export
                        </button>
                    </div>
                    <div class="table-body">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Véhicule</th>
                                    <th>Status</th>
                                    <th>Locations</th>
                                    <th>Revenus</th>
                                </tr>
                            </thead>
                            <tbody id="vehiclesTableBody">
                                <tr>
                                    <td>
                                        <div class="item-info">
                                            <div class="item-avatar vehicle-avatar">MC</div>
                                            <div class="item-details">
                                                <div class="item-name">Mercedes C200</div>
                                                <div class="item-meta">54321-C-89</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="status-badge status-maintenance">Maintenance</span></td>
                                    <td><span class="metric-value">18</span></td>
                                    <td><span class="revenue-amount">38,900 MAD</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="item-info">
                                            <div class="item-avatar vehicle-avatar">DL</div>
                                            <div class="item-details">
                                                <div class="item-name">Dacia Logan</div>
                                                <div class="item-meta">98765-D-12</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="status-badge status-disponible">Disponible</span></td>
                                    <td><span class="metric-value">15</span></td>
                                    <td><span class="revenue-amount">22,840 MAD</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tableau Chauffeurs -->
                <div class="table-container fade-in">
                    <div class="table-header">
                        <h3 class="table-title">
                            <i data-feather="users"></i>
                            Performance Chauffeurs
                        </h3>
                        <button class="btn btn-outline btn-sm">
                            <i data-feather="download"></i>
                            Export
                        </button>
                    </div>
                    <div class="table-body">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Chauffeur</th>
                                    <th>Status</th>
                                    <th>Courses</th>
                                    <th>Évaluation</th>
                                </tr>
                            </thead>
                            <tbody id="driversTableBody">
                                <tr>
                                    <td>
                                        <div class="item-info">
                                            <div class="item-avatar driver-avatar">AB</div>
                                            <div class="item-details">
                                                <div class="item-name">Ahmed Bennani</div>
                                                <div class="item-meta">06 12 34 56 78</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="status-badge status-actif">Actif</span></td>
                                    <td><span class="metric-value">42</span></td>
                                    <td>
                                        <div class="rating-stars">
                                            <span class="star">★</span>
                                            <span class="star">★</span>
                                            <span class="star">★</span>
                                            <span class="star">★</span>
                                            <span class="star">★</span>
                                            <span style="margin-left: 6px; font-weight: 600;">4.8</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="item-info">
                                            <div class="item-avatar driver-avatar">YA</div>
                                            <div class="item-details">
                                                <div class="item-name">Youssef Alami</div>
                                                <div class="item-meta">06 23 45 67 89</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="status-badge status-actif">Actif</span></td>
                                    <td><span class="metric-value">38</span></td>
                                    <td>
                                        <div class="rating-stars">
                                            <span class="star">★</span>
                                            <span class="star">★</span>
                                            <span class="star">★</span>
                                            <span class="star">★</span>
                                            <span class="star">★</span>
                                            <span style="margin-left: 6px; font-weight: 600;">4.9</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="item-info">
                                            <div class="item-avatar driver-avatar">MT</div>
                                            <div class="item-details">
                                                <div class="item-name">Mohamed Tazi</div>
                                                <div class="item-meta">06 34 56 78 90</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="status-badge status-inactif">Congé</span></td>
                                    <td><span class="metric-value">29</span></td>
                                    <td>
                                        <div class="rating-stars">
                                            <span class="star">★</span>
                                            <span class="star">★</span>
                                            <span class="star">★</span>
                                            <span class="star">★</span>
                                            <span class="star empty">★</span>
                                            <span style="margin-left: 6px; font-weight: 600;">4.2</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tableau des Locations Récentes - Pleine largeur -->
            <div class="table-container fade-in">
                <div class="table-header">
                    <h3 class="table-title">
                        <i data-feather="clock"></i>
                        Locations Récentes
                    </h3>
                    <div style="display: flex; gap: 8px;">
                        <button class="btn btn-outline btn-sm" onclick="filterLocations('today')">Aujourd'hui</button>
                        <button class="btn btn-outline btn-sm" onclick="filterLocations('week')">Cette semaine</button>
                        <button class="btn btn-primary btn-sm">
                            <i data-feather="plus"></i>
                            Nouvelle Location
                        </button>
                    </div>
                </div>
                <div class="table-body">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Véhicule</th>
                                <th>Chauffeur</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Montant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="locationsTableBody">
                            <tr>
                                <td><strong>#1247</strong></td>
                                <td>
                                    <div class="item-info">
                                        <div class="item-avatar client-avatar">HA</div>
                                        <div class="item-details">
                                            <div class="item-name">Hotel Atlas</div>
                                            <div class="item-meta">Client Premium</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="item-info">
                                        <div class="item-avatar vehicle-avatar">BS</div>
                                        <div class="item-details">
                                            <div class="item-name">BMW Série 3</div>
                                            <div class="item-meta">67890-B-33</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="item-info">
                                        <div class="item-avatar driver-avatar">AB</div>
                                        <div class="item-details">
                                            <div class="item-name">Ahmed Bennani</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div style="font-weight: 600;">03/09/2024</div>
                                        <div style="font-size: 12px; color: #64748b;">14:30</div>
                                    </div>
                                </td>
                                <td><span style="background: #e0f2fe; color: #006064; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">Transfert</span></td>
                                <td><span class="status-badge status-confirmee">Confirmée</span></td>
                                <td><span class="revenue-amount">450 MAD</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-outline btn-sm" title="Voir">
                                            <i data-feather="eye"></i>
                                        </button>
                                        <button class="btn btn-outline btn-sm" title="Modifier">
                                            <i data-feather="edit-2"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>#1246</strong></td>
                                <td>
                                    <div class="item-info">
                                        <div class="item-avatar client-avatar">SS</div>
                                        <div class="item-details">
                                            <div class="item-name">Société SAHAM</div>
                                            <div class="item-meta">Client Corporate</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="item-info">
                                        <div class="item-avatar vehicle-avatar">MC</div>
                                        <div class="item-details">
                                            <div class="item-name">Mercedes C200</div>
                                            <div class="item-meta">54321-C-89</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="item-info">
                                        <div class="item-avatar driver-avatar">YA</div>
                                        <div class="item-details">
                                            <div class="item-name">Youssef Alami</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div style="font-weight: 600;">02/09/2024</div>
                                        <div style="font-size: 12px; color: #64748b;">09:00</div>
                                    </div>
                                </td>
                                <td><span style="background: #f3e5f5; color: #6b21a8; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">Location Journée</span></td>
                                <td><span class="status-badge status-en-cours">En Cours</span></td>
                                <td><span class="revenue-amount">800 MAD</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-outline btn-sm" title="Voir">
                                            <i data-feather="eye"></i>
                                        </button>
                                        <button class="btn btn-outline btn-sm" title="Modifier">
                                            <i data-feather="edit-2"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>#1245</strong></td>
                                <td>
                                    <div class="item-info">
                                        <div class="item-avatar client-avatar">MA</div>
                                        <div class="item-details">
                                            <div class="item-name">M. Alaoui</div>
                                            <div class="item-meta">Client Particulier</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="item-info">
                                        <div class="item-avatar vehicle-avatar">TC</div>
                                        <div class="item-details">
                                            <div class="item-name">Toyota Corolla</div>
                                            <div class="item-meta">12345-A-67</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="item-info">
                                        <div class="item-avatar driver-avatar">MT</div>
                                        <div class="item-details">
                                            <div class="item-name">Mohamed Tazi</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div style="font-weight: 600;">01/09/2024</div>
                                        <div style="font-size: 12px; color: #64748b;">16:15</div>
                                    </div>
                                </td>
                                <td><span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">Événement</span></td>
                                <td><span class="status-badge status-terminee">Terminée</span></td>
                                <td><span class="revenue-amount">650 MAD</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-outline btn-sm" title="Voir">
                                            <i data-feather="eye"></i>
                                        </button>
                                        <button class="btn btn-outline btn-sm" title="Modifier">
                                            <i data-feather="edit-2"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===============================================
        // INITIALISATION ET CONFIGURATION
        // ===============================================
        document.addEventListener('DOMContentLoaded', function() {
            // Configuration des couleurs pour les graphiques
            const colors = {
                primary: '#3b82f6',
                secondary: '#8b5cf6',
                success: '#10b981',
                warning: '#f59e0b',
                danger: '#ef4444',
                info: '#06b6d4',
                purple: '#a855f7',
                cyan: '#06b6d4',
                gradient: {
                    primary: ['#667eea', '#764ba2'],
                    success: ['#10b981', '#059669'],
                    warning: ['#f59e0b', '#d97706'],
                    info: ['#06b6d4', '#0891b2']
                }
            };

            // Configuration commune pour tous les graphiques
            const chartDefaults = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                weight: 500
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: '#1a202c',
                        bodyColor: '#64748b',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        cornerRadius: 8,
                        titleFont: {
                            size: 13,
                            weight: 600
                        },
                        bodyFont: {
                            size: 12
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#64748b',
                            font: { size: 11 }
                        },
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false
                        },
                        border: { display: false }
                    },
                    y: {
                        ticks: {
                            color: '#64748b',
                            font: { size: 11 }
                        },
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false
                        },
                        border: { display: false }
                    }
                }
            };

            // ===============================================
            // GRAPHIQUES
            // ===============================================

            // 1. Graphique des revenus mensuels
            const revenueChart = new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: ['Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre'],
                    datasets: [{
                        label: 'Revenus (MAD)',
                        data: [95000, 105000, 98000, 112000, 108000, 125350],
                        borderColor: colors.primary,
                        backgroundColor: colors.primary + '20',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: colors.primary,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    ...chartDefaults,
                    scales: {
                        ...chartDefaults.scales,
                        y: {
                            ...chartDefaults.scales.y,
                            beginAtZero: false,
                            ticks: {
                                ...chartDefaults.scales.y.ticks,
                                callback: function(value) {
                                    return new Intl.NumberFormat('fr-FR', {
                                        notation: 'compact'
                                    }).format(value) + ' MAD';
                                }
                            }
                        }
                    }
                }
            });

            // 2. Graphique des types de services
            const servicesChart = new Chart(document.getElementById('servicesChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Transfert', 'Location Journée', 'Événement', 'Location Longue'],
                    datasets: [{
                        data: [45, 30, 15, 10],
                        backgroundColor: [
                            colors.primary,
                            colors.success,
                            colors.warning,
                            colors.purple
                        ],
                        borderWidth: 0,
                        cutout: '65%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            ...chartDefaults.plugins.tooltip,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${percentage}%`;
                                }
                            }
                        }
                    }
                }
            });

            // 3. Graphique des véhicules (Barres horizontales)
            const vehiclesChart = new Chart(document.getElementById('vehiclesChart'), {
                type: 'bar',
                data: {
                    labels: ['BMW S3', 'Toyota C.', 'Mercedes', 'Dacia L.', 'Renault C.'],
                    datasets: [{
                        label: 'Revenus',
                        data: [62150, 45230, 38900, 22840, 18520],
                        backgroundColor: colors.info + '80',
                        borderColor: colors.info,
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    ...chartDefaults,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            ...chartDefaults.scales.x,
                            beginAtZero: true,
                            ticks: {
                                ...chartDefaults.scales.x.ticks,
                                callback: function(value) {
                                    return new Intl.NumberFormat('fr-FR', {
                                        notation: 'compact'
                                    }).format(value);
                                }
                            }
                        },
                        y: {
                            ...chartDefaults.scales.y,
                            grid: { display: false }
                        }
                    }
                }
            });

            // 4. Graphique occupation hebdomadaire (Radar)
            const weeklyChart = new Chart(document.getElementById('weeklyChart'), {
                type: 'radar',
                data: {
                    labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                    datasets: [{
                        label: 'Locations',
                        data: [65, 59, 90, 81, 56, 75, 40],
                        fill: true,
                        backgroundColor: colors.success + '20',
                        borderColor: colors.success,
                        borderWidth: 2,
                        pointBackgroundColor: colors.success,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            beginAtZero: true,
                            angleLines: { color: '#f1f5f9' },
                            grid: { color: '#f1f5f9' },
                            pointLabels: {
                                color: '#64748b',
                                font: { size: 11 }
                            },
                            ticks: {
                                color: '#64748b',
                                backdropColor: 'transparent',
                                font: { size: 10 }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: { 
                                color: '#64748b',
                                font: { size: 12 }
                            }
                        },
                        tooltip: chartDefaults.plugins.tooltip
                    }
                }
            });

            // 5. Graphique tendances horaires
            const hourlyChart = new Chart(document.getElementById('hourlyChart'), {
                type: 'line',
                data: {
                    labels: ['6h', '8h', '10h', '12h', '14h', '16h', '18h', '20h', '22h'],
                    datasets: [{
                        label: 'Demandes',
                        data: [5, 15, 25, 45, 35, 55, 65, 45, 20],
                        backgroundColor: colors.cyan + '30',
                        borderColor: colors.cyan,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    ...chartDefaults,
                    scales: {
                        ...chartDefaults.scales,
                        y: { 
                            ...chartDefaults.scales.y,
                            beginAtZero: true
                        }
                    }
                }
            });

            // ===============================================
            // ANIMATIONS
            // ===============================================
            
            // Animation des compteurs
            function animateCounter(id, target, duration = 2000, suffix = '') {
                const element = document.getElementById(id);
                if (!element) return;
                
                let current = 0;
                const increment = target / (duration / 16);
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    
                    if (suffix === 'MAD') {
                        element.textContent = Math.floor(current).toLocaleString('fr-FR');
                    } else {
                        element.textContent = Math.floor(current).toString();
                    }
                }, 16);
            }

            // Lancer les animations après un délai
            setTimeout(() => {
                animateCounter('revenueValue', 125350, 2500, 'MAD');
                animateCounter('locationsValue', 147, 1500);
                animateCounter('vehiclesValue', 28, 1200);
                animateCounter('driversValue', 15, 1000);
                animateCounter('maintenanceValue', 5, 800);
                animateCounter('fuelValue', 9240, 2000);
            }, 500);

            // Animation des éléments au scroll
            const observeElements = () => {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                });

                document.querySelectorAll('.fade-in, .slide-in').forEach((el) => {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(30px)';
                    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    observer.observe(el);
                });
            };

            observeElements();

            // ===============================================
            // FONCTIONS UTILITAIRES
            // ===============================================

            // Actualiser le dashboard
            window.refreshDashboard = function() {
                const btn = document.querySelector('[onclick="refreshDashboard()"]');
                const icon = btn.querySelector('i');
                
                icon.style.animation = 'spin 1s linear infinite';
                
                setTimeout(() => {
                    location.reload();
                }, 1000);
            };

            // Export de rapport
            window.exportReport = function() {
                alert('Fonctionnalité d\'export en cours de développement');
            };

            // Filtrage des locations
            window.filterLocations = function(filter) {
                console.log('Filtre appliqué:', filter);
                // Ici vous pourriez implémenter le filtrage réel
            };

            // Gestion du redimensionnement
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    Chart.helpers.each(Chart.instances, (instance) => {
                        instance.resize();
                    });
                }, 250);
            });

            // Initialiser les icônes Feather
            feather.replace();

            console.log('Dashboard Location de Voitures initialisé avec succès!');
        });

        // CSS pour l'animation de rotation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    </script>