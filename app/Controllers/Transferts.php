<?php

namespace App\Controllers;

use DateTime;
use Exception;

class Transferts extends Security_Controller
{
    protected $Transferts_model;

    public function __construct()
    {
        parent::__construct();
        $this->Transferts_model = model('App\Models\Transferts_model');
        
        $this->check_module_availability("module_transferts");
        $this->init_permission_checker("transferts");
    }

    /**
     * Page principale avec liste des transferts et statistiques
     */
    public function index()
    {
        $this->access_only_allowed_members();
        
        // â CORRECTION : Statistiques appelées correctement
        $view_data['statistics'] = $this->Transferts_model->get_statistics();
        
        // Dropdowns pour filtres - â CORRECTION : Textes français corrects
        $view_data['statuts_dropdown'] = [
            '' => '- Tous les statuts -',
            'reserve' => 'Réservé',
            'confirme' => 'Confirmé',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'annule' => 'Annulé'
        ];
        
        $view_data['types_dropdown'] = [
            '' => '- Tous les types -',
            'arrivee' => 'Arrivée',
            'depart' => 'Départ',
            'aller_retour' => 'Aller-retour'
        ];
        
        // â CORRECTION : Utiliser rander (méthode personnalisée)
        return $this->template->rander("transferts/index", $view_data);
    }

    /**
     * Modal d'ajout de transfert
     */
    public function modal_add()
    {
        $this->access_only_allowed_members();
        
        $view_data = $this->_prepare_form_data();
        return $this->template->view('transferts/modal_add', $view_data);
    }

    /**
     * Modal de confirmation de transfert
     */
    public function modal_confirmer()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');
        
        $view_data['transfert_id'] = $id;
        $view_data['nouveau_statut'] = 'confirme';
        $view_data['action_title'] = 'Confirmer le transfert';
        $view_data['action_class'] = 'btn-success';
        
