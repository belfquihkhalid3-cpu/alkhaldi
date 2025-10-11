<?php

namespace App\Models;

use App\Models\Crud_model;

class Chauffeurs_model extends Crud_model {
    
    protected $table = 'rise_chauffeurs';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nom', 'prenom', 'cnie', 'telephone', 'telephone_urgence', 
        'email', 'adresse', 'date_naissance', 'date_embauche',
        'numero_permis', 'date_expiration_permis', 'categorie_permis',
        'salaire_base', 'statut', 'observations', 'photo'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted';
    
    protected $validationRules = [
        'nom' => 'required|max_length[100]',
        'prenom' => 'required|max_length[100]',
        'telephone' => 'required|max_length[20]',
        'email' => 'permit_empty|valid_email|max_length[255]',
        'cnie' => 'permit_empty|max_length[20]',
        'statut' => 'required|in_list[actif,inactif,suspendu]'
    ];
    
    protected $validationMessages = [
        'nom' => [
            'required' => 'Le nom est obligatoire',
            'max_length' => 'Le nom ne peut pas dépasser 100 caractères'
        ],
        'prenom' => [
            'required' => 'Le prénom est obligatoire',
            'max_length' => 'Le prénom ne peut pas dépasser 100 caractères'
        ],
        'telephone' => [
            'required' => 'Le téléphone est obligatoire'
        ],
        'email' => [
            'valid_email' => 'Veuillez saisir un email valide'
        ]
    ];
    
    public function __construct() {
        parent::__construct();
        $this->table = 'rise_chauffeurs';
    }

    /**
     * Récupérer les détails des chauffeurs avec filtres
     */
    public function get_details($options = array()) {
        $chauffeurs_table = $this->table;
        
        $where = "";
        
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $chauffeurs_table.id = " . intval($id);
        }
        
        $statut = get_array_value($options, "statut");
        if ($statut) {
            $where .= " AND $chauffeurs_table.statut = '" . $this->db->escapeString($statut) . "'";
        }
        
        $search = get_array_value($options, "search");
        if ($search) {
            $search = $this->db->escapeString($search);
            $where .= " AND ($chauffeurs_table.nom LIKE '%$search%' 
                        OR $chauffeurs_table.prenom LIKE '%$search%' 
                        OR $chauffeurs_table.cnie LIKE '%$search%'
                        OR $chauffeurs_table.telephone LIKE '%$search%')";
        }

        $sql = "SELECT $chauffeurs_table.*
                FROM $chauffeurs_table
                WHERE $chauffeurs_table.deleted = 0 $where
                ORDER BY $chauffeurs_table.prenom ASC, $chauffeurs_table.nom ASC";

        return $this->db->query($sql);
    }

    /**
     * Récupérer un chauffeur avec ses détails complets
     */
    public function get_chauffeur_details($id) {
        $chauffeurs_table = $this->table;
        
        $sql = "SELECT $chauffeurs_table.*
                FROM $chauffeurs_table
                WHERE $chauffeurs_table.deleted = 0 
                AND $chauffeurs_table.id = ?";

        return $this->db->query($sql, [$id])->getRow();
    }

    /**
     * Récupérer les chauffeurs disponibles (actifs)
     */
    public function get_available_chauffeurs() {
        $options = array('statut' => 'actif');
        return $this->get_details($options)->getResult();
    }

    /**
     * Recherche de chauffeurs
     */
    public function search_chauffeurs($keyword, $filters = array()) {
        $options = array();
        
        if (!empty($keyword)) {
            $options['search'] = $keyword;
        }
        
        if (!empty($filters['statut'])) {
            $options['statut'] = $filters['statut'];
        }
        
        return $this->get_details($options)->getResult();
    }

    /**
     * Mettre à jour le statut d'un chauffeur
     */
    public function update_status($id, $statut) {
        $data = array('statut' => $statut);
        return $this->update($id, $data);
    }

    /**
     * Obtenir les statistiques des chauffeurs
     */
    public function get_statistics() {
        $chauffeurs_table = $this->table;
        
        $sql = "SELECT 
                    COUNT(*) as total_chauffeurs,
                    COUNT(CASE WHEN statut = 'actif' THEN 1 END) as chauffeurs_actifs,
                    COUNT(CASE WHEN statut = 'inactif' THEN 1 END) as chauffeurs_inactifs,
                    COUNT(CASE WHEN statut = 'suspendu' THEN 1 END) as chauffeurs_suspendus
                FROM $chauffeurs_table 
                WHERE deleted = 0";

        return $this->db->query($sql)->getRow();
    }

    /**
     * Obtenir les chauffeurs avec permis expirant bientôt
     */
    public function get_expiring_licenses($days = 30) {
        $chauffeurs_table = $this->table;
        
        $sql = "SELECT $chauffeurs_table.*
                FROM $chauffeurs_table
                WHERE $chauffeurs_table.deleted = 0 
                AND $chauffeurs_table.date_expiration_permis IS NOT NULL 
                AND $chauffeurs_table.date_expiration_permis BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
                ORDER BY $chauffeurs_table.date_expiration_permis ASC";

        return $this->db->query($sql, [$days])->getResult();
    }

    /**
     * Vérifier si un chauffeur peut être supprimé
     */
    public function can_delete($id) {
        // Vérifier s'il y a des locations actives
        $sql = "SELECT COUNT(*) as count 
                FROM rise_locations 
                WHERE chauffeur_id = ? 
                AND statut IN ('confirmee', 'en_cours') 
                AND deleted = 0";
        
        $result = $this->db->query($sql, [$id])->getRow();
        
        return ($result->count == 0);
    }

    /**
     * Obtenir les performances d'un chauffeur
     */
    public function get_performance($id) {
        $sql = "SELECT 
                    COUNT(*) as total_locations,
                    COUNT(CASE WHEN statut = 'terminee' THEN 1 END) as locations_terminees,
                    COUNT(CASE WHEN statut IN ('confirmee', 'en_cours') THEN 1 END) as locations_actives,
                    COALESCE(SUM(prix_total), 0) as revenus_generes,
                    AVG(CASE WHEN statut = 'terminee' THEN 
                        DATEDIFF(date_fin, date_debut) + 1 
                    END) as duree_moyenne
                FROM rise_locations 
                WHERE chauffeur_id = ? AND deleted = 0";
        
        return $this->db->query($sql, [$id])->getRow();
    }

    /**
     * Formater les dates avant insertion/mise à jour
     */
    protected function formatDates($data) {
        $dateFields = ['date_naissance', 'date_embauche', 'date_expiration_permis'];
        
        foreach ($dateFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                // Conversion HTML5 datetime-local vers MySQL datetime
                if (strpos($data[$field], 'T') !== false) {
                    $data[$field] = str_replace('T', ' ', $data[$field]);
                    if (strlen($data[$field]) == 16) { // Si pas de secondes
                        $data[$field] .= ':00';
                    }
                }
            }
        }
        
        return $data;
    }

    /**
     * Override de la méthode insert pour formater les dates
     */
    public function insert($data = null, bool $returnID = true) {
        if (is_array($data)) {
            $data = $this->formatDates($data);
        }
        
        return parent::insert($data, $returnID);
    }
public function get_active_chauffeurs() {
    return $this->get_details(['statut' => 'actif'])->getResult();
}
    /**
     * Override de la méthode update pour formater les dates
     */
    public function update($id = null, $data = null): bool {
        if (is_array($data)) {
            $data = $this->formatDates($data);
        }
        
        return parent::update($id, $data);
    }
}