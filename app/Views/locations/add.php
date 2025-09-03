
<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i data-feather="map" class="icon-20 text-primary"></i> 
                <?= isset($edit_mode) && $edit_mode ? 'Modifier' : 'Nouvelle' ?> Location
            </h1>
            <div>
                <a href="<?= site_url('locations') ?>" class="btn btn-secondary btn-sm">
                    <i data-feather="arrow-left" class="icon-16"></i> Retour à la liste
                </a>
                <a href="<?= site_url('locations/calendar') ?>" class="btn btn-info btn-sm">
                    <i data-feather="calendar" class="icon-16"></i> Planning
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Formulaire principal -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i data-feather="edit" class="icon-16"></i> Informations de la location
                        </h6>
                    </div>
                    <div class="card-body">
                        <?= form_open('locations/save', ['class' => 'needs-validation', 'novalidate' => true, 'id' => 'locationForm']) ?>
                        
                        <!-- Champ ID caché -->
                        <input type="hidden" name="id" value="<?= isset($location->id) ? $location->id : (isset($location_info->id) ? $location_info->id : '') ?>" />
                        
                        <!-- Zone des messages (sera remplie dynamiquement) -->
                        <div id="message-zone"></div>
                        
                        <!-- Messages d'erreur/succès existants -->
                        <?php if (session()->getFlashdata('error_message')): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i data-feather="alert-triangle" class="icon-16"></i>
                                <?= session()->getFlashdata('error_message') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('success_message')): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i data-feather="check-circle" class="icon-16"></i>
                                <?= session()->getFlashdata('success_message') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Messages de validation -->
                        <?php if (isset($validation) && $validation->getErrors()): ?>
                            <div class="alert alert-danger">
                                <h6><i data-feather="alert-triangle" class="icon-16"></i> Erreurs de validation :</h6>
                                <ul class="mb-0">
                                    <?php foreach ($validation->getErrors() as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <!-- Informations de base -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i data-feather="info" class="icon-16"></i> Informations Générales
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" 
                                           id="titre" name="titre" 
                                           value="<?= old('titre', $location->titre ?? $location_info->titre ?? '') ?>" 
                                           required placeholder="Ex: Transfert aéroport VIP">
                                    <div class="invalid-feedback">Le titre est obligatoire</div>
                                </div>

                                <div class="mb-3">
                                    <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                    <select class="form-select" id="client_id" name="client_id" required>
                                        <option value="">Choisir un client...</option>
                                        <?php if (isset($clients) && is_array($clients)): ?>
                                            <?php foreach ($clients as $client): ?>
                                                <option value="<?= $client->id ?>" 
                                                        <?= old('client_id', $location->client_id ?? $location_info->client_id ?? '') == $client->id ? 'selected' : '' ?>>
                                                    <?= esc($client->company_name ?: 'Client #' . $client->id) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <div class="invalid-feedback">Le client est obligatoire</div>
                                </div>

                                <div class="mb-3">
                                    <label for="type_service" class="form-label">Type de Service <span class="text-danger">*</span></label>
                                    <select class="form-select" id="type_service" name="type_service" required>
                                        <option value="">Choisir le type...</option>
                                        <option value="transfert" <?= old('type_service', $location->type_service ?? $location_info->type_service ?? '') == 'transfert' ? 'selected' : '' ?>>Transfert</option>
                                        <option value="location_journee" <?= old('type_service', $location->type_service ?? $location_info->type_service ?? '') == 'location_journee' ? 'selected' : '' ?>>Location journée</option>
                                        <option value="location_longue" <?= old('type_service', $location->type_service ?? $location_info->type_service ?? '') == 'location_longue' ? 'selected' : '' ?>>Location longue durée</option>
                                        <option value="evenement" <?= old('type_service', $location->type_service ?? $location_info->type_service ?? '') == 'evenement' ? 'selected' : '' ?>>Événement</option>
                                    </select>
                                    <div class="invalid-feedback">Le type de service est obligatoire</div>
                                </div>

                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut</label>
                                    <select class="form-select" id="statut" name="statut">
                                        <option value="en_attente" <?= old('statut', $location->statut ?? $location_info->statut ?? 'en_attente') == 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                        <option value="confirmee" <?= old('statut', $location->statut ?? $location_info->statut ?? '') == 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                                        <option value="en_cours" <?= old('statut', $location->statut ?? $location_info->statut ?? '') == 'en_cours' ? 'selected' : '' ?>>En cours</option>
                                        <option value="terminee" <?= old('statut', $location->statut ?? $location_info->statut ?? '') == 'terminee' ? 'selected' : '' ?>>Terminée</option>
                                        <option value="annulee" <?= old('statut', $location->statut ?? $location_info->statut ?? '') == 'annulee' ? 'selected' : '' ?>>Annulée</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="prix_total" class="form-label">Prix Total (DH)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="prix_total" name="prix_total" 
                                               value="<?= old('prix_total', $location->prix_total ?? $location_info->prix_total ?? '') ?>" 
                                               step="0.01" min="0" placeholder="0.00">
                                        <span class="input-group-text">DH</span>
                                    </div>
                                    <div class="form-text">Montant total de la location</div>
                                </div>
                            </div>

                            <!-- Dates et lieux -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i data-feather="clock" class="icon-16"></i> Dates & Lieux
                                </h6>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date_debut" class="form-label">Date/Heure Début <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control" 
                                                   id="date_debut" name="date_debut" 
                                                   value="<?= old('date_debut', isset($location->date_debut) ? date('Y-m-d\TH:i', strtotime($location->date_debut)) : (isset($location_info->date_debut) ? date('Y-m-d\TH:i', strtotime($location_info->date_debut)) : '')) ?>" 
                                                   required>
                                            <div class="invalid-feedback">La date de début est obligatoire</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date_fin" class="form-label">Date/Heure Fin <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control" 
                                                   id="date_fin" name="date_fin" 
                                                   value="<?= old('date_fin', isset($location->date_fin) ? date('Y-m-d\TH:i', strtotime($location->date_fin)) : (isset($location_info->date_fin) ? date('Y-m-d\TH:i', strtotime($location_info->date_fin)) : '')) ?>" 
                                                   required>
                                            <div class="invalid-feedback">La date de fin est obligatoire</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="lieu_depart" class="form-label">Lieu de Départ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" 
                                           id="lieu_depart" name="lieu_depart" 
                                           value="<?= old('lieu_depart', $location->lieu_depart ?? $location_info->lieu_depart ?? '') ?>" 
                                           required placeholder="Ex: Aéroport Mohammed V">
                                    <div class="invalid-feedback">Le lieu de départ est obligatoire</div>
                                </div>

                                <div class="mb-3">
                                    <label for="lieu_arrivee" class="form-label">Lieu d'Arrivée <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" 
                                           id="lieu_arrivee" name="lieu_arrivee" 
                                           value="<?= old('lieu_arrivee', $location->lieu_arrivee ?? $location_info->lieu_arrivee ?? '') ?>" 
                                           required placeholder="Ex: Hôtel La Mamounia">
                                    <div class="invalid-feedback">Le lieu d'arrivée est obligatoire</div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <!-- Assignation véhicule/chauffeur -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i data-feather="users" class="icon-16"></i> Assignation
                                </h6>

                                <div class="mb-3">
                                    <label for="vehicle_id" class="form-label">Véhicule</label>
                                    <select class="form-select" id="vehicle_id" name="vehicle_id">
                                        <option value="">Aucun véhicule assigné</option>
                                        <?php if (isset($vehicles) && is_array($vehicles)): ?>
                                            <?php foreach ($vehicles as $vehicle): ?>
                                                <option value="<?= $vehicle->id ?>" 
                                                        <?= old('vehicle_id', $location->vehicle_id ?? $location_info->vehicle_id ?? '') == $vehicle->id ? 'selected' : '' ?>>
                                                    <?= esc($vehicle->numero_matricule ?? $vehicle->marque . ' ' . $vehicle->modele) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <div id="vehicle-availability-info" class="form-text"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="chauffeur_id" class="form-label">Chauffeur</label>
                                    <select class="form-select" id="chauffeur_id" name="chauffeur_id">
                                        <option value="">Aucun chauffeur assigné</option>
                                        <?php if (isset($chauffeurs) && is_array($chauffeurs)): ?>
                                            <?php foreach ($chauffeurs as $chauffeur): ?>
                                                <option value="<?= $chauffeur->id ?>" 
                                                        <?= old('chauffeur_id', $location->chauffeur_id ?? $location_info->chauffeur_id ?? '') == $chauffeur->id ? 'selected' : '' ?>>
                                                    <?= esc($chauffeur->prenom . ' ' . $chauffeur->nom) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <div id="chauffeur-availability-info" class="form-text"></div>
                                </div>

                                <div class="alert alert-info">
                                    <small><i data-feather="info" class="icon-14"></i> 
                                    Les disponibilités sont vérifiées automatiquement selon les dates sélectionnées.</small>
                                </div>
                            </div>

                            <!-- Description et observations -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i data-feather="file-text" class="icon-16"></i> Détails
                                </h6>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" 
                                              placeholder="Description détaillée du service..."><?= old('description', $location->description ?? $location_info->description ?? '') ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="observations" class="form-label">Observations</label>
                                    <textarea class="form-control" id="observations" name="observations" rows="3" 
                                              placeholder="Notes internes, instructions spéciales..."><?= old('observations', $location->observations ?? $location_info->observations ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Zone des boutons - FIXÉE -->
                        <div class="form-actions-section">
                            <hr>
                            <div class="form-actions">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="<?= site_url('locations') ?>" class="btn btn-secondary">
                                        <i data-feather="x" class="icon-16"></i> Annuler
                                    </a>
                                    <div class="d-flex gap-2">
                                        <?php if (isset($edit_mode) && $edit_mode && isset($location_info->id)): ?>
                                        <a href="<?= site_url('locations/duplicate/' . $location_info->id) ?>" class="btn btn-info">
                                            <i data-feather="copy" class="icon-16"></i> Dupliquer
                                        </a>
                                        <?php endif; ?>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i data-feather="save" class="icon-16"></i> 
                                            <?= isset($edit_mode) && $edit_mode ? 'Mettre à jour' : 'Enregistrer' ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?= form_close() ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar avec aide et outils -->
            <div class="col-lg-4">
                <!-- Aide -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i data-feather="help-circle" class="icon-16"></i> Aide
                        </h6>
                    </div>
                    <div class="card-body">
                        <h6>Types de services :</h6>
                        <ul class="small">
                            <li><strong>Transfert :</strong> Point A vers point B</li>
                            <li><strong>Location journée :</strong> Véhicule + chauffeur pour la journée</li>
                            <li><strong>Location longue durée :</strong> Plusieurs jours</li>
                            <li><strong>Événement :</strong> Mariages, cérémonies</li>
                        </ul>

                        <h6 class="mt-3">Statuts :</h6>
                        <ul class="small">
                            <li><strong>En attente :</strong> Demande reçue</li>
                            <li><strong>Confirmée :</strong> Validée par le client</li>
                            <li><strong>En cours :</strong> Service en cours</li>
                            <li><strong>Terminée :</strong> Service accompli</li>
                            <li><strong>Annulée :</strong> Location annulée</li>
                        </ul>

                        <h6 class="mt-3">Conseils :</h6>
                        <ul class="small">
                            <li>Vérifiez les disponibilités avant assignation</li>
                            <li>Confirmez les lieux avec le client</li>
                            <li>Prévoyez une marge pour les imprévus</li>
                        </ul>
                    </div>
                </div>

                <!-- Informations supplémentaires en mode édition -->
                <?php if (isset($edit_mode) && $edit_mode && isset($location_info)): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i data-feather="info" class="icon-16"></i> Informations
                        </h6>
                    </div>
                    <div class="card-body">
                        <p><strong>ID :</strong> #<?= $location_info->id ?? 'N/A' ?></p>
                        <?php if (isset($location_info->created_at)): ?>
                        <p><strong>Créée le :</strong> <?= date('d/m/Y H:i', strtotime($location_info->created_at)) ?></p>
                        <?php endif; ?>
                        <?php if (isset($location_info->updated_at)): ?>
                        <p><strong>Modifiée le :</strong> <?= date('d/m/Y H:i', strtotime($location_info->updated_at)) ?></p>
                        <?php endif; ?>
                        
                        <?php if (isset($location_info->date_debut) && isset($location_info->date_fin)): ?>
                        <?php
                            $debut = new DateTime($location_info->date_debut);
                            $fin = new DateTime($location_info->date_fin);
                            $duree = $debut->diff($fin);
                        ?>
                        <div class="alert alert-success">
                            <strong>Durée :</strong><br>
                            <?= $duree->days ?> jour(s), <?= $duree->h ?> heure(s), <?= $duree->i ?> minute(s)
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Calcul prix estimé -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i data-feather="calculator" class="icon-16"></i> Calcul Prix
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label small">Tarif/heure (DH):</label>
                            <input type="number" class="form-control form-control-sm" id="calc_tarif" step="0.01" placeholder="150.00">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Distance (km):</label>
                            <input type="number" class="form-control form-control-sm" id="calc_distance" step="0.1" placeholder="25.5">
                        </div>
                        <div class="mb-2">
                            <strong>Prix estimé : <span id="calc_result">0.00</span> DH</strong>
                        </div>
                        <button type="button" class="btn btn-sm btn-success w-100" id="applyCalculatedPrice">
                            <i data-feather="check" class="icon-14"></i> Appliquer ce prix
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================= PARTIE 2: CSS STYLES ======================= -->
<style>
/* RESET ET CORRECTIONS DE BASE */
.page-content {
    min-height: 100vh !important;
    height: auto !important;
    overflow: visible !important;
    padding-bottom: 2rem !important;
}

.container-fluid {
    max-height: none !important;
    overflow: visible !important;
    padding-bottom: 3rem;
}

/* CARDS ET CONTENEURS */
.card {
    max-height: none !important;
    margin-bottom: 2rem;
}

.card-body {
    max-height: none !important;
    overflow: visible !important;
    padding: 1.5rem;
}

/* SECTION DES BOUTONS D'ACTION - CRITIQUE */
.form-actions-section {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 2px solid #e3e6f0;
}

.form-actions {
    background: #f8f9fc;
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid #e3e6f0;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* BOUTONS */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 120px;
    justify-content: center;
}

.btn-primary {
    background: linear-gradient(135deg, #4e73df, #224abe);
    border: none;
    box-shadow: 0 2px 4px rgba(78,115,223,0.3);
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(78,115,223,0.4);
}

.btn-secondary {
    background: #6c757d;
    border: none;
    color: white;
}

.btn-info {
    background: #36b9cc;
    border: none;
    color: white;
}

/* GAP POUR LES GROUPES DE BOUTONS */
.d-flex.gap-2 > * {
    margin-left: 0.5rem;
}

.d-flex.gap-2 > *:first-child {
    margin-left: 0;
}

/* FORMULAIRE */
.form-label {
    font-weight: 600;
    color: #5a5c69;
    margin-bottom: 0.5rem;
}

.form-control,
.form-select {
    border: 1px solid #d1d3e2;
    border-radius: 0.375rem;
    padding: 0.75rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

/* SECTIONS AVEC ICÔNES */
h6.text-primary {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    color: #4e73df !important;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f1f3f4;
}

/* ALERTS */
.alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    border: none;
    border-radius: 0.5rem;
    padding: 1rem 1.25rem;
}

.alert-success {
    background: linear-gradient(135deg, #1cc88a, #13855c);
    color: white;
}

.alert-danger {
    background: linear-gradient(135deg, #e74a3b, #c82333);
    color: white;
}

.alert-info {
    background: linear-gradient(135deg, #36b9cc, #258391);
    color: white;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .form-actions .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .form-actions .d-flex > div {
        width: 100%;
    }
    
    .btn {
        width: 100%;
        min-width: auto;
    }
    
    .d-flex.gap-2 {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .d-flex.gap-2 > * {
        margin-left: 0;
        width: 100%;
    }
}

/* SCROLLING ET HAUTEUR */
html, body {
    height: auto !important;
    overflow-x: hidden;
    overflow-y: auto;
}

/* SIDEBAR */
.col-lg-4 .card {
    position: sticky;
    top: 20px;
    max-height: calc(100vh - 40px);
    overflow-y: auto;
}

/* ICÔNES FEATHER */
[data-feather] {
    width: 1em;
    height: 1em;
    vertical-align: -0.125em;
}

.icon-14 {
    width: 14px;
    height: 14px;
}

.icon-16 {
    width: 16px;
    height: 16px;
}

.icon-20 {
    width: 20px;
    height: 20px;
}

/* ANIMATION LOADER */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* VALIDATION BOOTSTRAP */
.was-validated .form-control:invalid,
.was-validated .form-select:invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.was-validated .form-control:valid,
.was-validated .form-select:valid {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}
</style>
<script>
// ================== INITIALISATION AU CHARGEMENT DE LA PAGE ==================
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les icônes Feather
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Assurer la visibilité des boutons
    ensureButtonsVisibility();
    
    // Initialiser la validation des formulaires
    initFormValidation();
    
    // Initialiser les événements
    initEventListeners();
    
    // Calculateur de prix
    initPriceCalculator();
});

// ================== FONCTIONS D'INITIALISATION ==================

// Assurer que les boutons sont visibles
function ensureButtonsVisibility() {
    const formActions = document.querySelector('.form-actions-section');
    if (formActions) {
        // S'assurer que la section est visible
        formActions.style.display = 'block';
        formActions.style.visibility = 'visible';
        
        // Ajouter un observer pour surveiller la visibilité
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) {
                    // Si les boutons ne sont pas visibles, ajouter une classe pour les fixer
                    document.body.classList.add('buttons-not-visible');
                } else {
                    document.body.classList.remove('buttons-not-visible');
                }
            });
        }, {
            threshold: 0.1
        });
        
        observer.observe(formActions);
    }
}

