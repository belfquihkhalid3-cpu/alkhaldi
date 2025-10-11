<?php

namespace App\Controllers;

use App\Models\Fuel_cards_model;

class Fuel_cards extends App_Controller
{
    public $Fuel_cards_model;
    public $Vehicles_model;
    public $Chauffeurs_model;
    
    public function __construct()
    {
        parent::__construct();
        $this->Fuel_cards_model = model('App\Models\Fuel_cards_model');
        
        // Charger les modèles nécessaires
        $this->Vehicles_model = model('App\Models\Vehicles_model');
        $this->Chauffeurs_model = model('App\Models\Chauffeurs_model');
    }

    /**
     * Page d'accueil - Liste des cartes carburant
     */
    public function index()
    {
        $filters = [];
        
        // Récupérer les filtres depuis la requête
        if ($this->request->getGet()) {
            $filters = [
                'numero_serie' => $this->request->getGet('numero_serie'),
                'type_carte' => $this->request->getGet('type_carte'),
                'statut' => $this->request->getGet('statut'),
                'vehicle_id' => $this->request->getGet('vehicle_id'),
                'chauffeur_id' => $this->request->getGet('chauffeur_id'),
                'expire_soon' => $this->request->getGet('expire_soon'),
            ];
        }

        $view_data = [
            'fuel_cards' => $this->Fuel_cards_model->get_all_fuel_cards($filters),
            'statistics' => $this->Fuel_cards_model->get_statistics(),
            'card_types' => $this->Fuel_cards_model->get_card_types(),
            'expiring_cards' => $this->Fuel_cards_model->get_expiring_cards(30),
            'vehicles' => $this->Vehicles_model->get_available_vehicles(),
            'chauffeurs' => $this->Chauffeurs_model->get_active_chauffeurs(),
            'filters' => $filters,
            'page_title' => 'Gestion des Cartes Carburant',
        ];

        return $this->template->rander("fuel_cards/index", $view_data);
    }

public function modal_form() {
    $id = $this->request->getPost('id') ?: $this->request->getGet('id');
    
    if ($id) {
        $model_info = $this->Fuel_cards_model->find($id);
    } else {
        $model_info = (object)[
            'id' => '',
            'numero_serie' => 'TEST',
            'type_carte' => 'easyone',
            'vehicle_id' => '',
            'chauffeur_id' => '',
            'solde_dotation' => 0,
            'prix_litre' => 0,
            'statut' => 'active',
            'date_expiration' => ''
        ];
    }
    
    $view_data = [
        'model_info' => $model_info,
        'vehicles_dropdown' => $this->_make_dropdown_vehicles(),
        'chauffeurs_dropdown' => $this->_make_dropdown_chauffeurs()
    ];
    
    // DEBUG
    echo "<!-- DEBUG VIEW DATA: " . json_encode($view_data['model_info']) . " -->";
    
    return $this->template->view('fuel_cards/modal_add', $view_data);
}
private function _get_empty_model() {
    return (object)[
        'id' => '',
        'numero_serie' => '',
        'type_carte' => 'easyone',
        'vehicle_id' => '',
        'chauffeur_id' => '',
        'solde_dotation' => 0,
        'prix_litre' => 0,
        'statut' => 'active',
        'created_at' => date('Y-m-d'),
        'date_expiration' => ''
    ];
}

private function _make_dropdown_vehicles() {
    $vehicles = $this->Vehicles_model->get_available_vehicles();
    $dropdown = ['' => '-'];
    foreach($vehicles as $v) {
        $dropdown[$v->id] = $v->numero_matricule ?? $v->marque;
    }
    return $dropdown;
}

private function _make_dropdown_chauffeurs() {
    $chauffeurs = $this->Chauffeurs_model->get_available_chauffeurs();
    $dropdown = ['' => '-'];
    foreach($chauffeurs as $c) {
        $dropdown[$c->id] = $c->prenom . ' ' . $c->nom;
    }
    return $dropdown;
}
public function save() {
    $id = $this->request->getPost('id');
    
    $data = [
        'numero_serie' => $this->request->getPost('numero_serie'),
        'type_carte' => $this->request->getPost('type_carte'),
        'vehicle_id' => $this->request->getPost('vehicle_id') ?: null,
        'chauffeur_id' => $this->request->getPost('chauffeur_id') ?: null,
        'solde_dotation' => $this->request->getPost('solde_dotation') ?: 0,
        'prix_litre' => $this->request->getPost('prix_litre') ?: 0,
        'statut' => $this->request->getPost('statut') ?: 'active',
        'date_expiration' => $this->request->getPost('date_expiration')
    ];

    try {
        if ($id) {
            $this->Fuel_cards_model->update($id, $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $id = $this->Fuel_cards_model->insert($data);
        }
        
        echo json_encode(['success' => true, 'id' => $id, 'message' => 'Sauvegardé']);
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

public function list_data() {
    $list_data = $this->Fuel_cards_model->get_details()->getResult();
    $result = [];
    foreach ($list_data as $data) {
        $result[] = $this->_make_row($data);
    }
    echo json_encode(["data" => $result]);
}

private function _make_row($data) {
    $edit = modal_anchor(get_uri("fuel_cards/modal_form"), "<i data-feather='edit' class='icon-16'></i>", ["class" => "edit", "title" => "Modifier", "data-post-id" => $data->id]);
    $delete = js_anchor("<i data-feather='x' class='icon-16'></i>", ['title' => 'Supprimer', "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("fuel_cards/delete"), "data-action" => "delete-confirmation"]);
    
    return [
        $data->numero_serie,
        $data->type_carte,
        $data->vehicle_id ? "V-" . $data->vehicle_id : "-",
        $data->chauffeur_id ? "C-" . $data->chauffeur_id : "-",
        number_format($data->solde_dotation, 2),
        "<span class='badge bg-" . ($data->statut == 'active' ? 'success' : 'danger') . "'>" . $data->statut . "</span>",
        $edit . $delete
    ];
}

 public function add() {
    $view_data = [
        'page_title' => 'Ajouter une Carte Carburant',
        'model_info' => (object) [
            'numero_serie' => '',
            'type_carte' => 'easyone',
            'vehicle_id' => '',
            'chauffeur_id' => '',
            'solde_dotation' => '',
            'prix_litre' => '',
            'statut' => 'active',
            'date_creation' => date('Y-m-d'),
            'date_expiration' => ''
        ],
        'vehicles_dropdown' => $this->_make_dropdown_vehicles(),
        'chauffeurs_dropdown' => $this->_make_dropdown_chauffeurs()
    ];

    if ($this->request->getMethod() === 'POST') {
        return $this->_process_fuel_card_form();
    }

    return $this->template->rander("fuel_cards/add", $view_data);
}

    /**
     * Formulaire de modification de carte carburant
     */
    public function edit($id = null)
    {
        if (!$id) {
            show_404();
        }

        $fuel_card = $this->Fuel_cards_model->find($id);
        if (!$fuel_card) {
            show_404();
        }

        $view_data = [
            'page_title' => 'Modifier la Carte Carburant',
            'fuel_card' => $fuel_card,
            'vehicles' => $this->Vehicles_model->get_available_vehicles(),
            'chauffeurs' => $this->Chauffeurs_model->get_active_chauffeurs(),
            'edit_mode' => true
        ];

        if ($this->request->getMethod() === 'POST') {
            return $this->_process_fuel_card_form($id);
        }

        return $this->template->rander("fuel_cards/add", $view_data);
    }

    /**
     * Traitement du formulaire d'ajout/modification
     */
    private function _process_fuel_card_form($id = null)
    {
        $data = [
            'numero_serie' => $this->request->getPost('numero_serie'),
            'type_carte' => $this->request->getPost('type_carte'),
            'vehicle_id' => $this->request->getPost('vehicle_id') ?: null,
            'chauffeur_id' => $this->request->getPost('chauffeur_id') ?: null,
            'solde_dotation' => $this->request->getPost('solde_dotation') ?: 0,
            'prix_litre' => $this->request->getPost('prix_litre') ?: null,
            'statut' => $this->request->getPost('statut'),
            'date_creation' => $this->request->getPost('date_creation'),
            'date_expiration' => $this->request->getPost('date_expiration') ?: null,
        ];

        // Validation
        if (!$this->Fuel_cards_model->validateData($data)) {
            $validation = \Config\Services::validation();
            $validation->setRules($this->Fuel_cards_model->validationRules);
            $validation->run($data);
            
            // Ajouter les erreurs personnalisées du modèle
            $customErrors = $this->Fuel_cards_model->getCustomValidationErrors();
            foreach ($customErrors as $field => $error) {
                $validation->setError($field, $error);
            }

            $view_data = [
                'page_title' => $id ? 'Modifier la Carte Carburant' : 'Ajouter une Carte Carburant',
                'fuel_card' => (object) $data,
                'vehicles' => $this->Vehicles_model->get_available_vehicles(),
                'chauffeurs' => $this->Chauffeurs_model->get_active_chauffeurs(),
                'validation' => $validation,
                'edit_mode' => !empty($id)
            ];

            return $this->template->rander("fuel_cards/add", $view_data);
        }

        // Enregistrement
        try {
            if ($id) {
                $this->Fuel_cards_model->update($id, $data);
                session()->setFlashdata('success', 'Carte carburant modifiée avec succès');
            } else {
                $this->Fuel_cards_model->insert($data);
                session()->setFlashdata('success', 'Carte carburant ajoutée avec succès');
            }
            
            return redirect()->to(site_url('fuel_cards'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Voir les détails d'une carte carburant
     */
    public function view($id = null)
    {
        if (!$id) {
            show_404();
        }

        $fuel_card = $this->Fuel_cards_model->get_card_details($id);
        if (!$fuel_card) {
            show_404();
        }

        $view_data = [
            'fuel_card' => $fuel_card,
            'page_title' => 'Carte ' . $fuel_card->numero_serie,
        ];

        return $this->template->rander("fuel_cards/view", $view_data);
    }

    /**
     * Supprimer une carte carburant
     */
    public function delete($id = null)
    {
        if (!$id) {
            show_404();
        }

        try {
            $fuel_card = $this->Fuel_cards_model->find($id);
            if (!$fuel_card) {
                session()->setFlashdata('error', 'Carte carburant non trouvée');
                return redirect()->to(site_url('fuel_cards'));
            }

            // Vérifier s'il y a des consommations liées
            $consumptionModel = model('App\Models\Fuel_consumption_model');
            $hasConsumptions = $consumptionModel->where('fuel_card_id', $id)->countAllResults();

            if ($hasConsumptions > 0) {
                session()->setFlashdata('error', 'Impossible de supprimer cette carte. Elle a des consommations enregistrées.');
                return redirect()->to(site_url('fuel_cards'));
            }

            $this->Fuel_cards_model->delete($id);
            session()->setFlashdata('success', 'Carte carburant supprimée avec succès');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }

        return redirect()->to(site_url('fuel_cards'));
    }

    /**
     * Assigner une carte à un véhicule
     */
    public function assign_to_vehicle($id = null)
    {
        if (!$id) {
            show_404();
        }

        $vehicle_id = $this->request->getPost('vehicle_id');
        
        try {
            if ($this->Fuel_cards_model->assign_to_vehicle($id, $vehicle_id)) {
                session()->setFlashdata('success', 'Carte assignée au véhicule avec succès');
            } else {
                session()->setFlashdata('error', 'Erreur lors de l\'assignation');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Erreur: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Assigner une carte à un chauffeur
     */
    public function assign_to_chauffeur($id = null)
    {
        if (!$id) {
            show_404();
        }

        $chauffeur_id = $this->request->getPost('chauffeur_id');
        
        try {
            if ($this->Fuel_cards_model->assign_to_chauffeur($id, $chauffeur_id)) {
                session()->setFlashdata('success', 'Carte assignée au chauffeur avec succès');
            } else {
                session()->setFlashdata('error', 'Erreur lors de l\'assignation');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Erreur: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Libérer une carte (désassigner)
     */
    public function unassign($id = null)
    {
        if (!$id) {
            show_404();
        }
        
        try {
            if ($this->Fuel_cards_model->unassign_card($id)) {
                session()->setFlashdata('success', 'Carte libérée avec succès');
            } else {
                session()->setFlashdata('error', 'Erreur lors de la libération');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Erreur: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Mettre à jour le solde d'une carte
     */
    public function update_balance($id = null)
    {
        if (!$id) {
            show_404();
        }

        $new_balance = $this->request->getPost('solde_dotation');
        
        try {
            if ($this->Fuel_cards_model->update_balance($id, $new_balance)) {
                session()->setFlashdata('success', 'Solde mis à jour avec succès');
            } else {
                session()->setFlashdata('error', 'Erreur lors de la mise à jour du solde');
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
        
        $fuel_cards = $this->Fuel_cards_model->search_fuel_cards($keyword, $filters);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $fuel_cards
        ]);
    }

    /**
     * API pour récupérer les cartes disponibles
     */
    public function available_api()
    {
        $cards = $this->Fuel_cards_model->get_available_cards();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $cards
        ]);
    }

    /**
     * Statistiques pour le dashboard
     */
    public function statistics()
    {
        $stats = $this->Fuel_cards_model->get_statistics();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Rapport des cartes expirant bientôt
     */
    public function expiring_cards()
    {
        $days = $this->request->getGet('days') ?? 30;
        $cards = $this->Fuel_cards_model->get_expiring_cards($days);
        
        $view_data = [
            'fuel_cards' => $cards,
            'days' => $days,
            'page_title' => 'Cartes Expirant - Prochains ' . $days . ' jours',
        ];

        return $this->template->rander("fuel_cards/expiring_cards", $view_data);
    }
}