<?php

namespace App\Models;

use CodeIgniter\Model;

class Transferts_model extends Model
{
    protected $table = 'rise_transferts';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted';
    protected $returnType = 'object';

    protected $allowedFields = [
        'client_id',
        'nom_client',
        'telephone_client', 
        'email_client',
        'nombre_passagers',
        'type_transfert',
        'service_type',
        'numero_vol',
        'compagnie',
        'heure_arrivee_prevue',
        'heure_depart_prevue', 
        'date_transfert',
        'lieu_prise_en_charge',
        'lieu_destination',
        'adresse_complete',
        'vehicle_id',
        'chauffeur_id',
        'prix_prevu',
        'prix_facture',
        'devise',
        'statut',
        'heure_prise_en_charge_reelle',
        'heure_arrivee_reelle',
        'instructions_particulieres',
        'notes_chauffeur',
        'remarques_client',
        'note_service',
        'commentaire_evaluation',
        'created_by',
        'deleted'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'nom_client' => 'required|min_length[3]',
        'type_transfert' => 'required|in_list[arrivee,depart,aller_retour]',
        'service_type' => 'required|in_list[aeroport_hotel,hotel_aeroport,gare_hotel,autre]',
        'date_transfert' => 'required',
        'lieu_prise_en_charge' => 'required|min_length[3]',
        'lieu_destination' => 'required|min_length[3]',
        'nombre_passagers' => 'permit_empty|integer|greater_than[0]',
        'vehicle_id' => 'permit_empty|integer',
        'chauffeur_id' => 'permit_empty|integer',
        'prix_prevu' => 'permit_empty|decimal',
        'statut' => 'permit_empty|in_list[reserve,confirme,en_cours,termine,annule]'
    ];

    /**
     * Convertit les dates et heures HTML5 vers MySQL
     */
    public function formatDates($data)
    {
        // Date de transfert
        if (isset($data['date_transfert']) && strpos($data['date_transfert'], 'T') !== false) {
            $data['date_transfert'] = date('Y-m-d', strtotime($data['date_transfert']));
        }
        
        // Heures prévues
        if (isset($data['heure_arrivee_prevue']) && !empty($data['heure_arrivee_prevue'])) {
            $data['heure_arrivee_prevue'] = date('H:i:s', strtotime($data['heure_arrivee_prevue']));
        }
        
        if (isset($data['heure_depart_prevue']) && !empty($data['heure_depart_prevue'])) {
            $data['heure_depart_prevue'] = date('H:i:s', strtotime($data['heure_depart_prevue']));
        }
        
        // Heures réelles (datetime)
        if (isset($data['heure_prise_en_charge_reelle']) && strpos($data['heure_prise_en_charge_reelle'], 'T') !== false) {
            $data['heure_prise_en_charge_reelle'] = str_replace('T', ' ', $data['heure_prise_en_charge_reelle']) . ':00';
        }
        
        if (isset($data['heure_arrivee_reelle']) && strpos($data['heure_arrivee_reelle'], 'T') !== false) {
            $data['heure_arrivee_reelle'] = str_replace('T', ' ', $data['heure_arrivee_reelle']) . ':00';
        }
        
        return $data;
    }

