<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrat de Location - ZAKS CAR</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #1a237e 0%, #3f51b5 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .main-content {
            display: flex;
            min-height: 600px;
        }

        .form-section {
            flex: 1;
            padding: 30px;
            background: #f8f9fa;
            border-right: 1px solid #e9ecef;
            overflow-y: auto;
            max-height: 80vh;
        }

        .preview-section {
            flex: 1;
            padding: 20px;
            background: white;
            overflow-y: auto;
            max-height: 80vh;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3f51b5;
            box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.1);
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .section-title {
            background: linear-gradient(135deg, #3f51b5, #1a237e);
            color: white;
            padding: 15px 20px;
            margin: 30px -30px 20px -30px;
            font-size: 1.2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .preview-section .section-title {
            margin: 20px -20px 20px -20px;
        }

        .download-btn {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            margin: 20px 0;
            width: 100%;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .download-btn:active {
            transform: translateY(0);
        }

        /* PDF Preview Styles */
        #pdf-preview {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            transform: scale(0.7);
            transform-origin: top left;
            width: 142.85%;
            margin-bottom: -200px;
        }

        #pdf-preview .header {
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
            background: white;
            color: black;
        }

        #pdf-preview .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #1a237e;
            float: left;
            width: 60%;
        }

        #pdf-preview .company-info {
            font-size: 9px;
            clear: left;
            width: 60%;
            margin-top: 5px;
            color: black;
        }

        #pdf-preview .logo-section {
            float: right;
            width: 35%;
            text-align: center;
            border: 2px solid #1a237e;
            padding: 8px;
            margin-top: 10px;
            background: white;
        }

        #pdf-preview .clear {
            clear: both;
        }

        #pdf-preview .contract-title {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
        }

        #pdf-preview .contract-title h2 {
            font-size: 18px;
            font-weight: bold;
            color: #1a237e;
            margin: 0;
        }

        #pdf-preview .contract-number {
            font-size: 14px;
            color: #d32f2f;
            font-weight: bold;
            margin-top: 5px;
        }

        #pdf-preview .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        #pdf-preview .info-table td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        #pdf-preview .label {
            background-color: #e8e8e8;
            font-weight: bold;
            width: 25%;
            font-size: 10px;
        }

        #pdf-preview .value {
            background-color: #f9f9f9;
            font-size: 11px;
        }

        #pdf-preview .vehicle-section {
            margin: 20px 0;
        }

        #pdf-preview .vehicle-left {
            float: left;
            width: 55%;
            margin-right: 2%;
        }

        #pdf-preview .vehicle-right {
            float: right;
            width: 43%;
        }

        #pdf-preview .vehicle-table {
            width: 100%;
            border-collapse: collapse;
        }

        #pdf-preview .vehicle-table td,
        #pdf-preview .vehicle-table th {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            font-size: 10px;
        }

        #pdf-preview .v-label {
            background-color: #e8e8e8;
            font-weight: bold;
        }

        #pdf-preview .v-value {
            background-color: #f9f9f9;
        }

        #pdf-preview .condition-section {
            clear: both;
            margin: 20px 0;
            border: 2px solid #000;
            padding: 10px;
        }

        #pdf-preview .condition-left {
            float: left;
            width: 60%;
        }

        #pdf-preview .condition-right {
            float: right;
            width: 35%;
            text-align: center;
        }

        #pdf-preview .circle {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid #000;
            border-radius: 50%;
            margin-right: 8px;
        }

        #pdf-preview .car-box {
            width: 80px;
            height: 50px;
            border: 2px solid #000;
            margin: 0 auto 10px;
            background-color: #f9f9f9;
        }

        #pdf-preview .signature-section {
            clear: both;
            margin-top: 30px;
        }

        #pdf-preview .signature-left {
            float: left;
            width: 48%;
            text-align: center;
            border: 1px solid #000;
            padding: 30px 10px 10px;
            margin-right: 4%;
        }

        #pdf-preview .signature-right {
            float: right;
            width: 48%;
            text-align: center;
            border: 1px solid #000;
            padding: 30px 10px 10px;
        }

        #pdf-preview .footer {
            clear: both;
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            padding: 8px;
            background-color: #fff3cd;
            border: 1px solid #ffc107;
        }

        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
            }
            
            .form-section,
            .preview-section {
                max-height: none;
            }
            
            #pdf-preview {
                transform: scale(0.5);
                width: 200%;
                margin-bottom: -300px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ZAKS CAR</h1>
            <p>Générateur de Contrat de Location</p>
        </div>

        <div class="main-content">
            <div class="form-section">
                <div class="section-title">Informations du Contrat</div>
                
                <div class="form-group">
                    <label for="numero_contrat">Numéro de Contrat</label>
                    <input type="text" id="numero_contrat" value="000007" oninput="updatePreview()">
                </div>

                <div class="section-title">Informations Client</div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="client_prenom">Prénom</label>
                        <input type="text" id="client_prenom" oninput="updatePreview()">
                    </div>
                    <div class="form-group">
                        <label for="client_nom">Nom</label>
                        <input type="text" id="client_nom" oninput="updatePreview()">
                    </div>
                </div>

                <div class="form-group">
                    <label for="client_nationalite">Nationalité</label>
                    <input type="text" id="client_nationalite" oninput="updatePreview()">
                </div>

                <div class="form-group">
                    <label for="client_adresse_maroc">Adresse au Maroc</label>
                    <input type="text" id="client_adresse_maroc" oninput="updatePreview()">
                </div>

                <div class="form-group">
                    <label for="client_adresse_etranger">Adresse à l'étranger</label>
                    <input type="text" id="client_adresse_etranger" oninput="updatePreview()">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="document_numero">Passeport/CIN N°</label>
                        <input type="text" id="document_numero" oninput="updatePreview()">
                    </div>
                    <div class="form-group">
                        <label for="document_date">Délivré le</label>
                        <input type="date" id="document_date" oninput="updatePreview()">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="permis_numero">Permis de Conduire N°</label>
                        <input type="text" id="permis_numero" oninput="updatePreview()">
                    </div>
                    <div class="form-group">
                        <label for="permis_date">Délivré le</label>
                        <input type="date" id="permis_date" oninput="updatePreview()">
                    </div>
                </div>

                <div class="form-group">
                    <label for="deuxieme_conducteur">2ème Conducteur</label>
                    <input type="text" id="deuxieme_conducteur" oninput="updatePreview()">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="deuxieme_permis_numero">Permis 2ème Conducteur</label>
                        <input type="text" id="deuxieme_permis_numero" oninput="updatePreview()">
                    </div>
                    <div class="form-group">
                        <label for="deuxieme_permis_date">Délivré le</label>
                        <input type="date" id="deuxieme_permis_date" oninput="updatePreview()">
                    </div>
                </div>

                <div class="section-title">Informations Véhicule</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="voiture_marque">Marque</label>
                        <input type="text" id="voiture_marque" oninput="updatePreview()">
                    </div>
                    <div class="form-group">
                        <label for="voiture_immatriculation">Immatriculation</label>
                        <input type="text" id="voiture_immatriculation" oninput="updatePreview()">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_depart">Date de Départ</label>
                        <input type="datetime-local" id="date_depart" oninput="updatePreview()">
                    </div>
                    <div class="form-group">
                        <label for="date_retour">Date de Retour</label>
                        <input type="datetime-local" id="date_retour" oninput="updatePreview()">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="km_depart">Kilométrage Départ</label>
                        <input type="number" id="km_depart" oninput="updatePreview()">
                    </div>
                    <div class="form-group">
                        <label for="km_retour">Kilométrage Retour</label>
                        <input type="number" id="km_retour" oninput="updatePreview()">
                    </div>
                </div>

                <div class="section-title">Tarification</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre_jours">Nombre de Jours</label>
                        <input type="number" id="nombre_jours" value="1" oninput="updatePreview()">
                    </div>
                    <div class="form-group">
                        <label for="prix_jour">Prix par Jour (DH)</label>
                        <input type="number" id="prix_jour" value="200" oninput="updatePreview()">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="frais_autres">Autres Frais (DH)</label>
                        <input type="number" id="frais_autres" value="0" oninput="updatePreview()">
                    </div>
                    <div class="form-group">
                        <label for="total_final">Total Final (DH)</label>
                        <input type="number" id="total_final" value="200" oninput="updatePreview()">
                    </div>
                </div>

                <button class="download-btn" onclick="downloadPDF()">
                    📄 Télécharger le Contrat PDF
                </button>
            </div>

            <div class="preview-section">
                <div class="section-title">Aperçu du Contrat</div>
                
                <div id="pdf-preview">
                    <!-- Le contenu sera généré par JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR');
        }

        function formatTime(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        }

        function formatNumber(num) {
            if (!num) return '';
            return new Intl.NumberFormat('fr-FR').format(num);
        }

        function updatePreview() {
            const numeroContrat = document.getElementById('numero_contrat').value;
            const clientPrenom = document.getElementById('client_prenom').value;
            const clientNom = document.getElementById('client_nom').value;
            const clientNationalite = document.getElementById('client_nationalite').value;
            const clientAdresseMaroc = document.getElementById('client_adresse_maroc').value;
            const clientAdresseEtranger = document.getElementById('client_adresse_etranger').value;
            const documentNumero = document.getElementById('document_numero').value;
            const documentDate = document.getElementById('document_date').value;
            const permisNumero = document.getElementById('permis_numero').value;
            const permisDate = document.getElementById('permis_date').value;
            const deuxiemeConducteur = document.getElementById('deuxieme_conducteur').value;
            const deuxiemePermisNumero = document.getElementById('deuxieme_permis_numero').value;
            const deuxiemePermisDate = document.getElementById('deuxieme_permis_date').value;
            const voitureMarque = document.getElementById('voiture_marque').value;
            const voitureImmatriculation = document.getElementById('voiture_immatriculation').value;
            const dateDepart = document.getElementById('date_depart').value;
            const dateRetour = document.getElementById('date_retour').value;
            const kmDepart = document.getElementById('km_depart').value;
            const kmRetour = document.getElementById('km_retour').value;
            const nombreJours = document.getElementById('nombre_jours').value;
            const prixJour = document.getElementById('prix_jour').value;
            const fraisAutres = document.getElementById('frais_autres').value;
            const totalFinal = document.getElementById('total_final').value;

            // Calcul automatique du total
            const total = (parseInt(nombreJours) || 0) * (parseInt(prixJour) || 0) + (parseInt(fraisAutres) || 0);
            document.getElementById('total_final').value = total;

            // Calcul des KM parcourus
            const kmParcourus = (parseInt(kmRetour) || 0) - (parseInt(kmDepart) || 0);

            const previewHTML = `
                <div class="header">
                    <div class="company-name">ZAKS CAR</div>
                    <div class="company-info">
                        56, Avenue de France et 37 Avenue Ibn Sina - Agdal<br>
                        GSM : 06 61 11 03 60 / 06 61 54 19 86<br>
                        E-mail : zakscar@gmail.com<br>
                        Site web : www.zaks-car.com
                    </div>
                    <div class="logo-section">
                        <div style="padding: 20px; font-size: 12px; color: #1a237e;">LOGO</div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="contract-title">
                    <h2>CONTRAT DE LOCATION</h2>
                    <div class="contract-number">N° ${numeroContrat}</div>
                </div>

                <table class="info-table">
                    <tr>
                        <td class="label">Nom et Prénom</td>
                        <td class="value" colspan="3">${clientPrenom} ${clientNom}</td>
                    </tr>
                    <tr>
                        <td class="label">Nationalité</td>
                        <td class="value" style="width: 35%;">${clientNationalite}</td>
                        <td class="value" colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="label">Adresse au Maroc</td>
                        <td class="value" colspan="3">${clientAdresseMaroc}</td>
                    </tr>
                    <tr>
                        <td class="label">Adresse à l'étranger</td>
                        <td class="value" colspan="3">${clientAdresseEtranger}</td>
                    </tr>
                    <tr>
                        <td class="label">Passeport - CIN N°</td>
                        <td class="value" style="width: 35%;">${documentNumero}</td>
                        <td class="label" style="width: 15%;">Délivré le</td>
                        <td class="value" style="width: 25%;">${formatDate(documentDate)}</td>
                    </tr>
                    <tr>
                        <td class="label">Permis de Conduire N°</td>
                        <td class="value" style="width: 35%;">${permisNumero}</td>
                        <td class="label" style="width: 15%;">Délivré le</td>
                        <td class="value" style="width: 25%;">${formatDate(permisDate)}</td>
                    </tr>
                    <tr>
                        <td class="label">2ème Conducteur</td>
                        <td class="value" colspan="3">${deuxiemeConducteur}</td>
                    </tr>
                    <tr>
                        <td class="label">Permis de Conduire N°</td>
                        <td class="value" style="width: 35%;">${deuxiemePermisNumero}</td>
                        <td class="label" style="width: 15%;">Délivré le</td>
                        <td class="value" style="width: 25%;">${formatDate(deuxiemePermisDate)}</td>
                    </tr>
                </table>

                <div class="vehicle-section">
                    <div class="vehicle-left">
                        <table class="vehicle-table">
                            <tr>
                                <td class="v-label" style="width: 30%;">MARQUE</td>
                                <td class="v-value" style="width: 30%; font-weight: bold;">${voitureMarque}</td>
                                <td class="v-label" style="width: 40%;">MATRICULE</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="v-value" style="font-size: 16px; font-weight: bold; padding: 8px;">${voitureImmatriculation}</td>
                            </tr>
                            <tr>
                                <td class="v-label">Kilométrage</td>
                                <td class="v-label">DEPART</td>
                                <td class="v-label">RETOUR</td>
                            </tr>
                            <tr>
                                <td class="v-label">Date</td>
                                <td class="v-value">${formatDate(dateDepart)}</td>
                                <td class="v-value">${formatDate(dateRetour)}</td>
                            </tr>
                            <tr>
                                <td class="v-label">Heure</td>
                                <td class="v-value">${formatTime(dateDepart)}</td>
                                <td class="v-value">${formatTime(dateRetour)}</td>
                            </tr>
                            <tr>
                                <td class="v-label">Kilométrage</td>
                                <td class="v-value">${formatNumber(kmDepart)}</td>
                                <td class="v-value">${formatNumber(kmRetour)}</td>
                            </tr>
                            <tr>
                                <td class="v-label" rowspan="2" style="font-size: 8px;">Niveau carburant</td>
                                <td class="v-value" style="font-size: 8px;">PLEIN</td>
                                <td class="v-value" style="font-size: 8px;">PLEIN</td>
                            </tr>
                            <tr>
                                <td class="v-value" style="font-size: 8px;">VIDE</td>
                                <td class="v-value" style="font-size: 8px;">VIDE</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="vehicle-right">
                        <table class="vehicle-table">
                            <tr>
                                <th colspan="2" class="v-label" style="font-weight: bold; padding: 6px;">DECOMPTE LOCATION</th>
                            </tr>
                            <tr>
                                <td class="v-label">Nombre de Jour</td>
                                <td class="v-value">${nombreJours}</td>
                            </tr>
                            <tr>
                                <td class="v-label">KM</td>
                                <td class="v-value">${kmParcourus > 0 ? formatNumber(kmParcourus) : ''}</td>
                            </tr>
                            <tr>
                                <td class="v-label">Autres :</td>
                                <td class="v-value">${fraisAutres > 0 ? formatNumber(fraisAutres) + ' DH' : ''}</td>
                            </tr>
                            <tr>
                                <td class="v-label">Prix de jour en dhs</td>
                                <td class="v-value" style="font-weight: bold;">${formatNumber(prixJour)} DH</td>
                            </tr>
                            <tr style="background-color: #f0f0f0;">
                                <td class="v-label" style="font-weight: bold;">Total général en dhs</td>
                                <td class="v-value" style="font-weight: bold; font-size: 12px;">${formatNumber(total)} DH</td>
                            </tr>
                        </table>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="condition-section">
                    <div class="condition-left">
                        <div style="margin-bottom: 8px; font-size: 10px;">
                            <span class="circle"></span><strong>Rayure Coup</strong>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="circle"></span><strong>Rayure Profonde</strong>
                        </div>
                        <div style="font-size: 9px; margin-top: 10px;">
                            <strong>CONDITION ACCEPTEE</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>LOCATAIRE</strong>
                        </div>
                    </div>
                    <div class="condition-right">
                        <div class="car-box"></div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="signature-section">
                    <div class="signature-left">
                        <div style="font-weight: bold; font-size: 12px;">Signature du Client</div>
                    </div>
                    <div class="signature-right">
                        <div style="font-weight: bold; font-size: 12px;">Lu et approuvé</div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="footer">
                    ATTENTION : CONSERVER CE DOCUMENT QUI DOIT ÊTRE PRÉSENTÉ À TOUT CONTRÔLE DE POLICE
                </div>
            `;

            document.getElementById('pdf-preview').innerHTML = previewHTML;
        }

        async function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const element = document.getElementById('pdf-preview');
            
            try {
                const canvas = await html2canvas(element, {
                    scale: 2,
                    useCORS: true,
                    backgroundColor: '#ffffff'
                });
                
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF('p', 'mm', 'a4');
                
                const imgWidth = 210;
                const pageHeight = 295;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                let heightLeft = imgHeight;
                
                let position = 0;
                
                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
                
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }
                
                const numeroContrat = document.getElementById('numero_contrat').value || 'contrat';
                const clientNom = document.getElementById('client_nom').value || 'client';
                const fileName = `Contrat_${numeroContrat}_${clientNom}.pdf`;
                
                pdf.save(fileName);
                
            } catch (error) {
                console.error('Erreur lors de la génération du PDF:', error);
                alert('Erreur lors de la génération du PDF. Veuillez réessayer.');
            }
        }

        // Initialiser l'aperçu au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
            
            // Mise à jour automatique du total quand les valeurs changent
            document.getElementById('nombre_jours').addEventListener('input', calculateTotal);
            document.getElementById('prix_jour').addEventListener('input', calculateTotal);
            document.getElementById('frais_autres').addEventListener('input', calculateTotal);
        });

        function calculateTotal() {
            const nombreJours = parseInt(document.getElementById('nombre_jours').value) || 0;
            const prixJour = parseInt(document.getElementById('prix_jour').value) || 0;
            const fraisAutres = parseInt(document.getElementById('frais_autres').value) || 0;
            
            const total = (nombreJours * prixJour) + fraisAutres;
            document.getElementById('total_final').value = total;
            
            updatePreview();
        }

        // Fonctions utilitaires pour remplir automatiquement certains champs
        function setDateToday() {
            const today = new Date();
            const dateString = today.toISOString().slice(0, 16);
            document.getElementById('date_depart').value = dateString;
            updatePreview();
        }

        function setReturnDate() {
            const departDate = new Date(document.getElementById('date_depart').value);
            const nombreJours = parseInt(document.getElementById('nombre_jours').value) || 1;
            
            if (departDate) {
                const returnDate = new Date(departDate);
                returnDate.setDate(returnDate.getDate() + nombreJours);
                const dateString = returnDate.toISOString().slice(0, 16);
                document.getElementById('date_retour').value = dateString;
                updatePreview();
            }
        }

        // Mise à jour automatique de la date de retour quand on change le nombre de jours
        document.getElementById('nombre_jours').addEventListener('input', function() {
            setReturnDate();
            calculateTotal();
        });

        document.getElementById('date_depart').addEventListener('input', function() {
            setReturnDate();
        });