<?php

namespace App\Controllers;

use App\Models\Locations_model;
use Dompdf\Dompdf;
use DateTime;  // ← AJOUTER CETTE LIGNE
use Exception; // ← ET CELLE-CI AUSSI
class Locations extends Security_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->init_permission_checker("locations");
    }

    public function index()
    {
        $this->access_only_allowed_members();
        
        $filters = [];
        if ($this->request->getGet()) {
            $filters = [
                'client_id' => $this->request->getGet('client_id'),
                'vehicle_id' => $this->request->getGet('vehicle_id'),
                'chauffeur_id' => $this->request->getGet('chauffeur_id'),
                'statut' => $this->request->getGet('statut'),
                'type_service' => $this->request->getGet('type_service'),
                'date_debut' => $this->request->getGet('date_debut'),
                'date_fin' => $this->request->getGet('date_fin'),
            ];
        }

        $view_data = [
            'locations' => $this->Locations_model->get_details(['filters' => $filters])->getResult(),
            'statistics' => $this->Locations_model->get_statistics(),
            'today_locations' => $this->Locations_model->get_today_locations(),
            'clients' => $this->Clients_model->get_details()->getResult(),
            'vehicles' => $this->Vehicles_model->get_details()->getResult(),
            'chauffeurs' => $this->Chauffeurs_model->get_details(['where' => ['statut' => 'actif']])->getResult(),
            'filters' => $filters,
            'page_title' => 'Gestion des Locations',
        ];
        return $this->template->rander("locations/index", $view_data);
    }
/**
 * Méthode save() avec correction du format des dates
 */
public function save()
{
    $this->access_only_allowed_members();
    
    $id = $this->request->getPost('id');
    
    // Récupération des données brutes
    $raw_data = [
        'titre' => $this->request->getPost('titre'),
        'client_id' => $this->request->getPost('client_id'),
        'date_debut' => $this->request->getPost('date_debut'),
        'date_fin' => $this->request->getPost('date_fin'),
        'lieu_depart' => $this->request->getPost('lieu_depart'),
        'lieu_arrivee' => $this->request->getPost('lieu_arrivee'),
        'type_service' => $this->request->getPost('type_service'),
        'statut' => $this->request->getPost('statut') ?: 'en_attente',
        'vehicle_id' => $this->request->getPost('vehicle_id') ?: null,
        'chauffeur_id' => $this->request->getPost('chauffeur_id') ?: null,
        'prix_total' => $this->request->getPost('prix_total') ?: null,
        'description' => $this->request->getPost('description'),
        'observations' => $this->request->getPost('observations'),
    ];
    
    // CORRECTION DES FORMATS DE DATES
    $data = $raw_data;
    
    // Convertir les dates au bon format
    try {
        // Format attendu par votre input HTML5 : "2024-08-12T14:30"
        // Format requis par la DB : "2024-08-12 14:30:00"
        
        if (!empty($data['date_debut'])) {
            // Si c'est le format datetime-local HTML5
            if (strpos($data['date_debut'], 'T') !== false) {
                $data['date_debut'] = str_replace('T', ' ', $data['date_debut']) . ':00';
            }
            // Vérifier que c'est un format valide
            $date_test = DateTime::createFromFormat('Y-m-d H:i:s', $data['date_debut']);
            if (!$date_test) {
                // Essayer d'autres formats
                $date_test = DateTime::createFromFormat('Y-m-d H:i', $data['date_debut']);
                if ($date_test) {
                    $data['date_debut'] = $date_test->format('Y-m-d H:i:s');
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Format de date de début invalide. Format attendu: YYYY-MM-DD HH:MM'
                    ]);
                    return;
                }
            }
        }
        
        if (!empty($data['date_fin'])) {
            // Si c'est le format datetime-local HTML5
            if (strpos($data['date_fin'], 'T') !== false) {
                $data['date_fin'] = str_replace('T', ' ', $data['date_fin']) . ':00';
            }
            // Vérifier que c'est un format valide
            $date_test = DateTime::createFromFormat('Y-m-d H:i:s', $data['date_fin']);
            if (!$date_test) {
                // Essayer d'autres formats
                $date_test = DateTime::createFromFormat('Y-m-d H:i', $data['date_fin']);
                if ($date_test) {
                    $data['date_fin'] = $date_test->format('Y-m-d H:i:s');
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Format de date de fin invalide. Format attendu: YYYY-MM-DD HH:MM'
                    ]);
                    return;
                }
            }
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erreur de conversion des dates: ' . $e->getMessage()
        ]);
        return;
    }
    
    // Ajouter created_by si c'est une insertion
    if (!$id) {
        $data['created_by'] = $this->login_user->id;
    }
    
    // Validation basique
    if (empty($data['titre']) || empty($data['client_id']) || empty($data['date_debut']) || empty($data['date_fin'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Veuillez remplir tous les champs obligatoires (titre, client, dates)'
        ]);
        return;
    }
    
    // Validation des dates : fin après début
    try {
        $dateDebut = new DateTime($data['date_debut']);
        $dateFin = new DateTime($data['date_fin']);
        
        if ($dateFin <= $dateDebut) {
            echo json_encode([
                'success' => false,
                'message' => 'La date de fin doit être après la date de début'
            ]);
            return;
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erreur de validation des dates: ' . $e->getMessage()
        ]);
        return;
    }
    
    try {
        // Sauvegarde
        if ($id) {
            // Mise à jour
            $data['id'] = $id;
            $result = $this->Locations_model->save($data);
            $actual_id = $id;
        } else {
            // Insertion
            $result = $this->Locations_model->save($data);
            $actual_id = $this->Locations_model->getInsertID();
        }
        
        if ($result && $actual_id) {
            // Récupérer les données complètes pour la réponse
            try {
                $location_info = $this->Locations_model->get_details(['id' => $actual_id])->getRow();
                
                echo json_encode([
                    'success' => true,
                    'data' => $location_info,
                    'id' => (int)$actual_id,  // ID numérique
                    'message' => $id ? 'Location mise à jour avec succès' : 'Location créée avec succès'
                ]);
            } catch (Exception $e) {
                // Si on ne peut pas récupérer les détails, retourner quand même le succès
                echo json_encode([
                    'success' => true,
                    'id' => (int)$actual_id,
                    'message' => $id ? 'Location mise à jour avec succès' : 'Location créée avec succès'
                ]);
            }
        } else {
            // Récupérer les erreurs du modèle
            $errors = $this->Locations_model->errors();
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la sauvegarde: ' . implode(', ', $errors)
            ]);
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erreur système: ' . $e->getMessage()
        ]);
    }
}
  public function view($id = 0)
{
    $this->access_only_allowed_members();
    
    if ($id) {
        // Utiliser get_details() selon vos conventions
        $location_info = $this->Locations_model->get_details(['id' => $id])->getRow();
        
        if ($location_info) {
            $view_data = [
                'location_info' => $location_info,
                'location' => $location_info,  // Ajouter cette ligne pour compatibilité avec la vue
                'page_title' => 'Détails Location #' . $id
            ];
            
            return $this->template->rander("locations/view", $view_data);
        } else {
            show_404();
        }
    } else {
        show_404();
    }
}
public function edit($id = 0)
{
    $this->access_only_allowed_members();
    
    if ($id) {
        $location_info = $this->Locations_model->get_details(['id' => $id])->getRow();
        
        if ($location_info) {
            $view_data = [
                'location' => $location_info,
                'location_info' => $location_info,
                'edit_mode' => true,
                'clients' => $this->Clients_model->get_details()->getResult(),
                'vehicles' => $this->Vehicles_model->get_details()->getResult(),
                'chauffeurs' => $this->Chauffeurs_model->get_details(['where' => ['statut' => 'actif']])->getResult(),
                'page_title' => 'Modifier Location #' . $id
            ];
            
            return $this->template->rander("locations/add", $view_data);
        } else {
            show_404();
        }
    } else {
        show_404();
    }
}
/**
 * Méthode add() pour le formulaire d'ajout
 */
