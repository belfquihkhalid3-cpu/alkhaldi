<?php

namespace App\Controllers;

use DateTime;
use Exception;

class Jawaz extends Security_Controller
{
    public $Jawaz_model;
    protected $Jawaz_historique_model;

    public function __construct()
    {
        parent::__construct();
        
        // Chargement des modèles avec gestion d'erreur
        try {
            $this->Jawaz_model = model('App\Models\Jawaz_model');
            $this->Jawaz_historique_model = model('App\Models\Jawaz_historique_model');
        } catch (Exception $e) {
            log_message('error', 'Erreur chargement modèles Jawaz: ' . $e->getMessage());
            throw new \RuntimeException('Erreur de chargement des modèles Jawaz');
        }
        
        // Cette vérification utilise le paramètre que vous avez ajouté via votre script de migration
        $this->check_module_availability("module_jawaz");
        
        // Définit la permission requise pour ce module
        $this->init_permission_checker("jawaz");
    }

    /**
     * Affiche la vue principale avec la liste des badges Jawaz et les statistiques.
     */
    public function index()
    {
        $this->access_only_allowed_members();
        
        // Vérification que le modèle est bien chargé
        if (!$this->Jawaz_model) {
            log_message('error', 'Modèle Jawaz non disponible dans index()');
            // Redirection vers une page d'erreur ou retour avec message
            return redirect()->to(base_url())->with('error', 'Module Jawaz non disponible');
        }
        
        // Récupérer les statistiques pour le dashboard
        try {
            $view_data['statistics'] = $this->Jawaz_model->get_statistics();
        } catch (Exception $e) {
            // Si get_statistics() n'existe pas, utiliser des statistiques basiques
            log_message('error', 'Méthode get_statistics() non trouvée: ' . $e->getMessage());
            $view_data['statistics'] = $this->_get_basic_statistics();
        }
        
        // Préparer les dropdowns pour les filtres
        $view_data['statuts_dropdown'] = [
            '' => '- Tous les statuts -',
            'actif' => 'Actifs (En circulation)',
            'retourne' => 'Retournés',
            'inactif' => 'Inactifs',
            'en_maintenance' => 'En maintenance'
        ];
        
        return $this->template->rander("jawaz/index", $view_data);
    }

    /**
     * Affiche le formulaire modal pour ajouter ou modifier un badge.
     */
   public function modal_form()
    {
        $this->access_only_allowed_members();

        $this->validate_submitted_data(["id" => "numeric"]);
        $id = $this->request->getPost('id');
        
        // CORRECTION: S'assurer qu'on a vraiment un ID valide
        $view_data['model_info'] = null;
        
        if ($id && $id > 0) {
            // Récupérer les données du badge existant UNIQUEMENT si ID valide
            try {
                $badge_data = $this->Jawaz_model->get_details(['id' => $id])->getRow();
                if ($badge_data && isset($badge_data->id) && $badge_data->id == $id) {
                    $view_data['model_info'] = $badge_data;
                }
            } catch (Exception $e) {
                log_message('error', 'Erreur récupération badge ID ' . $id . ': ' . $e->getMessage());
                // En cas d'erreur, on laisse model_info à null pour un formulaire vide
            }
        }
        
        // Debug log pour identifier le problème
        log_message('info', 'Modal_form - ID reçu: ' . ($id ?? 'null') . ', model_info: ' . ($view_data['model_info'] ? 'trouvé' : 'null'));

        // -- Préparation des menus déroulants --
        
        // Véhicules
        $view_data['vehicles_dropdown'] = ["" => "- Sélectionner un véhicule -"];
        try {
            $vehicles = model('App\Models\Vehicles_model')->get_details()->getResult();
            foreach ($vehicles as $vehicle) {
                $view_data['vehicles_dropdown'][$vehicle->id] = $vehicle->marque . " " . $vehicle->modele . " (" . $vehicle->numero_matricule . ")";
            }
        } catch (Exception $e) {
            log_message('error', 'Erreur chargement véhicules: ' . $e->getMessage());
            $view_data['vehicles_dropdown'][""] = "- Erreur chargement véhicules -";
        }
        
        // Chauffeurs
        $view_data['chauffeurs_dropdown'] = ["" => "- Sélectionner un chauffeur -"];
        try {
            $chauffeurs = model('App\Models\Chauffeurs_model')->get_details()->getResult();
            foreach ($chauffeurs as $chauffeur) {
                $view_data['chauffeurs_dropdown'][$chauffeur->id] = $chauffeur->prenom . " " . $chauffeur->nom;
            }
        } catch (Exception $e) {
            log_message('error', 'Erreur chargement chauffeurs: ' . $e->getMessage());
            $view_data['chauffeurs_dropdown'][""] = "- Erreur chargement chauffeurs -";
        }
        
        return $this->template->view('jawaz/modal_form', $view_data);
    }

