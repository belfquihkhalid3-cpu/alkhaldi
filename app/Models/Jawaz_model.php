<?php

namespace App\Models;

use CodeIgniter\Model;

class Jawaz_model extends Model
{
    // Nom de la table dans la base de données
    protected $table = 'rise_jawaz';

    // Clé primaire
    protected $primaryKey = 'id';

    // Activer la suppression douce (soft delete)
    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted';

    // Type de retour
    protected $returnType = 'object';

    // Champs autorisés à être insérés/mis à jour
    protected $allowedFields = [
        'numero_serie',
    'vehicle_id',
    'chauffeur_id',
    'solde',
    'solde_retour',           // AJOUTER
    'consommation_mensuelle',
    'evenement',
    'date_affectation',
    'date_retour',            // AJOUTER
    'motif_retour',           // AJOUTER
    'peut_redistribuer',      // AJOUTER
    'statut',
    'deleted'
    ];

    // Les timestamps ne sont pas gérés par le modèle
    protected $useTimestamps = false;

    // Règles de validation pour les formulaires
   // Règles de validation pour les formulaires
protected $validationRules = [
    'numero_serie'      => 'required',
    'vehicle_id'        => 'permit_empty|integer',
    'chauffeur_id'      => 'permit_empty|integer',
    'date_affectation'  => 'required',
    'statut'            => 'required|in_list[actif,inactif,retourne,en_maintenance]',
    'solde'             => 'permit_empty|numeric'
];

/**
 * Valide l'unicité du numéro de série
 */
public function validateUniqueNumeroSerie($data)
{
    $numero_serie = $data['numero_serie'] ?? '';
    $id = $data['id'] ?? 0;
    
    $builder = $this->db->table($this->table);
    $builder->where('numero_serie', $numero_serie);
    $builder->where('deleted', 0);
    
    if ($id > 0) {
        $builder->where('id !=', $id);
    }
    
    $existing = $builder->get()->getRow();
    
    if ($existing) {
        $this->setValidationError('numero_serie', 'Ce numéro de série existe déjà');
        return false;
    }
    
    return true;
}

    /**
     * Override de la méthode save pour ajouter du debugging
     */
  public function save($data): bool
{
    log_message('info', 'Jawaz_model->save() called with data: ' . json_encode($data));
    
    // Vérifier les règles de validation de base
    if (!$this->validate($data)) {
        $errors = $this->errors();
        log_message('error', 'Jawaz_model validation failed: ' . json_encode($errors));
        return false;
    }
    
    // Vérifier l'unicité du numéro de série
    if (!$this->validateUniqueNumeroSerie($data)) {
        log_message('error', 'Numero serie not unique');
        return false;
    }
    
    try {
        $result = parent::save($data);
        log_message('info', 'Jawaz_model->save() result: ' . ($result ? 'TRUE' : 'FALSE'));
        return $result;
    } catch (\Exception $e) {
        log_message('error', 'Jawaz_model->save() exception: ' . $e->getMessage());
        return false;
    }
}
     public function process_redistribution($jawaz_id, $data)
    {
        $this->db->transStart();
        
        try {
            // Formatage des dates
            $data = $this->formatDates($data);
            
            // Réinitialiser le badge pour nouvelle affectation
            $update_data = [
                'vehicle_id' => $data['vehicle_id'],
                'chauffeur_id' => $data['chauffeur_id'],
                'date_affectation' => $data['date_affectation'],
                'solde' => $data['solde'],
                'date_retour' => null,
                'solde_retour' => null,
                'motif_retour' => null,
                'statut' => 'actif'
            ];
            
            $this->update($jawaz_id, $update_data);
            
            // Enregistrer dans l'historique
            $this->enregistrer_historique($jawaz_id, 'redistribution', $data);
            
            $this->db->transComplete();
            
            return $this->db->transStatus();
            
        } catch (Exception $e) {
            $this->db->transRollback();
            return false;
        }
    }

