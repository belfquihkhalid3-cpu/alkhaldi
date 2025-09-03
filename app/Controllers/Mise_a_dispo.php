<?php

namespace App\Controllers;

use DateTime;
use Exception;

class Mise_a_dispo extends Security_Controller
{
    protected $MiseADispo_model;

    public function __construct()
    {
        parent::__construct();
        $this->MiseADispo_model = model('App\Models\MiseADispo_model');
        
        $this->check_module_availability("module_mise_a_dispo");
        $this->init_permission_checker("mise_a_dispo");
    }

    /**
     * Page principale avec liste et statistiques
     */
    public function index()
    {
        $this->access_only_allowed_members();
        
        // Statistiques
        $view_data['statistics'] = $this->MiseADispo_model->get_statistics();
        
        // Dropdowns pour filtres
        $view_data['statuts_dropdown'] = [
            '' => '- Tous les statuts -',
            'demande' => 'Demandé',
            'confirme' => 'Confirmé',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'facture' => 'Facturé',
            'annule' => 'Annulé'
        ];
        
        $view_data['types_dropdown'] = [
            '' => '- Tous les types -',
            'chauffeur_seul' => 'Chauffeur seul',
            'vehicule_seul' => 'Véhicule seul',
            'chauffeur_vehicule' => 'Chauffeur + Véhicule'
        ];
        
        return $this->template->rander("mise_a_dispo/index", $view_data);
    }

    /**
     * Modal d'ajout
     */
    public function modal_add()
    {
        $this->access_only_allowed_members();
        
        $view_data = $this->_prepare_form_data();
        return $this->template->view('mise_a_dispo/modal_add', $view_data);
    }

    /**
     * Modal de modification
     */
    public function modal_form()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');
        
        $mise_a_dispo = $this->MiseADispo_model->get_details(['id' => $id])->getRow();
        if (!$mise_a_dispo) {
            echo json_encode(["success" => false, 'message' => 'Mise à disposition non trouvée']);
            return;
        }
        
        $view_data = $this->_prepare_form_data();
        $view_data['model_info'] = $mise_a_dispo;
        
