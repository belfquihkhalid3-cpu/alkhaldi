<!DOCTYPE html>
<html>
<head>
    <title>Contrat de Location - <?php echo $contrat_info->numero_contrat; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 25px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        td, th {
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        h1, h2, h3 {
            margin: 0;
            padding: 0;
            font-weight: normal;
        }

        /* --- En-tête --- */
        .header-table .company-details h2 {
            color: #1d4ed8; /* Bleu foncé et moderne */
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header-table .company-details p {
            font-size: 10px;
            line-height: 1.3;
            color: #555;
        }
        .header-table .contract-title {
            text-align: right;
        }
        .header-table .contract-title h1 {
            font-size: 22px;
            color: #333;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-table .contract-title h2 {
            font-size: 14px;
            color: #ffffff;
            background-color: #1d4ed8;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
        }

        /* --- Sections --- */
        .section-title {
            background-color: #eff6ff; /* Bleu très clair */
            color: #1d4ed8;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            font-size: 14px;
            border-left: 4px solid #1d4ed8;
        }
        .section-table {
            border: 1px solid #dee2e6;
        }
        .section-table td {
            border-bottom: 1px solid #dee2e6;
        }
        .section-table tr:last-child td {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #555;
            width: 25%;
        }

        /* --- Section Financière --- */
        .total-row .label, .total-row .value {
            font-weight: bold;
            font-size: 14px;
            background-color: #f8f9fa;
        }
        .total-row .value {
            color: #1d4ed8;
            text-align: right;
        }
        
        /* --- Signature et Notes --- */
        .notes-signature-table td {
            border: none;
            vertical-align: bottom;
            padding-right: 15px;
        }
        .signature-box {
            margin-top: 10px;
            border-top: 1px solid #555;
            padding-top: 8px;
            font-size: 10px;
            color: #777;
        }
        .notes-box {
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
            padding: 10px;
            height: 90px;
            border-radius: 4px;
        }

        /* --- Pied de page --- */
        .footer {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            height: 40px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="company-details">
                <h2>LANS CAR</h2>
                <p>
                    56, Avenue de France et 37 Avenue Ibn Sina - Agdal<br>
                    GSM: 06 61 11 03 60 / 06 61 54 19 86 | E-mail: zakscar@gmail.com
                </p>
            </td>
            <td class="contract-title">
                <h1>Contrat de Location</h1>
                <h2>N°: <?php echo $contrat_info->numero_contrat; ?></h2>
            </td>
        </tr>
    </table>

    <div class="section-title">Informations du Locataire</div>
    <table class="section-table">
        <tr>
            <td class="label">Nom et Prénom</td>
            <td><?php echo $contrat_info->client_prenom . " " . $contrat_info->client_nom; ?></td>
            <td class="label">Nationalité</td>
            <td><?php echo $contrat_info->client_nationalite; ?></td>
        </tr>
        <tr>
            <td class="label">Adresse au Maroc</td>
            <td colspan="3"><?php echo $contrat_info->client_adresse_maroc; ?></td>
        </tr>
        <tr>
            <td class="label">Permis de Conduire N°</td>
            <td><?php echo $contrat_info->client_permis_numero; ?></td>
            <td class="label">Délivré le</td>
            <td><?php echo format_to_date($contrat_info->client_permis_delivre_le, false); ?></td>
        </tr>
        <tr>
            <td class="label">Passeport / CIN N°</td>
            <td><?php echo $contrat_info->client_cin_numero ?: $contrat_info->client_passeport_numero; ?></td>
            <td class="label">Délivré le</td>
            <td><?php echo format_to_date($contrat_info->client_cin_delivre_le ?: $contrat_info->client_passeport_delivre_le, false); ?></td>
        </tr>
    </table>

    <?php if ($contrat_info->deuxieme_conducteur_nom) { ?>
    <div class="section-title">2ème Conducteur</div>
    <table class="section-table">
        <tr>
            <td class="label">Nom et Prénom</td>
            <td colspan="3"><?php echo $contrat_info->deuxieme_conducteur_nom; ?></td>
        </tr>
        <tr>
            <td class="label">Permis de Conduire N°</td>
            <td><?php echo $contrat_info->deuxieme_conducteur_permis_numero; ?></td>
            <td class="label">Délivré le</td>
            <td><?php echo format_to_date($contrat_info->deuxieme_conducteur_permis_delivre_le, false); ?></td>
        </tr>
    </table>
    <?php } ?>

    <div class="section-title">Informations du Véhicule</div>
    <table class="section-table">
        <tr>
            <td class="label">Marque / Modèle</td>
            <td><?php echo $contrat_info->voiture_marque . " / " . $contrat_info->voiture_modele; ?></td>
            <td class="label">Immatriculation</td>
            <td><?php echo $contrat_info->voiture_immatriculation; ?></td>
        </tr>
        <tr>
            <td class="label">Kilométrage Départ</td>
            <td><?php echo $contrat_info->km_depart; ?> Km</td>
            <td class="label">Niveau Carburant</td>
            <td><?php echo $contrat_info->carburant_depart; ?></td>
        </tr>
        <tr>
            <td class="label">Date et Heure Départ</td>
            <td><?php echo $contrat_info->date_depart; ?></td>
            <td class="label">Lieu de Départ</td>
            <td><?php echo $contrat_info->lieu_depart; ?></td>
        </tr>
        <tr>
            <td class="label">Date et Heure Retour</td>
            <td><?php echo $contrat_info->date_retour_prevue; ?></td>
            <td class="label">Lieu de Retour</td>
            <td><?php echo $contrat_info->lieu_retour; ?></td>
        </tr>
    </table>

    <div class="section-title">Décompte de la Location</div>
    <table class="section-table">
        <tr>
            <td class="label">Prix par jour</td>
            <td><?php echo to_currency($contrat_info->prix_jour); ?></td>
            <td class="label">Nombre de jours</td>
            <td><?php echo $contrat_info->nombre_jours; ?></td>
        </tr>
        <tr>
            <td class="label">Avance</td>
            <td><?php echo to_currency($contrat_info->avance); ?></td>
            <td class="label">Caution</td>
            <td><?php echo to_currency($contrat_info->caution); ?></td>
        </tr>
        <tr class="total-row">
            <td class="label" colspan="3">TOTAL À PAYER</td>
            <td class="value"><?php echo to_currency($contrat_info->total_final); ?></td>
        </tr>
    </table>

    <table class="notes-signature-table">
        <tr>
            <td width="50%">
                <div class="label">État du véhicule / Notes:</div>
                <div class="notes-box"><?php echo nl2br($contrat_info->notes_etat_vehicule_depart); ?></div>
            </td>
            <td width="50%">
                <div class="label">Signature du Locataire:</div>
                <div class="signature-box">
                    (précédée de la mention "Lu et approuvé")
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        ATTENTION: CONSERVER CE DOCUMENT QUI DOIT ÊTRE PRÉSENTÉ À TOUT CONTRÔLE DE POLICE
    </div>

</body>
</html>
