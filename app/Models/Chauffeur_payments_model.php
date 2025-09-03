<?php
namespace App\Models;
use CodeIgniter\Model;

class Chauffeur_payments_model extends Model
{
    protected $table = 'rise_chauffeur_payments';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted';
    protected $returnType = 'object';
    
    protected $allowedFields = [
        'chauffeur_id',
        'type_paiement',
        'montant',
        'mois_concerne',
        'date_paiement',
        'mode_paiement',
        'description',
        'statut',
        'created_by',
        'avance_reference_id',
        'avance_deduite',
        'solde_avance',
        'deleted'
    ];
    
    protected $useTimestamps = false;
    
    protected $validationRules = [
        'chauffeur_id'  => 'required|integer',
        'type_paiement' => 'required|in_list[salaire,prime,avance,remboursement,deduction]',
        'montant'       => 'required|numeric',
        'date_paiement' => 'required|valid_date',
        'statut'        => 'required|in_list[en_attente,paye,annule]'
    ];

    /**
     * Méthode générique pour récupérer les détails des paiements
     */
    public function get_details($options = [])
    {
        $payments_table = $this->table;
        $chauffeurs_table = 'rise_chauffeurs';
        $users_table = 'rise_users';
        
        $builder = $this->db->table($payments_table);
        
        $builder->select("$payments_table.*, 
                          CONCAT($chauffeurs_table.prenom, ' ', $chauffeurs_table.nom) AS chauffeur_name,
                          CONCAT($users_table.first_name, ' ', $users_table.last_name) AS creator_name,
                          avance_ref.montant AS avance_originale,
                          avance_ref.date_paiement AS date_avance_originale");
        
        $builder->join($chauffeurs_table, "$chauffeurs_table.id = $payments_table.chauffeur_id", 'left');
        $builder->join($users_table, "$users_table.id = $payments_table.created_by", 'left');
        
        // Jointure pour récupérer les infos de l'avance de référence
        $builder->join("$payments_table as avance_ref", 
                      "avance_ref.id = $payments_table.avance_reference_id AND avance_ref.deleted = 0", 
                      'left');
        
        $builder->where("$payments_table.deleted", 0);
        
        // Filtres optionnels
        $id = get_array_value($options, "id");
        if ($id) {
            $builder->where("$payments_table.id", $id);
        }
        
        $chauffeur_id = get_array_value($options, "chauffeur_id");
        if ($chauffeur_id) {
            $builder->where("$payments_table.chauffeur_id", $chauffeur_id);
        }
        
        $type_paiement = get_array_value($options, "type_paiement");
        if ($type_paiement) {
            $builder->where("$payments_table.type_paiement", $type_paiement);
        }
        
        // Ordre par défaut : plus récents en premier
        $builder->orderBy("$payments_table.date_paiement", 'DESC');
        $builder->orderBy("$payments_table.id", 'DESC');
        
        return $builder->get();
    }
    
    /**
     * Calcule le solde total des avances pour un chauffeur
     */
    public function get_solde_avances_chauffeur($chauffeur_id)
    {
        $payments_table = $this->table;
        
        $builder = $this->db->table($payments_table);
        $builder->select("
            COALESCE(SUM(CASE WHEN type_paiement = 'avance' AND statut = 'paye' THEN montant ELSE 0 END), 0) as total_avances,
            COALESCE(SUM(CASE WHEN type_paiement != 'avance' AND avance_deduite > 0 AND statut = 'paye' THEN avance_deduite ELSE 0 END), 0) as total_deduites
        ");
        $builder->where('chauffeur_id', $chauffeur_id);
        $builder->where('deleted', 0);
        
        $result = $builder->get()->getRow();
        
        return $result->total_avances - $result->total_deduites;
    }
    
    /**
     * Récupère les avances non entièrement déduites pour un chauffeur
     */
    public function get_avances_disponibles($chauffeur_id)
    {
        $payments_table = $this->table;
        
        $builder = $this->db->table($payments_table);
        $builder->select("id, montant, date_paiement, description,
                         (montant - COALESCE((
                             SELECT SUM(avance_deduite) 
                             FROM $payments_table as p2 
                             WHERE p2.avance_reference_id = $payments_table.id 
                             AND p2.deleted = 0 
                             AND p2.statut = 'paye'
                         ), 0)) as solde_restant");
        
        $builder->where('chauffeur_id', $chauffeur_id);
        $builder->where('type_paiement', 'avance');
        $builder->where('statut', 'paye');
        $builder->where('deleted', 0);
        
        // Seulement les avances qui ont encore un solde
        $builder->having('solde_restant >', 0);
        
        $builder->orderBy('date_paiement', 'ASC');
        
        return $builder->get();
    }
    
    /**
     * Met à jour automatiquement les déductions d'avances lors d'un paiement
     */
    public function deduire_avances_automatiquement($chauffeur_id, $montant_paiement, $paiement_id)
    {
        $montant_restant = $montant_paiement;
        $total_deduit = 0;
        
        // Récupérer les avances disponibles par ordre chronologique
        $avances = $this->get_avances_disponibles($chauffeur_id)->getResult();
        
        foreach ($avances as $avance) {
            if ($montant_restant <= 0) break;
            
            $montant_a_deduire = min($montant_restant, $avance->solde_restant);
            
            if ($montant_a_deduire > 0) {
                // Mettre à jour le paiement avec la référence d'avance et le montant déduit
                $this->update($paiement_id, [
                    'avance_reference_id' => $avance->id,
                    'avance_deduite' => $montant_a_deduire
                ]);
                
                $montant_restant -= $montant_a_deduire;
                $total_deduit += $montant_a_deduire;
                
                // Si on ne peut déduire qu'une partie, on s'arrête ici
                // Sinon, on continue avec la prochaine avance
                if ($montant_a_deduire == $avance->solde_restant) {
                    continue;
                } else {
                    break;
                }
            }
        }
        
        return $total_deduit;
    }
}