    /**
     * Affiche le formulaire modal pour le retour d'un badge.
     */
    public function modal_retour()
    {
        $this->access_only_allowed_members();

        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');
        
        $view_data['model_info'] = $this->Jawaz_model->get_details(['id' => $id])->getRow();
        
      if (!$view_data['model_info']) {
    echo json_encode(["success" => false, 'message' => 'Badge non trouvé']);
    return;
}
        return $this->template->view('jawaz/modal_retour', $view_data);
    }

    /**
     * Affiche le formulaire modal pour la redistribution d'un badge.
     */
    public function modal_redistribution()
    {
        $this->access_only_allowed_members();

        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');
        
        $badge = $this->Jawaz_model->get_details(['id' => $id])->getRow();
        
        if (!$badge || $badge->statut !== 'retourne') {
            echo json_encode(["success" => false, 'message' => 'Badge non disponible pour redistribution']);
            return;
        }
        
        $view_data['model_info'] = $badge;

        // Préparer les dropdowns
        $vehicles = model('App\Models\Vehicles_model')->get_details()->getResult();
        $vehicles_dropdown = ["" => "- Sélectionner un véhicule -"];
        foreach ($vehicles as $vehicle) {
            $vehicles_dropdown[$vehicle->id] = $vehicle->marque . " " . $vehicle->modele . " (" . $vehicle->numero_matricule . ")";
        }
        $view_data['vehicles_dropdown'] = $vehicles_dropdown;
        
        $chauffeurs = model('App\Models\Chauffeurs_model')->get_details()->getResult();
        $chauffeurs_dropdown = ["" => "- Sélectionner un chauffeur -"];
        foreach ($chauffeurs as $chauffeur) {
            $chauffeurs_dropdown[$chauffeur->id] = $chauffeur->prenom . " " . $chauffeur->nom;
        }
        $view_data['chauffeurs_dropdown'] = $chauffeurs_dropdown;
        
        return $this->template->view('jawaz/modal_redistribution', $view_data);
    }

    /**
     * Affiche les détails d'un badge avec son historique.
     */
    public function view($id = 0)
    {
        $this->access_only_allowed_members();
        
        if (!$id) {
            return redirect()->to(get_uri('jawaz'))->with('error', 'ID badge manquant');
        }
        
        $badge = $this->Jawaz_model->get_details(['id' => $id])->getRow();
        if (!$badge) {
            return redirect()->to(get_uri('jawaz'))->with('error', 'Badge non trouvé');
        }
        
        $view_data['badge'] = $badge;
        $view_data['historique'] = $this->Jawaz_historique_model->get_historique_badge($id);
        
        return $this->template->rander("jawaz/view", $view_data);
    }