// Initialiser la validation des formulaires Bootstrap
function initFormValidation() {
    const forms = document.getElementsByClassName('needs-validation');
    
    Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            if (form.checkValidity() === false) {
                form.classList.add('was-validated');
                showValidationErrors();
                return false;
            }
            
            // Validation personnalisée des dates
            if (!validateDates()) {
                return false;
            }
            
            // Tout est valide, soumettre le formulaire
            submitForm();
        }, false);
    });
}

// Initialiser les événements
function initEventListeners() {
    // Événements pour les dates (vérification disponibilité)
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    
    if (dateDebut) {
        dateDebut.addEventListener('change', function() {
            checkAvailability();
            calculatePrice();
        });
    }
    
    if (dateFin) {
        dateFin.addEventListener('change', function() {
            checkAvailability();
            calculatePrice();
        });
    }
    
    // Validation en temps réel
    const requiredFields = document.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', validateField);
        field.addEventListener('input', clearFieldError);
    });
}

// ================== VALIDATION ==================

// Valider un champ spécifique
function validateField(event) {
    const field = event.target;
    const isValid = field.checkValidity();
    
    if (!isValid) {
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');
    } else {
        field.classList.add('is-valid');
        field.classList.remove('is-invalid');
    }
}

// Effacer l'erreur d'un champ
function clearFieldError(event) {
    const field = event.target;
    if (field.value.trim() !== '') {
        field.classList.remove('is-invalid');
    }
}