     public function process_retour($jawaz_id, $data)
    {
        $this->db->transStart();
        
        try {
            // Formatage des dates
            $data = $this->formatDates($data);
            
            // Mise à jour du badge
            $update_data = [
                'date_retour' => $data['date_retour'],
                'solde_retour' => $data['solde_retour'],
                'motif_retour' => $data['motif_retour'] ?? '',
                'peut_redistribuer' => $data['peut_redistribuer'] ?? 1,
                'statut' => 'retourne'
            ];
            
            $this->update($jawaz_id, $update_data);
            
            // Enregistrer dans l'historique
            $this->enregistrer_historique($jawaz_id, 'retour', $data);
            
            $this->db->transComplete();
            
            return $this->db->transStatus();
            
        } catch (Exception $e) {
            $this->db->transRollback();
            return false;
        }
    }
      private function enregistrer_historique($jawaz_id, $type_mouvement, $data)
    {
        $historique_data = [
            'jawaz_id' => $jawaz_id,
            'type_mouvement' => $type_mouvement,
            'vehicle_id' => $data['vehicle_id'] ?? null,
            'chauffeur_id' => $data['chauffeur_id'] ?? null,
            'solde_avant' => $data['solde_avant'] ?? null,
            'solde_apres' => $data['solde'] ?? $data['solde_retour'] ?? null,
            'date_mouvement' => $data['date_retour'] ?? $data['date_affectation'] ?? date('Y-m-d H:i:s'),
            'motif' => $data['motif_retour'] ?? $data['evenement'] ?? null
        ];
        
        $this->db->table('rise_jawaz_historique')->insert($historique_data);
    }
       public function formatDates($data)
    {
        if (isset($data['date_affectation']) && strpos($data['date_affectation'], 'T') !== false) {
            $data['date_affectation'] = str_replace('T', ' ', $data['date_affectation']) . ':00';
        }
        
        if (isset($data['date_retour']) && !empty($data['date_retour'])) {
            if (strpos($data['date_retour'], 'T') !== false) {
                $data['date_retour'] = str_replace('T', ' ', $data['date_retour']) . ':00';
            } else {
                // Si c'est juste une date, ajouter l'heure
                $data['date_retour'] = $data['date_retour'] . ' 00:00:00';
            }
        }
        
        return $data;
    }
public function get_statistics()
{
    try {
        $jawaz_table = $this->table;
        $db = \Config\Database::connect();
        
        // Total des badges (requête directe)
        $stats['total_badges'] = $db->table($jawaz_table)
            ->where('deleted', 0)
            ->countAllResults();
            
        // Badges actifs (requête directe)
        $stats['badges_actifs'] = $db->table($jawaz_table)
            ->where('deleted', 0)
            ->where('statut', 'actif')
            ->countAllResults();
            
        // Badges retournés (requête directe)
        $stats['badges_retournes'] = $db->table($jawaz_table)
            ->where('deleted', 0)
            ->where('statut', 'retourne')
            ->countAllResults();
            
        // Solde total en circulation (requête directe)
        $result = $db->table($jawaz_table)
            ->selectSum('solde')
            ->where('deleted', 0)
            ->where('statut', 'actif')
            ->get()
            ->getRow();
        $stats['solde_total'] = $result->solde ?? 0;
        
        // Solde moyen par badge
        if ($stats['badges_actifs'] > 0) {
            $stats['solde_moyen'] = $stats['solde_total'] / $stats['badges_actifs'];
        } else {
            $stats['solde_moyen'] = 0;
        }
        
        // Badges disponibles pour redistribution
        $stats['badges_redistribuables'] = 0;

        // Log pour debug
        log_message('info', 'Statistiques calculées: ' . json_encode($stats));

        return $stats;
        
    } catch (\Exception $e) {
        log_message('error', 'Erreur get_statistics: ' . $e->getMessage());
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

    /**
     * Méthode générique pour récupérer les détails des badges Jawaz.
     */
    public function get_details($options = [])
    {
        $this->useSoftDeletes = true;
        $jawaz_table = $this->table;
        $vehicles_table = 'rise_vehicules';
        $chauffeurs_table = 'rise_chauffeurs';

        $builder = $this->db->table($jawaz_table);

        // Sélection des champs nécessaires avec calcul de jours_utilisation
        $builder->select("$jawaz_table.*, 
                          CONCAT($vehicles_table.marque, ' ', $vehicles_table.modele, ' (', $vehicles_table.numero_matricule, ')') AS vehicle_name,
                          CONCAT($chauffeurs_table.prenom, ' ', $chauffeurs_table.nom) AS chauffeur_name,
                          DATEDIFF(NOW(), $jawaz_table.date_affectation) AS jours_utilisation");

        // Jointure avec la table des véhicules
        $builder->join($vehicles_table, "$vehicles_table.id = $jawaz_table.vehicle_id", 'left');

        // Jointure avec la table des chauffeurs
        $builder->join($chauffeurs_table, "$chauffeurs_table.id = $jawaz_table.chauffeur_id", 'left');

        // Filtre de base pour exclure les enregistrements supprimés
        $builder->where("$jawaz_table.deleted", 0);

        // -- Filtres optionnels --
        $id = get_array_value($options, "id");
        if ($id) {
            $builder->where("$jawaz_table.id", $id);
        }

        $vehicle_id = get_array_value($options, "vehicle_id");
        if ($vehicle_id) {
            $builder->where("$jawaz_table.vehicle_id", $vehicle_id);
        }
        
        $chauffeur_id = get_array_value($options, "chauffeur_id");
        if ($chauffeur_id) {
            $builder->where("$jawaz_table.chauffeur_id", $chauffeur_id);
        }

        $statut = get_array_value($options, "statut");
        if ($statut) {
            $builder->where("$jawaz_table.statut", $statut);
        }

        return $builder->get();
    }
}