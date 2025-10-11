<!-- ========================================
     FICHIER : Views/vehicles/add.php
     Formulaire d'ajout/modification
     ======================================== -->

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-<?= isset($edit_mode) ? 'edit' : 'plus' ?> text-primary"></i> 
                <?= isset($edit_mode) ? 'Modifier' : 'Ajouter' ?> un véhicule
            </h1>
            <a href="<?= site_url('vehicles') ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8">
                <!-- Informations Véhicule -->
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0 font-weight-bold">🚗 Informations du véhicule</h6>
                    </div>
                    <div class="card-body">
                        
                        <!-- Messages d'erreur -->
                        <?php if (isset($validation) && $validation->getErrors()): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h6><i class="fas fa-exclamation-triangle"></i> Erreurs de validation :</h6>
                                <ul class="mb-0">
                                    <?php foreach ($validation->getErrors() as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?= form_open_multipart(isset($edit_mode) ? 'vehicles/edit/' . $vehicle->id : 'vehicles/add', 
                                               ['class' => 'needs-validation', 'novalidate' => '']) ?>
                        
                        <!-- Marque -->
                        <div class="form-group">
                            <div class="row">
                                <label for="marque" class="col-md-3 col-form-label">
                                    🏷 Marque <span class="text-danger">*</span>
                                </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="marque" name="marque" 
                                           value="<?= old('marque', $vehicle->marque ?? '') ?>" 
                                           placeholder="Ex: Toyota, BMW..." required>
                                    <div class="invalid-feedback">Veuillez saisir la marque du véhicule.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Modèle -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="modele" class="form-label">🎯 Modèle <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="modele" name="modele" 
                                           value="<?= old('modele', $vehicle->modele ?? '') ?>" 
                                           placeholder="Ex: Corolla, X5..." required>
                                    <div class="invalid-feedback">Veuillez saisir le modèle du véhicule.</div>
                                </div>
                            </div>

                            <!-- Année -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="annee" class="form-label">📅 Année <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="annee" name="annee" 
                                           value="<?= old('annee', $vehicle->annee ?? date('Y')) ?>" 
                                           min="1900" max="<?= date('Y') + 1 ?>" required>
                                    <div class="invalid-feedback">Veuillez saisir une année valide.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Prix -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prix" class="form-label">💰 Prix (EUR) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="prix" name="prix" 
                                           value="<?= old('prix', $vehicle->prix ?? '') ?>" 
                                           min="0" step="0.01" required>
                                    <div class="invalid-feedback">Veuillez saisir un prix valide.</div>
                                </div>
                            </div>

                            <!-- Kilométrage -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kilometrage" class="form-label">🛣 Kilométrage <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="kilometrage" name="kilometrage" 
                                           value="<?= old('kilometrage', $vehicle->kilometrage ?? '0') ?>" 
                                           min="0" required>
                                    <div class="invalid-feedback">Veuillez saisir le kilométrage.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Carburant -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="carburant" class="form-label">⛽ Carburant <span class="text-danger">*</span></label>
                                    <select class="form-select" id="carburant" name="carburant" required>
                                        <option value="">- Sélectionner -</option>
                                        <option value="essence" <?= old('carburant', $vehicle->carburant ?? '') == 'essence' ? 'selected' : '' ?>>⛽ Essence</option>
                                        <option value="diesel" <?= old('carburant', $vehicle->carburant ?? '') == 'diesel' ? 'selected' : '' ?>>🛢️ Diesel</option>
                                        <option value="hybride" <?= old('carburant', $vehicle->carburant ?? '') == 'hybride' ? 'selected' : '' ?>>🔋 Hybride</option>
                                        <option value="electrique" <?= old('carburant', $vehicle->carburant ?? '') == 'electrique' ? 'selected' : '' ?>>⚡ Électrique</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Transmission -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="transmission" class="form-label">⚙️ Transmission <span class="text-danger">*</span></label>
                                    <select class="form-select" id="transmission" name="transmission" required>
                                        <option value="">- Sélectionner -</option>
                                        <option value="manuelle" <?= old('transmission', $vehicle->transmission ?? '') == 'manuelle' ? 'selected' : '' ?>>🔧 Manuelle</option>
                                        <option value="automatique" <?= old('transmission', $vehicle->transmission ?? '') == 'automatique' ? 'selected' : '' ?>>🤖 Automatique</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Couleur -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="couleur" class="form-label">🎨 Couleur <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="couleur" name="couleur" 
                                           value="<?= old('couleur', $vehicle->couleur ?? '') ?>" 
                                           placeholder="Ex: Blanc, Noir..." required>
                                    <div class="invalid-feedback">Veuillez saisir la couleur du véhicule.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Statut -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="statut" class="form-label">📊 Statut</label>
                                    <select class="form-select" id="statut" name="statut">
                                        <option value="disponible" <?= old('statut', $vehicle->statut ?? '') == 'disponible' ? 'selected' : '' ?>>✅ Disponible</option>
                                        <option value="reserve" <?= old('statut', $vehicle->statut ?? '') == 'reserve' ? 'selected' : '' ?>>⏳ Réservé</option>
                                        <option value="vendu" <?= old('statut', $vehicle->statut ?? '') == 'vendu' ? 'selected' : '' ?>>✔️ Vendu</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Image -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image" class="form-label">📷 Image du véhicule</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <div class="form-text">Formats acceptés : JPG, PNG, GIF (max 2MB)</div>
                                    
                                    <?php if (isset($vehicle->image) && $vehicle->image): ?>
                                        <div class="mt-2">
                                            <img src="<?= base_url('writable/uploads/vehicles/' . $vehicle->image) ?>" 
                                                 class="img-thumbnail" style="max-width: 150px;" alt="Image actuelle">
                                            <small class="text-muted d-block">Image actuelle</small>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div id="imagePreview" class="mt-2"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <div class="row">
                                <label for="description" class="col-md-3 col-form-label">📝 Description</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" id="description" name="description" rows="4" 
                                              placeholder="Décrivez les caractéristiques du véhicule..."><?= old('description', $vehicle->description ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="<?= site_url('vehicles') ?>" class="btn btn-secondary me-md-2">
                                ❌ Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                💾 <?= isset($edit_mode) ? 'Mettre à jour' : 'Enregistrer' ?> le véhicule
                            </button>
                        </div>

                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validation Bootstrap
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

// Prévisualisation d'image
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-thumbnail';
            img.style.maxWidth = '200px';
            
            const caption = document.createElement('small');
            caption.className = 'text-muted d-block';
            caption.textContent = 'Aperçu de la nouvelle image';
            
            preview.appendChild(img);
            preview.appendChild(caption);
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>