// Validation des dates
function validateDates() {
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin').value;
    
    if (!dateDebut || !dateFin) {
        showErrorMessage('Les dates de début et fin sont obligatoires');
        return false;
    }
    
    const debut = new Date(dateDebut);
    const fin = new Date(dateFin);
    const now = new Date();
    
    if (debut >= fin) {
        showErrorMessage('La date de fin doit être après la date de début');
        document.getElementById('date_fin').focus();
        return false;
    }
    
    if (debut < now) {
        const confirm = window.confirm('La date de début est dans le passé. Voulez-vous continuer ?');
        if (!confirm) {
            document.getElementById('date_debut').focus();
            return false;
        }
    }
    
    return true;
}

// Afficher les erreurs de validation
function showValidationErrors() {
    const invalidFields = document.querySelectorAll('.form-control:invalid, .form-select:invalid');
    if (invalidFields.length > 0) {
        showErrorMessage('Veuillez corriger les champs en erreur avant de continuer');
        invalidFields[0].focus();
    }
}

// ================== SOUMISSION DU FORMULAIRE ==================

// Soumettre le formulaire via AJAX
function submitForm() {
    const form = document.getElementById('locationForm');
    const formData = new FormData(form);
    const submitBtn = document.getElementById('submitBtn');
    
    // Sauvegarder le contenu original du bouton
    const originalContent = submitBtn.innerHTML;
    
    // Afficher le state de chargement
    submitBtn.innerHTML = '<i data-feather="loader" class="icon-16 animate-spin"></i> Enregistrement...';
    submitBtn.disabled = true;
    
    // Remplacer les icônes Feather
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Envoyer la requête
    fetch('<?= site_url("locations/save") ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            handleSuccess(data);
        } else {
            handleError(data.message || 'Erreur lors de l\'enregistrement');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        handleError('Erreur de communication avec le serveur');
    })
    .finally(() => {
        // Restaurer le bouton dans tous les cas
        submitBtn.innerHTML = originalContent;
        submitBtn.disabled = false;
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
}

// Gérer le succès
function handleSuccess(data) {
    showSuccessMessage(data.message || 'Location enregistrée avec succès');
    
    // Redirection après un délai
    setTimeout(() => {
        if (data.id) {
            window.location.href = '<?= site_url("locations/view/") ?>' + data.id;
        } else {
            window.location.href = '<?= site_url("locations") ?>';
        }
    }, 1500);
}

// Gérer les erreurs
function handleError(message) {
    showErrorMessage(message);
    
    // Scroll vers le haut pour voir le message
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// ================== MESSAGES D'ALERTE ==================

// Afficher un message de succès
function showSuccessMessage(message) {
    const alertHtml = `
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i data-feather="check-circle" class="icon-16"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    insertMessage(alertHtml);
}

// Afficher un message d'erreur
function showErrorMessage(message) {
    const alertHtml = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i data-feather="alert-triangle" class="icon-16"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    insertMessage(alertHtml);
}

// Insérer un message dans la zone de messages
function insertMessage(html) {
    const messageZone = document.getElementById('message-zone');
    if (messageZone) {
        // Effacer les anciens messages
        messageZone.innerHTML = '';
        
        // Ajouter le nouveau message
        messageZone.innerHTML = html;
        
        // Remplacer les icônes Feather
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Auto-supprimer après 5 secondes
        setTimeout(() => {
            const alert = messageZone.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }, 5000);
    }
}

// ================== VÉRIFICATION DES DISPONIBILITÉS ==================

// Vérifier la disponibilité des véhicules et chauffeurs
function checkAvailability() {
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin').value;
    const excludeId = document.querySelector('input[name="id"]').value || null;
    
    if (!dateDebut || !dateFin) {
        return;
    }
    
    // Valider que les dates sont cohérentes
    if (new Date(dateDebut) >= new Date(dateFin)) {
        return;
    }
    
    // Afficher un indicateur de chargement
    updateAvailabilityInfo('vehicle-availability-info', 'Vérification en cours...');
    updateAvailabilityInfo('chauffeur-availability-info', 'Vérification en cours...');
    
    // Envoyer la requête
    fetch('<?= site_url('locations/check_availability') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `date_debut=${encodeURIComponent(dateDebut)}&date_fin=${encodeURIComponent(dateFin)}&exclude_id=${excludeId || ''}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateAvailableOptions('vehicle_id', data.vehicles, 'numero_matricule', 'marque', 'modele');
            updateAvailableOptions('chauffeur_id', data.chauffeurs, null, 'prenom', 'nom');
            
            // Mettre à jour les infos de disponibilité
            updateAvailabilityInfo('vehicle-availability-info', 
                `${data.vehicles.length} véhicule(s) disponible(s)`);
            updateAvailabilityInfo('chauffeur-availability-info', 
                `${data.chauffeurs.length} chauffeur(s) disponible(s)`);
        } else {
            updateAvailabilityInfo('vehicle-availability-info', 'Erreur lors de la vérification');
            updateAvailabilityInfo('chauffeur-availability-info', 'Erreur lors de la vérification');
        }
    })
    .catch(error => {
        console.error('Erreur vérification disponibilités:', error);
        updateAvailabilityInfo('vehicle-availability-info', 'Erreur de vérification');
        updateAvailabilityInfo('chauffeur-availability-info', 'Erreur de vérification');
    });
}

