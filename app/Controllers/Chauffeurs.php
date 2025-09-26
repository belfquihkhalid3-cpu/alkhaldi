<?php

namespace App\Controllers;

use App\Controllers\Security_Controller;
use Exception;

class Chauffeurs extends Security_Controller
{
    public $Chauffeurs_model;

    public function __construct()
    {
        parent::__construct();
        $this->init_permission_checker("chauffeur");
        
        // Charger le modèle
        $this->Chauffeurs_model = model('App\Models\Chauffeurs_model');
    }

    /**
     * Page principale - Liste des chauffeurs
     */
    public function index()
    {
        $this->access_only_allowed_members();

        try {
            // Charger directement les chauffeurs
            $view_data['chauffeurs'] = $this->Chauffeurs_model->get_details()->getResult();
            
            // Statistiques
            $view_data['statistics'] = $this->Chauffeurs_model->get_statistics();

            return $this->template->rander("chauffeurs/index", $view_data);
            
        } catch (Exception $e) {
            log_message('error', 'Chauffeurs index error: ' . $e->getMessage());
            
        }
    }

    /**
     * Modal d'ajout
     */
    public function modal_add()
    {
        $this->access_only_allowed_members();
        
        $view_data['model_info'] = $this->_get_empty_model();
        
        return $this->template->view('chauffeurs/modal_form', $view_data);
    }

