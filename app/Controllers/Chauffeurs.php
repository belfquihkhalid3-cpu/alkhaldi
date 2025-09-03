<?php

namespace App\Controllers;

use App\Models\Chauffeurs_model;
use CodeIgniter\Files\File;

class Chauffeurs extends App_Controller
{
    public $Chauffeurs_model;
    
    public function __construct()
    {
        parent::__construct();
        $this->Chauffeurs_model = model('App\Models\Chauffeurs_model');
    }

    /**
     * Page d'accueil - Liste des chauffeurs
     */
    public function index()
    {
        $filters = [];
        
        // Récupérer les filtres depuis la requête
        if ($this->request->getGet()) {
            $filters = [
                'nom' => $this->request->getGet('nom'),
                'prenom' => $this->request->getGet('prenom'),
                'statut' => $this->request->getGet('statut'),
                'categorie_permis' => $this->request->getGet('categorie_permis'),
                'salaire_min' => $this->request->getGet('salaire_min'),
                'salaire_max' => $this->request->getGet('salaire_max'),
                'permis_expire_soon' => $this->request->getGet('permis_expire_soon'),
            ];
        }

        $view_data = [
            'chauffeurs' => $this->Chauffeurs_model->get_all_chauffeurs($filters),
            'statistics' => $this->Chauffeurs_model->get_statistics(),
            'license_categories' => $this->Chauffeurs_model->get_unique_license_categories(),
            'expiring_licenses' => $this->Chauffeurs_model->get_expiring_licenses(30),
            'filters' => $filters,
            'page_title' => 'Gestion des Chauffeurs',
        ];

        return $this->template->rander("chauffeurs/index", $view_data);
    }

    /**
     * Formulaire d'ajout de chauffeur
     */
    public function add()
    {
        $view_data = [
            'page_title' => 'Ajouter un Chauffeur',
            'chauffeur' => (object) [
                'nom' => '',
                'prenom' => '',
                'cnie' => '',
                'telephone' => '',
                'telephone_urgence' => '',
                'email' => '',
                'adresse' => '',
                'date_naissance' => '',
                'date_embauche' => date('Y-m-d'),
                'numero_permis' => '',
                'date_expiration_permis' => '',
                'categorie_permis' => 'D',
                'salaire_base' => '',
                'statut' => 'actif',
                'observations' => ''
            ]
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->_process_chauffeur_form();
        }

        return $this->template->rander("chauffeurs/add", $view_data);
    }

    /**
     * Formulaire de modification de chauffeur
     */
    public function edit($id = null)
    {
        if (!$id) {
            show_404();
        }

        $chauffeur = $this->Chauffeurs_model->find($id);
        if (!$chauffeur) {
            show_404();
        }

        $view_data = [
            'page_title' => 'Modifier le Chauffeur',
            'chauffeur' => $chauffeur,
            'edit_mode' => true
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->_process_chauffeur_form($id);
        }

        return $this->template->rander("chauffeurs/add", $view_data);
    }

    /**
     * Traitement du formulaire d'ajout/modification
     */
    private function _process_chauffeur_form($id = null)
    {
        $data = [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'cnie' => $this->request->getPost('cnie'),
            'telephone' => $this->request->getPost('telephone'),
            'telephone_urgence' => $this->request->getPost('telephone_urgence'),
            'email' => $this->request->getPost('email'),
            'adresse' => $this->request->getPost('adresse'),
            'date_naissance' => $this->request->getPost('date_naissance'),
            'date_embauche' => $this->request->getPost('date_embauche'),
            'numero_permis' => $this->request->getPost('numero_permis'),
            'date_expiration_permis' => $this->request->getPost('date_expiration_permis'),
            'categorie_permis' => $this->request->getPost('categorie_permis'),
            'salaire_base' => $this->request->getPost('salaire_base'),
            'statut' => $this->request->getPost('statut'),
            'observations' => $this->request->getPost('observations'),
        ];

        // Validation avec règles dynamiques
        if (!$this->Chauffeurs_model->validateData($data)) {
            $validation = \Config\Services::validation();
            $validation->setRules($this->Chauffeurs_model->validationRules);
            $validation->run($data);
            
            // Ajouter les erreurs personnalisées du modèle
            $customErrors = $this->Chauffeurs_model->getCustomValidationErrors();
            foreach ($customErrors as $field => $error) {
                $validation->setError($field, $error);
            }

            $view_data = [
                'page_title' => $id ? 'Modifier le Chauffeur' : 'Ajouter un Chauffeur',
                'chauffeur' => (object) $data,
                'validation' => $validation,
                'edit_mode' => !empty($id)
            ];

            return $this->template->rander("chauffeurs/add", $view_data);
        }

        // Gestion de l'upload de photo
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $upload_result = $this->_handle_photo_upload($photo);
            if ($upload_result['success']) {
                $data['photo'] = $upload_result['file_name'];
            }
        }

