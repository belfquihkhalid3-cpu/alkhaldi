<?php
namespace App\Models;

use CodeIgniter\Model;

class Locations_model extends Model
{
    protected $table            = 'rise_locations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'client_id', 'vehicle_id', 'chauffeur_id', 'date_debut', 'date_fin', 
        'lieu_depart', 'lieu_arrivee', 'prix_total', 'statut', 'type_service', 
        'observations', 'titre', 'description', 'created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted';

    // Validation
    protected $validationRules = [
        'client_id'      => 'required|integer|greater_than[0]',
        'date_debut'     => 'required|valid_date[Y-m-d H:i:s]',
        'date_fin'       => 'required|valid_date[Y-m-d H:i:s]',
        'lieu_depart'    => 'required|min_length[3]|max_length[300]',
        'lieu_arrivee'   => 'required|min_length[3]|max_length[300]',
        'prix_total'     => 'permit_empty|decimal|greater_than_equal_to[0]',
        'statut'         => 'in_list[en_attente,confirmee,en_cours,terminee,annulee]',
        'type_service'   => 'required|in_list[transfert,location_journee,location_longue,evenement]',
        'titre'          => 'required|min_length[3]|max_length[255]',
        'vehicle_id'     => 'permit_empty|integer|greater_than[0]',
        'chauffeur_id'   => 'permit_empty|integer|greater_than[0]',
    ];

    protected $validationMessages = [
        'client_id' => [
            'required' => 'Le client est obligatoire',
            'integer' => 'ID client invalide',
            'greater_than' => 'ID client invalide',
        ],
        'date_debut' => [
            'required' => 'La date de début est obligatoire',
            'valid_date' => 'Format de date/heure invalide',
        ],
        'date_fin' => [
            'required' => 'La date de fin est obligatoire',
            'valid_date' => 'Format de date/heure invalide',
        ],
        'lieu_depart' => [
            'required' => 'Le lieu de départ est obligatoire',
            'min_length' => 'Le lieu de départ doit contenir au moins 3 caractères',
        ],
        'lieu_arrivee' => [
            'required' => 'Le lieu d\'arrivée est obligatoire',
            'min_length' => 'Le lieu d\'arrivée doit contenir au moins 3 caractères',
        ],
        'titre' => [
            'required' => 'Le titre est obligatoire',
            'min_length' => 'Le titre doit contenir au moins 3 caractères',
        ],
        'type_service' => [
            'required' => 'Le type de service est obligatoire',
            'in_list' => 'Type de service invalide',
        ],
    ];

    protected $skipValidation = false;
    
    // Propriété pour stocker les erreurs de validation personnalisées
    protected $customValidationErrors = [];

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
            
            // Validation dates : fin après début
            if (!empty($data['date_debut']) && !empty($data['date_fin'])) {
                try {
                    $debut = new \DateTime($data['date_debut']);
                    $fin = new \DateTime($data['date_fin']);
                    
                    if ($fin <= $debut) {
                        $customErrors['date_fin'] = 'La date de fin doit être après la date de début';
                    }
                    
                    // Validation : pas dans le passé (sauf modification)
                    $now = new \DateTime();
                    if ($debut < $now && !isset($data['id'])) {
                        $customErrors['date_debut'] = 'La date de début ne peut pas être dans le passé';
                    }
                } catch (\Exception $e) {
                    $customErrors['date_debut'] = 'Format de date invalide';
                }
            }
            
            // Validation disponibilité véhicule
            if (!empty($data['vehicle_id']) && !empty($data['date_debut']) && !empty($data['date_fin'])) {
                $locationId = $data['id'] ?? null;
                if (!$this->isVehicleAvailable($data['vehicle_id'], $data['date_debut'], $data['date_fin'], $locationId)) {
                    $customErrors['vehicle_id'] = 'Ce véhicule n\'est pas disponible pour cette période';
                }
            }
            