    /**
     * Modal de modification
     */
    public function modal_form()
    {
        $this->access_only_allowed_members();
        
        $id = $this->request->getPost('id');
        if (!$id) {
            echo json_encode(["success" => false, 'message' => 'ID manquant']);
            return;
        }
        
        try {
            $chauffeur = $this->Chauffeurs_model->get_chauffeur_details($id);
            if (!$chauffeur) {
                echo json_encode(["success" => false, 'message' => 'Chauffeur non trouvé']);
                return;
            }
            
            $view_data['model_info'] = $chauffeur;
            
            return $this->template->view('chauffeurs/modal_form', $view_data);
            
        } catch (Exception $e) {
            echo json_encode(["success" => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Sauvegarde (ajout/modification)
     */
 public function save()
{
    $this->access_only_allowed_members();
    
    try {
        $id = $this->request->getPost('id');
        
        $data = array(
            'nom' => trim($this->request->getPost('nom')),
            'prenom' => trim($this->request->getPost('prenom')),
            'cnie' => trim($this->request->getPost('cnie')),
            'telephone' => trim($this->request->getPost('telephone')),
            'telephone_urgence' => trim($this->request->getPost('telephone_urgence')),
            'email' => trim($this->request->getPost('email')),
            'adresse' => trim($this->request->getPost('adresse')),
            'date_naissance' => $this->request->getPost('date_naissance') ?: null,
            'date_embauche' => $this->request->getPost('date_embauche') ?: date('Y-m-d'),
            'numero_permis' => trim($this->request->getPost('numero_permis')),
            'date_expiration_permis' => $this->request->getPost('date_expiration_permis') ?: null,
            'categorie_permis' => $this->request->getPost('categorie_permis'),
            'salaire_base' => $this->request->getPost('salaire_base') ?: null,
            'statut' => $this->request->getPost('statut') ?: 'actif',
            'observations' => trim($this->request->getPost('observations'))
        );

        // Nettoyer les chaînes vides
        foreach ($data as $key => $value) {
            if ($value === '' || $value === null) {
                $data[$key] = null;
            }
        }

        // Validation
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['telephone'])) {
            throw new Exception('Nom, prénom et téléphone sont obligatoires');
        }

        // DEBUG - Log les données avant sauvegarde
        log_message('debug', 'Data to insert: ' . json_encode($data));

        if ($id && !empty($id)) {
            // Modification
            $result = $this->Chauffeurs_model->update($id, $data);
            $actual_id = $id;
        } else {
            // Ajout - avec debug détaillé
            try {
                $actual_id = $this->Chauffeurs_model->insert($data);
                log_message('debug', 'Insert result: ' . ($actual_id ?: 'FAILED'));
                
                if (!$actual_id) {
                    // Si l'insert échoue, récupérer l'erreur DB
                    $db = \Config\Database::connect();
                    $error = $db->error();
                    log_message('error', 'DB Error: ' . json_encode($error));
                    throw new Exception('Erreur DB: ' . $error['message']);
                }
            } catch (\Exception $e) {
                log_message('error', 'Insert exception: ' . $e->getMessage());
                throw new Exception('Erreur lors de l\'ajout: ' . $e->getMessage());
            }
        }

        echo json_encode(array(
            "success" => true, 
            "id" => (int)$actual_id,
            "message" => $id ? 'Chauffeur modifié avec succès' : 'Chauffeur ajouté avec succès'
        ));
        
    } catch (Exception $e) {
        log_message('error', 'Chauffeurs save error: ' . $e->getMessage());
        echo json_encode(array("success" => false, "message" => $e->getMessage()));
    }
}

    /**
     * Suppression
     */
    public function delete()
    {
        $this->access_only_allowed_members();
        
        try {
            $id = $this->request->getPost('id');
            
            if (!$id) {
                throw new Exception('ID manquant');
            }

            // Vérifier si le chauffeur peut être supprimé
            if (!$this->Chauffeurs_model->can_delete($id)) {
                throw new Exception('Impossible de supprimer ce chauffeur. Il a des locations actives.');
            }

            if ($this->request->getPost('undo')) {
                // Restaurer
                $result = $this->Chauffeurs_model->update($id, ['deleted' => 0]);
                $message = 'Chauffeur restauré';
            } else {
                // Supprimer (soft delete)
                $result = $this->Chauffeurs_model->update($id, ['deleted' => 1]);
                $message = 'Chauffeur supprimé';
            }
            
            if ($result) {
                echo json_encode(array("success" => true, "message" => $message));
            } else {
                throw new Exception('Erreur lors de la suppression');
            }
            
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
        }
    }

    /**
     * Changer le statut
     */
    public function change_status()
    {
        $this->access_only_allowed_members();

        try {
            $id = $this->request->getPost('id');
            $new_status = $this->request->getPost('statut');
            
            if (!$id || !$new_status) {
                throw new Exception('Paramètres manquants');
            }
            
            if (!in_array($new_status, ['actif', 'inactif', 'suspendu'])) {
                throw new Exception('Statut invalide');
            }
            
            $result = $this->Chauffeurs_model->update_status($id, $new_status);
            
            if ($result) {
                echo json_encode(array("success" => true, "message" => 'Statut mis à jour'));
            } else {
                throw new Exception('Erreur lors de la mise à jour');
            }
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
        }
    }

    /**
     * Vue détaillée
     */
    public function view($id = null)
    {
        if (!$id) {
            show_404();
        }

        $this->access_only_allowed_members();

        try {
            $chauffeur = $this->Chauffeurs_model->get_chauffeur_details($id);
            if (!$chauffeur) {
                show_404();
            }

            // Récupérer les performances
            $performance = $this->Chauffeurs_model->get_performance($id);

            $view_data = [
                'chauffeur' => $chauffeur,
                'performance' => $performance,
                'page_title' => $chauffeur->prenom . ' ' . $chauffeur->nom,
            ];

            return $this->template->rander("chauffeurs/view", $view_data);
            
        } catch (Exception $e) {
            log_message('error', 'Chauffeurs view error: ' . $e->getMessage());
          
        }
    }

    /**
     * Retourne un modèle vide
     */
    private function _get_empty_model()
    {
        $model_info = new \stdClass();
        $model_info->id = "";
        $model_info->nom = "";
        $model_info->prenom = "";
        $model_info->cnie = "";
        $model_info->telephone = "";
        $model_info->telephone_urgence = "";
        $model_info->email = "";
        $model_info->adresse = "";
        $model_info->date_naissance = "";
        $model_info->date_embauche = "";
        $model_info->numero_permis = "";
        $model_info->date_expiration_permis = "";
        $model_info->categorie_permis = "";
        $model_info->salaire_base = "";
        $model_info->statut = "actif";
        $model_info->observations = "";
        
        return $model_info;
    }
}