        // Enregistrement
        try {
            if ($id) {
                $this->Chauffeurs_model->update($id, $data);
                session()->setFlashdata('success', 'Chauffeur modifié avec succès');
            } else {
                $this->Chauffeurs_model->insert($data);
                session()->setFlashdata('success', 'Chauffeur ajouté avec succès');
            }
            
            return redirect()->to(site_url('chauffeurs'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Gestion de l'upload de photo
     */
    private function _handle_photo_upload($photo)
    {
        $uploadPath = FCPATH . 'uploads/chauffeurs/';
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        try {
            // Vérifier le type de fichier
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($photo->getExtension()), $allowedTypes)) {
                return [
                    'success' => false,
                    'error' => 'Type de fichier non autorisé. Formats acceptés: JPG, PNG, GIF'
                ];
            }

            // Vérifier la taille (max 2MB)
            if ($photo->getSize() > 2097152) {
                return [
                    'success' => false,
                    'error' => 'Le fichier est trop volumineux. Taille maximum: 2MB'
                ];
            }

            // Générer un nom unique
            $newName = $photo->getRandomName();
            
            // Déplacer le fichier
            $photo->move($uploadPath, $newName);
            
            return [
                'success' => true,
                'file_name' => $newName
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Voir les détails d'un chauffeur
     */
    public function view($id = null)
    {
        if (!$id) {
            show_404();
        }

        $chauffeur = $this->Chauffeurs_model->get_chauffeur_details($id);
        if (!$chauffeur) {
            show_404();
        }

        $view_data = [
            'chauffeur' => $chauffeur,
            'page_title' => $chauffeur->prenom . ' ' . $chauffeur->nom,
        ];

        return $this->template->rander("chauffeurs/view", $view_data);
    }

    /**
     * Supprimer un chauffeur
     */
    public function delete($id = null)
    {
        if (!$id) {
            show_404();
        }

        try {
            $chauffeur = $this->Chauffeurs_model->find($id);
            if (!$chauffeur) {
                session()->setFlashdata('error', 'Chauffeur non trouvé');
                return redirect()->to(site_url('chauffeurs'));
            }

            // Vérifier s'il a des locations actives
            $locationsModel = model('App\Models\Locations_model');
            $activeLocations = $locationsModel->where('chauffeur_id', $id)
                                             ->whereIn('statut', ['confirmee', 'en_cours'])
                                             ->countAllResults();

            if ($activeLocations > 0) {
                session()->setFlashdata('error', 'Impossible de supprimer ce chauffeur. Il a des locations actives.');
                return redirect()->to(site_url('chauffeurs'));
            }

            // Supprimer la photo si elle existe
            if ($chauffeur->photo) {
                $photoPath = WRITEPATH . 'uploads/chauffeurs/' . $chauffeur->photo;
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }

            $this->Chauffeurs_model->delete($id);
            session()->setFlashdata('success', 'Chauffeur supprimé avec succès');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }

        return redirect()->to(site_url('chauffeurs'));
    }

    /**
     * Changer le statut d'un chauffeur
     */
    public function change_status($id = null)
    {
        if (!$id) {
            show_404();
        }

        $new_status = $this->request->getPost('statut');
        
        try {
            if ($this->Chauffeurs_model->update_status($id, $new_status)) {
                session()->setFlashdata('success', 'Statut mis à jour avec succès');
            } else {
                session()->setFlashdata('error', 'Erreur lors de la mise à jour du statut');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Erreur: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * API pour la recherche AJAX
     */
    public function search_api()
    {
        $keyword = $this->request->getGet('q');
        $filters = $this->request->getGet();
        
        $chauffeurs = $this->Chauffeurs_model->search_chauffeurs($keyword, $filters);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $chauffeurs
        ]);
    }

    /**
     * API pour récupérer les chauffeurs disponibles
     */
    public function available_api()
    {
        $chauffeurs = $this->Chauffeurs_model->get_available_chauffeurs();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $chauffeurs
        ]);
    }

    /**
     * Statistiques pour le dashboard
     */
    public function statistics()
    {
        $stats = $this->Chauffeurs_model->get_statistics();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Rapport des permis expirant bientôt
     */
    public function expiring_licenses()
    {
        $days = $this->request->getGet('days') ?? 30;
        $chauffeurs = $this->Chauffeurs_model->get_expiring_licenses($days);
        
        $view_data = [
            'chauffeurs' => $chauffeurs,
            'days' => $days,
            'page_title' => 'Permis Expirant - Prochains ' . $days . ' jours',
        ];

        return $this->template->rander("chauffeurs/expiring_licenses", $view_data);
    }
}