    /**
     * Sauvegarde (ajoute ou modifie) un badge Jawaz.
     */
  public function save()
    {
        $this->access_only_allowed_members();

        $this->validate_submitted_data([
            "id" => "numeric",
            "numero_serie" => "required",
            "vehicle_id" => "permit_empty|numeric",
            "chauffeur_id" => "permit_empty|numeric",
            "date_affectation" => "required",
        ]);

        try {
            $id = $this->request->getPost('id');
            $data = [
                "numero_serie" => $this->request->getPost('numero_serie'),
                "vehicle_id" => $this->request->getPost('vehicle_id') ?: null,
                "chauffeur_id" => $this->request->getPost('chauffeur_id') ?: null,
                "solde" => $this->request->getPost('solde') ?: 0,
                "date_affectation" => $this->request->getPost('date_affectation'),
                "statut" => $this->request->getPost('statut') ?: 'actif',
                "evenement" => $this->request->getPost('evenement'),
            ];
            
            // Debug des données reçues
            log_message('info', 'Jawaz Save - ID: ' . ($id ?? 'null'));
            log_message('info', 'Jawaz Save - Data: ' . json_encode($data));
            
            if ($id && $id > 0) {
                $data["id"] = $id;
                log_message('info', 'Mode modification - ID: ' . $id);
            } else {
                log_message('info', 'Mode ajout - Nouveau badge');
            }

            $save_result = $this->Jawaz_model->save($data);
            log_message('info', 'Jawaz Save Result: ' . ($save_result ? 'SUCCESS' : 'FAILED'));
            
            if ($save_result) {
                // Pour nouvel enregistrement, récupérer l'ID du dernier insert
                if (!$id || $id == 0) {
                    $actual_id = $this->Jawaz_model->getInsertID();
                    log_message('info', 'Nouvel ID inséré: ' . $actual_id);
                } else {
                    $actual_id = (int)$id;
                    log_message('info', 'ID modifié: ' . $actual_id);
                }
                
                $badge_data = $this->Jawaz_model->get_details(["id" => $actual_id])->getRow();
                
                if ($badge_data) {
                    echo json_encode([
                        "success" => true, 
                        "data" => $this->_make_row($badge_data), 
                        'id' => $actual_id, 
                        'message' => lang('record_saved')
                    ]);
                } else {
                    log_message('error', 'Badge sauvé mais non trouvé pour ID: ' . $actual_id);
                    echo json_encode(["success" => false, 'message' => 'Badge sauvé mais non trouvé']);
                }
            } else {
                // Récupérer les erreurs de validation du modèle
                $errors = $this->Jawaz_model->errors();
                $error_msg = !empty($errors) ? implode(', ', $errors) : 'Erreur de sauvegarde inconnue';
                log_message('error', 'Jawaz Save Failed - Errors: ' . $error_msg);
                echo json_encode(["success" => false, 'message' => $error_msg]);
            }
            
        } catch (Exception $e) {
            log_message('error', 'Jawaz Save Exception: ' . $e->getMessage());
            echo json_encode(["success" => false, 'message' => 'Erreur système : ' . $e->getMessage()]);
        }
    }

