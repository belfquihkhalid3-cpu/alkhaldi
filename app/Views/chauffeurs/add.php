<!-- ========================================
     FICHIER : Views/chauffeurs/add.php
     Formulaire d'ajout/modification chauffeur
     ======================================== -->

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-tie text-primary"></i> 
                <?= isset($edit_mode) && $edit_mode ? 'Modifier' : 'Ajouter' ?> un Chauffeur
            </h1>
            <a href="<?= site_url('chauffeurs') ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour ï¿½ la liste
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Formulaire principal -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Informations du chauffeur
                        </h6>
                    </div>
                    <div class="card-body">
                        <?= form_open_multipart('', ['class' => 'needs-validation', 'novalidate' => true, 'id' => 'chauffeurForm']) ?>
                        
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
                            <!-- Informations personnelles -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user"></i> Informations Personnelles
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('nom') ? 'is-invalid' : '' ?>" 
                                           id="nom" name="nom" value="<?= old('nom', $chauffeur->nom ?? '') ?>" required>
                                    <div class="invalid-feedback">
                                        <?= isset($validation) ? $validation->getError('nom') : 'Le nom est obligatoire' ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="prenom" class="form-label">Prï¿½nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('prenom') ? 'is-invalid' : '' ?>" 
                                           id="prenom" name="prenom" value="<?= old('prenom', $chauffeur->prenom ?? '') ?>" required>
                                    <div class="invalid-feedback">
                                        <?= isset($validation) ? $validation->getError('prenom') : 'Le prï¿½nom est obligatoire' ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="cnie" class="form-label">CNIE <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('cnie') ? 'is-invalid' : '' ?>" 
                                           id="cnie" name="cnie" value="<?= old('cnie', $chauffeur->cnie ?? '') ?>" required>
                                    <div class="invalid-feedback">
                                        <?= isset($validation) ? $validation->getError('cnie') : 'Le CNIE est obligatoire' ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="date_naissance" class="form-label">Date de Naissance <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control <?= isset($validation) && $validation->hasError('date_naissance') ? 'is-invalid' : '' ?>" 
                                           id="date_naissance" name="date_naissance" value="<?= old('date_naissance', $chauffeur->date_naissance ?? '') ?>" required>
                                    <div class="invalid-feedback">
                                        <?= isset($validation) ? $validation->getError('date_naissance') : 'La date de naissance est obligatoire' ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <textarea class="form-control" id="adresse" name="adresse" rows="3"><?= old('adresse', $chauffeur->adresse ?? '') ?></textarea>
                                </div>
                            </div>

                            <!-- Contact et Permis -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-phone"></i> Contact & Permis
                                </h6>

                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Tï¿½lï¿½phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control <?= isset($validation) && $validation->hasError('telephone') ? 'is-invalid' : '' ?>" 
                                           id="telephone" name="telephone" value="<?= old('telephone', $chauffeur->telephone ?? '') ?>" required>
                                    <div class="invalid-feedback">
                                        <?= isset($validation) ? $validation->getError('telephone') : 'Le tï¿½lï¿½phone est obligatoire' ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="telephone_urgence" class="form-label">Tï¿½lï¿½phone d'Urgence</label>
                                    <input type="tel" class="form-control" id="telephone_urgence" name="telephone_urgence" 
                                           value="<?= old('telephone_urgence', $chauffeur->telephone_urgence ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                                           id="email" name="email" value="<?= old('email', $chauffeur->email ?? '') ?>">
                                    <div class="invalid-feedback">
                                        <?= isset($validation) ? $validation->getError('email') : '' ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="numero_permis" class="form-label">Nï¿½ Permis <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control <?= isset($validation) && $validation->hasError('numero_permis') ? 'is-invalid' : '' ?>" 
                                                   id="numero_permis" name="numero_permis" value="<?= old('numero_permis', $chauffeur->numero_permis ?? '') ?>" required>
                                            <div class="invalid-feedback">
                                                <?= isset($validation) ? $validation->getError('numero_permis') : 'Le numï¿½ro de permis est obligatoire' ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="categorie_permis" class="form-label">Catï¿½gorie <span class="text-danger">*</span></label>
                                            <select class="form-select <?= isset($validation) && $validation->hasError('categorie_permis') ? 'is-invalid' : '' ?>" 
                                                    id="categorie_permis" name="categorie_permis" required>
                                                <option value="">Choisir...</option>
                                                <option value="A" <?= old('categorie_permis', $chauffeur->categorie_permis ?? '') == 'A' ? 'selected' : '' ?>>A - Moto</option>
                                                <option value="A1" <?= old('categorie_permis', $chauffeur->categorie_permis ?? '') == 'A1' ? 'selected' : '' ?>>A1 - Moto lï¿½gï¿½re</option>
                                                <option value="A2" <?= old('categorie_permis', $chauffeur->categorie_permis ?? '') == 'A2' ? 'selected' : '' ?>>A2 - Moto puissance limitï¿½e</option>
                                                <option value="B" <?= old('categorie_permis', $chauffeur->categorie_permis ?? '') == 'B' ? 'selected' : '' ?>>B - Voiture</option>
                                                <option value="C" <?= old('categorie_permis', $chauffeur->categorie_permis ?? '') == 'C' ? 'selected' : '' ?>>C - Poids lourd</option>
                                                <option value="D" <?= old('categorie_permis', $chauffeur->categorie_permis ?? '') == 'D' ? 'selected' : '' ?>>D - Transport en commun</option>
                                                <option value="BE" <?= old('categorie_permis', $chauffeur->categorie_permis ?? '') == 'BE' ? 'selected' : '' ?>>BE - Voiture + remorque</option>
                                                <option value="CE" <?= old('categorie_permis', $chauffeur->categorie_permis ?? '') == 'CE' ? 'selected' : '' ?>>CE - Poids lourd + remorque</option>
                                                <option value="DE" <?= old('categorie_permis', $chauffeur->categorie_permis ?? '') == 'DE' ? 'selected' : '' ?>>DE - Bus + remorque</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <?= isset($validation) ? $validation->getError('categorie_permis') : 'La catï¿½gorie est obligatoire' ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="date_expiration_permis" class="form-label">Date Expiration Permis <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control <?= isset($validation) && $validation->hasError('date_expiration_permis') ? 'is-invalid' : '' ?>" 
                                           id="date_expiration_permis" name="date_expiration_permis" value="<?= old('date_expiration_permis', $chauffeur->date_expiration_permis ?? '') ?>" required>
                                    <div class="invalid-feedback">
                                        <?= isset($validation) ? $validation->getError('date_expiration_permis') : 'La date d\'expiration est obligatoire' ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <!-- Informations Emploi -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-briefcase"></i> Informations Emploi
                                </h6>

                                <div class="mb-3">
                                    <label for="date_embauche" class="form-label">Date d'Embauche <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control <?= isset($validation) && $validation->hasError('date_embauche') ? 'is-invalid' : '' ?>" 
                                           id="date_embauche" name="date_embauche" value="<?= old('date_embauche', $chauffeur->date_embauche ?? '') ?>" required>
                                    <div class="invalid-feedback">
                                        <?= isset($validation) ? $validation->getError('date_embauche') : 'La date d\'embauche est obligatoire' ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="salaire_base" class="form-label">Salaire de Base (DH)</label>
                                    <input type="number" class="form-control" id="salaire_base" name="salaire_base" 
                                           value="<?= old('salaire_base', $chauffeur->salaire_base ?? '') ?>" step="0.01" min="0">
                                    <div class="form-text">Salaire mensuel en dirhams</div>
                                </div>

                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut</label>
                                    <select class="form-select" id="statut" name="statut">
                                        <option value="actif" <?= old('statut', $chauffeur->statut ?? 'actif') == 'actif' ? 'selected' : '' ?>>Actif</option>
                                        <option value="inactif" <?= old('statut', $chauffeur->statut ?? '') == 'inactif' ? 'selected' : '' ?>>Inactif</option>
                                        <option value="suspendu" <?= old('statut', $chauffeur->statut ?? '') == 'suspendu' ? 'selected' : '' ?>>Suspendu</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Photo et Observations -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-image"></i> Photo & Observations
                                </h6>

                                <div class="mb-3">
                                    <label for="photo" class="form-label">Photo du Chauffeur</label>
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                    <div class="form-text">Formats acceptï¿½s: JPG, PNG, GIF (Max: 2MB)</div>
                                    
                                    <?php if (isset($chauffeur->photo) && $chauffeur->photo): ?>
                                        <div class="mt-2">
                                            <img src="<?= base_url('public/uploads/chauffeurs/' . $chauffeur->photo) ?>" 
                                                 class="img-thumbnail" alt="Photo actuelle" style="max-width: 150px;">
                                            <div class="form-text">Photo actuelle</div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="observations" class="form-label">Observations</label>
                                    <textarea class="form-control" id="observations" name="observations" rows="4" 
                                              placeholder="Notes sur le chauffeur, compï¿½tences particuliï¿½res, etc."><?= old('observations', $chauffeur->observations ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= site_url('chauffeurs') ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> 
                                <?= isset($edit_mode) && $edit_mode ? 'Mettre ï¿½ jour' : 'Enregistrer' ?>
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
                        <h6>Informations importantes :</h6>
                        <ul class="small">
                            <li><strong>CNIE :</strong> Numï¿½ro unique d'identification</li>
                            <li><strong>Permis :</strong> Vï¿½rifiez la date d'expiration</li>
                            <li><strong>Catï¿½gorie D :</strong> Obligatoire pour transport de personnes</li>
                            <li><strong>Photo :</strong> Recommandï¿½e pour identification</li>
                        </ul>

                        <h6 class="mt-3">Catï¿½gories de permis :</h6>
                        <ul class="small">
                            <li><strong>A/A1/A2 :</strong> Motocycles</li>
                            <li><strong>B :</strong> Vï¿½hicules lï¿½gers</li>
                            <li><strong>C :</strong> Poids lourds</li>
                            <li><strong>D :</strong> Transport en commun</li>
                            <li><strong>E :</strong> Avec remorque</li>
                        </ul>
                    </div>
                </div>

                <?php if (isset($edit_mode) && $edit_mode): ?>
                <!-- Informations supplï¿½mentaires en mode ï¿½dition -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-chart-line"></i> Informations
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php 
                        $age = (new DateTime())->diff(new DateTime($chauffeur->date_naissance ?? date('Y-m-d')))->y;
                        $anciennete = (new DateTime())->diff(new DateTime($chauffeur->date_embauche ?? date('Y-m-d')));
                        ?>
                        <p><strong>ï¿½ge :</strong> <?= $age ?> ans</p>
                        <p><strong>Anciennetï¿½ :</strong> <?= $anciennete->y ?> an(s), <?= $anciennete->m ?> mois</p>
                        <p><strong>Ajoutï¿½ le :</strong> <?= isset($chauffeur->created_at) ? date('d/m/Y H:i', strtotime($chauffeur->created_at)) : 'N/A' ?></p>
                        <?php if (isset($chauffeur->updated_at)): ?>
                        <p><strong>Modifiï¿½ le :</strong> <?= date('d/m/Y H:i', strtotime($chauffeur->updated_at)) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Validation cï¿½tï¿½ client
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

// Calcul automatique de l'ï¿½ge
document.getElementById('date_naissance').addEventListener('change', function() {
    const birthDate = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    if (age < 18) {
        alert('Attention: Le chauffeur doit avoir au moins 18 ans.');
    }
    if (age > 70) {
        alert('Attention: ï¿½ge supï¿½rieur ï¿½ 70 ans.');
    }
});

// Validation de la date d'expiration du permis
document.getElementById('date_expiration_permis').addEventListener('change', function() {
    const expDate = new Date(this.value);
    const today = new Date();
    const diffTime = expDate - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays < 0) {
        alert('Attention: Le permis est dï¿½jï¿½ expirï¿½ !');
        this.classList.add('is-invalid');
    } else if (diffDays < 30) {
        alert('Attention: Le permis expire dans moins de 30 jours !');
        this.classList.add('is-warning');
    } else {
        this.classList.remove('is-invalid', 'is-warning');
    }
});
</script>

<style>
.is-warning {
    border-color: #ffc107;
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

.img-thumbnail {
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    padding: 0.25rem;
}
</style>