            // Validation disponibilité chauffeur
            if (!empty($data['chauffeur_id']) && !empty($data['date_debut']) && !empty($data['date_fin'])) {
                $locationId = $data['id'] ?? null;
                if (!$this->isChauffeurAvailable($data['chauffeur_id'], $data['date_debut'], $data['date_fin'], $locationId)) {
                    $customErrors['chauffeur_id'] = 'Ce chauffeur n\'est pas disponible pour cette période';
                }
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
     * Récupérer toutes les locations avec filtres et jointures
     */
    public function get_all_locations($filters = [])
    {
        $builder = $this->builder();
        
        // Jointures pour récupérer les noms
        $builder->select('rise_locations.*, 
                         clients.company_name, clients.type as client_type, clients.phone, clients.address,
                         vehicules.marque, vehicules.modele, vehicules.numero_matricule,
                         chauffeurs.nom as chauffeur_nom, chauffeurs.prenom as chauffeur_prenom,
                         users.first_name as created_by_name, users.last_name as created_by_lastname')
                ->join('rise_clients clients', 'clients.id = rise_locations.client_id', 'left')
                ->join('rise_vehicules vehicules', 'vehicules.id = rise_locations.vehicle_id', 'left')
                ->join('rise_chauffeurs chauffeurs', 'chauffeurs.id = rise_locations.chauffeur_id', 'left')
                ->join('rise_users users', 'users.id = rise_locations.created_by', 'left');
        
        // Appliquer les filtres
        if (!empty($filters['client_id'])) {
            $builder->where('rise_locations.client_id', $filters['client_id']);
        }
        
        if (!empty($filters['vehicle_id'])) {
            $builder->where('rise_locations.vehicle_id', $filters['vehicle_id']);
        }
        
        if (!empty($filters['chauffeur_id'])) {
            $builder->where('rise_locations.chauffeur_id', $filters['chauffeur_id']);
        }
        
        if (!empty($filters['statut'])) {
            $builder->where('rise_locations.statut', $filters['statut']);
        }
        
        if (!empty($filters['type_service'])) {
            $builder->where('rise_locations.type_service', $filters['type_service']);
        }
        
        if (!empty($filters['date_debut'])) {
            $builder->where('DATE(rise_locations.date_debut) >=', $filters['date_debut']);
        }
        
        if (!empty($filters['date_fin'])) {
            $builder->where('DATE(rise_locations.date_fin) <=', $filters['date_fin']);
        }
        
        return $builder->orderBy('rise_locations.date_debut', 'DESC')->get()->getResult();
    }

    /**
     * Récupérer les locations actives (en cours ou confirmées)
     */
    public function get_active_locations()
    {
        return $this->select('rise_locations.*, 
                             clients.company_name, clients.type as client_type, clients.phone, clients.address,
                             vehicules.marque, vehicules.modele, vehicules.numero_matricule,
                             chauffeurs.nom as chauffeur_nom, chauffeurs.prenom as chauffeur_prenom')
                   ->join('rise_clients clients', 'clients.id = rise_locations.client_id', 'left')
                   ->join('rise_vehicules vehicules', 'vehicules.id = rise_locations.vehicle_id', 'left')
                   ->join('rise_chauffeurs chauffeurs', 'chauffeurs.id = rise_locations.chauffeur_id', 'left')
                   ->whereIn('rise_locations.statut', ['confirmee', 'en_cours'])
                   ->orderBy('rise_locations.date_debut', 'ASC')
                   ->findAll();
    }

    /**
     * Récupérer les locations du jour
     */
    public function get_today_locations()
    {
        $today = date('Y-m-d');
        
        return $this->select('rise_locations.*, 
                             clients.company_name, clients.type as client_type, clients.phone, clients.address,
                             vehicules.marque, vehicules.modele, vehicules.numero_matricule,
                             chauffeurs.nom as chauffeur_nom, chauffeurs.prenom as chauffeur_prenom')
                   ->join('rise_clients clients', 'clients.id = rise_locations.client_id', 'left')
                   ->join('rise_vehicules vehicules', 'vehicules.id = rise_locations.vehicle_id', 'left')
                   ->join('rise_chauffeurs chauffeurs', 'chauffeurs.id = rise_locations.chauffeur_id', 'left')
                   ->where('DATE(rise_locations.date_debut) <=', $today)
                   ->where('DATE(rise_locations.date_fin) >=', $today)
                   ->whereIn('rise_locations.statut', ['confirmee', 'en_cours'])
                   ->orderBy('rise_locations.date_debut', 'ASC')
                   ->findAll();
    }

    /**
     * Vérifier la disponibilité d'un véhicule
     */
    public function isVehicleAvailable($vehicle_id, $date_debut, $date_fin, $exclude_location_id = null)
    {
        $builder = $this->builder();
        
        $builder->where('vehicle_id', $vehicle_id)
                ->whereIn('statut', ['confirmee', 'en_cours'])
                ->groupStart()
                    ->where('date_debut <=', $date_fin)
                    ->where('date_fin >=', $date_debut)
                ->groupEnd();
        
        if ($exclude_location_id) {
            $builder->where('id !=', $exclude_location_id);
        }
        
        return $builder->countAllResults() == 0;
    }

    /**
     * Vérifier la disponibilité d'un chauffeur
     */
    public function isChauffeurAvailable($chauffeur_id, $date_debut, $date_fin, $exclude_location_id = null)
    {
        $builder = $this->builder();
        
        $builder->where('chauffeur_id', $chauffeur_id)
                ->whereIn('statut', ['confirmee', 'en_cours'])
                ->groupStart()
                    ->where('date_debut <=', $date_fin)
                    ->where('date_fin >=', $date_debut)
                ->groupEnd();
        
        if ($exclude_location_id) {
            $builder->where('id !=', $exclude_location_id);
        }
        
        return $builder->countAllResults() == 0;
    }

    /**
     * Récupérer les véhicules disponibles pour une période
     */
    public function getAvailableVehicles($date_debut, $date_fin, $exclude_location_id = null)
    {
        $vehiclesModel = model('App\Models\Vehicles_model');
        $allVehicles = $vehiclesModel->where('statut', 'disponible')->findAll();
        
        $availableVehicles = [];
        foreach ($allVehicles as $vehicle) {
            if ($this->isVehicleAvailable($vehicle->id, $date_debut, $date_fin, $exclude_location_id)) {
                $availableVehicles[] = $vehicle;
            }
        }
        
        return $availableVehicles;
    }

    /**
     * Récupérer les chauffeurs disponibles pour une période
     */
    public function getAvailableChauffeurs($date_debut, $date_fin, $exclude_location_id = null)
    {
        $chauffeursModel = model('App\Models\Chauffeurs_model');
        $allChauffeurs = $chauffeursModel->where('statut', 'actif')->findAll();
        
        $availableChauffeurs = [];
        foreach ($allChauffeurs as $chauffeur) {
            if ($this->isChauffeurAvailable($chauffeur->id, $date_debut, $date_fin, $exclude_location_id)) {
                $availableChauffeurs[] = $chauffeur;
            }
        }
        
        return $availableChauffeurs;
    }

    /**
     * Recherche avancée
     */
    public function search_locations($keyword = '', $filters = [])
    {
        $builder = $this->builder();
        
        $builder->select('rise_locations.*, 
                         clients.company_name, clients.type as client_type, clients.phone, clients.address,
                         vehicules.marque, vehicules.modele, vehicules.numero_matricule,
                         chauffeurs.nom as chauffeur_nom, chauffeurs.prenom as chauffeur_prenom')
                ->join('rise_clients clients', 'clients.id = rise_locations.client_id', 'left')
                ->join('rise_vehicules vehicules', 'vehicules.id = rise_locations.vehicle_id', 'left')
                ->join('rise_chauffeurs chauffeurs', 'chauffeurs.id = rise_locations.chauffeur_id', 'left');
        
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('rise_locations.titre', $keyword)
                    ->orLike('rise_locations.lieu_depart', $keyword)
                    ->orLike('rise_locations.lieu_arrivee', $keyword)
                    ->orLike('clients.company_name', $keyword)
                    ->orLike('vehicules.numero_matricule', $keyword)
                    ->orLike('chauffeurs.nom', $keyword)
                    ->groupEnd();
        }
        
        // Appliquer les filtres
        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                if (!empty($value) && in_array($field, $this->allowedFields)) {
                    $builder->where('rise_locations.' . $field, $value);
                }
            }
        }
        
        return $builder->orderBy('rise_locations.date_debut', 'DESC')->get()->getResult();
    }

    /**
     * Statistiques des locations
     */
   public function get_statistics()
{
    $db = \Config\Database::connect();
    
    // Total
    $total = $db->table('rise_locations')
               ->where('deleted', 0)
               ->countAllResults();
    
    // Statistiques par statut
    $statut_stats = $db->query("
        SELECT statut, COUNT(*) as count 
        FROM rise_locations 
        WHERE deleted = 0 
        GROUP BY statut
    ")->getResult();
    
    // Initialiser les compteurs
    $stats = [
        'en_attente' => 0,
        'confirmee' => 0,
        'en_cours' => 0,
        'terminee' => 0,
        'annulee' => 0
    ];
    
    // Remplir les vraies valeurs
    foreach ($statut_stats as $stat) {
        $stats[$stat->statut] = (int)$stat->count;
    }
    
    // CA du mois
    $currentMonth = date('Y-m');
    $ca_result = $db->query("
        SELECT SUM(prix_total) as total_ca 
        FROM rise_locations 
        WHERE statut = 'terminee' 
        AND deleted = 0 
        AND prix_total > 0
    ", [$currentMonth])->getRow();
    
    $ca_mois = $ca_result ? (float)$ca_result->total_ca : 0;
    
    // Locations aujourd'hui
    $today = date('Y-m-d');
    $today_result = $db->query("
        SELECT COUNT(*) as count 
        FROM rise_locations 
        WHERE DATE(date_debut) <= ? 
        AND DATE(date_fin) >= ? 
        AND statut IN ('confirmee', 'en_cours') 
        AND deleted = 0
    ", [$today, $today])->getRow();
    
    $today_locations = $today_result ? (int)$today_result->count : 0;
    
    return [
        'total' => $total,
        'en_attente' => $stats['en_attente'],
        'confirmee' => $stats['confirmee'],
        'en_cours' => $stats['en_cours'], 
        'terminee' => $stats['terminee'],
        'annulee' => $stats['annulee'],
        'ca_mois' => $ca_mois,
        'today_locations' => $today_locations,
    ];
}

    /**
     * Récupérer les données pour le calendrier
     */
    public function getCalendarData($start_date = null, $end_date = null)
    {
        $builder = $this->builder();
        
        $builder->select('rise_locations.id, rise_locations.titre, rise_locations.date_debut, 
                         rise_locations.date_fin, rise_locations.statut, rise_locations.type_service,
                         clients.company_name, clients.type as client_type,
                         vehicules.marque, vehicules.modele, vehicules.numero_matricule')
                ->join('rise_clients clients', 'clients.id = rise_locations.client_id', 'left')
                ->join('rise_vehicules vehicules', 'vehicules.id = rise_locations.vehicle_id', 'left')
                ->whereNotIn('rise_locations.statut', ['annulee']);
        
        if ($start_date) {
            $builder->where('rise_locations.date_fin >=', $start_date);
        }
        
        if ($end_date) {
            $builder->where('rise_locations.date_debut <=', $end_date);
        }
        
        $locations = $builder->get()->getResult();
        
        // Formater pour le calendrier
        $events = [];
        foreach ($locations as $location) {
            $color = $this->getStatusColor($location->statut);
            $clientName = $location->company_name ?: 'Client #' . $location->client_id;
            
            $events[] = [
                'id' => $location->id,
                'title' => $location->titre,
                'start' => $location->date_debut,
                'end' => $location->date_fin,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'client' => $clientName,
                    'vehicle' => $location->numero_matricule ?? ($location->marque . ' ' . $location->modele),
                    'status' => $location->statut,
                    'type' => $location->type_service
                ]
            ];
        }
        
        return $events;
    }

    /**
     * Récupérer la couleur selon le statut
     */
    private function getStatusColor($statut)
    {
        $colors = [
            'en_attente' => '#ffc107',
            'confirmee' => '#28a745',
            'en_cours' => '#007bff',
            'terminee' => '#6c757d',
            'annulee' => '#dc3545'
        ];
        
        return $colors[$statut] ?? '#6c757d';
    }

    /**
     * Changer le statut d'une location
     */
    public function changeStatus($location_id, $new_status)
    {
        $validStatuses = ['en_attente', 'confirmee', 'en_cours', 'terminee', 'annulee'];
        
        if (!in_array($new_status, $validStatuses)) {
            return false;
        }
        
        return $this->update($location_id, ['statut' => $new_status]);
    }

    /**
     * Récupérer les détails complets d'une location
     */
    public function get_location_details($id)
    {
        $location = $this->select('rise_locations.*, 
                                  clients.company_name, clients.type as client_type, clients.phone, clients.address,
                                  vehicules.marque, vehicules.modele, vehicules.numero_matricule,
                                  chauffeurs.nom as chauffeur_nom, chauffeurs.prenom as chauffeur_prenom,
                                  chauffeurs.telephone as chauffeur_telephone,
                                  users.first_name as created_by_name, users.last_name as created_by_lastname')
                        ->join('rise_clients clients', 'clients.id = rise_locations.client_id', 'left')
                        ->join('rise_vehicules vehicules', 'vehicules.id = rise_locations.vehicle_id', 'left')
                        ->join('rise_chauffeurs chauffeurs', 'chauffeurs.id = rise_locations.chauffeur_id', 'left')
                        ->join('rise_users users', 'users.id = rise_locations.created_by', 'left')
                        ->find($id);
        
        if (!$location) {
            return null;
        }
        
        // Calculer la durée
        $debut = new \DateTime($location->date_debut);
        $fin = new \DateTime($location->date_fin);
        $location->duree = $debut->diff($fin);
        
        return $location;
    }
    public function get_details($options = [])
{
    $builder = $this->builder();
    
    // Jointures par défaut
    $builder->select('rise_locations.*, 
                     clients.company_name, clients.type as client_type, clients.phone, clients.address,
                     vehicules.marque, vehicules.modele, vehicules.numero_matricule,
                     chauffeurs.nom as chauffeur_nom, chauffeurs.prenom as chauffeur_prenom,
                     chauffeurs.telephone as chauffeur_telephone,
                     users.first_name as created_by_name, users.last_name as created_by_lastname')
            ->join('rise_clients clients', 'clients.id = rise_locations.client_id', 'left')
            ->join('rise_vehicules vehicules', 'vehicules.id = rise_locations.vehicle_id', 'left')
            ->join('rise_chauffeurs chauffeurs', 'chauffeurs.id = rise_locations.chauffeur_id', 'left')
            ->join('rise_users users', 'users.id = rise_locations.created_by', 'left');
    
    // Condition WHERE avec ID
    if (isset($options["id"])) {
        $builder->where("rise_locations.id", $options["id"]);
    }
    
    // Conditions WHERE personnalisées
    if (isset($options["where"]) && is_array($options["where"])) {
        foreach ($options["where"] as $key => $value) {
            $builder->where("rise_locations." . $key, $value);
        }
    }
    
    // Filtres spécifiques aux locations
    if (isset($options['filters']) && is_array($options['filters'])) {
        $filters = $options['filters'];
        
        if (!empty($filters['client_id'])) {
            $builder->where('rise_locations.client_id', $filters['client_id']);
        }
        
        if (!empty($filters['vehicle_id'])) {
            $builder->where('rise_locations.vehicle_id', $filters['vehicle_id']);
        }
        
        if (!empty($filters['chauffeur_id'])) {
            $builder->where('rise_locations.chauffeur_id', $filters['chauffeur_id']);
        }
        
        if (!empty($filters['statut'])) {
            $builder->where('rise_locations.statut', $filters['statut']);
        }
        
        if (!empty($filters['type_service'])) {
            $builder->where('rise_locations.type_service', $filters['type_service']);
        }
        
        if (!empty($filters['date_debut'])) {
            $builder->where('DATE(rise_locations.date_debut) >=', $filters['date_debut']);
        }
        
        if (!empty($filters['date_fin'])) {
            $builder->where('DATE(rise_locations.date_fin) <=', $filters['date_fin']);
        }
    }
    
    // Limite
    if (isset($options["limit"])) {
        $builder->limit($options["limit"]);
    }
    
    // Tri
    $builder->orderBy('rise_locations.date_debut', 'DESC');
    
    // IMPORTANT : Retourner l'objet de requête selon vos conventions
    return $builder->get();
}

/**
 * Méthode save_location() pour respecter les conventions
 */
public function save_location($data, $id = null)
{
    if ($id) {
        // Mise à jour
        $data['id'] = $id;
        if ($this->save($data)) {
            return $id;
        }
    } else {
        // Insertion
        if ($this->save($data)) {
            return $this->getInsertID();
        }
    }
    return false;
}

    /**
     * Récupérer les locations d'un client
     */
    public function get_client_locations($client_id, $limit = 10)
    {
        return $this->where('client_id', $client_id)
                   ->orderBy('date_debut', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Récupérer les locations d'un véhicule
     */
    public function get_vehicle_locations($vehicle_id, $limit = 10)
    {
        return $this->where('vehicle_id', $vehicle_id)
                   ->orderBy('date_debut', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Récupérer les locations d'un chauffeur
     */
    public function get_chauffeur_locations($chauffeur_id, $limit = 10)
    {
        return $this->where('chauffeur_id', $chauffeur_id)
                   ->orderBy('date_debut', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}