// Mettre à jour les options disponibles dans un select
function updateAvailableOptions(selectId, items, matriculeField, field1, field2) {
    const select = document.getElementById(selectId);
    if (!select) return;
    
    const currentValue = select.value;
    
    // Garder la première option
    const firstOption = select.querySelector('option[value=""]');
    select.innerHTML = '';
    if (firstOption) {
        select.appendChild(firstOption.cloneNode(true));
    }
    
    // Ajouter les options disponibles
    items.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        
        if (matriculeField && item[matriculeField]) {
            option.textContent = item[matriculeField];
        } else if (field1 && field2) {
            option.textContent = `${item[field1]} ${item[field2]}`;
        } else {
            option.textContent = `Item #${item.id}`;
        }
        
        if (item.id == currentValue) {
            option.selected = true;
        }
        
        select.appendChild(option);
    });
}

// Mettre à jour les informations de disponibilité
function updateAvailabilityInfo(elementId, text) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = text;
        element.className = 'form-text text-info';
    }
}

// ================== CALCULATEUR DE PRIX ==================

// Initialiser le calculateur de prix
function initPriceCalculator() {
    const calcTarif = document.getElementById('calc_tarif');
    const calcDistance = document.getElementById('calc_distance');
    const applyBtn = document.getElementById('applyCalculatedPrice');
    
    if (calcTarif) {
        calcTarif.addEventListener('input', calculatePrice);
    }
    
    if (calcDistance) {
        calcDistance.addEventListener('input', calculatePrice);
    }
    
    if (applyBtn) {
        applyBtn.addEventListener('click', applyCalculatedPrice);
    }
}

