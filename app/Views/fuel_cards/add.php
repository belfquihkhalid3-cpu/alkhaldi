<!-- ========================================
     FICHIER : Views/fuel_cards/add.php
     Formulaire d'ajout/modification carte carburant
     ======================================== -->

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-credit-card text-primary"></i> 
                <?= isset($edit_mode) && $edit_mode ? 'Modifier' : 'Ajouter' ?> une Carte Carburant
            </h1>
            <a href="<?= site_url('fuel_cards') ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Formulaire principal -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Informations de la carte carburant
                        </h6>
                    </div>
                    <div class="card-body">
                        <?= form_open('', ['class' => 'needs-validation', 'novalidate' => true, 'id' => 'fuelCardForm']) ?>
                        
                        <!-- Messages d'erreur -->
                        <?php if (isset($validation) && $validation->getErrors()): ?>
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-exclamation-triangle"></i> Erreurs de validation :</h6>
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
                                    <i class="fas fa-credit-card"></i> Informations de Base
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="numero_serie" class="form-label">Numéro de Série <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('numero_serie') ? 'is-invalid' : '' ?>" 
                                           id="numero_serie" name="numero_serie" value="<?= old('numero_serie', $fuel_card->numero_serie ?? '') ?>" required
                                           placeholder="Ex: EO-123456789">
                                    <div class="invalid-feedback">
                                        <?= isset($validation) ? $validation->getError('numero_serie') : 'Le numéro de série est obligatoire' ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="type_carte" class="form-label">Type de Carte <span class="text-danger">*</span></label>
                                    <select class="form-select <?= isset($validation) && $validation->hasError('type_carte') ? 'is-invalid' : '' ?>" 
                                            id="type_carte" name="type_carte" required>
                                        <option value="">Choisir le type...</option>
                                        <option value="easyone" <?= old('type_carte', $fuel_card->type_carte ?? '') == 'easyone' ? 'selected' : '' ?>>EasyOne</option>
                                        <option value="autre" <?= old('type_carte', $fuel_card->type_carte ?? '') == 'autre' ? 'selected' : '' ?>>Autre</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?= isset($validation) ? $validation->getError('type_carte') : 'Le type de carte est obligatoire' ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut</label>
                                    <select class="form-select" id="statut" name="statut">
                                        <option value="active" <?= old('statut', $fuel_card->statut ?? 'active') == 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= old('statut', $fuel_card->statut ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                        <option value="bloquee" <?= old('statut', $fuel_card->statut ?? '') == 'bloquee' ? 'selected' : '' ?>>Bloquée</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date_creation" class="form-label">Date de Création</label>
                                            <input type="date" class="form-control" id="date_creation" name="date_creation" 
                                                   value="<?= old('date_creation', $fuel_card->date_creation ?? date('Y-m-d')) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date_expiration" class="form-label">Date d'Expiration</label>
                                            <input type="date" class="form-control <?= isset($validation) && $validation->hasError('date_expiration') ? 'is-invalid' : '' ?>" 
                                                   id="date_expiration" name="date_expiration" 
                                                   value="<?= old('date_expiration', $fuel_card->date_expiration ?? '') ?>">
                                            <div class="invalid-feedback">
                                                <?= isset($validation) ? $validation->getError('date_expiration') : '' ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Assignation et Dotation -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-link"></i> Assignation & Dotation
                                </h6>

                                <div class="alert alert-info">
                                    <small><i class="fas fa-info-circle"></i> 
                                    Une carte ne peut être assignée qu'à un véhicule OU un chauffeur, pas les deux.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="vehicle_id" class="form-label">Assigner à un Véhicule</label>
                                    <select class="form-select <?= isset($validation) && $validation->hasError('vehicle_id') ? 'is-invalid' : '' ?>" 
                                            id="vehicle_id" name="vehicle_id">
                                        <option value="">Aucun véhicule</option>
                                        <?php foreach ($vehicles as $vehicle): ?>
                                            <option value="<?= $vehicle->id ?>" 
                                                    <?= old('vehicle_id', $fuel_card->vehicle_id ?? '') == $vehicle->id ? 'selected' : '' ?>>
                                                <?= esc($vehicle->numero_matricule ?? $vehicle->marque . ' ' . $vehicle->modele) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?= isset($validation) ? $validation->getError('vehicle_id') : '' ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="chauffeur_id" class="form-label">Assigner à un Chauffeur</label>
                                    <select class="form-select <?= isset($validation) && $validation->hasError('chauffeur_id') ? 'is-invalid' : '' ?>" 
                                            id="chauffeur_id" name="chauffeur_id">
                                        <option value="">Aucun chauffeur</option>
                                        <?php foreach ($chauffeurs as $chauffeur): ?>
                                            <option value="<?= $chauffeur->id ?>" 
                                                    <?= old('chauffeur_id', $fuel_card->chauffeur_id ?? '') == $chauffeur->id ? 'selected' : '' ?>>
                                                <?= esc($chauffeur->prenom . ' ' . $chauffeur->nom) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?= isset($validation) ? $validation->getError('chauffeur_id') : '' ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="solde_dotation" class="form-label">Solde Dotation (DH)</label>
                                            <input type="number" class="form-control" id="solde_dotation" name="solde_dotation" 
                                                   value="<?= old('solde_dotation', $fuel_card->solde_dotation ?? '') ?>" 
                                                   step="0.01" min="0" placeholder="0.00">
                                            <div class="form-text">Montant disponible sur la carte</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="prix_litre" class="form-label">Prix par Litre (DH)</label>
                                            <input type="number" class="form-control" id="prix_litre" name="prix_litre" 
                                                   value="<?= old('prix_litre', $fuel_card->prix_litre ?? '') ?>" 
                                                   step="0.001" min="0" placeholder="0.000">
                                            <div class="form-text">Prix préférentiel si applicable</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= site_url('fuel_cards') ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> 
                                <?= isset($edit_mode) && $edit_mode ? 'Mettre à jour' : 'Enregistrer' ?>
                            </button>
                        </div>

                        <?= form_close() ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar avec aide -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle"></i> Aide
                        </h6>
                    </div>
                    <div class="card-body">
                        <h6>Types de cartes :</h6>
                        <ul class="small">
                            <li><strong>EasyOne :</strong> Cartes du réseau EasyOne (stations Total, etc.)</li>
                            <li><strong>Autre :</strong> Autres fournisseurs de cartes carburant</li>
                        </ul>

                        <h6 class="mt-3">Statuts :</h6>
                        <ul class="small">
                            <li><strong>Active :</strong> Carte utilisable</li>
                            <li><strong>Inactive :</strong> Carte temporairement désactivée</li>
                            <li><strong>Bloquée :</strong> Carte bloquée définitivement</li>
                        </ul>

                        <h6 class="mt-3">Assignation :</h6>
                        <ul class="small">
                            <li>Une carte peut être assignée à un <strong>véhicule</strong> (usage par tous les chauffeurs de ce véhicule)</li>
                            <li>Ou assignée à un <strong>chauffeur</strong> spécifique</li>
                            <li>Mais <strong>pas les deux à la fois</strong></li>
                        </ul>
                    </div>
                </div>

                <?php if (isset($edit_mode) && $edit_mode): ?>
                <!-- Informations supplémentaires en mode édition -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-chart-line"></i> Informations
                        </h6>
                    </div>
                    <div class="card-body">
                        <p><strong>ID :</strong> #<?= $fuel_card->id ?? 'N/A' ?></p>
                        <?php if (isset($fuel_card->created_at)): ?>
                        <p><strong>Créée le :</strong> <?= date('d/m/Y', strtotime($fuel_card->created_at)) ?></p>
                        <?php endif; ?>
                        
                        <?php if (isset($fuel_card->solde_dotation) && $fuel_card->solde_dotation > 0): ?>
                        <div class="alert alert-success">
                            <strong>Solde actuel :</strong><br>
                            <?= number_format($fuel_card->solde_dotation, 2, ',', ' ') ?> DH
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Calcul rapide -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-calculator"></i> Calcul Rapide
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label small">Montant (DH):</label>
                            <input type="number" class="form-control form-control-sm" id="calc_montant" step="0.01" placeholder="100.00">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Prix/Litre (DH):</label>
                            <input type="number" class="form-control form-control-sm" id="calc_prix" step="0.001" placeholder="12.50">
                        </div>
                        <div class="mb-2">
                            <strong>Litres possibles : <span id="calc_result">0.00</span> L</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validation côté client
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Validation exclusive véhicule/chauffeur
document.getElementById('vehicle_id').addEventListener('change', function() {
    if (this.value) {
        document.getElementById('chauffeur_id').value = '';
    }
});

document.getElementById('chauffeur_id').addEventListener('change', function() {
    if (this.value) {
        document.getElementById('vehicle_id').value = '';
    }
});

// Validation de la date d'expiration
document.getElementById('date_expiration').addEventListener('change', function() {
    if (this.value) {
        const expDate = new Date(this.value);
        const today = new Date();
        
        if (expDate <= today) {
            alert('Attention: La date d\'expiration doit être dans le futur !');
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    }
});

// Calcul rapide litres
function calculateLiters() {
    const montant = parseFloat(document.getElementById('calc_montant').value) || 0;
    const prix = parseFloat(document.getElementById('calc_prix').value) || 0;
    
    if (prix > 0) {
        const liters = montant / prix;
        document.getElementById('calc_result').textContent = liters.toFixed(2);
    } else {
        document.getElementById('calc_result').textContent = '0.00';
    }
}

document.getElementById('calc_montant').addEventListener('input', calculateLiters);
document.getElementById('calc_prix').addEventListener('input', calculateLiters);

// Auto-remplir le prix par litre selon le type
document.getElementById('type_carte').addEventListener('change', function() {
    const prixField = document.getElementById('prix_litre');
    if (this.value === 'easyone' && !prixField.value) {
        prixField.value = '12.50'; // Prix indicatif EasyOne
    }
});
</script>

<style>
.is-invalid {
    border-color: #dc3545;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.text-danger {
    color: #e74a3b !important;
}

.form-text {
    font-size: 0.875em;
    color: #6c757d;
}

.alert {
    border-radius: 0.35rem;
}

#calc_result {
    color: #28a745;
    font-size: 1.1em;
}
</style>