    /**
     * Traite le retour d'un badge.
     */
    public function save_retour()
    {
        $this->access_only_allowed_members();

        $this->validate_submitted_data([
            "id" => "required|numeric",
            "date_retour" => "required",
            "solde_retour" => "required|numeric"
        ]);

        try {
            $id = $this->request->getPost('id');
            $data = [
                "date_retour" => $this->request->getPost('date_retour'),
                "solde_retour" => $this->request->getPost('solde_retour'),
                "motif_retour" => $this->request->getPost('motif_retour'),
                "peut_redistribuer" => $this->request->getPost('peut_redistribuer') ? 1 : 0
            ];

            $result = $this->Jawaz_model->process_retour($id, $data);
            
            if ($result) {
                // Enregistrer dans l'historique
               
                $badge_data = $this->Jawaz_model->get_details(["id" => $id])->getRow();
                echo json_encode([
                    "success" => true, 
                    "data" => $this->_make_row($badge_data), 
                    'id' => (int)$id, 
                    'message' => 'Badge retourné avec succès'
                ]);
            } else {
                echo json_encode(["success" => false, 'message' => 'Erreur lors du retour du badge']);
            }
            
        } catch (Exception $e) {
            echo json_encode(["success" => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Traite la redistribution d'un badge.
     */
    public function save_redistribution()
    {
        $this->access_only_allowed_members();

        $this->validate_submitted_data([
            "id" => "required|numeric",
            "vehicle_id" => "required|numeric",
            "chauffeur_id" => "required|numeric",
            "date_affectation" => "required",
            "solde" => "required|numeric"
        ]);

        try {
            $id = $this->request->getPost('id');
            $data = [
                "vehicle_id" => $this->request->getPost('vehicle_id'),
                "chauffeur_id" => $this->request->getPost('chauffeur_id'),
                "date_affectation" => $this->request->getPost('date_affectation'),
                "solde" => $this->request->getPost('solde'),
                "evenement" => $this->request->getPost('evenement')
            ];

            $result = $this->Jawaz_model->process_redistribution($id, $data);
            
            if ($result) {
                // Enregistrer dans l'historique
               
                $badge_data = $this->Jawaz_model->get_details(["id" => $id])->getRow();
                echo json_encode([
                    "success" => true, 
                    "data" => $this->_make_row($badge_data), 
                    'id' => (int)$id, 
                    'message' => 'Badge redistribué avec succès'
                ]);
            } else {
                echo json_encode(["success" => false, 'message' => 'Erreur lors de la redistribution']);
            }
            
        } catch (Exception $e) {
            echo json_encode(["success" => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Supprime (soft delete) un badge Jawaz.
     */
 public function delete()
{
    $this->access_only_allowed_members();
    $this->validate_submitted_data(["id" => "required|numeric"]);
    $id = $this->request->getPost('id');

    // Debug
    log_message('info', 'Tentative suppression badge ID: ' . $id);
    
    try {
        // Utiliser une requête directe pour la suppression douce
        $db = \Config\Database::connect();
        $result = $db->table('rise_jawaz')
                    ->where('id', $id)
                    ->update(['deleted' => 1]);
        
        log_message('info', 'Résultat suppression: ' . ($result ? 'SUCCESS' : 'FAILED'));
        log_message('info', 'Rows affected: ' . $db->affectedRows());

        if ($result && $db->affectedRows() > 0) {
            echo json_encode(["success" => true, 'message' => 'Badge supprimé avec succès']);
        } else {
            echo json_encode(["success" => false, 'message' => 'Aucun badge trouvé ou déjà supprimé']);
        }
        
    } catch (Exception $e) {
        log_message('error', 'Erreur suppression: ' . $e->getMessage());
        echo json_encode(["success" => false, 'message' => 'Erreur lors de la suppression']);
    }
}

    /**
     * Prépare les données pour la liste (DataTables).
     */
    public function list_data()
    {
        $this->access_only_allowed_members();
        
        // Récupérer les filtres
        $options = [];
        $statut = $this->request->getPost('statut');
        if ($statut) {
            $options['statut'] = $statut;
        }
        
        $list_data = $this->Jawaz_model->get_details($options)->getResult();
        $result = [];
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(["data" => $result]);
    }

    /**
     * Prépare une ligne de données pour l'affichage dans la table.
     */
    private function _make_row($data)
    {
        // Badge pour le statut avec couleurs
        $statut_classes = [
            'actif' => 'bg-success',
            'retourne' => 'bg-primary', 
            'inactif' => 'bg-secondary',
            'en_maintenance' => 'bg-warning'
        ];
        $statut_class = $statut_classes[$data->statut] ?? 'bg-secondary';
        $statut_text = [
            'actif' => 'En circulation',
            'retourne' => 'Retourné',
            'inactif' => 'Inactif',
            'en_maintenance' => 'Maintenance'
        ][$data->statut] ?? $data->statut;
        
        $statut = "<span class='badge $statut_class'>$statut_text</span>";

        // Affichage conditionnel des informations
        $vehicle_info = $data->vehicle_name ? anchor(get_uri("vehicles/view/" . $data->vehicle_id), $data->vehicle_name) : "<span class='text-muted'>Non affecté</span>";
        $chauffeur_info = $data->chauffeur_name ? anchor(get_uri("chauffeurs/view/" . $data->chauffeur_id), $data->chauffeur_name) : "<span class='text-muted'>Non affecté</span>";
        
        // Solde avec couleur selon le montant
        $solde_class = $data->solde > 100 ? 'text-success' : ($data->solde > 50 ? 'text-warning' : 'text-danger');
        $solde_display = "<span class='$solde_class font-weight-bold'>" . to_currency($data->solde) . "</span>";
        
        // Si retourné, afficher aussi le solde de retour
        if ($data->statut === 'retourne' && $data->solde_retour !== null) {
            $solde_display .= "<br><small class='text-muted'>Retour: " . to_currency($data->solde_retour) . "</small>";
        }

        // Actions selon le statut
        $options = "";
        
        // Bouton voir détails
        $options .= anchor(get_uri("jawaz/view/" . $data->id), "<i data-feather='eye' class='icon-16'></i>", ["class" => "btn btn-outline-info btn-sm", "title" => "Voir détails"]);
        
        if ($data->statut === 'actif') {
            // Badge actif : modifier + retourner
            $options .= modal_anchor(get_uri("jawaz/modal_form"), "<i data-feather='edit' class='icon-16'></i>", 
                ["class" => "btn btn-outline-primary btn-sm ms-1", "title" => "Modifier", "data-post-id" => $data->id]);
            $options .= modal_anchor(get_uri("jawaz/modal_retour"), "<i data-feather='corner-up-left' class='icon-16'></i>", 
                ["class" => "btn btn-outline-warning btn-sm ms-1", "title" => "Retourner le badge", "data-post-id" => $data->id]);
        } 
        elseif ($data->statut === 'retourne') {
            // Badge retourné : redistribuer (si autorisé)
            if ($data->peut_redistribuer) {
                $options .= modal_anchor(get_uri("jawaz/modal_redistribution"), "<i data-feather='repeat' class='icon-16'></i>", 
                    ["class" => "btn btn-outline-success btn-sm ms-1", "title" => "Redistribuer", "data-post-id" => $data->id]);
            }
        }
        else {
            // Autres statuts : modifier seulement
            $options .= modal_anchor(get_uri("jawaz/modal_form"), "<i data-feather='edit' class='icon-16'></i>", 
                ["class" => "btn btn-outline-primary btn-sm ms-1", "title" => "Modifier", "data-post-id" => $data->id]);
        }
        
        // Supprimer (toujours disponible)
        $options .= js_anchor("<i data-feather='trash-2' class='icon-16'></i>", 
            ['title' => "Supprimer", "class" => "btn btn-outline-danger btn-sm ms-1", 
             "data-id" => $data->id, "data-action-url" => get_uri("jawaz/delete"), "data-action" => "delete-confirmation"]);

        // Dates d'affichage
        $date_affectation = format_to_date($data->date_affectation, false);
        $periode_info = $date_affectation;
        
        if ($data->date_retour) {
            $date_retour = format_to_date($data->date_retour, false);
            $periode_info .= "<br><small class='text-muted'>Retour: $date_retour</small>";
            if ($data->jours_utilisation) {
                $periode_info .= "<br><small class='text-info'>Durée: {$data->jours_utilisation} jours</small>";
            }
        } elseif ($data->statut === 'actif' && $data->jours_utilisation) {
            $periode_info .= "<br><small class='text-info'>En cours: {$data->jours_utilisation} jours</small>";
        }

        return [
            $data->id,
            $data->numero_serie,
            $vehicle_info,
            $chauffeur_info,
            $solde_display,
            $periode_info,
            $statut,
            $options
        ];
    }
  public function add()
    {
        $this->access_only_allowed_members();

        // -- Préparation des menus déroulants --
        
        // Véhicules
        $view_data['vehicles_dropdown'] = ["" => "- Sélectionner un véhicule -"];
        try {
            $vehicles = model('App\Models\Vehicles_model')->get_details()->getResult();
            foreach ($vehicles as $vehicle) {
                $view_data['vehicles_dropdown'][$vehicle->id] = $vehicle->marque . " " . $vehicle->modele . " (" . $vehicle->numero_matricule . ")";
            }
        } catch (Exception $e) {
            log_message('error', 'Erreur chargement véhicules: ' . $e->getMessage());
            $view_data['vehicles_dropdown'][""] = "- Erreur chargement véhicules -";
        }
        
        // Chauffeurs
        $view_data['chauffeurs_dropdown'] = ["" => "- Sélectionner un chauffeur -"];
        try {
            $chauffeurs = model('App\Models\Chauffeurs_model')->get_details()->getResult();
            foreach ($chauffeurs as $chauffeur) {
                $view_data['chauffeurs_dropdown'][$chauffeur->id] = $chauffeur->prenom . " " . $chauffeur->nom;
            }
        } catch (Exception $e) {
            log_message('error', 'Erreur chargement chauffeurs: ' . $e->getMessage());
            $view_data['chauffeurs_dropdown'][""] = "- Erreur chargement chauffeurs -";
        }
        
        return $this->template->rander("jawaz/add", $view_data);
    }
     public function modal_add()
    {
        $this->access_only_allowed_members();

        // -- Préparation des menus déroulants --
        
        // Véhicules
        $view_data['vehicles_dropdown'] = ["" => "- Sélectionner un véhicule -"];
        try {
            $vehicles = model('App\Models\Vehicles_model')->get_details()->getResult();
            foreach ($vehicles as $vehicle) {
                $view_data['vehicles_dropdown'][$vehicle->id] = $vehicle->marque . " " . $vehicle->modele . " (" . $vehicle->numero_matricule . ")";
            }
        } catch (Exception $e) {
            log_message('error', 'Erreur chargement véhicules: ' . $e->getMessage());
            $view_data['vehicles_dropdown'][""] = "- Erreur chargement véhicules -";
        }
        
        // Chauffeurs
        $view_data['chauffeurs_dropdown'] = ["" => "- Sélectionner un chauffeur -"];
        try {
            $chauffeurs = model('App\Models\Chauffeurs_model')->get_details()->getResult();
            foreach ($chauffeurs as $chauffeur) {
                $view_data['chauffeurs_dropdown'][$chauffeur->id] = $chauffeur->prenom . " " . $chauffeur->nom;
            }
        } catch (Exception $e) {
            log_message('error', 'Erreur chargement chauffeurs: ' . $e->getMessage());
            $view_data['chauffeurs_dropdown'][""] = "- Erreur chargement chauffeurs -";
        }
        
        return $this->template->view('jawaz/add', $view_data);
    }
    /**
     * Statistiques basiques en cas d'absence de la méthode get_statistics() dans le modèle
     */
    private function _get_basic_statistics()
    {
        try {
            // Vérifier que le modèle est disponible
            if (!$this->Jawaz_model) {
                throw new Exception('Modèle Jawaz non disponible');
            }
            
            // Récupérer tous les badges
            $all_badges = $this->Jawaz_model->get_details()->getResult();
            
            $stats = [
                'total_badges' => count($all_badges),
                'badges_actifs' => 0,
                'badges_retournes' => 0,
                'solde_total' => 0,
                'solde_moyen' => 0,
                'badges_redistribuables' => 0
            ];
            
            foreach ($all_badges as $badge) {
                if ($badge->statut === 'actif') {
                    $stats['badges_actifs']++;
                    $stats['solde_total'] += (float)($badge->solde ?? 0);
                } elseif ($badge->statut === 'retourne') {
                    $stats['badges_retournes']++;
                    if (isset($badge->peut_redistribuer) && $badge->peut_redistribuer) {
                        $stats['badges_redistribuables']++;
                    }
                }
            }
            
            // Calcul de la moyenne
            if ($stats['badges_actifs'] > 0) {
                $stats['solde_moyen'] = $stats['solde_total'] / $stats['badges_actifs'];
            }
            
            return $stats;
            
        } catch (Exception $e) {
            log_message('error', 'Erreur calcul statistiques basiques: ' . $e->getMessage());
            // Retourner des statistiques vides mais valides
            return [
                'total_badges' => 0,
                'badges_actifs' => 0,
                'badges_retournes' => 0,
                'solde_total' => 0,
                'solde_moyen' => 0,
                'badges_redistribuables' => 0
            ];
        }
    }
}