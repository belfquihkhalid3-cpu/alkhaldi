<?php

namespace App\Models;

use CodeIgniter\Model;

class MiseADispo_model extends Model
{
    protected $table = 'rise_mise_a_dispo';
    protected $primaryKey = 'id';
   // SOFT DELETE - Configuration correcte pour votre structure
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted';  // Colonne TINYINT(1) DEFAULT 0
    
   
   

    protected $allowedFields = [
        'client_id',
        'nom_client',
        'telephone_client',
        'email_client',
        'hotel_partenaire',
        'type_service',
        'date_debut',
        'date_fin',
        'duree_heures',
        'chauffeur_id',
        'vehicle_id',
        'tarif_type',
        'prix_unitaire',
        'nombre_unites',
        'prix_total',
        'devise',
        'lieu_prise_en_charge',
        'destination_principale',
        'programme_detaille',
        'instructions_particulieres',
        'statut',
        'heure_debut_reelle',
        'heure_fin_reelle',
        'kilometres_parcourus',
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
        'type_service' => 'required|in_list[chauffeur_seul,vehicule_seul,chauffeur_vehicule]',
        'date_debut' => 'required',
        'date_fin' => 'required',
        'lieu_prise_en_charge' => 'required|min_length[3]',
        'tarif_type' => 'permit_empty|in_list[horaire,journalier,forfait]',
        'prix_unitaire' => 'permit_empty|decimal',
        'prix_total' => 'permit_empty|decimal',
        'statut' => 'permit_empty|in_list[demande,confirme,en_cours,termine,facture,annule]'
    ];

    /**
     * Convertit les dates et heures HTML5 vers MySQL
     */
    public function formatDates($data)
    {
        // Dates de dï¿½but et fin
        if (isset($data['date_debut']) && strpos($data['date_debut'], 'T') !== false) {
            $data['date_debut'] = str_replace('T', ' ', $data['date_debut']) . ':00';
        }
        
        if (isset($data['date_fin']) && strpos($data['date_fin'], 'T') !== false) {
            $data['date_fin'] = str_replace('T', ' ', $data['date_fin']) . ':00';
        }
        
        // Heures rï¿½elles
        if (isset($data['heure_debut_reelle']) && strpos($data['heure_debut_reelle'], 'T') !== false) {
            $data['heure_debut_reelle'] = str_replace('T', ' ', $data['heure_debut_reelle']) . ':00';
        }
        
        if (isset($data['heure_fin_reelle']) && strpos($data['heure_fin_reelle'], 'T') !== false) {
            $data['heure_fin_reelle'] = str_replace('T', ' ', $data['heure_fin_reelle']) . ':00';
        }
        
        return $data;
    }

    /**
     * Calcule automatiquement la durï¿½e en heures
     */
    public function calculateDuration($data)
    {
        if (isset($data['date_debut']) && isset($data['date_fin'])) {
            $debut = new \DateTime($data['date_debut']);
            $fin = new \DateTime($data['date_fin']);
            $interval = $debut->diff($fin);
            
            // Convertir en heures dï¿½cimales
            $heures = $interval->days * 24 + $interval->h + ($interval->i / 60);
            $data['duree_heures'] = round($heures, 2);
            
            // Calculer nombre d'unitï¿½s selon le type de tarif
            if (isset($data['tarif_type'])) {
                switch ($data['tarif_type']) {
                    case 'horaire':
                        $data['nombre_unites'] = $heures;
                        break;
                    case 'journalier':
                        $data['nombre_unites'] = ceil($heures / 24); // Journï¿½es entiï¿½res
                        break;
                    case 'forfait':
                        $data['nombre_unites'] = 1;
                        break;
                }
            }
        }
        
        return $data;
    }

    /**
     * Calcule le prix total automatiquement
     */
    public function calculateTotal($data)
    {
        if (isset($data['prix_unitaire']) && isset($data['nombre_unites'])) {
            $data['prix_total'] = $data['prix_unitaire'] * $data['nombre_unites'];
        }
        
        return $data;
    }