// Calculer le prix estimé
function calculatePrice() {
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin').value;
    const tarif = parseFloat(document.getElementById('calc_tarif').value) || 0;
    const distance = parseFloat(document.getElementById('calc_distance').value) || 0;
    
    let prix = 0;
    
    if (dateDebut && dateFin && tarif > 0) {
        const debut = new Date(dateDebut);
        const fin = new Date(dateFin);
        const heures = Math.abs(fin - debut) / (1000 * 60 * 60); // Différence en heures
        
        prix = heures * tarif;
        
        // Ajouter un coût pour la distance si applicable
        if (distance > 0) {
            prix += distance * 2; // 2 DH par km (exemple)
        }
    }
    
    const resultElement = document.getElementById('calc_result');
    if (resultElement) {
        resultElement.textContent = prix.toFixed(2);
    }
}

// Appliquer le prix calculé au formulaire
function applyCalculatedPrice() {
    const calculatedPrice = document.getElementById('calc_result').textContent;
    const priceInput = document.getElementById('prix_total');
    
    if (priceInput && calculatedPrice) {
        priceInput.value = calculatedPrice;
        priceInput.focus();
        
        // Animation de feedback
        priceInput.style.background = '#d4edda';
        setTimeout(() => {
            priceInput.style.background = '';
        }, 1000);
    }
}


</script>