        return $this->template->view('transferts/modal_change_statut', $view_data);
    }

    /**
     * Modal de démarrage de transfert
     */
    public function modal_demarrer()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');
        
        $view_data['transfert_id'] = $id;
        $view_data['nouveau_statut'] = 'en_cours';
        $view_data['action_title'] = 'Démarrer le transfert';
        $view_data['action_class'] = 'btn-warning';
        
        return $this->template->view('transferts/modal_change_statut', $view_data);
    }

    /**
     * Modal de finalisation de transfert
     */
    public function modal_terminer()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');
        
        $view_data['transfert_id'] = $id;
        $view_data['nouveau_statut'] = 'termine';
        $view_data['action_title'] = 'Terminer le transfert';
        $view_data['action_class'] = 'btn-success';
        
        return $this->template->view('transferts/modal_change_statut', $view_data);
    }

    /**
     * Modal d'annulation de transfert
     */
    public function modal_annuler()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');
        
        $view_data['transfert_id'] = $id;
        $view_data['nouveau_statut'] = 'annule';
        $view_data['action_title'] = 'Annuler le transfert';
        $view_data['action_class'] = 'btn-danger';
        
        return $this->template->view('transferts/modal_change_statut', $view_data);
    }
    public function modal_form()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');
        
        $transfert = $this->Transferts_model->get_details(['id' => $id])->getRow();
        if (!$transfert) {
            echo json_encode(["success" => false, 'message' => 'Transfert non trouvé']);
            return;
        }
        
        $view_data = $this->_prepare_form_data();
        $view_data['model_info'] = $transfert;
        
        return $this->template->view('transferts/modal_form', $view_data);
    }

    /**
     * Vue détaillée d'un transfert
     */
    public function view($id = 0)
    {
        $this->access_only_allowed_members();
        
        if (!$id) {
            return redirect()->to(get_uri('transferts'))->with('error', 'ID transfert manquant');
        }
        
        $transfert = $this->Transferts_model->get_details(['id' => $id])->getRow();
        if (!$transfert) {
            return redirect()->to(get_uri('transferts'))->with('error', 'Transfert non trouvé');
        }
        
        $view_data['transfert'] = $transfert;
        $view_data['historique'] = $this->Transferts_model->get_historique_statut($id);
        
        // Informations additionnelles pour la vue
        $view_data['statut_classes'] = [
            'reserve' => 'bg-secondary',
            'confirme' => 'bg-primary',
            'en_cours' => 'bg-warning',
            'termine' => 'bg-success',
            'annule' => 'bg-danger'
        ];
        
        $view_data['statut_text'] = [
            'reserve' => 'Réservé',
            'confirme' => 'Confirmé',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'annule' => 'Annulé'
        ];
        
        $view_data['type_text'] = [
            'arrivee' => 'Arrivée',
            'depart' => 'Départ',
            'aller_retour' => 'Aller-retour'
        ];
        
        return $this->template->rander("transferts/view", $view_data);
    }

    /**
     * Sauvegarde (ajout/modification)
     */
    public function save()
    {
        $this->access_only_allowed_members();
        
        $this->validate_submitted_data([
            "nom_client" => "required",
            "type_transfert" => "required",
            "service_type" => "required",
            "date_transfert" => "required",
            "lieu_prise_en_charge" => "required",
            "lieu_destination" => "required"
        ]);

        try {
            $id = $this->request->getPost('id');
            $data = [
                'client_id' => $this->request->getPost('client_id') ?: null,
                'nom_client' => $this->request->getPost('nom_client'),
                'telephone_client' => $this->request->getPost('telephone_client'),
                'email_client' => $this->request->getPost('email_client'),
                'nombre_passagers' => $this->request->getPost('nombre_passagers') ?: 1,
                'type_transfert' => $this->request->getPost('type_transfert'),
                'service_type' => $this->request->getPost('service_type'),
                'numero_vol' => $this->request->getPost('numero_vol'),
                'compagnie' => $this->request->getPost('compagnie'),
                'heure_arrivee_prevue' => $this->request->getPost('heure_arrivee_prevue'),
                'heure_depart_prevue' => $this->request->getPost('heure_depart_prevue'),
                'date_transfert' => $this->request->getPost('date_transfert'),
                'lieu_prise_en_charge' => $this->request->getPost('lieu_prise_en_charge'),
                'lieu_destination' => $this->request->getPost('lieu_destination'),
                'adresse_complete' => $this->request->getPost('adresse_complete'),
                'vehicle_id' => $this->request->getPost('vehicle_id') ?: null,
                'chauffeur_id' => $this->request->getPost('chauffeur_id') ?: null,
                'prix_prevu' => $this->request->getPost('prix_prevu') ?: null,
                'statut' => $this->request->getPost('statut') ?: 'reserve',
                'instructions_particulieres' => $this->request->getPost('instructions_particulieres')
            ];
            
            // Formatage des dates
            $data = $this->Transferts_model->formatDates($data);
            
            if ($id && $id > 0) {
                $data['id'] = $id;
            }

            $save_result = $this->Transferts_model->save($data);
            
            if ($save_result) {
                $actual_id = ($id && $id > 0) ? (int)$id : $this->Transferts_model->getInsertID();
                $transfert_data = $this->Transferts_model->get_details(["id" => $actual_id])->getRow();
                
                echo json_encode([
                    "success" => true,
                    "data" => $this->_make_row($transfert_data),
                    'id' => $actual_id,
                    'message' => 'Transfert sauvegardé avec succès'
                ]);
            } else {
                $errors = $this->Transferts_model->errors();
                $error_msg = !empty($errors) ? implode(', ', $errors) : 'Erreur de sauvegarde';
                echo json_encode(["success" => false, 'message' => $error_msg]);
            }
            
        } catch (Exception $e) {
            echo json_encode(["success" => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Changement de statut (appelé par les modals)
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
            
            // Préparer les données de mise à jour
            $data = ['statut' => $statut];
            
            // Ajouter les champs spécifiques selon le statut
            if ($statut === 'en_cours') {
                if ($heure_prise_en_charge = $this->request->getPost('heure_prise_en_charge_reelle')) {
                    // Convertir HTML5 datetime vers MySQL
                    if (strpos($heure_prise_en_charge, 'T') !== false) {
                        $data['heure_prise_en_charge_reelle'] = str_replace('T', ' ', $heure_prise_en_charge) . ':00';
                    }
                }
            }
            
            if ($statut === 'termine') {
                if ($heure_arrivee = $this->request->getPost('heure_arrivee_reelle')) {
                    // Convertir HTML5 datetime vers MySQL
                    if (strpos($heure_arrivee, 'T') !== false) {
                        $data['heure_arrivee_reelle'] = str_replace('T', ' ', $heure_arrivee) . ':00';
                    }
                }
                
                if ($prix_facture = $this->request->getPost('prix_facture')) {
                    $data['prix_facture'] = floatval($prix_facture);
                }
                
                if ($notes_chauffeur = $this->request->getPost('notes_chauffeur')) {
                    $data['notes_chauffeur'] = $notes_chauffeur;
                }
            }
            
            // Mettre à jour le transfert
            $result = $this->Transferts_model->update($id, $data);
            
            if ($result) {
                // Enregistrer dans l'historique
                $this->Transferts_model->change_statut($id, $statut, $commentaire, $this->login_user->id);
                
                // Récupérer les données mises à jour
                $transfert_data = $this->Transferts_model->get_details(["id" => $id])->getRow();
                
                echo json_encode([
                    "success" => true,
                    "data" => $this->_make_row($transfert_data),
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
    public function delete()
    {
        // Log pour debug
        log_message('info', 'Delete method called');
        
        $this->access_only_allowed_members();
        
        // Debug des données reçues
        $post_data = $this->request->getPost();
        log_message('info', 'POST data: ' . json_encode($post_data));
        
        if (!$this->request->getPost('id')) {
            log_message('error', 'ID manquant dans delete');
            echo json_encode(["success" => false, 'message' => 'ID manquant']);
            return;
        }
        
        $id = $this->request->getPost('id');
        log_message('info', 'Tentative suppression ID: ' . $id);
        
        try {
            // Test simple avec requête directe
            $db = \Config\Database::connect();
            
            // Vérifier que l'enregistrement existe
            $exists = $db->table('rise_transferts')->where('id', $id)->countAllResults();
            log_message('info', 'Transfert exists: ' . $exists);
            
            if ($exists == 0) {
                echo json_encode(["success" => false, 'message' => 'Transfert non trouvé (ID: ' . $id . ')']);
                return;
            }
            
            // Tentative de suppression (soft delete)
            $result = $db->table('rise_transferts')
                        ->where('id', $id)
                        ->update(['deleted' => 1]);
            
            log_message('info', 'Update result: ' . ($result ? 'true' : 'false'));
            log_message('info', 'Affected rows: ' . $db->affectedRows());
            
            if ($result && $db->affectedRows() > 0) {
                echo json_encode([
                    "success" => true, 
                    'message' => 'Transfert supprimé avec succès (ID: ' . $id . ')'
                ]);
            } else {
                echo json_encode([
                    "success" => false, 
                    'message' => 'Aucune ligne affectée (ID: ' . $id . ')'
                ]);
            }
            
        } catch (Exception $e) {
            log_message('error', 'Erreur delete: ' . $e->getMessage());
            echo json_encode([
                "success" => false, 
                'message' => 'Erreur : ' . $e->getMessage()
            ]);
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
        
        if ($type = $this->request->getPost('type_transfert')) {
            $options['type_transfert'] = $type;
        }
        
        if ($search = $this->request->getPost('search')) {
            $options['search'] = $search;
        }
        
        $list_data = $this->Transferts_model->get_details($options)->getResult();
        $result = [];
        
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        
        echo json_encode(["data" => $result]);
    }

    /**
     * Prépare les données pour les formulaires
     */
    private function _prepare_form_data()
    {
        $view_data = [];
        
        // Clients - structure corrigée
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
            'reserve' => 'bg-secondary',
            'confirme' => 'bg-primary',
            'en_cours' => 'bg-warning',
            'termine' => 'bg-success',
            'annule' => 'bg-danger'
        ];
        $statut_text = [
            'reserve' => 'Réservé',
            'confirme' => 'Confirmé',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'annule' => 'Annulé'
        ];
        
        $statut_class = $statut_classes[$data->statut] ?? 'bg-secondary';
        $statut_label = $statut_text[$data->statut] ?? $data->statut;
        $statut = "<span class='badge $statut_class'>$statut_label</span>";

        // Type de transfert avec icônes Feather
        $type_icons = [
            'arrivee' => 'plane-landing',
            'depart' => 'plane-takeoff',
            'aller_retour' => 'repeat'
        ];
        $type_labels = [
            'arrivee' => 'Arrivée',
            'depart' => 'Départ',
            'aller_retour' => 'A/R'
        ];
        
        $type_icon = $type_icons[$data->type_transfert] ?? 'arrow-right';
        $type_label = $type_labels[$data->type_transfert] ?? $data->type_transfert;
        $type_transfert = "<i data-feather='$type_icon' class='icon-16 me-1'></i>$type_label";

        // Informations client
        $client_info = "<strong>" . htmlspecialchars($data->nom_client) . "</strong>";

        $telephone = $data->telephone_client ?: ($data->client_phone_db ?? '');
        if ($telephone) {
            $client_info .= "<br><small class='text-muted'><i data-feather='phone' class='icon-12'></i> " . $telephone . "</small>";
        }

        if ($data->client_company && $data->client_company !== $data->nom_client) {
            $client_info .= "<br><small class='text-info'><i data-feather='building' class='icon-12'></i> " . $data->client_company . "</small>";
        }

        if ($data->nombre_passagers > 1) {
            $client_info .= "<br><small class='text-info'><i data-feather='users' class='icon-12'></i> " . $data->nombre_passagers . " passagers</small>";
        }

        // Vol et horaires
        $vol_info = "";
        if ($data->numero_vol) {
            $vol_info = "<strong><i data-feather='plane' class='icon-12'></i> " . $data->numero_vol . "</strong>";
            if ($data->compagnie) {
                $vol_info .= "<br><small>" . $data->compagnie . "</small>";
            }
        }
        
        if ($data->heure_arrivee_prevue) {
            $vol_info .= "<br><small class='text-primary'><i data-feather='clock' class='icon-12'></i> " . date('H:i', strtotime($data->heure_arrivee_prevue)) . "</small>";
        }
        
        if (!$vol_info) {
            $vol_info = "<span class='text-muted'>-</span>";
        }

        // Itinéraire avec icônes
        $itineraire = "<small class='text-success'><i data-feather='map-pin' class='icon-12'></i> " . htmlspecialchars($data->lieu_prise_en_charge) . "</small>";
        $itineraire .= "<br><small class='text-danger'><i data-feather='flag' class='icon-12'></i> " . htmlspecialchars($data->lieu_destination) . "</small>";

        // Véhicule et chauffeur avec icônes
        $assignation = "";
        if ($data->vehicle_name) {
            $assignation .= "<small><i data-feather='truck' class='icon-12'></i> " . $data->vehicle_name . "</small>";
        }
        if ($data->chauffeur_name) {
            $assignation .= "<br><small><i data-feather='user' class='icon-12'></i> " . $data->chauffeur_name . "</small>";
        }
        if (!$assignation) {
            $assignation = "<span class='text-muted'>Non assigné</span>";
        }

        // Prix avec icône
        $prix_display = "";
        if ($data->prix_prevu) {
            $prix_display = "<span class='text-primary'><i data-feather='dollar-sign' class='icon-12'></i> " . to_currency($data->prix_prevu) . "</span>";
        }
        if ($data->prix_facture && $data->statut === 'termine') {
            $prix_display .= "<br><strong class='text-success'><i data-feather='check-circle' class='icon-12'></i> " . to_currency($data->prix_facture) . "</strong>";
        }
        if (!$prix_display) {
            $prix_display = "<span class='text-muted'>-</span>";
        }

        // Date avec indicateur de période
        $date_display = format_to_date($data->date_transfert, false);
        if (isset($data->periode_relative)) {
            $periode_class = [
                "Aujourd'hui" => 'text-warning',
                'Demain' => 'text-info',
                'Passé' => 'text-muted',
                'À venir' => 'text-primary'
            ];
            $class = $periode_class[$data->periode_relative] ?? 'text-primary';
            $date_display .= "<br><small class='$class'>{$data->periode_relative}</small>";
        }

        // Actions selon le statut avec icônes Feather
        $options = "";
        
        // Voir détails
        $options .= anchor(get_uri("transferts/view/" . $data->id), 
            "<i data-feather='eye' class='icon-16'></i>", 
            ["class" => "btn btn-outline-info btn-sm", "title" => "Voir détails"]);
        
        // Modifier
        $options .= modal_anchor(get_uri("transferts/modal_form"), 
            "<i data-feather='edit' class='icon-16'></i>", 
            ["class" => "btn btn-outline-primary btn-sm ms-1", "title" => "Modifier", "data-post-id" => $data->id]);
        
        // Actions de statut avec icônes Feather
        if ($data->statut === 'reserve') {
            $options .= "<button type='button' class='btn btn-outline-success btn-sm ms-1 change-statut' 
                         data-id='{$data->id}' data-statut='confirme' title='Confirmer'>
                         <i data-feather='check' class='icon-16'></i></button>";
        } elseif ($data->statut === 'confirme') {
            $options .= "<button type='button' class='btn btn-outline-warning btn-sm ms-1 change-statut' 
                         data-id='{$data->id}' data-statut='en_cours' title='Démarrer'>
                         <i data-feather='play' class='icon-16'></i></button>";
        } elseif ($data->statut === 'en_cours') {
            $options .= "<button type='button' class='btn btn-outline-success btn-sm ms-1 change-statut' 
                         data-id='{$data->id}' data-statut='termine' title='Terminer'>
                         <i data-feather='check-circle' class='icon-16'></i></button>";
        }
        
        // Annuler (sauf si déjà terminé)
        if (!in_array($data->statut, ['termine', 'annule'])) {
            $options .= "<button type='button' class='btn btn-outline-danger btn-sm ms-1 change-statut' 
                         data-id='{$data->id}' data-statut='annule' title='Annuler'>
                         <i data-feather='x' class='icon-16'></i></button>";
        }
        
        // Supprimer
        $options .= js_anchor("<i data-feather='trash-2' class='icon-16'></i>", 
            ['title' => "Supprimer", "class" => "btn btn-outline-danger btn-sm ms-1", 
             "data-id" => $data->id, "data-action-url" => get_uri("transferts/delete"), 
             "data-action" => "delete-confirmation"]);

        return [
            $data->id,
            $date_display,
            $type_transfert,
            $client_info,
            $vol_info,
            $itineraire,
            $assignation,
            $prix_display,
            $statut,
            $options // â Pas de script, juste les icônes HTML
        ];
    }
}