    /**
     * Rï¿½cupï¿½re les dï¿½tails des mises ï¿½ disposition avec jointures
     */
    public function get_details($options = [])
    {
        $mise_a_dispo_table = $this->table;
        $clients_table = 'rise_clients';
        $vehicles_table = 'rise_vehicules';
        $chauffeurs_table = 'rise_chauffeurs';

        $builder = $this->db->table($mise_a_dispo_table);

        $builder->select("$mise_a_dispo_table.*, 
                          $clients_table.company_name as client_company,
                          $clients_table.phone as client_phone_db,
                          CONCAT(IFNULL($vehicles_table.marque, ''), ' ', IFNULL($vehicles_table.modele, ''), ' (', IFNULL($vehicles_table.numero_matricule, ''), ')') AS vehicle_name,
                          CONCAT(IFNULL($chauffeurs_table.prenom, ''), ' ', IFNULL($chauffeurs_table.nom, '')) AS chauffeur_name,
                          CASE 
                              WHEN $mise_a_dispo_table.date_debut <= NOW() AND $mise_a_dispo_table.date_fin >= NOW() THEN 'En cours'
                              WHEN $mise_a_dispo_table.date_debut > NOW() THEN 'ï¿½ venir'
                              ELSE 'Passï¿½'
                          END as periode_statut,
                          DATEDIFF($mise_a_dispo_table.date_fin, $mise_a_dispo_table.date_debut) + 1 as nombre_jours");

        // Jointures LEFT
        $builder->join($clients_table, "$clients_table.id = $mise_a_dispo_table.client_id", 'left');
        $builder->join($vehicles_table, "$vehicles_table.id = $mise_a_dispo_table.vehicle_id", 'left');
        $builder->join($chauffeurs_table, "$chauffeurs_table.id = $mise_a_dispo_table.chauffeur_id", 'left');

        // Filtre soft delete
        $builder->where("$mise_a_dispo_table.deleted", 0);

        // Filtres optionnels
        if ($id = get_array_value($options, "id")) {
            $builder->where("$mise_a_dispo_table.id", $id);
        }

        if ($statut = get_array_value($options, "statut")) {
            $builder->where("$mise_a_dispo_table.statut", $statut);
        }

        if ($date_debut = get_array_value($options, "date_debut")) {
            $builder->where("$mise_a_dispo_table.date_debut >=", $date_debut);
        }

        if ($date_fin = get_array_value($options, "date_fin")) {
            $builder->where("$mise_a_dispo_table.date_fin <=", $date_fin);
        }

        if ($type_service = get_array_value($options, "type_service")) {
            $builder->where("$mise_a_dispo_table.type_service", $type_service);
        }

        if ($chauffeur_id = get_array_value($options, "chauffeur_id")) {
            $builder->where("$mise_a_dispo_table.chauffeur_id", $chauffeur_id);
        }

        if ($vehicle_id = get_array_value($options, "vehicle_id")) {
            $builder->where("$mise_a_dispo_table.vehicle_id", $vehicle_id);
        }

        if ($client_id = get_array_value($options, "client_id")) {
            $builder->where("$mise_a_dispo_table.client_id", $client_id);
        }

        // Recherche textuelle
        if ($search = get_array_value($options, "search")) {
            $builder->groupStart();
            $builder->like("$mise_a_dispo_table.nom_client", $search);
            $builder->orLike("$mise_a_dispo_table.hotel_partenaire", $search);
            $builder->orLike("$mise_a_dispo_table.lieu_prise_en_charge", $search);
            $builder->orLike("$mise_a_dispo_table.destination_principale", $search);
            $builder->groupEnd();
        }

        // Filtre pï¿½riode active
        if (get_array_value($options, "periode_active")) {
            $builder->where("$mise_a_dispo_table.date_debut <=", date('Y-m-d H:i:s'));
            $builder->where("$mise_a_dispo_table.date_fin >=", date('Y-m-d H:i:s'));
        }

        // Ordre par dï¿½faut
        $builder->orderBy("$mise_a_dispo_table.date_debut", "DESC");

        return $builder->get();
    }

    /**
     * Statistiques du module mise ï¿½ disposition
     */
    public function get_statistics()
    {
        try {
            $today = date('Y-m-d');
            $debut_mois = date('Y-m-01');
            $fin_mois = date('Y-m-t');
            
            $stats = [];
            $builder = $this->db->table($this->table);
            
            // Total mises ï¿½ disposition
            $stats['total_mises_a_dispo'] = $builder->where('deleted', 0)->countAllResults(false);
            
            // En cours aujourd'hui
            $builder = $this->db->table($this->table);
            $stats['en_cours_aujourdhui'] = $builder->where('deleted', 0)
                ->where('date_debut <=', $today . ' 23:59:59')
                ->where('date_fin >=', $today . ' 00:00:00')
                ->where('statut', 'en_cours')
                ->countAllResults();
            
            // Confirmï¿½es ï¿½ venir
            $builder = $this->db->table($this->table);
            $stats['confirmees_a_venir'] = $builder->where('deleted', 0)
                ->where('date_debut >', date('Y-m-d H:i:s'))
                ->where('statut', 'confirme')
                ->countAllResults();
            
            // En attente de confirmation
            $builder = $this->db->table($this->table);
            $stats['en_attente'] = $builder->where('deleted', 0)
                ->where('statut', 'demande')
                ->countAllResults();
            
            // Chiffre d'affaires du mois
            $builder = $this->db->table($this->table);
            $ca_result = $builder->selectSum('prix_total')
                ->where('deleted', 0)
                ->where('statut', 'facture')
                ->where('date_debut >=', $debut_mois)
                ->where('date_debut <=', $fin_mois . ' 23:59:59')
                ->get()->getRow();
            
            $stats['ca_mois'] = $ca_result->prix_total ?? 0;
            
            // Note moyenne
            $builder = $this->db->table($this->table);
            $note_result = $builder->selectAvg('note_service')
                ->where('deleted', 0)
                ->where('note_service IS NOT NULL')
                ->get()->getRow();
            
            $stats['note_moyenne'] = round($note_result->note_service ?? 0, 1);

            // Debug log
            log_message('info', 'Statistiques mise ï¿½ disposition calculï¿½es: ' . json_encode($stats));

            return $stats;
            
        } catch (\Exception $e) {
            log_message('error', 'Erreur statistiques mise ï¿½ disposition: ' . $e->getMessage());
            
            return [
                'total_mises_a_dispo' => 0,
                'en_cours_aujourdhui' => 0,
                'confirmees_a_venir' => 0,
                'en_attente' => 0,
                'ca_mois' => 0,
                'note_moyenne' => 0
            ];
        }
    }

    /**
     * Vï¿½rifier la disponibilitï¿½ d'un chauffeur ou vï¿½hicule
     */
    public function checkDisponibilite($date_debut, $date_fin, $chauffeur_id = null, $vehicle_id = null, $exclude_id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->where('deleted', 0);
        $builder->whereNotIn('statut', ['annule', 'termine']);
        
        // Chevauchement de dates
        $builder->groupStart();
        $builder->where('date_debut <=', $date_fin);
        $builder->where('date_fin >=', $date_debut);
        $builder->groupEnd();
        
        if ($chauffeur_id) {
            $builder->where('chauffeur_id', $chauffeur_id);
        }
        
        if ($vehicle_id) {
            $builder->where('vehicle_id', $vehicle_id);
        }
        
        if ($exclude_id) {
            $builder->where('id !=', $exclude_id);
        }
        
        $conflit = $builder->get()->getRow();
        
        return $conflit ? false : true;
    }

    /**
     * Change le statut avec historique
     */
    public function change_statut($mise_a_dispo_id, $nouveau_statut, $commentaire = '', $user_id = null)
    {
        $this->db->transStart();
        
        try {
            // Rï¿½cupï¿½rer l'ancien statut
            $mise_a_dispo = $this->find($mise_a_dispo_id);
            $ancien_statut = $mise_a_dispo->statut ?? null;
            
            // Mettre ï¿½ jour le statut
            $this->update($mise_a_dispo_id, ['statut' => $nouveau_statut]);
            
            // Enregistrer dans l'historique si la table existe
            if ($this->db->tableExists('rise_mise_a_dispo_historique')) {
                $this->db->table('rise_mise_a_dispo_historique')->insert([
                    'mise_a_dispo_id' => $mise_a_dispo_id,
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
            log_message('error', 'Erreur changement statut mise ï¿½ disposition: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Rï¿½cupï¿½re l'historique des changements de statut
     */
    public function get_historique_statut($mise_a_dispo_id)
    {
        try {
            if (!$this->db->tableExists('rise_mise_a_dispo_historique')) {
                return [];
            }
            
            return $this->db->table('rise_mise_a_dispo_historique h')
                ->select('h.*, u.first_name, u.last_name')
                ->join('rise_users u', 'u.id = h.changed_by', 'left')
                ->where('h.mise_a_dispo_id', $mise_a_dispo_id)
                ->orderBy('h.changed_at', 'DESC')
                ->get()
                ->getResult();
                
        } catch (\Exception $e) {
            log_message('error', 'Erreur rï¿½cupï¿½ration historique mise ï¿½ disposition: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Rï¿½cupï¿½rer les conflits de planning
     */
    public function getConflitsPlanning($date_debut, $date_fin)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('deleted', 0);
        $builder->whereNotIn('statut', ['annule']);
        
        // Chevauchement de dates
        $builder->groupStart();
        $builder->where('date_debut <=', $date_fin);
        $builder->where('date_fin >=', $date_debut);
        $builder->groupEnd();
        
        return $builder->get()->getResult();
    }
}