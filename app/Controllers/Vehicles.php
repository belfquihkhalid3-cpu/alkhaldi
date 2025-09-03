<?php

namespace App\Controllers;

use App\Models\Vehicles_model;
use CodeIgniter\Files\File;

class Vehicles extends App_Controller
{
    public $Vehicles_model;
    
    public function __construct()
    {
        parent::__construct();
        $this->Vehicles_model = model('App\Models\Vehicles_model');
    }

    /**
     * Page d'accueil - Liste des véhicules
     */
    public function index()
    {
        $filters = [];
        
        // Récupérer les filtres depuis la requête
        if ($this->request->getGet()) {
            $filters = [
                'marque' => $this->request->getGet('marque'),
                'carburant' => $this->request->getGet('carburant'),
                'statut' => $this->request->getGet('statut'),
                'prix_min' => $this->request->getGet('prix_min'),
                'prix_max' => $this->request->getGet('prix_max'),
            ];
        }

        $view_data = [
            'vehicles' => $this->Vehicles_model->get_all_vehicles($filters),
            'statistics' => $this->Vehicles_model->get_statistics(),
            'brands' => $this->Vehicles_model->get_unique_brands(),
            'filters' => $filters,
            'page_title' => 'Gestion des Véhicules',
        ];

        return $this->template->rander("vehicles/index", $view_data);
    }

    /**
     * Formulaire d'ajout de véhicule
     */
    public function add()
    {
        $view_data = [
            'page_title' => 'Ajouter un Véhicule',
            'vehicle' => (object) [
                'marque' => '',
                'modele' => '',
                'annee' => date('Y'),
                'prix' => '',
                'kilometrage' => 0,
                'carburant' => 'essence',
                'transmission' => 'manuelle',
                'couleur' => '',
                'description' => '',
                'statut' => 'disponible'
            ]
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->_process_vehicle_form();
        }

        return $this->template->rander("vehicles/add", $view_data);
    }

    /**
     * Formulaire de modification de véhicule
     */
    public function edit($id = null)
    {
        if (!$id) {
            show_404();
        }

        $vehicle = $this->Vehicles_model->find($id);
        if (!$vehicle) {
            show_404();
        }

        $view_data = [
            'page_title' => 'Modifier le Véhicule',
            'vehicle' => $vehicle,
            'edit_mode' => true
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->_process_vehicle_form($id);
        }

        return $this->template->rander("vehicles/add", $view_data);
    }

    /**
     * Traitement du formulaire d'ajout/modification
     */
    private function _process_vehicle_form($id = null)
    {
        $data = [
            'marque' => $this->request->getPost('marque'),
            'modele' => $this->request->getPost('modele'),
            'annee' => $this->request->getPost('annee'),
            'prix' => $this->request->getPost('prix'),
            'kilometrage' => $this->request->getPost('kilometrage'),
            'carburant' => $this->request->getPost('carburant'),
            'transmission' => $this->request->getPost('transmission'),
            'couleur' => $this->request->getPost('couleur'),
            'description' => $this->request->getPost('description'),
            'statut' => $this->request->getPost('statut'),
        ];

        // Validation avec règles dynamiques
        if (!$this->Vehicles_model->validateData($data)) {
            $validation = \Config\Services::validation();
            $validation->setRules($this->Vehicles_model->getValidationRules($data));
            $validation->run($data);

            $view_data = [
                'page_title' => $id ? 'Modifier le Véhicule' : 'Ajouter un Véhicule',
                'vehicle' => (object) $data,
                'validation' => $validation,
                'edit_mode' => !empty($id)
            ];

            return $this->template->rander("vehicles/add", $view_data);
        }

        // Gestion de l'upload d'image
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $upload_result = $this->_handle_image_upload($image);
            if ($upload_result['success']) {
                $data['image'] = $upload_result['file_name'];
            }
        }

        // Enregistrement
        try {
            if ($id) {
                $this->Vehicles_model->update($id, $data);
                session()->setFlashdata('success', 'Véhicule modifié avec succès');
            } else {
                $this->Vehicles_model->insert($data);
                session()->setFlashdata('success', 'Véhicule ajouté avec succès');
            }
            
            return redirect()->to(site_url('vehicles'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Gestion de l'upload d'image
     */
    private function _handle_image_upload($image)
    {
        $uploadPath = FCPATH . 'uploads/vehicles/';
      
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        try {
            // Générer un nom unique
            $newName = $image->getRandomName();
            
            // Déplacer le fichier
            $image->move($uploadPath, $newName);
            
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
     * Voir les détails d'un véhicule
     */
    public function view($id = null)
    {
        if (!$id) {
            show_404();
        }

        $vehicle = $this->Vehicles_model->find($id);
        if (!$vehicle) {
            show_404();
        }

        $view_data = [
            'vehicle' => $vehicle,
            'page_title' => $vehicle->marque . ' ' . $vehicle->modele,
        ];

        return $this->template->rander("vehicles/view", $view_data);
    }

    /**
     * Supprimer un véhicule
     */
    public function delete($id = null)
    {
        if (!$id) {
            show_404();
        }

        try {
            $vehicle = $this->Vehicles_model->find($id);
            if (!$vehicle) {
                session()->setFlashdata('error', 'Véhicule non trouvé');
                return redirect()->to(site_url('vehicles'));
            }

            // Supprimer l'image si elle existe
            if ($vehicle->image) {
                $imagePath = WRITEPATH . 'uploads/vehicles/' . $vehicle->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $this->Vehicles_model->delete($id);
            session()->setFlashdata('success', 'Véhicule supprimé avec succès');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }

        return redirect()->to(site_url('vehicles'));
    }

    /**
     * API pour la recherche AJAX
     */
    public function search_api()
    {
        $keyword = $this->request->getGet('q');
        $filters = $this->request->getGet();
        
        $vehicles = $this->Vehicles_model->search_vehicles($keyword, $filters);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $vehicles
        ]);
    }

    /**
     * Statistiques pour le dashboard
     */
    public function statistics()
    {
        $stats = $this->Vehicles_model->get_statistics();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $stats
        ]);
    }
}