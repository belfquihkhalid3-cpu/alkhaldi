<?php
namespace App\Models;

use CodeIgniter\Model;

class Fuel_cards_model extends Model
{
    protected $table            = 'rise_fuel_cards';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
protected $allowedFields    = [
    'numero_serie', 'type_carte', 'vehicle_id', 'chauffeur_id', 
    'solde_dotation', 'prix_litre', 'statut', 'date_creation', 
    'date_expiration'
];

    // Dates
    protected $useTimestamps = false;  // Pas de updated_at dans votre table
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';  // Existe dans votre table
    protected $deletedField  = 'deleted';

    // Validation
    protected $validationRules = [
        'numero_serie'     => 'required|min_length[5]|max_length[100]|is_unique[fuel_cards.numero_serie,id,{id}]',
        'type_carte'       => 'required|in_list[easyone,autre]',
        'vehicle_id'       => 'permit_empty|integer|greater_than[0]',
        'chauffeur_id'     => 'permit_empty|integer|greater_than[0]',
        'solde_dotation'   => 'permit_empty|decimal|greater_than_equal_to[0]',
        'prix_litre'       => 'permit_empty|decimal|greater_than[0]',
        'statut'           => 'in_list[active,inactive,bloquee]',
        'date_creation'    => 'permit_empty|valid_date[Y-m-d]',
        'date_expiration'  => 'permit_empty|valid_date[Y-m-d]',
    ];

    protected $validationMessages = [
        'numero_serie' => [
            'required' => 'Le numéro de série est obligatoire',
            'min_length' => 'Le numéro de série doit contenir au moins 5 caractères',
            'max_length' => 'Le numéro de série ne peut pas dépasser 100 caractères',
            'is_unique' => 'Ce numéro de série existe déjà',
        ],
        'type_carte' => [
            'required' => 'Le type de carte est obligatoire',
            'in_list' => 'Type de carte invalide',
        ],
        'vehicle_id' => [
            'integer' => 'L\'ID du véhicule doit être un nombre entier',
            'greater_than' => 'L\'ID du véhicule doit être supérieur à 0',
        ],
        'chauffeur_id' => [
            'integer' => 'L\'ID du chauffeur doit être un nombre entier',
            'greater_than' => 'L\'ID du chauffeur doit être supérieur à 0',
        ],
        'solde_dotation' => [
            'decimal' => 'Le solde doit être un nombre décimal',
            'greater_than_equal_to' => 'Le solde doit être supérieur ou égal à 0',
        ],
        'prix_litre' => [
            'decimal' => 'Le prix par litre doit être un nombre décimal',
            'greater_than' => 'Le prix par litre doit être supérieur à 0',
        ],
    ];

    protected $skipValidation = false;
    
    // Propriété pour stocker les erreurs de validation personnalisées
    protected $customValidationErrors = [];

    /**
     * Méthode pour obtenir les règles de validation avec conditions dynamiques
     */
    public function getValidationRules(array $options = []): array
    {
        $rules = $this->validationRules;
        return $rules;
    }

    /**
     * Validation personnalisée avec règles dynamiques
     */
    public function validateData($data)
    {
        $validation = \Config\Services::validation();
        $validation->setRules($this->validationRules);
        
        // Validation de base CodeIgniter 4
        $isValid = $validation->run($data);
        
        // Validations personnalisées
        if ($isValid) {
            $customErrors = [];
            
            // Validation date d'expiration (doit être dans le futur si spécifiée)
            if (!empty($data['date_expiration'])) {
                try {
                    $expDate = new \DateTime($data['date_expiration']);
                    $today = new \DateTime();
                    
                    if ($expDate <= $today) {
                        $customErrors['date_expiration'] = 'La date d\'expiration doit être dans le futur';
                    }
                } catch (\Exception $e) {
                    $customErrors['date_expiration'] = 'Format de date invalide';
                }
            }
            
            // Validation: une carte ne peut être assignée qu'à un véhicule OU un chauffeur
            if (!empty($data['vehicle_id']) && !empty($data['chauffeur_id'])) {
                $customErrors['vehicle_id'] = 'Une carte ne peut être assignée qu\'à un véhicule OU un chauffeur';
                $customErrors['chauffeur_id'] = 'Une carte ne peut être assignée qu\'à un véhicule OU un chauffeur';
            }
            
            // Si des erreurs personnalisées existent
            if (!empty($customErrors)) {
                $this->customValidationErrors = $customErrors;
                return false;
            }
        }
        
        return $isValid;
    }
    