    /**
     * Récupère les détails des transferts avec jointures
     */
    public function get_details($options = [])
    {
        $transferts_table = $this->table;
        $clients_table = 'rise_clients';
        $vehicles_table = 'rise_vehicules';
        $chauffeurs_table = 'rise_chauffeurs';

        $builder = $this->db->table($transferts_table);

        $builder->select("$transferts_table.*, 
                          $clients_table.company_name as client_company,
                          $clients_table.phone as client_phone_db,
                          CONCAT(IFNULL($vehicles_table.marque, ''), ' ', IFNULL($vehicles_table.modele, ''), ' (', IFNULL($vehicles_table.numero_matricule, ''), ')') AS vehicle_name,
                          CONCAT(IFNULL($chauffeurs_table.prenom, ''), ' ', IFNULL($chauffeurs_table.nom, '')) AS chauffeur_name,
                          CASE 
                              WHEN $transferts_table.date_transfert = CURDATE() THEN 'Aujourd\\'hui'
                              WHEN $transferts_table.date_transfert = DATE_ADD(CURDATE(), INTERVAL 1 DAY) THEN 'Demain'
                              WHEN $transferts_table.date_transfert < CURDATE() THEN 'Passé'
                              ELSE 'À venir'
                          END as periode_relative");

        // Jointures LEFT
        $builder->join($clients_table, "$clients_table.id = $transferts_table.client_id", 'left');
        $builder->join($vehicles_table, "$vehicles_table.id = $transferts_table.vehicle_id", 'left');
        $builder->join($chauffeurs_table, "$chauffeurs_table.id = $transferts_table.chauffeur_id", 'left');

        // Filtre soft delete
        $builder->where("$transferts_table.deleted", 0);

        // Filtres optionnels
        if ($id = get_array_value($options, "id")) {
            $builder->where("$transferts_table.id", $id);
        }

        if ($statut = get_array_value($options, "statut")) {
            $builder->where("$transferts_table.statut", $statut);
        }

        if ($date_debut = get_array_value($options, "date_debut")) {
            $builder->where("$transferts_table.date_transfert >=", $date_debut);
        }

        if ($date_fin = get_array_value($options, "date_fin")) {
            $builder->where("$transferts_table.date_transfert <=", $date_fin);
        }

        if ($type_transfert = get_array_value($options, "type_transfert")) {
            $builder->where("$transferts_table.type_transfert", $type_transfert);
        }

        if ($chauffeur_id = get_array_value($options, "chauffeur_id")) {
            $builder->where("$transferts_table.chauffeur_id", $chauffeur_id);
        }

        if ($vehicle_id = get_array_value($options, "vehicle_id")) {
            $builder->where("$transferts_table.vehicle_id", $vehicle_id);
        }

        // Recherche textuelle
        if ($search = get_array_value($options, "search")) {
            $builder->groupStart();
            $builder->like("$transferts_table.nom_client", $search);
            $builder->orLike("$transferts_table.numero_vol", $search);
            $builder->orLike("$transferts_table.lieu_prise_en_charge", $search);
            $builder->orLike("$transferts_table.lieu_destination", $search);
            $builder->groupEnd();
        }

        // Ordre par défaut
        $builder->orderBy("$transferts_table.date_transfert", "DESC");
        $builder->orderBy("$transferts_table.heure_arrivee_prevue", "ASC");

        // ✅ CORRECTION CRITIQUE : Retourner l'objet de requête, pas le builder
        return $builder->get();
    }

    /**
     * ✅ CORRECTION : Statistiques du module transferts
     */
    public function get_statistics()
    {
        try {
            $today = date('Y-m-d');
            $tomorrow = date('Y-m-d', strtotime('+1 day'));
            $debut_mois = date('Y-m-01');
            $fin_mois = date('Y-m-t');
            
            $stats = [];
            
            // ✅ CORRECTION : Utiliser le builder pour compter
            $builder = $this->db->table($this->table);
            
            // Total transferts
            $stats['total_transferts'] = $builder->where('deleted', 0)->countAllResults(false);
            
            // Transferts aujourd'hui
            $builder = $this->db->table($this->table);
            $stats['transferts_aujourdhui'] = $builder->where('deleted', 0)
                ->where('date_transfert', $today)
                ->countAllResults();
            
            // Transferts demain
            $builder = $this->db->table($this->table);
            $stats['transferts_demain'] = $builder->where('deleted', 0)
                ->where('date_transfert', $tomorrow)
                ->countAllResults();
            
            // Transferts en cours
            $builder = $this->db->table($this->table);
            $stats['transferts_en_cours'] = $builder->where('deleted', 0)
                ->where('statut', 'en_cours')
                ->countAllResults();
            
            // Chiffre d'affaires du mois
            $builder = $this->db->table($this->table);
            $ca_result = $builder->selectSum('prix_facture')
                ->where('deleted', 0)
                ->where('statut', 'termine')
                ->where('date_transfert >=', $debut_mois)
                ->where('date_transfert <=', $fin_mois)
                ->get()->getRow();
            
            $stats['ca_mois'] = $ca_result->prix_facture ?? 0;
            
            // Note moyenne
            $builder = $this->db->table($this->table);
            $note_result = $builder->selectAvg('note_service')
                ->where('deleted', 0)
                ->where('note_service IS NOT NULL')
                ->get()->getRow();
            
            $stats['note_moyenne'] = round($note_result->note_service ?? 0, 1);

            // ✅ Debug log
            log_message('info', 'Statistiques transferts calculées: ' . json_encode($stats));

            return $stats;
            
        } catch (\Exception $e) {
            log_message('error', 'Erreur statistiques transferts: ' . $e->getMessage());
            
            // ✅ Retourner des valeurs par défaut en cas d'erreur
            return [
                'total_transferts' => 0,
                'transferts_aujourdhui' => 0,
                'transferts_demain' => 0,
                'transferts_en_cours' => 0,
                'ca_mois' => 0,
                'note_moyenne' => 0
            ];
        }
    }

    /**
     * Change le statut d'un transfert avec historique
     */
    public function change_statut($transfert_id, $nouveau_statut, $commentaire = '', $user_id = null)
    {
        $this->db->transStart();
        
        try {
            // Récupérer l'ancien statut
            $transfert = $this->find($transfert_id);
            $ancien_statut = $transfert->statut ?? null;
            
            // Mettre à jour le statut
            $this->update($transfert_id, ['statut' => $nouveau_statut]);
            
            // ✅ Vérifier si la table d'historique existe
            if ($this->db->tableExists('rise_transferts_historique')) {
                // Enregistrer dans l'historique
                $this->db->table('rise_transferts_historique')->insert([
                    'transfert_id' => $transfert_id,
                    'ancien_statut' => $ancien_statut,
                    'nouveau_statut' => $nouveau_statut,
                    'commentaire' => $commentaire,
                    'changed_by' => $user_id,
                    'changed_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            $this->db->transComplete();
            return $this->db->transStatus();
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Erreur changement statut transfert: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère l'historique des changements de statut
     */
    public function get_historique_statut($transfert_id)
    {
        try {
            if (!$this->db->tableExists('rise_transferts_historique')) {
                return [];
            }
            
            // ✅ CORRECTION : Utiliser first_name et last_name
            return $this->db->table('rise_transferts_historique h')
                ->select('h.*, u.first_name, u.last_name')
                ->join('rise_users u', 'u.id = h.changed_by', 'left')
                ->where('h.transfert_id', $transfert_id)
                ->orderBy('h.changed_at', 'DESC')
                ->get()
                ->getResult();
                
        } catch (\Exception $e) {
            log_message('error', 'Erreur récupération historique: ' . $e->getMessage());
            return [];
        }
    }
}