        return $this->template->view('mise_a_dispo/modal_form', $view_data);
    }

    /**
     * Page d'ajout/modification
     */
    public function add($id = 0)
    {
        $this->access_only_allowed_members();
        
        $view_data = $this->_prepare_form_data();
        
        if ($id) {
            $mise_a_dispo = $this->MiseADispo_model->get_details(['id' => $id])->getRow();
            if (!$mise_a_dispo) {
                return redirect()->to(get_uri('mise_a_dispo'))->with('error', 'Mise à disposition non trouvée');
            }
            $view_data['model_info'] = $mise_a_dispo;
        }
        
        return $this->template->rander("mise_a_dispo/add", $view_data);
    }

    /**
     * Vue détaillée
     */
    public function view($id = 0)
    {
        $this->access_only_allowed_members();
        
        if (!$id) {
            return redirect()->to(get_uri('mise_a_dispo'))->with('error', 'ID manquant');
        }
        
        $mise_a_dispo = $this->MiseADispo_model->get_details(['id' => $id])->getRow();
        if (!$mise_a_dispo) {
            return redirect()->to(get_uri('mise_a_dispo'))->with('error', 'Mise à disposition non trouvée');
        }
        
        $view_data['mise_a_dispo'] = $mise_a_dispo;
        $view_data['historique'] = $this->MiseADispo_model->get_historique_statut($id);
        
        // Données pour l'affichage
        $view_data['statut_classes'] = [
            'demande' => 'bg-warning',
            'confirme' => 'bg-primary',
            'en_cours' => 'bg-info',
            'termine' => 'bg-success',
            'facture' => 'bg-success',
            'annule' => 'bg-danger'
        ];
        
        $view_data['statut_text'] = [
            'demande' => 'Demandé',
            'confirme' => 'Confirmé',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'facture' => 'Facturé',
            'annule' => 'Annulé'
        ];
        
        $view_data['type_text'] = [
            'chauffeur_seul' => 'Chauffeur seul',
            'vehicule_seul' => 'Véhicule seul',
            'chauffeur_vehicule' => 'Chauffeur + Véhicule'
        ];
        
        return $this->template->rander("mise_a_dispo/view", $view_data);
    }

    /**
     * Sauvegarde (ajout/modification)
     */
    public function save()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data([
            "nom_client" => "required",
            "type_service" => "required",
            "date_debut" => "required",
            "date_fin" => "required",
            "lieu_prise_en_charge" => "required"
        ]);

        try {
            $id = $this->request->getPost('id');
            $data = [
                'client_id' => $this->request->getPost('client_id') ?: null,
                'nom_client' => $this->request->getPost('nom_client'),
                'telephone_client' => $this->request->getPost('telephone_client'),
                'email_client' => $this->request->getPost('email_client'),
                'hotel_partenaire' => $this->request->getPost('hotel_partenaire'),
                'type_service' => $this->request->getPost('type_service'),
                'date_debut' => $this->request->getPost('date_debut'),
                'date_fin' => $this->request->getPost('date_fin'),
                'chauffeur_id' => $this->request->getPost('chauffeur_id') ?: null,
                'vehicle_id' => $this->request->getPost('vehicle_id') ?: null,
                'tarif_type' => $this->request->getPost('tarif_type') ?: 'journalier',
                'prix_unitaire' => $this->request->getPost('prix_unitaire') ?: null,
                'nombre_unites' => $this->request->getPost('nombre_unites') ?: null,
                'prix_total' => $this->request->getPost('prix_total') ?: null,
                'devise' => $this->request->getPost('devise') ?: 'MAD',
                'lieu_prise_en_charge' => $this->request->getPost('lieu_prise_en_charge'),
                'destination_principale' => $this->request->getPost('destination_principale'),
                'programme_detaille' => $this->request->getPost('programme_detaille'),
                'instructions_particulieres' => $this->request->getPost('instructions_particulieres'),
                'statut' => $this->request->getPost('statut') ?: 'demande'
            ];
            
            // Formatage des dates
            $data = $this->MiseADispo_model->formatDates($data);
            
            // Calculs automatiques
            $data = $this->MiseADispo_model->calculateDuration($data);
            $data = $this->MiseADispo_model->calculateTotal($data);
            
            // Vérification de disponibilité
            $chauffeur_id = $data['chauffeur_id'];
            $vehicle_id = $data['vehicle_id'];
            
            if ($chauffeur_id || $vehicle_id) {
                $disponible = $this->MiseADispo_model->checkDisponibilite(
                    $data['date_debut'], 
                    $data['date_fin'], 
                    $chauffeur_id, 
                    $vehicle_id, 
                    $id
                );
                
                if (!$disponible) {
                    echo json_encode([
                        "success" => false, 
                        'message' => 'Chauffeur ou véhicule non disponible pour cette période'
                    ]);
                    return;
                }
            }
            
            if ($id && $id > 0) {
                $data['id'] = $id;
            }

            $save_result = $this->MiseADispo_model->save($data);
            
            if ($save_result) {
                $actual_id = ($id && $id > 0) ? (int)$id : $this->MiseADispo_model->getInsertID();
                $mise_a_dispo_data = $this->MiseADispo_model->get_details(["id" => $actual_id])->getRow();
                
                echo json_encode([
                    "success" => true,
                    "data" => $this->_make_row($mise_a_dispo_data),
                    'id' => $actual_id,
                    'message' => 'Mise à disposition sauvegardée avec succès'
                ]);
            } else {
                $errors = $this->MiseADispo_model->errors();
                $error_msg = !empty($errors) ? implode(', ', $errors) : 'Erreur de sauvegarde';
                echo json_encode(["success" => false, 'message' => $error_msg]);
            }
            
        } catch (Exception $e) {
            echo json_encode(["success" => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Changement de statut
     */
    public function change_statut()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data([
            "id" => "required|numeric",
            "statut" => "required"
        ]);

        try {
            $id = $this->request->getPost('id');
            $statut = $this->request->getPost('statut');
            $commentaire = $this->request->getPost('commentaire') ?: '';
            
            // Données additionnelles selon le statut
            $data = ['statut' => $statut];
            
            if ($statut === 'en_cours') {
                if ($heure_debut = $this->request->getPost('heure_debut_reelle')) {
                    if (strpos($heure_debut, 'T') !== false) {
                        $data['heure_debut_reelle'] = str_replace('T', ' ', $heure_debut) . ':00';
                    }
                }
            }
            
            if ($statut === 'termine') {
                if ($heure_fin = $this->request->getPost('heure_fin_reelle')) {
                    if (strpos($heure_fin, 'T') !== false) {
                        $data['heure_fin_reelle'] = str_replace('T', ' ', $heure_fin) . ':00';
                    }
                }
                
                if ($kilometres = $this->request->getPost('kilometres_parcourus')) {
                    $data['kilometres_parcourus'] = intval($kilometres);
                }
                
                if ($notes = $this->request->getPost('notes_chauffeur')) {
                    $data['notes_chauffeur'] = $notes;
                }
            }
            
            if ($statut === 'facture') {
                if ($prix_final = $this->request->getPost('prix_total_final')) {
                    $data['prix_total'] = floatval($prix_final);
                }
            }
            
            // Mettre à jour
            $result = $this->MiseADispo_model->update($id, $data);
            
            if ($result) {
                // Enregistrer dans l'historique
                $this->MiseADispo_model->change_statut($id, $statut, $commentaire, $this->login_user->id);
                
                $mise_a_dispo_data = $this->MiseADispo_model->get_details(["id" => $id])->getRow();
                echo json_encode([
                    "success" => true,
                    "data" => $this->_make_row($mise_a_dispo_data),
                    'id' => (int)$id,
                    'message' => 'Statut mis à jour avec succès'
                ]);
            } else {
                echo json_encode(["success" => false, 'message' => 'Erreur lors du changement de statut']);
            }
            
        } catch (Exception $e) {
            echo json_encode(["success" => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Suppression
     */
// MÉTHODE delete() AVEC DEBUG pour identifier le problème

/**
 * Suppression avec debug
 */
public function delete()
{
    $this->access_only_allowed_members();
    
    try {
        $id = $this->request->getPost('id');
        
        if (!$id || !is_numeric($id)) {
            echo json_encode(["success" => false, 'message' => 'ID invalide']);
            return;
        }
        
        $id = (int)$id;
        
        // Debug: Vérifier l'enregistrement avant suppression
        $before = $this->MiseADispo_model->db->table('rise_mise_a_dispo')
                                             ->where('id', $id)
                                             ->get()
                                             ->getRow();
        
        if (!$before) {
            echo json_encode(["success" => false, 'message' => 'Enregistrement non trouvé']);
            return;
        }
        
        log_message('debug', 'DELETE: Avant suppression - ID=' . $id . ', deleted=' . $before->deleted);
        
        // Vérifier qu'on peut supprimer selon le statut
        if (in_array($before->statut, ['en_cours', 'facture'])) {
            echo json_encode([
                "success" => false, 
                'message' => 'Impossible de supprimer une mise à disposition en cours ou facturée'
            ]);
            return;
        }
        
        // MÉTHODE 1: Utiliser la méthode native du modèle
        $result = $this->MiseADispo_model->delete($id);
        
        // Vérifier que ça a marché
        $after = $this->MiseADispo_model->db->table('rise_mise_a_dispo')
                                            ->where('id', $id)
                                            ->get()
                                            ->getRow();
        
        log_message('debug', 'DELETE: Après suppression - ID=' . $id . ', deleted=' . $after->deleted . ', result=' . var_export($result, true));
        
        // Si la méthode native n'a pas marché, utiliser update manuel
        if ($result && $after->deleted == 0) {
            log_message('debug', 'DELETE: Méthode native n\'a pas marché, utilisation update manuel');
            $manual_result = $this->MiseADispo_model->update($id, ['deleted' => 1]);
            
            // Vérifier à nouveau
            $final_check = $this->MiseADispo_model->db->table('rise_mise_a_dispo')
                                                      ->where('id', $id)
                                                      ->get()
                                                      ->getRow();
            
            log_message('debug', 'DELETE: Après update manuel - ID=' . $id . ', deleted=' . $final_check->deleted);
            
            echo json_encode([
                "success" => $manual_result, 
                'message' => $manual_result ? 'Supprimé avec succès (méthode manuelle)' : 'Erreur de suppression'
            ]);
        } else {
            echo json_encode([
                "success" => $result && $after->deleted == 1, 
                'message' => ($result && $after->deleted == 1) ? 'Supprimé avec succès' : 'Erreur de suppression'
            ]);
        }
        
    } catch (Exception $e) {
        log_message('error', 'DELETE: Exception - ' . $e->getMessage());
        echo json_encode(["success" => false, 'message' => $e->getMessage()]);
    }
}


    /**
     * Données pour DataTable
     */
    public function list_data()
    {
        $this->access_only_allowed_members();
        
        $options = [];
        
        // Filtres
        if ($statut = $this->request->getPost('statut')) {
            $options['statut'] = $statut;
        }
        
        if ($type = $this->request->getPost('type_service')) {
            $options['type_service'] = $type;
        }
        
        if ($search = $this->request->getPost('search')) {
            $options['search'] = $search;
        }
        
        $list_data = $this->MiseADispo_model->get_details($options)->getResult();
        $result = [];
        
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        
        echo json_encode(["data" => $result]);
    }

    /**
     * Vérification de disponibilité AJAX
     */
    public function check_disponibilite()
    {
        $this->access_only_allowed_members();
        
        $date_debut = $this->request->getPost('date_debut');
        $date_fin = $this->request->getPost('date_fin');
        $chauffeur_id = $this->request->getPost('chauffeur_id');
        $vehicle_id = $this->request->getPost('vehicle_id');
        $exclude_id = $this->request->getPost('exclude_id');
        
        try {
            $disponible = $this->MiseADispo_model->checkDisponibilite(
                $date_debut, $date_fin, $chauffeur_id, $vehicle_id, $exclude_id
            );
            
            if ($disponible) {
                echo json_encode(["success" => true, 'message' => 'Disponible']);
            } else {
                // Récupérer les conflits
                $conflits = $this->MiseADispo_model->getConflitsPlanning($date_debut, $date_fin);
                $details_conflits = [];
                
                foreach ($conflits as $conflit) {
                    $details_conflits[] = [
                        'client' => $conflit->nom_client,
                        'date_debut' => $conflit->date_debut,
                        'date_fin' => $conflit->date_fin,
                        'statut' => $conflit->statut
                    ];
                }
                
                echo json_encode([
                    "success" => false, 
                    'message' => 'Non disponible pour cette période',
                    'conflits' => $details_conflits
                ]);
            }
            
        } catch (Exception $e) {
            echo json_encode(["success" => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Modals pour changement de statut
     */
    public function modal_confirmer()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');
        
        $view_data['mise_a_dispo_id'] = $id;
        $view_data['nouveau_statut'] = 'confirme';
        $view_data['action_title'] = 'Confirmer la mise à disposition';
        $view_data['action_class'] = 'btn-primary';
        
        return $this->template->view('mise_a_dispo/modal_change_statut', $view_data);
    }

    public function modal_demarrer()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');
        
        $view_data['mise_a_dispo_id'] = $id;
        $view_data['nouveau_statut'] = 'en_cours';
        $view_data['action_title'] = 'Démarrer la mise à disposition';
        $view_data['action_class'] = 'btn-info';
        
        return $this->template->view('mise_a_dispo/modal_change_statut', $view_data);
    }

    public function modal_terminer()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');
        
        $view_data['mise_a_dispo_id'] = $id;
        $view_data['nouveau_statut'] = 'termine';
        $view_data['action_title'] = 'Terminer la mise à disposition';
        $view_data['action_class'] = 'btn-success';
        
        return $this->template->view('mise_a_dispo/modal_change_statut', $view_data);
    }

    public function modal_facturer()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');
        
        $view_data['mise_a_dispo_id'] = $id;
        $view_data['nouveau_statut'] = 'facture';
        $view_data['action_title'] = 'Facturer la mise à disposition';
        $view_data['action_class'] = 'btn-success';
        
        return $this->template->view('mise_a_dispo/modal_change_statut', $view_data);
    }

    public function modal_annuler()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');
        
        $view_data['mise_a_dispo_id'] = $id;
        $view_data['nouveau_statut'] = 'annule';
        $view_data['action_title'] = 'Annuler la mise à disposition';
        $view_data['action_class'] = 'btn-danger';
        
        return $this->template->view('mise_a_dispo/modal_change_statut', $view_data);
    }

    /**
     * Prépare les données pour les formulaires
     */
    private function _prepare_form_data()
    {
        $view_data = [];
        
        // Clients
        $view_data['clients_dropdown'] = ["" => "- Nouveau client -"];
        try {
            if (class_exists('App\Models\Clients_model')) {
                $clients = model('App\Models\Clients_model')->get_details()->getResult();
                foreach ($clients as $client) {
                    $nom = $client->company_name ?: 'Client #' . $client->id;
                    $view_data['clients_dropdown'][$client->id] = $nom;
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Erreur chargement clients: ' . $e->getMessage());
        }
        
        // Véhicules
        $view_data['vehicles_dropdown'] = ["" => "- Sélectionner un véhicule -"];
        try {
            if (class_exists('App\Models\Vehicles_model')) {
                $vehicles = model('App\Models\Vehicles_model')->get_details()->getResult();
                foreach ($vehicles as $vehicle) {
                    $view_data['vehicles_dropdown'][$vehicle->id] = $vehicle->marque . " " . $vehicle->modele . " (" . $vehicle->numero_matricule . ")";
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Erreur chargement véhicules: ' . $e->getMessage());
        }
        
        // Chauffeurs
        $view_data['chauffeurs_dropdown'] = ["" => "- Sélectionner un chauffeur -"];
        try {
            if (class_exists('App\Models\Chauffeurs_model')) {
                $chauffeurs = model('App\Models\Chauffeurs_model')->get_details()->getResult();
                foreach ($chauffeurs as $chauffeur) {
                    $view_data['chauffeurs_dropdown'][$chauffeur->id] = $chauffeur->prenom . " " . $chauffeur->nom;
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Erreur chargement chauffeurs: ' . $e->getMessage());
        }
        
        return $view_data;
    }

    /**
     * Formate une ligne pour DataTable
     */
    private function _make_row($data)
    {
        // Badge du statut
        $statut_classes = [
            'demande' => 'bg-warning',
            'confirme' => 'bg-primary',
            'en_cours' => 'bg-info',
            'termine' => 'bg-success',
            'facture' => 'bg-success',
            'annule' => 'bg-danger'
        ];
        $statut_text = [
            'demande' => 'Demandé',
            'confirme' => 'Confirmé',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'facture' => 'Facturé',
            'annule' => 'Annulé'
        ];
        
        $statut_class = $statut_classes[$data->statut] ?? 'bg-secondary';
        $statut_label = $statut_text[$data->statut] ?? $data->statut;
        $statut = "<span class='badge $statut_class'>$statut_label</span>";

        // Type de service avec emojis
        $type_icons = [
            'chauffeur_seul' => '👨‍💼',
            'vehicule_seul' => '🚗',
            'chauffeur_vehicule' => '👨‍💼🚗'
        ];
        $type_labels = [
            'chauffeur_seul' => 'Chauffeur seul',
            'vehicule_seul' => 'Véhicule seul',
            'chauffeur_vehicule' => 'Chauffeur + Véhicule'
        ];
        
        $type_icon = $type_icons[$data->type_service] ?? '🎯';
        $type_label = $type_labels[$data->type_service] ?? $data->type_service;
        $type_service = "$type_icon $type_label";

        // Informations client
        $client_info = "<strong>" . htmlspecialchars($data->nom_client) . "</strong>";

        if ($data->telephone_client) {
            $client_info .= "<br><small class='text-muted'>📞 " . $data->telephone_client . "</small>";
        }

        if ($data->hotel_partenaire) {
            $client_info .= "<br><small class='text-info'>🏨 " . $data->hotel_partenaire . "</small>";
        }

        // Période
        $periode_info = "<strong>📅 " . format_to_date($data->date_debut, false) . "</strong>";
        $periode_info .= "<br><strong>🕐 " . date('H:i', strtotime($data->date_debut)) . "</strong>";
        $periode_info .= "<br><small class='text-muted'>jusqu'au</small>";
        $periode_info .= "<br>" . format_to_date($data->date_fin, false);
        $periode_info .= " <strong>" . date('H:i', strtotime($data->date_fin)) . "</strong>";
        
        if ($data->nombre_jours) {
            $jours_text = $data->nombre_jours > 1 ? 'jours' : 'jour';
            $periode_info .= "<br><small class='text-info'>⏱️ " . $data->nombre_jours . " $jours_text</small>";
        }

        // Assignation
        $assignation = "";
        if ($data->vehicle_name) {
            $assignation .= "<small>🚗 " . $data->vehicle_name . "</small>";
        }
        if ($data->chauffeur_name) {
            $assignation .= "<br><small>👨‍💼 " . $data->chauffeur_name . "</small>";
        }
        if (!$assignation) {
            $assignation = "<span class='text-muted'>Non assigné</span>";
        }

        // Prix
        $prix_display = "";
        if ($data->prix_total) {
            $prix_display = "<span class='text-success'><strong>💰 " . to_currency($data->prix_total) . "</strong></span>";
            
            if ($data->tarif_type && $data->prix_unitaire) {
                $unite_text = [
                    'horaire' => 'h',
                    'journalier' => 'j',
                    'forfait' => 'forfait'
                ][$data->tarif_type] ?? '';
                
                $prix_display .= "<br><small class='text-muted'>" . to_currency($data->prix_unitaire) . "/$unite_text";
                if ($data->nombre_unites) {
                    $prix_display .= " × " . $data->nombre_unites;
                }
                $prix_display .= "</small>";
            }
        } else {
            $prix_display = "<span class='text-muted'>Non défini</span>";
        }

        // Lieu
        $lieu_display = "<small class='text-primary'>📍 " . htmlspecialchars($data->lieu_prise_en_charge) . "</small>";
        if ($data->destination_principale) {
            $lieu_display .= "<br><small class='text-success'>🎯 " . htmlspecialchars($data->destination_principale) . "</small>";
        }

        // Actions selon le statut
        $options = "";
        
        // Voir détails
        $options .= anchor(get_uri("mise_a_dispo/view/" . $data->id), 
            "👁️ Voir", 
            ["class" => "btn btn-outline-info btn-sm", "title" => "Voir détails"]);
        
        // Modifier
        $options .= modal_anchor(get_uri("mise_a_dispo/modal_form"), 
            "✏️ Mod", 
            ["class" => "btn btn-outline-primary btn-sm ms-1", "title" => "Modifier", "data-post-id" => $data->id]);
        
        // Actions de statut
        if ($data->statut === 'demande') {
            $options .= modal_anchor(get_uri("mise_a_dispo/modal_confirmer"), 
                "✅ Conf", 
                ["class" => "btn btn-outline-primary btn-sm ms-1", "title" => "Confirmer", "data-post-id" => $data->id]);
        } 
        elseif ($data->statut === 'confirme') {
            $options .= modal_anchor(get_uri("mise_a_dispo/modal_demarrer"), 
                "▶️ Start", 
                ["class" => "btn btn-outline-info btn-sm ms-1", "title" => "Démarrer", "data-post-id" => $data->id]);
        }
        elseif ($data->statut === 'en_cours') {
            $options .= modal_anchor(get_uri("mise_a_dispo/modal_terminer"), 
                "🏁 Fin", 
                ["class" => "btn btn-outline-success btn-sm ms-1", "title" => "Terminer", "data-post-id" => $data->id]);
        }
        elseif ($data->statut === 'termine') {
            $options .= modal_anchor(get_uri("mise_a_dispo/modal_facturer"), 
                "💳 Fact", 
                ["class" => "btn btn-outline-success btn-sm ms-1", "title" => "Facturer", "data-post-id" => $data->id]);
        }
        
        // Annuler (sauf si déjà facturé ou annulé)
        if (!in_array($data->statut, ['facture', 'annule'])) {
            $options .= modal_anchor(get_uri("mise_a_dispo/modal_annuler"), 
                "❌ Ann", 
                ["class" => "btn btn-outline-danger btn-sm ms-1", "title" => "Annuler", "data-post-id" => $data->id]);
        }
        
        // Supprimer
    $options .= js_anchor("🗑️ Sup", [
    'title' => "Supprimer", 
    "class" => "btn btn-outline-danger btn-sm ms-1", 
    'data-id' => $data->id, 
    "data-action-url" => get_uri("mise_a_dispo/delete"), 
    "data-action" => "delete-confirmation"
]);

        return [
            $data->id,
            $periode_info,
            $type_service,
            $client_info,
            $lieu_display,
            $assignation,
            $prix_display,
            $statut,
            $options
        ];
    }
}