    /**
     * Récupérer les erreurs de validation personnalisées
     */
    public function getCustomValidationErrors()
    {
        return $this->customValidationErrors ?? [];
    }

    /**
     * Récupérer toutes les cartes carburant avec filtres
     */
    public function get_all_fuel_cards($filters = [])
    {
        $builder = $this->builder();
        
        // Jointures pour récupérer les noms
        $builder->select('fuel_cards.*, 
                         vehicules.marque, vehicules.modele, vehicules.numero_matricule,
                         chauffeurs.nom as chauffeur_nom, chauffeurs.prenom as chauffeur_prenom')
                ->join('rise_vehicules vehicules', 'vehicules.id = fuel_cards.vehicle_id', 'left')
                ->join('rise_chauffeurs chauffeurs', 'chauffeurs.id = fuel_cards.chauffeur_id', 'left');
        
        if (!empty($filters['numero_serie'])) {
            $builder->like('fuel_cards.numero_serie', $filters['numero_serie']);
        }
        
        if (!empty($filters['type_carte'])) {
            $builder->where('fuel_cards.type_carte', $filters['type_carte']);
        }
        
        if (!empty($filters['statut'])) {
            $builder->where('fuel_cards.statut', $filters['statut']);
        }
        
        if (!empty($filters['vehicle_id'])) {
            $builder->where('fuel_cards.vehicle_id', $filters['vehicle_id']);
        }
        
        if (!empty($filters['chauffeur_id'])) {
            $builder->where('fuel_cards.chauffeur_id', $filters['chauffeur_id']);
        }

        if (!empty($filters['expire_soon'])) {
            $dateLimit = date('Y-m-d', strtotime('+30 days'));
            $builder->where('fuel_cards.date_expiration <=', $dateLimit)
                    ->where('fuel_cards.date_expiration >=', date('Y-m-d'));
        }
        
        return $builder->orderBy('fuel_cards.created_at', 'DESC')->get()->getResult();
    }

    /**
     * Récupérer les cartes actives
     */
    public function get_active_cards()
    {
        return $this->where('statut', 'active')
                   ->orderBy('numero_serie', 'ASC')
                   ->findAll();
    }

    /**
     * Récupérer les cartes disponibles (non assignées)
     */
    public function get_available_cards()
    {
        return $this->where('statut', 'active')
                   ->where('vehicle_id IS NULL')
                   ->where('chauffeur_id IS NULL')
                   ->orderBy('numero_serie', 'ASC')
                   ->findAll();
    }

    /**
     * Recherche avancée
     */
    public function search_fuel_cards($keyword = '', $filters = [])
    {
        $builder = $this->builder();
        
        $builder->select('fuel_cards.*, 
                         vehicules.marque, vehicules.modele, vehicules.numero_matricule,
                         chauffeurs.nom as chauffeur_nom, chauffeurs.prenom as chauffeur_prenom')
                ->join('vehicules', 'vehicules.id = fuel_cards.vehicle_id', 'left')
                ->join('chauffeurs', 'chauffeurs.id = fuel_cards.chauffeur_id', 'left');
        
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('fuel_cards.numero_serie', $keyword)
                    ->orLike('vehicules.numero_matricule', $keyword)
                    ->orLike('vehicules.marque', $keyword)
                    ->orLike('chauffeurs.nom', $keyword)
                    ->orLike('chauffeurs.prenom', $keyword)
                    ->groupEnd();
        }
        
        // Appliquer les filtres
        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                if (!empty($value) && in_array($field, $this->allowedFields)) {
                    $builder->where('fuel_cards.' . $field, $value);
                }
            }
        }
        