public function add()
{
    $this->access_only_allowed_members();
    
    $view_data = [
        'edit_mode' => false,
        'clients' => $this->Clients_model->get_details()->getResult(),
        'vehicles' => $this->Vehicles_model->get_details()->getResult(),
        'chauffeurs' => $this->Chauffeurs_model->get_details(['where' => ['statut' => 'actif']])->getResult(),
        'page_title' => 'Nouvelle Location'
    ];
    
    return $this->template->rander("locations/add", $view_data);
}

    public function delete($id = 0)
    {
        $this->access_only_allowed_members();
        
        if ($this->Locations_model->delete($id)) {
            echo json_encode(['success' => true, 'message' => app_lang('record_deleted')]);
        } else {
            echo json_encode(['success' => false, 'message' => app_lang('record_cannot_be_deleted')]);
        }
    }

    public function modal_form()
    {
        $this->access_only_allowed_members();
        
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Locations_model->get_details(['id' => $id])->getRow();
        $view_data['clients'] = $this->Clients_model->get_details()->getResult();
        $view_data['vehicles'] = $this->Vehicles_model->get_details()->getResult();
        $view_data['chauffeurs'] = $this->Chauffeurs_model->get_details(['where' => ['statut' => 'actif']])->getResult();
        
        return $this->template->view("locations/modal_form", $view_data);
    }

    public function check_availability()
    {
        $this->access_only_allowed_members();
        
        $date_debut = $this->request->getPost('date_debut');
        $date_fin = $this->request->getPost('date_fin');
        $exclude_id = $this->request->getPost('exclude_id');
        
        if ($date_debut && $date_fin) {
            $available_vehicles = $this->Locations_model->getAvailableVehicles($date_debut, $date_fin, $exclude_id);
            $available_chauffeurs = $this->Locations_model->getAvailableChauffeurs($date_debut, $date_fin, $exclude_id);
            
            echo json_encode([
                'success' => true,
                'vehicles' => $available_vehicles,
                'chauffeurs' => $available_chauffeurs
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function change_status($id = 0)
    {
        $this->access_only_allowed_members();
        
        $new_status = $this->request->getPost('statut');
        
        if ($this->Locations_model->changeStatus($id, $new_status)) {
            echo json_encode(['success' => true, 'message' => 'Statut mis à jour']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
        }
    }

    public function download_pdf($id = 0)
    {
        $this->access_only_allowed_members();
        
        if (!$id) {
            show_404();
        }

        $location_info = $this->Locations_model->get_details(['id' => $id])->getRow();
        if (!$location_info) {
            show_404();
        }
        
        $view_data['location_info'] = $location_info;

        ob_end_clean();

        $dompdf = new Dompdf();
        $html = view('locations/pdf_template', $view_data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $dompdf->stream("Location-" . $location_info->id . ".pdf", array("Attachment" => 0));
        
        exit();
    }

    // Méthodes complémentaires selon vos besoins
    public function calendar()
    {
        $this->access_only_allowed_members();
        
        $view_data['page_title'] = 'Planning des Locations';
        return $this->template->rander("locations/calendar", $view_data);
    }

    public function get_calendar_data()
    {
        $this->access_only_allowed_members();
        
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');
        
        $events = $this->Locations_model->getCalendarData($start, $end);
        echo json_encode($events);
    }
}