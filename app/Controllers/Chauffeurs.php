<?php

namespace App\Controllers;

use App\Controllers\Security_Controller;
use App\Models\Chauffeurs_model;
use Exception;
use DateTime;

class Chauffeurs extends Security_Controller
{
    public $Chauffeurs_model;

    public function __construct()
    {
        parent::__construct();
        $this->init_permission_checker("chauffeur");
        $this->Chauffeurs_model = new Chauffeurs_model();
    }

    /**
     * Page principale - Liste des chauffeurs (style direct)
     */
    public function index()
    {
        $this->access_only_allowed_members();

        // Charger directement les chauffeurs
        $view_data['chauffeurs'] = $this->Chauffeurs_model->get_details()->getResult();
        
        // Statistiques
        $view_data['statistics'] = $this->get_statistics();

        return $this->template->rander("chauffeurs/index", $view_data);
    }

    /**
     * Récupérer les statistiques
     */
    private function get_statistics()
    {
        $db = \Config\Database::connect();
        
        return $db->query("
            SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN statut = 'actif' THEN 1 END) as actifs,
                COUNT(CASE WHEN statut = 'inactif' THEN 1 END) as inactifs,
                COUNT(CASE WHEN statut = 'suspendu' THEN 1 END) as suspendus
            FROM rise_chauffeurs 
            WHERE deleted = 0
        ")->getRow();
    }

    /**
     * Modal d'ajout
     */
    public function modal_add()
    {
        $this->access_only_allowed_members();
        
        $view_data = $this->_prepare_form_data();
        $view_data['model_info'] = $this->_get_empty_model();
        
        return $this->template->view('chauffeurs/modal_form', $view_data);
    }

    /**
     * Modal de modification
     */
    public function modal_form()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');
        
        $chauffeur = $this->Chauffeurs_model->get_details(['id' => $id])->getRow();
        if (!$chauffeur) {
            echo json_encode(["success" => false, 'message' => 'Chauffeur non trouvé']);
            return;
        }
        
        $view_data = $this->_prepare_form_data();
        $view_data['model_info'] = $chauffeur;
        