        return $builder->orderBy('fuel_cards.created_at', 'DESC')->get()->getResult();
    }

    /**
     * Statistiques des cartes carburant
     */
    public function get_statistics()
    {
        $total = $this->countAllResults();
        $active = $this->where('statut', 'active')->countAllResults();
        $inactive = $this->where('statut', 'inactive')->countAllResults();
        $bloquee = $this->where('statut', 'bloquee')->countAllResults();
        
        // Cartes assignées
        $assigned_to_vehicles = $this->where('vehicle_id IS NOT NULL')->countAllResults();
        $assigned_to_chauffeurs = $this->where('chauffeur_id IS NOT NULL')->countAllResults();
        $available = $this->where('vehicle_id IS NULL')->where('chauffeur_id IS NULL')->countAllResults();
        
        // Solde total des dotations
        $total_dotation = $this->selectSum('solde_dotation')->first();
        
        // Cartes expirant bientôt
        $expire_soon = $this->where('date_expiration <=', date('Y-m-d', strtotime('+30 days')))
                           ->where('date_expiration >=', date('Y-m-d'))
                           ->where('statut', 'active')
                           ->countAllResults();
        
        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'bloquee' => $bloquee,
            'assigned_to_vehicles' => $assigned_to_vehicles,
            'assigned_to_chauffeurs' => $assigned_to_chauffeurs,
            'available' => $available,
            'total_dotation' => $total_dotation->solde_dotation ?? 0,
            'expire_soon' => $expire_soon,
        ];
    }

    /**
     * Récupérer les cartes avec date d'expiration proche
     */
    public function get_expiring_cards($days = 30)
    {
        $dateLimit = date('Y-m-d', strtotime("+{$days} days"));
        
        return $this->select('fuel_cards.*, 
                             vehicules.marque, vehicules.modele, vehicules.numero_matricule,
                             chauffeurs.nom as chauffeur_nom, chauffeurs.prenom as chauffeur_prenom')
                   ->join('vehicules', 'vehicules.id = fuel_cards.vehicle_id', 'left')
                   ->join('chauffeurs', 'chauffeurs.id = fuel_cards.chauffeur_id', 'left')
                   ->where('fuel_cards.date_expiration <=', $dateLimit)
                   ->where('fuel_cards.date_expiration >=', date('Y-m-d'))
                   ->where('fuel_cards.statut', 'active')
                   ->orderBy('fuel_cards.date_expiration', 'ASC')
                   ->findAll();
    }

    /**
     * Récupérer les types de cartes uniques
     */
    public function get_card_types()
    {
        return $this->select('type_carte')
                   ->distinct()
                   ->orderBy('type_carte', 'ASC')
                   ->findAll();
    }

    /**
     * Assigner une carte à un véhicule
     */
    public function assign_to_vehicle($card_id, $vehicle_id)
    {
        return $this->update($card_id, [
            'vehicle_id' => $vehicle_id,
            'chauffeur_id' => null
        ]);
    }

    /**
     * Assigner une carte à un chauffeur
     */
    public function assign_to_chauffeur($card_id, $chauffeur_id)
    {
        return $this->update($card_id, [
            'chauffeur_id' => $chauffeur_id,
            'vehicle_id' => null
        ]);
    }

    /**
     * Libérer une carte (désassigner)
     */
    public function unassign_card($card_id)
    {
        return $this->update($card_id, [
            'vehicle_id' => null,
            'chauffeur_id' => null
        ]);
    }

    /**
     * Mettre à jour le solde d'une carte
     */
    public function update_balance($card_id, $new_balance)
    {
        return $this->update($card_id, ['solde_dotation' => $new_balance]);
    }

    /**
     * Récupérer les détails complets d'une carte
     */
    public function get_card_details($id)
    {
        $card = $this->select('fuel_cards.*, 
                              vehicules.marque, vehicules.modele, vehicules.numero_matricule,
                              chauffeurs.nom as chauffeur_nom, chauffeurs.prenom as chauffeur_prenom,
                              chauffeurs.telephone as chauffeur_telephone')
                    ->join('vehicules', 'vehicules.id = fuel_cards.vehicle_id', 'left')
                    ->join('chauffeurs', 'chauffeurs.id = fuel_cards.chauffeur_id', 'left')
                    ->find($id);
        
        if (!$card) {
            return null;
        }
        
        // Récupérer les consommations récentes
        $consumptionModel = model('App\Models\Fuel_consumption_model');
        $card->consommations_recentes = $consumptionModel->where('fuel_card_id', $id)
                                                         ->orderBy('date_plein', 'DESC')
                                                         ->limit(10)
                                                         ->findAll();
        
        return $card;
    }
}