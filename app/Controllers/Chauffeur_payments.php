<?php

namespace App\Controllers;

class Chauffeur_payments extends Security_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->Chauffeur_payments_model = model('App\Models\Chauffeur_payments_model');
        $this->check_module_availability("module_chauffeur_payments");
        $this->init_permission_checker("chauffeur_payment");
    }

    /**
     * Affiche la vue principale avec la liste des paiements
     */
    public function index()
    {
        $this->access_only_allowed_members();
        return $this->template->rander("chauffeur_payments/index");
    }

    /**
     * Affiche le formulaire modal pour ajouter ou modifier un paiement
     */
 /**
     * Affiche le formulaire modal pour ajouter ou modifier un paiement - VERSION CORRIGÉE
     */
    public function modal_form()
    {
        $this->access_only_allowed_members();

        $this->validate_submitted_data(["id" => "numeric"]);
        $id = $this->request->getPost('id');
        
        // ✅ CORRECTION PRINCIPALE - Bien distinguer ajout vs modification
        if ($id) {
            // MODE MODIFICATION - Charger les données existantes
            $view_data['model_info'] = $this->Chauffeur_payments_model->get_details(['id' => $id])->getRow();
        } else {
            // MODE AJOUT - Créer un objet vide
            $view_data['model_info'] = (object) [
                'id' => '',
                'chauffeur_id' => '',
                'type_paiement' => '',
                'montant' => '',
                'mois_concerne' => date('Y-m'),  // Mois actuel par défaut
                'date_paiement' => date('Y-m-d'), // Date du jour par défaut
                'mode_paiement' => 'especes',     // Mode par défaut
                'description' => '',
                'statut' => 'en_attente',         // Statut par défaut
                'avance_reference_id' => '',
                'avance_deduite' => ''
            ];
        }

        // Préparation des menus déroulants
        $chauffeurs = model('App\Models\Chauffeurs_model')->get_details()->getResult();
        $chauffeurs_dropdown = ['' => '-- Sélectionner un chauffeur --']; // ✅ Option vide en premier
        foreach ($chauffeurs as $chauffeur) {
            $chauffeurs_dropdown[$chauffeur->id] = $chauffeur->prenom . " " . $chauffeur->nom;
        }
        $view_data['chauffeurs_dropdown'] = $chauffeurs_dropdown;

        $view_data['types_dropdown'] = [
            '' => '-- Sélectionner un type --',  // ✅ Option vide en premier
            "salaire" => "Salaire",
            "prime" => "Prime",
            "avance" => "Avance sur salaire",
            "remboursement" => "Remboursement de frais",
            "deduction" => "Déduction"
        ];
        
        $view_data['modes_dropdown'] = [
            "especes" => "Espèces", 
            "virement" => "Virement", 
            "cheque" => "Chèque"
        ];
        
        $view_data['statuts_dropdown'] = [
            "en_attente" => "En attente", 
            "paye" => "Payé", 
            "annule" => "Annulé"
        ];

        // ✅ CORRECTION - Gestion des avances seulement en mode modification avec chauffeur
        if ($id && !empty($view_data['model_info']->chauffeur_id)) {
            $chauffeur_id = $view_data['model_info']->chauffeur_id;
            $view_data['solde_avances'] = $this->Chauffeur_payments_model->get_solde_avances_chauffeur($chauffeur_id);
            $view_data['avances_disponibles'] = $this->Chauffeur_payments_model->get_avances_disponibles($chauffeur_id)->getResult();
        } else {
            // Mode ajout - Pas d'avances préchargées
            $view_data['solde_avances'] = 0;
            $view_data['avances_disponibles'] = [];
        }
        
        return $this->template->view('chauffeur_payments/modal_form', $view_data);
    }

    /**
     * NOUVELLE MÉTHODE : Récupère les infos d'avances pour un chauffeur (AJAX)
     */
    public function get_chauffeur_avances()
    {
        $this->access_only_allowed_members();
        
        $chauffeur_id = $this->request->getPost('chauffeur_id');
        
        if (!$chauffeur_id || !is_numeric($chauffeur_id)) {
            echo json_encode([
                'success' => false, 
                'message' => 'ID chauffeur invalide'
            ]);
            return;
        }
        
        $solde_avances = $this->Chauffeur_payments_model->get_solde_avances_chauffeur($chauffeur_id);
        $avances_disponibles = $this->Chauffeur_payments_model->get_avances_disponibles($chauffeur_id)->getResult();
        
        echo json_encode([
            'success' => true,
            'solde_avances' => $solde_avances,
            'avances_disponibles' => $avances_disponibles
        ]);
    }

    /**
     * Sauvegarde (ajoute ou modifie) un paiement avec gestion des avances
     */
    public function save()
    {
        $this->access_only_allowed_members();

        $this->validate_submitted_data([
            "id" => "numeric",
            "chauffeur_id" => "required|numeric",
            "montant" => "required|numeric",
            "date_paiement" => "required",
        ]);

        $id = $this->request->getPost('id');
        $type_paiement = $this->request->getPost('type_paiement');
        $montant = $this->request->getPost('montant');
        $chauffeur_id = $this->request->getPost('chauffeur_id');
        
        $data = [
            "chauffeur_id" => $chauffeur_id,
            "type_paiement" => $type_paiement,
            "montant" => $montant,
            "mois_concerne" => $this->request->getPost('mois_concerne'),
            "date_paiement" => $this->request->getPost('date_paiement'),
            "mode_paiement" => $this->request->getPost('mode_paiement'),
            "description" => $this->request->getPost('description'),
            "statut" => $this->request->getPost('statut'),
        ];

        // NOUVEAUTÉ : Gestion des avances
        $avance_reference_id = $this->request->getPost('avance_reference_id');
        $avance_deduite = $this->request->getPost('avance_deduite');
        
        if ($avance_reference_id && $avance_deduite > 0) {
            $data['avance_reference_id'] = $avance_reference_id;
            $data['avance_deduite'] = $avance_deduite;
        }

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
        } else {
            $data["id"] = $id;
        }

        $save_result = $this->Chauffeur_payments_model->save($data);

        if ($save_result) {
            $actual_id = $id ? $id : $save_result;
            
            // NOUVEAUTÉ : Déduction automatique des avances pour les salaires
            if (!$id && $type_paiement === 'salaire' && $data['statut'] === 'paye') {
                $this->Chauffeur_payments_model->deduire_avances_automatiquement(
                    $chauffeur_id, 
                    $montant, 
                    $actual_id
                );
            }

            $options = ["id" => $actual_id];
            $data = $this->Chauffeur_payments_model->get_details($options)->getRow();
            
            echo json_encode([
                "success" => true, 
                "data" => $this->_make_row($data), 
                'id' => $actual_id, 
                'message' => lang('record_saved')
            ]);
        } else {
            echo json_encode(["success" => false, 'message' => lang('error_occurred')]);
        }
    }

    /**
     * Supprime (soft delete) un paiement
     */
    public function delete()
    {
        $this->access_only_allowed_members();
        $this->validate_submitted_data(["id" => "required|numeric"]);
        $id = $this->request->getPost('id');

        if ($this->Chauffeur_payments_model->delete($id)) {
            echo json_encode(["success" => true, 'message' => lang('record_deleted')]);
        } else {
            echo json_encode(["success" => false, 'message' => lang('record_cannot_be_deleted')]);
        }
    }

    /**
     * Prépare les données pour la liste (DataTables)
     */
    public function list_data()
    {
        $this->access_only_allowed_members();
        $list_data = $this->Chauffeur_payments_model->get_details()->getResult();
        $result = [];
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(["data" => $result]);
    }

    /**
     * Prépare une ligne de données pour l'affichage dans la table
     */
    private function _make_row($data)
    {
        // Badge coloré pour le statut
        $statut_class = "bg-light";
        if ($data->statut == "paye") {
            $statut_class = "bg-success";
        } else if ($data->statut == "annule") {
            $statut_class = "bg-danger";
        } else if ($data->statut == "en_attente") {
            $statut_class = "bg-warning";
        }
        $statut = "<span class='badge $statut_class'>" . lang($data->statut) . "</span>";

        // NOUVEAUTÉ : Affichage des informations d'avance
        $type_display = lang($data->type_paiement);
        if ($data->type_paiement === 'avance') {
            $type_display .= " <small class='text-info'><i data-feather='arrow-up-circle' class='icon-12'></i></small>";
        } else if ($data->avance_deduite > 0) {
            $type_display .= " <small class='text-warning'><i data-feather='arrow-down-circle' class='icon-12'></i> -" . to_currency($data->avance_deduite) . "</small>";
        }

        $options = modal_anchor(get_uri("chauffeur_payments/modal_form"), "<i data-feather='edit' class='icon-16'></i>", [
            "class" => "edit", 
            "title" => "Modifier le paiement", 
            "data-post-id" => $data->id
        ]);
        
        $options .= js_anchor("<i data-feather='x' class='icon-16'></i>", [
            'title' => "Supprimer le paiement", 
            "class" => "delete", 
            "data-id" => $data->id, 
            "data-action-url" => get_uri("chauffeur_payments/delete"), 
            "data-action" => "delete-confirmation"
        ]);
        
        $options .= anchor(get_uri("chauffeur_payments/receipt/" . $data->id), "<i data-feather='printer' class='icon-16'></i>", [
            "class" => "edit", 
            "title" => "Voir/Imprimer le reçu", 
            "target" => "_blank"
        ]);

        return [
            $data->id,
            anchor(get_uri("chauffeurs/view/" . $data->chauffeur_id), $data->chauffeur_name),
            $type_display,
            to_currency($data->montant),
            $data->mois_concerne,
            format_to_date($data->date_paiement, false),
            $statut,
            $options
        ];
    }

    /**
     * NOUVELLE MÉTHODE : Vue pour consulter le solde des avances d'un chauffeur
     */
    public function solde_avances($chauffeur_id = null)
    {
        $this->access_only_allowed_members();
        
        if ($chauffeur_id) {
            validate_numeric_value($chauffeur_id);
            
            $view_data['chauffeur_id'] = $chauffeur_id;
            $view_data['solde_total'] = $this->Chauffeur_payments_model->get_solde_avances_chauffeur($chauffeur_id);
            $view_data['avances_detail'] = $this->Chauffeur_payments_model->get_avances_disponibles($chauffeur_id)->getResult();
            
            // Récupérer les infos du chauffeur
            $chauffeurs_model = model('App\Models\Chauffeurs_model');
            $view_data['chauffeur_info'] = $chauffeurs_model->get_details(['id' => $chauffeur_id])->getRow();
            
            return $this->template->view("chauffeur_payments/solde_avances", $view_data);
        } else {
            // Vue générale avec tous les chauffeurs
            $chauffeurs = model('App\Models\Chauffeurs_model')->get_details()->getResult();
            $view_data['chauffeurs_soldes'] = [];
            
            foreach ($chauffeurs as $chauffeur) {
                $solde = $this->Chauffeur_payments_model->get_solde_avances_chauffeur($chauffeur->id);
                if ($solde > 0) {
                    $view_data['chauffeurs_soldes'][] = [
                        'chauffeur' => $chauffeur,
                        'solde' => $solde
                    ];
                }
            }
            
            return $this->template->rander("chauffeur_payments/soldes_generaux", $view_data);
        }
    }

    /**
     * Affiche un reçu de paiement formaté pour l'impression
     */
    public function receipt($id = 0)
    {
        $this->access_only_allowed_members();

        if (!$id || !is_numeric($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $payment_info = $this->Chauffeur_payments_model->get_details(["id" => $id])->getRow();

        if (!$payment_info) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $view_data['payment_info'] = $payment_info;
        
        return $this->template->view("chauffeur_payments/receipt", $view_data);
    }
    
    /**
     * Récupère les statistiques globales pour les widgets du tableau de bord
     */
    public function get_statistics()
    {
        $this->access_only_allowed_members();
        
        try {
            $db = \Config\Database::connect();
            $current_month = date('Y-m');
            $current_year = date('Y');
            
            // 1. Total des paiements ce mois (montant)
            $total_paiements_mois = $db->query("
                SELECT COALESCE(SUM(montant), 0) as total
                FROM rise_chauffeur_payments 
                WHERE DATE_FORMAT(date_paiement, '%Y-%m') = '$current_month'
                AND statut = 'paye'
                AND deleted = 0
            ")->getRow()->total;
            
            // 2. Total des avances en cours (non entièrement déduites)
            $total_avances = $db->query("
                SELECT COALESCE(SUM(
                    CASE 
                        WHEN type_paiement = 'avance' AND statut = 'paye' THEN montant 
                        ELSE 0 
                    END
                ) - SUM(
                    CASE 
                        WHEN type_paiement != 'avance' AND avance_deduite > 0 AND statut = 'paye' THEN avance_deduite 
                        ELSE 0 
                    END
                ), 0) as total
                FROM rise_chauffeur_payments 
                WHERE deleted = 0
            ")->getRow()->total;
            
            // 3. Nombre de paiements en attente
            $paiements_attente = $db->query("
                SELECT COUNT(*) as total
                FROM rise_chauffeur_payments 
                WHERE statut = 'en_attente'
                AND deleted = 0
            ")->getRow()->total;
            
            // 4. Nombre de chauffeurs avec des avances en cours
            $chauffeurs_avec_avances = $db->query("
                SELECT COUNT(DISTINCT p1.chauffeur_id) as total
                FROM rise_chauffeur_payments p1
                WHERE p1.deleted = 0
                AND (
                    SELECT COALESCE(SUM(
                        CASE 
                            WHEN p2.type_paiement = 'avance' AND p2.statut = 'paye' THEN p2.montant 
                            ELSE 0 
                        END
                    ) - SUM(
                        CASE 
                            WHEN p2.type_paiement != 'avance' AND p2.avance_deduite > 0 AND p2.statut = 'paye' THEN p2.avance_deduite 
                            ELSE 0 
                        END
                    ), 0)
                    FROM rise_chauffeur_payments p2 
                    WHERE p2.chauffeur_id = p1.chauffeur_id 
                    AND p2.deleted = 0
                ) > 0
            ")->getRow()->total;
            
            echo json_encode([
                'success' => true,
                'data' => [
                    'total_paiements_mois' => number_format($total_paiements_mois, 0, ',', ' '),
                    'total_avances' => $total_avances,
                    'paiements_attente' => $paiements_attente,
                    'chauffeurs_avec_avances' => $chauffeurs_avec_avances
                ]
            ]);
            
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors du chargement des statistiques: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Récupère les données pour le graphique d'évolution des paiements (6 derniers mois)
     */
    public function get_payments_chart_data()
    {
        $this->access_only_allowed_members();
        
        try {
            $db = \Config\Database::connect();
            
            // Générer les 6 derniers mois
            $months = [];
            $labels = [];
            $amounts = [];
            $counts = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $date = date('Y-m', strtotime("-$i months"));
                $months[] = $date;
                $labels[] = date('M Y', strtotime("-$i months"));
            }
            
            foreach ($months as $month) {
                // Montant total du mois
                $amount = $db->query("
                    SELECT COALESCE(SUM(montant), 0) as total
                    FROM rise_chauffeur_payments 
                    WHERE DATE_FORMAT(date_paiement, '%Y-%m') = '$month'
                    AND statut = 'paye'
                    AND deleted = 0
                ")->getRow()->total;
                
                // Nombre de paiements du mois
                $count = $db->query("
                    SELECT COUNT(*) as total
                    FROM rise_chauffeur_payments 
                    WHERE DATE_FORMAT(date_paiement, '%Y-%m') = '$month'
                    AND statut = 'paye'
                    AND deleted = 0
                ")->getRow()->total;
                
                $amounts[] = floatval($amount);
                $counts[] = intval($count);
            }
            
            echo json_encode([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'amounts' => $amounts,
                    'counts' => $counts
                ]
            ]);
            
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors du chargement des données du graphique: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Récupère les données pour le graphique de répartition par type
     */
    public function get_types_chart_data()
    {
        $this->access_only_allowed_members();
        
        try {
            $db = \Config\Database::connect();
            $current_year = date('Y');
            
            $results = $db->query("
                SELECT 
                    type_paiement,
                    COUNT(*) as nombre,
                    SUM(montant) as montant_total
                FROM rise_chauffeur_payments 
                WHERE YEAR(date_paiement) = '$current_year'
                AND statut = 'paye'
                AND deleted = 0
                GROUP BY type_paiement
                ORDER BY montant_total DESC
            ")->getResult();
            
            $labels = [];
            $values = [];
            
            $type_labels = [
                'salaire' => 'Salaires',
                'prime' => 'Primes',
                'avance' => 'Avances',
                'remboursement' => 'Remboursements',
                'deduction' => 'Déductions'
            ];
            
            foreach ($results as $result) {
                $labels[] = isset($type_labels[$result->type_paiement]) 
                    ? $type_labels[$result->type_paiement] 
                    : ucfirst($result->type_paiement);
                $values[] = floatval($result->montant_total);
            }
            
            echo json_encode([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'values' => $values
                ]
            ]);
            
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors du chargement des données du graphique: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Récupère le top des chauffeurs avec les plus gros soldes d'avances
     */
    public function get_top_avances()
    {
        $this->access_only_allowed_members();
        
        try {
            $db = \Config\Database::connect();
            
            $results = $db->query("
                SELECT 
                    c.id,
                    CONCAT(c.prenom, ' ', c.nom) as chauffeur_name,
                    COALESCE(SUM(
                        CASE 
                            WHEN p.type_paiement = 'avance' AND p.statut = 'paye' THEN p.montant 
                            ELSE 0 
                        END
                    ) - SUM(
                        CASE 
                            WHEN p.type_paiement != 'avance' AND p.avance_deduite > 0 AND p.statut = 'paye' THEN p.avance_deduite 
                            ELSE 0 
                        END
                    ), 0) as solde_avance,
                    COUNT(CASE WHEN p.type_paiement = 'avance' AND p.statut = 'paye' THEN 1 END) as nb_avances
                FROM rise_chauffeurs c
                LEFT JOIN rise_chauffeur_payments p ON p.chauffeur_id = c.id AND p.deleted = 0
                WHERE c.deleted = 0
                GROUP BY c.id, c.prenom, c.nom
                HAVING solde_avance > 0
                ORDER BY solde_avance DESC
                LIMIT 5
            ")->getResult();
            
            $data = [];
            foreach ($results as $result) {
                $data[] = [
                    'chauffeur_id' => $result->id,
                    'chauffeur_name' => $result->chauffeur_name,
                    'solde' => number_format($result->solde_avance, 2) . ' DH',
                    'solde_raw' => floatval($result->solde_avance),
                    'nb_avances' => intval($result->nb_avances)
                ];
            }
            
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors du chargement du top avances: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Récupère l'activité récente (derniers paiements)
     */
    public function get_recent_activity()
    {
        $this->access_only_allowed_members();
        
        try {
            $results = $this->Chauffeur_payments_model->get_details()->getResult();
            
            // Prendre seulement les 8 plus récents
            $recent_payments = array_slice($results, 0, 8);
            
            $data = [];
            foreach ($recent_payments as $payment) {
                // Calculer le temps relatif
                $date_payment = new \DateTime($payment->date_paiement);
                $now = new \DateTime();
                $diff = $now->diff($date_payment);
                
                if ($diff->days == 0) {
                    if ($diff->h == 0) {
                        $date_relative = "Il y a " . $diff->i . " minute(s)";
                    } else {
                        $date_relative = "Il y a " . $diff->h . " heure(s)";
                    }
                } elseif ($diff->days == 1) {
                    $date_relative = "Hier";
                } elseif ($diff->days < 7) {
                    $date_relative = "Il y a " . $diff->days . " jour(s)";
                } else {
                    $date_relative = $date_payment->format('d/m/Y');
                }
                
                $data[] = [
                    'id' => $payment->id,
                    'chauffeur_name' => $payment->chauffeur_name,
                    'type_paiement' => ucfirst($payment->type_paiement),
                    'montant' => to_currency($payment->montant),
                    'date_paiement' => $payment->date_paiement,
                    'date_relative' => $date_relative,
                    'statut' => $payment->statut
                ];
            }
            
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors du chargement de l\'activité récente: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * BONUS: Récupère un résumé financier mensuel pour un chauffeur spécifique
     */
    public function get_chauffeur_monthly_summary($chauffeur_id = null)
    {
        $this->access_only_allowed_members();
        
        if (!$chauffeur_id) {
            $chauffeur_id = $this->request->getPost('chauffeur_id');
        }
        
        if (!$chauffeur_id || !is_numeric($chauffeur_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'ID chauffeur requis'
            ]);
            return;
        }
        
        try {
            $db = \Config\Database::connect();
            $current_month = date('Y-m');
            
            // Récupérer les totaux par type pour le mois en cours
            $results = $db->query("
                SELECT 
                    type_paiement,
                    COUNT(*) as nombre,
                    SUM(montant) as total,
                    SUM(CASE WHEN statut = 'paye' THEN montant ELSE 0 END) as total_paye,
                    SUM(CASE WHEN statut = 'en_attente' THEN montant ELSE 0 END) as total_attente
                FROM rise_chauffeur_payments 
                WHERE chauffeur_id = $chauffeur_id
                AND DATE_FORMAT(date_paiement, '%Y-%m') = '$current_month'
                AND deleted = 0
                GROUP BY type_paiement
            ")->getResult();
            
            // Solde d'avances total
            $solde_avances = $this->Chauffeur_payments_model->get_solde_avances_chauffeur($chauffeur_id);
            
            $summary = [
                'chauffeur_id' => $chauffeur_id,
                'mois' => $current_month,
                'solde_avances' => $solde_avances,
                'details' => []
            ];
            
            foreach ($results as $result) {
                $summary['details'][$result->type_paiement] = [
                    'nombre' => intval($result->nombre),
                    'total' => floatval($result->total),
                    'total_paye' => floatval($result->total_paye),
                    'total_attente' => floatval($result->total_attente)
                ];
            }
            
            echo json_encode([
                'success' => true,
                'data' => $summary
            ]);
            
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors du chargement du résumé: ' . $e->getMessage()
            ]);
        }
    }
}