        return $this->template->view('chauffeurs/modal_form', $view_data);
    }

    /**
     * Vue détaillée d'un chauffeur
     */
    public function view($id = null)
    {
        if (!$id) {
            show_404();
        }

        $this->access_only_allowed_members();

        $chauffeur = $this->Chauffeurs_model->get_details(['id' => $id])->getRow();
        if (!$chauffeur) {
            show_404();
        }

        // Récupérer les statistiques du chauffeur
        $db = \Config\Database::connect();
        
        $stats = $db->query("
            SELECT 
                COUNT(*) as total_locations,
                COUNT(CASE WHEN statut = 'terminee' THEN 1 END) as locations_terminees,
                COUNT(CASE WHEN statut IN ('confirmee', 'en_cours') THEN 1 END) as locations_actives,
                COALESCE(SUM(prix_total), 0) as revenus_generes
            FROM rise_locations 
            WHERE chauffeur_id = ? AND deleted = 0
        ", [$id])->getRow();

        // Récupérer les dernières locations
        $recent_locations = $db->query("
            SELECT l.*, c.company_name, c.first_name, v.marque, v.modele, v.immatriculation
            FROM rise_locations l
            LEFT JOIN rise_clients c ON l.client_id = c.id
            LEFT JOIN rise_vehicules v ON l.vehicule_id = v.id
            WHERE l.chauffeur_id = ? AND l.deleted = 0
            ORDER BY l.created_at DESC
            LIMIT 10
        ", [$id])->getResult();

        $view_data = [
            'chauffeur' => $chauffeur,
            'stats' => $stats,
            'recent_locations' => $recent_locations,
            'page_title' => $chauffeur->prenom . ' ' . $chauffeur->nom,
        ];

        return $this->template->rander("chauffeurs/view", $view_data);
    }

    /**
     * Sauvegarde (ajout/modification)
     */
   
public function save()
{
    $this->access_only_allowed_members();
    
    // DEBUG - Ajoute ceci temporairement
    log_message('debug', 'Chauffeurs::save() appelée');
    log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
    
    try {
        // Validation des données
        $this->validate_submitted_data(array(
            "nom" => "required",
            "prenom" => "required",
            "telephone" => "required"
        ));

        $id = $this->request->getPost('id');
        
        $data = array(
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            // ... reste des données
        );

        log_message('debug', 'Data to save: ' . json_encode($data));
        
        if ($id) {
            $result = $this->Chauffeurs_model->update($id, $data);
            log_message('debug', 'Update result: ' . ($result ? 'success' : 'failed'));
        } else {
            $id = $this->Chauffeurs_model->insert($data);
            log_message('debug', 'Insert result: ' . $id);
        }

        echo json_encode(array(
            "success" => true, 
            "id" => (int)$id,
            "message" => app_lang('record_saved')
        ));
        
    } catch (Exception $e) {
        log_message('error', 'Chauffeurs::save() error: ' . $e->getMessage());
        echo json_encode(array("success" => false, "message" => $e->getMessage()));
    }
}
    /**
     * Suppression (soft delete)
     */
    public function delete()
    {
        $this->access_only_allowed_members();
        $id = $this->request->getPost('id');
        
        if ($this->request->getPost('undo')) {
            // Restaurer un enregistrement supprimé
            $result = $this->Chauffeurs_model->db->table('rise_chauffeurs')
                        ->where('id', $id)
                        ->update(['deleted' => 0]);
            
            if ($result) {
                echo json_encode(array("success" => true, "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
            }
        } else {
            // Vérifier s'il a des locations actives
            $db = \Config\Database::connect();
            $activeLocations = $db->query("SELECT COUNT(*) as count FROM rise_locations WHERE chauffeur_id = ? AND statut IN ('confirmee', 'en_cours') AND deleted = 0", [$id])->getRow();
            
            if ($activeLocations->count > 0) {
                echo json_encode(array("success" => false, "message" => "Impossible de supprimer ce chauffeur. Il a des locations actives."));
                return;
            }

            // Supprimer (soft delete)
            $result = $this->Chauffeurs_model->db->table('rise_chauffeurs')
                        ->where('id', $id)
                        ->update(['deleted' => 1]);
            
            if ($result) {
                echo json_encode(array("success" => true, "message" => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, "message" => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    /**
     * Changer le statut d'un chauffeur
     */
    public function change_status()
    {
        $this->access_only_allowed_members();

        $id = $this->request->getPost('id');
        $new_status = $this->request->getPost('statut');
        
        try {
            $result = $this->Chauffeurs_model->update($id, ['statut' => $new_status]);
            
            if ($result) {
                echo json_encode(array("success" => true, "message" => app_lang('record_saved')));
            } else {
                echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
            }
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
        }
    }

    /**
     * API pour la recherche AJAX
     */
    public function search_api()
    {
        $this->access_only_allowed_members();

        $keyword = $this->request->getGet('q');
        $options = array('search' => $keyword);
        
        $chauffeurs = $this->Chauffeurs_model->get_details($options)->getResult();
        
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
        $this->access_only_allowed_members();

        $options = array('statut' => 'actif');
        $chauffeurs = $this->Chauffeurs_model->get_details($options)->getResult();
        
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
        $this->access_only_allowed_members();

        $stats = $this->get_statistics();
        
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
        $this->access_only_allowed_members();

        $days = $this->request->getGet('days') ?? 30;
        
        $db = \Config\Database::connect();
        $chauffeurs = $db->query("
            SELECT *
            FROM rise_chauffeurs 
            WHERE deleted = 0 
            AND date_expiration_permis IS NOT NULL 
            AND date_expiration_permis BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
            ORDER BY date_expiration_permis ASC
        ", [$days])->getResult();
        
        $view_data = [
            'chauffeurs' => $chauffeurs,
            'days' => $days,
            'page_title' => 'Permis Expirant - Prochains ' . $days . ' jours',
        ];

        return $this->template->rander("chauffeurs/expiring_licenses", $view_data);
    }

    /**
     * Export CSV
     */
    public function export_csv()
    {
        $this->access_only_allowed_members();

        $chauffeurs = $this->Chauffeurs_model->get_details()->getResult();
        
        $filename = 'chauffeurs_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // En-têtes CSV
        fputcsv($output, [
            'ID', 'Nom', 'Prénom', 'CNIE', 'Téléphone', 'Email', 
            'Adresse', 'Date Naissance', 'Date Embauche', 'N° Permis', 
            'Expiration Permis', 'Catégorie', 'Salaire Base', 'Statut'
        ]);
        
        // Données
        foreach ($chauffeurs as $chauffeur) {
            fputcsv($output, [
                $chauffeur->id,
                $chauffeur->nom,
                $chauffeur->prenom,
                $chauffeur->cnie,
                $chauffeur->telephone,
                $chauffeur->email,
                $chauffeur->adresse,
                $chauffeur->date_naissance,
                $chauffeur->date_embauche,
                $chauffeur->numero_permis,
                $chauffeur->date_expiration_permis,
                $chauffeur->categorie_permis,
                $chauffeur->salaire_base,
                $chauffeur->statut
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * Prépare les données pour les formulaires
     */
    private function _prepare_form_data()
    {
        return array(
            'categories_permis' => array(
                'D' => 'D - Transport de personnes',
                'B' => 'B - Véhicules légers', 
                'C' => 'C - Poids lourds',
                'E' => 'E - Avec remorque'
            ),
            'statuts' => array(
                'actif' => 'Actif',
                'inactif' => 'Inactif',
                'suspendu' => 'Suspendu'
            )
        );
    }

    /**
     * Retourne un modèle vide pour les nouveaux chauffeurs
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

    /**
     * Formatage des dates
     */
    private function _format_dates($data)
    {
        $date_fields = ['date_naissance', 'date_embauche', 'date_expiration_permis'];
        
        foreach ($date_fields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                // Conversion HTML5 datetime-local vers MySQL datetime si nécessaire
                if (strpos($data[$field], 'T') !== false) {
                    $data[$field] = str_replace('T', ' ', $data[$field]);
                }
            }
        }
        
        return $data;
    }
}