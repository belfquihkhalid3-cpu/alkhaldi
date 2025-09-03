<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contrat de Location N° <?php echo $contrat->numero_contrat; ?></title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        .header-table, .main-table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: top; }
        .main-table th, .main-table td { border: 1px solid #000; padding: 5px; }
        .main-table th { background-color: #f2f2f2; text-align: left; }
        .section-title { font-weight: bold; font-size: 14px; text-decoration: underline; text-align: center; margin-bottom: 15px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .no-border { border: none; }
        .bordered { border: 1px solid #000; padding: 10px; margin-bottom: 15px; }
        .signature-section { margin-top: 40px; }
        .footer { position: fixed; bottom: 20px; width: 100%; text-align: center; font-style: italic; font-size: 9px; }
    </style>
</head>
<body>
    <div class="container">
        <table class="header-table">
            <tr>
                <td style="width: 70%;">
                    <strong>EL KHALDI CAR</strong><br>
                    Votre Adresse, Ville<br>
                    GSM: Votre Numéro<br>
                    E-mail: votre.email@example.com
                </td>
                <td style="width: 30%;" class="text-right">
                    <div class="bordered">
                        <strong>CONTRAT DE LOCATION</strong><br>
                        N°: <strong><?php echo $contrat->numero_contrat; ?></strong>
                    </div>
                </td>
            </tr>
        </table>

        <br><br>

        <table class="main-table">
            <tr>
                <th colspan="2">Informations du Locataire (Client)</th>
                <th colspan="2">Informations du Véhicule</th>
            </tr>
            <tr>
                <td><strong>Nom & Prénom:</strong></td>
                <td><?php echo $contrat->client_nom; ?></td>
                <td><strong>Marque:</strong></td>
                <td><?php echo $contrat->marque; ?></td>
            </tr>
            <tr>
                <td><strong>Nationalité:</strong></td>
                <td><?php echo $contrat->nationalite; ?></td>
                <td><strong>Modèle:</strong></td>
                <td><?php echo $contrat->modele; ?></td>
            </tr>
             <tr>
                <td><strong>CIN / Passeport:</strong></td>
                <td><?php echo $contrat->cin_passeport; ?></td>
                <td rowspan="2"><strong>Matricule:</strong></td>
                <td rowspan="2" style="font-weight: bold; font-size: 14px; text-align:center; vertical-align: middle;"><?php echo $contrat->matricule; ?></td>
            </tr>
            <tr>
                <td><strong>Permis N°:</strong></td>
                <td><?php echo $contrat->permis_numero; ?> (Délivré le: <?php echo format_to_date($contrat->permis_delivre_le, false); ?>)</td>
            </tr>
             <tr>
                <td><strong>Adresse:</strong></td>
                <td colspan="3"><?php echo $contrat->adresse; ?></td>
            </tr>
             <tr>
                <td><strong>Téléphone:</strong></td>
                <td colspan="3"><?php echo $contrat->telephone; ?></td>
            </tr>
        </table>

        <br>

        <table class="main-table">
            <tr>
                <th></th>
                <th class="text-center">Départ</th>
                <th class="text-center">Retour Prévu</th>
                <th class="text-center">Retour Effectif</th>
            </tr>
            <tr>
                <td><strong>Date et Heure</strong></td>
                <td class="text-center"><?php echo format_to_datetime($contrat->date_depart); ?></td>
                <td class="text-center"><?php echo format_to_datetime($contrat->date_retour); ?></td>
                <td class="text-center"><?php echo $contrat->date_retour_effective ? format_to_datetime($contrat->date_retour_effective) : '---'; ?></td>
            </tr>
             <tr>
                <td><strong>Kilométrage</strong></td>
                <td class="text-center"><?php echo $contrat->km_depart; ?> km</td>
                <td class="text-center">---</td>
                <td class="text-center"><?php echo $contrat->km_retour ? $contrat->km_retour . ' km' : '---'; ?></td>
            </tr>
             <tr>
                <td><strong>Niveau Carburant</strong></td>
                <td class="text-center"><?php echo $contrat->carburant_depart; ?></td>
                <td class="text-center">---</td>
                <td class="text-center"><?php echo $contrat->carburant_retour ? $contrat->carburant_retour : '---'; ?></td>
            </tr>
        </table>

        <br>

        <table class="main-table">
            <tr>
                <th colspan="2">Décompte de la Location</th>
            </tr>
            <tr>
                <td style="width: 70%;">Location <?php echo $contrat->nombre_jours; ?> Jour(s) x <?php echo number_format($contrat->prix_jour, 2); ?> DH/Jour</td>
                <td style="width: 30%;" class="text-right"><?php echo number_format($contrat->nombre_jours * $contrat->prix_jour, 2); ?> DH</td>
            </tr>
            <tr>
                <td class="text-right"><strong>TOTAL GÉNÉRAL</strong></td>
                <td class="text-right" style="font-weight: bold; font-size: 14px;"><?php echo number_format($contrat->total_general, 2); ?> DH</td>
            </tr>
        </table>
        
        <br>

        <div class="bordered">
            <strong>Notes sur l'état du véhicule (Départ):</strong>
            <p><?php echo nl2br($contrat->notes_etat_vehicule); ?></p>
        </div>
        
        <div class="signature-section">
            <table class="main-table no-border">
                <tr>
                    <td class="no-border text-center">
                        <strong>Signature du Gérant</strong><br><br><br><br>
                        ...................................
                    </td>
                    <td class="no-border text-center">
                        <strong>Signature du Client</strong><br>
                        (Lu et approuvé)<br><br><br>
                        ...................................
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            ATTENTION: CONSERVER CE DOCUMENT QUI DOIT ÊTRE PRÉSENTÉ À TOUT CONTRÔLE DE POLICE
        </div>

    </div>
</body>
</html>