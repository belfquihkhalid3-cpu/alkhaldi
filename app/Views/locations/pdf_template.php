<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Location #<?= $location_info->id ?></title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-info {
            text-align: right;
            color: #666;
        }
        
        .document-title {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin: 20px 0;
        }
        
        .location-info {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 20px 0;
        }
        
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 30%;
            color: #495057;
        }
        
        .info-value {
            display: table-cell;
            width: 70%;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-en-attente { background: #fff3cd; color: #856404; }
        .status-confirmee { background: #d4edda; color: #155724; }
        .status-en-cours { background: #cce7ff; color: #004085; }
        .status-terminee { background: #e2e3e5; color: #383d41; }
        .status-annulee { background: #f8d7da; color: #721c24; }
        
        .section {
            margin: 25px 0;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        
        .trajet-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .trajet-point {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        
        .trajet-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
            text-align: center;
            line-height: 20px;
            color: white;
            font-weight: bold;
        }
        
        .depart { background: #28a745; }
        .arrivee { background: #dc3545; }
        
        .assignment-grid {
            display: table;
            width: 100%;
        }
        
        .assignment-item {
            display: table-cell;
            width: 50%;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }
        
        .assignment-item:first-child {
            border-right: none;
        }
        
        .price-box {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .price-amount {
            font-size: 24px;
            font-weight: bold;
            color: #155724;
        }
        
        .observations {
            background: #fff9c4;
            padding: 15px;
            border-left: 4px solid #ffc107;
            font-style: italic;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-weight-bold { font-weight: bold; }
        .text-muted { color: #6c757d; }
        .mb-0 { margin-bottom: 0; }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <div class="company-info">
            <h1 style="margin: 0; color: #007bff;">EL KHALDI CAR</h1>
            <p class="mb-0">Service de transport VIP</p>
            <p class="mb-0">Tél: +212 XXX XXX XXX</p>
            <p class="mb-0">Email: contact@elkhaldicar.ma</p>
        </div>
    </div>

    <!-- Titre du document -->
    <div class="document-title text-center">
        FICHE DE LOCATION #<?= $location_info->id ?>
    </div>

    <!-- Informations principales -->
    <div class="location-info">
        <div class="info-row">
            <div class="info-label">Titre :</div>
            <div class="info-value font-weight-bold"><?= $location_info->titre ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Type de service :</div>
            <div class="info-value"><?= ucfirst(str_replace('_', ' ', $location_info->type_service)) ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Statut :</div>
            <div class="info-value">
                <span class="status-badge status-<?= $location_info->statut ?>">
                    <?= ucfirst(str_replace('_', ' ', $location_info->statut)) ?>
                </span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de création :</div>
            <div class="info-value"><?= date('d/m/Y à H:i', strtotime($location_info->created_at)) ?></div>
        </div>
    </div>

    <!-- Informations client -->
    <div class="section">
        <h2 class="section-title">INFORMATIONS CLIENT</h2>
        <div class="info-row">
            <div class="info-label">Nom/Société :</div>
            <div class="info-value font-weight-bold"><?= $location_info->company_name ?: 'Client #' . $location_info->client_id ?></div>
        </div>
        <?php if ($location_info->phone): ?>
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value"><?= $location_info->phone ?></div>
        </div>
        <?php endif; ?>
        <?php if ($location_info->address): ?>
        <div class="info-row">
            <div class="info-label">Adresse :</div>
            <div class="info-value"><?= $location_info->address ?></div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Planning et trajet -->
    <div class="section">
        <h2 class="section-title">PLANNING ET TRAJET</h2>
        
        <div class="info-row">
            <div class="info-label">Date et heure de début :</div>
            <div class="info-value font-weight-bold"><?= date('d/m/Y à H:i', strtotime($location_info->date_debut)) ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Date et heure de fin :</div>
            <div class="info-value font-weight-bold"><?= date('d/m/Y à H:i', strtotime($location_info->date_fin)) ?></div>
        </div>
        
        <?php if (isset($location_info->duree)): ?>
        <div class="info-row">
            <div class="info-label">Durée totale :</div>
            <div class="info-value">
                <?= $location_info->duree->days ?> jour(s), 
                <?= $location_info->duree->h ?> heure(s), 
                <?= $location_info->duree->i ?> minute(s)
            </div>
        </div>
        <?php endif; ?>

        <div class="trajet-box">
            <div class="trajet-point">
                <div class="trajet-icon depart">D</div>
                <div>
                    <strong>Lieu de départ :</strong><br>
                    <?= $location_info->lieu_depart ?>
                </div>
            </div>
            <div class="trajet-point">
                <div class="trajet-icon arrivee">A</div>
                <div>
                    <strong>Lieu d'arrivée :</strong><br>
                    <?= $location_info->lieu_arrivee ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignations -->
    <div class="section">
        <h2 class="section-title">ASSIGNATIONS</h2>
        
        <div class="assignment-grid">
            <div class="assignment-item">
                <h3 style="margin-top: 0; color: #495057;">🚗 Véhicule assigné</h3>
                <?php if ($location_info->vehicle_id): ?>
                    <p><strong>Matricule :</strong> <?= $location_info->numero_matricule ?></p>
                    <p><strong>Modèle :</strong> <?= $location_info->marque . ' ' . $location_info->modele ?></p>
                <?php else: ?>
                    <p class="text-muted">Aucun véhicule assigné</p>
                <?php endif; ?>
            </div>
            
            <div class="assignment-item">
                <h3 style="margin-top: 0; color: #495057;">👨‍✈️ Chauffeur assigné</h3>
                <?php if ($location_info->chauffeur_id): ?>
                    <p><strong>Nom :</strong> <?= $location_info->chauffeur_prenom . ' ' . $location_info->chauffeur_nom ?></p>
                    <?php if ($location_info->chauffeur_telephone): ?>
                    <p><strong>Téléphone :</strong> <?= $location_info->chauffeur_telephone ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-muted">Aucun chauffeur assigné</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Prix -->
    <?php if ($location_info->prix_total): ?>
    <div class="section">
        <h2 class="section-title">TARIFICATION</h2>
        <div class="price-box">
            <div>Prix total de la location</div>
            <div class="price-amount"><?= number_format($location_info->prix_total, 2, ',', ' ') ?> DH</div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Description et observations -->
    <?php if ($location_info->description): ?>
    <div class="section">
        <h2 class="section-title">DESCRIPTION</h2>
        <p><?= nl2br($location_info->description) ?></p>
    </div>
    <?php endif; ?>

    <?php if ($location_info->observations): ?>
    <div class="section">
        <h2 class="section-title">OBSERVATIONS</h2>
        <div class="observations">
            <?= nl2br($location_info->observations) ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Pied de page -->
    <div class="footer">
        <p>Document généré le <?= date('d/m/Y à H:i:s') ?> | EL KHALDI CAR - Système de gestion de flotte</p>
        <p>Ce document est confidentiel et destiné uniquement aux parties concernées</p>
    </div>
</body>
</html>