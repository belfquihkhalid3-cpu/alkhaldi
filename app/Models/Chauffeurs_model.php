<?php
namespace App\Models;

use CodeIgniter\Model;

class Chauffeurs_model extends Model
{
    protected $table            = 'rise_chauffeurs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nom', 'prenom', 'cnie', 'telephone', 'telephone_urgence', 
        'email', 'adresse', 'date_naissance', 'date_embauche', 
        'numero_permis', 'date_expiration_permis', 'categorie_permis', 
        'salaire_base', 'statut', 'observations', 'photo'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted';

    // Validation
    protected $validationRules = [
        'nom'                      => 'required|min_length[2]|max_length[100]',
        'prenom'                   => 'required|min_length[2]|max_length[100]',
        'cnie'                     => 'required|min_length[8]|max_length[20]|is_unique[chauffeurs.cnie,id,{id}]',
        'telephone'                => 'required|min_length[10]|max_length[20]',
        'telephone_urgence'        => 'permit_empty|min_length[10]|max_length[20]',
        'email'                    => 'permit_empty|valid_email|max_length[255]|is_unique[chauffeurs.email,id,{id}]',
        'adresse'                  => 'permit_empty|max_length[500]',
        'date_naissance'           => 'required|valid_date[Y-m-d]',
        'date_embauche'            => 'required|valid_date[Y-m-d]',
        'numero_permis'            => 'required|min_length[5]|max_length[50]|is_unique[chauffeurs.numero_permis,id,{id}]',
        'date_expiration_permis'   => 'required|valid_date[Y-m-d]',
        'categorie_permis'         => 'required|in_list[A,A1,A2,B,C,D,BE,CE,DE]',
        'salaire_base'             => 'permit_empty|decimal|greater_than_equal_to[0]',
        'statut'                   => 'in_list[actif,inactif,suspendu]',
    ];

    protected $validationMessages = [
        'nom' => [
            'required' => 'Le nom est obligatoire',
            'min_length' => 'Le nom doit contenir au moins 2 caractères',
            'max_length' => 'Le nom ne peut pas dépasser 100 caractères',
        ],
        'prenom' => [
            'required' => 'Le prénom est obligatoire',
            'min_length' => 'Le prénom doit contenir au moins 2 caractères',
            'max_length' => 'Le prénom ne peut pas dépasser 100 caractères',
        ],
        'cnie' => [
            'required' => 'Le CNIE est obligatoire',
            'min_length' => 'Le CNIE doit contenir au moins 8 caractères',
            'max_length' => 'Le CNIE ne peut pas dépasser 20 caractères',
            'is_unique' => 'Ce numéro CNIE existe déjà',
        ],
        'telephone' => [
            'required' => 'Le téléphone est obligatoire',
            'min_length' => 'Le téléphone doit contenir au moins 10 caractères',
            'max_length' => 'Le téléphone ne peut pas dépasser 20 caractères',
        ],
        'email' => [
            'valid_email' => 'L\'adresse email n\'est pas valide',
            'is_unique' => 'Cette adresse email existe déjà',
        ],
        'date_naissance' => [
            'required' => 'La date de naissance est obligatoire',
            'valid_date' => 'Format de date invalide (YYYY-MM-DD)',
        ],
        'date_embauche' => [
            'required' => 'La date d\'embauche est obligatoire',
            'valid_date' => 'Format de date invalide (YYYY-MM-DD)',
        ],
        'numero_permis' => [
            'required' => 'Le numéro de permis est obligatoire',
            'min_length' => 'Le numéro de permis doit contenir au moins 5 caractères',
            'is_unique' => 'Ce numéro de permis existe déjà',
        ],
        'date_expiration_permis' => [
            'required' => 'La date d\'expiration du permis est obligatoire',
            'valid_date' => 'Format de date invalide (YYYY-MM-DD)',
        ],
        'categorie_permis' => [
            'required' => 'La catégorie de permis est obligatoire',
            'in_list' => 'Catégorie de permis invalide',
        ],
        'salaire_base' => [
            'decimal' => 'Le salaire doit être un nombre décimal',
            'greater_than_equal_to' => 'Le salaire doit être supérieur ou égal à 0',
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
        
        // Les règles de base sont suffisantes pour CodeIgniter 4
        // La validation des dates sera faite côté contrôleur si nécessaire
        
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
        
        // Validations personnalisées pour les dates
        if ($isValid) {
            $customErrors = [];
            
            // Validation âge (18-70 ans)
            if (!empty($data['date_naissance'])) {
                try {
                    $birthDate = new \DateTime($data['date_naissance']);
                    $today = new \DateTime();
                    $age = $today->diff($birthDate)->y;
                    
                    if ($age < 18) {
                        $customErrors['date_naissance'] = 'Le chauffeur doit avoir au moins 18 ans';
                    } elseif ($age > 70) {
                        $customErrors['date_naissance'] = 'Âge maximum autorisé : 70 ans';
                    }
                } catch (\Exception $e) {
                    $customErrors['date_naissance'] = 'Format de date invalide';
                }
            }
            
            // Validation date d'embauche (pas dans le futur)
            if (!empty($data['date_embauche'])) {
                try {
                    $hireDate = new \DateTime($data['date_embauche']);
                    $today = new \DateTime();
                    
                    if ($hireDate > $today) {
                        $customErrors['date_embauche'] = 'La date d\'embauche ne peut pas être dans le futur';
                    }
                } catch (\Exception $e) {
                    $customErrors['date_embauche'] = 'Format de date invalide';
                }
            }
            
            // Validation expiration permis (doit être dans le futur)
            if (!empty($data['date_expiration_permis'])) {
                try {
                    $expDate = new \DateTime($data['date_expiration_permis']);
                    $today = new \DateTime();
                    
                    if ($expDate <= $today) {
                        $customErrors['date_expiration_permis'] = 'La date d\'expiration du permis doit être dans le futur';
                    }
                } catch (\Exception $e) {
                    $customErrors['date_expiration_permis'] = 'Format de date invalide';
                }
            }
            
            // Si des erreurs personnalisées existent
            if (!empty($customErrors)) {
                // Stocker les erreurs pour le contrôleur
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
     * Récupérer tous les chauffeurs avec filtres
     */
    public function get_all_chauffeurs($filters = [])
    {
        $builder = $this->builder();
        
        if (!empty($filters['nom'])) {
            $builder->like('nom', $filters['nom']);
        }
        
        if (!empty($filters['prenom'])) {
            $builder->like('prenom', $filters['prenom']);
        }
        
        if (!empty($filters['statut'])) {
            $builder->where('statut', $filters['statut']);
        }
        
        if (!empty($filters['categorie_permis'])) {
            $builder->where('categorie_permis', $filters['categorie_permis']);
        }
        
        if (!empty($filters['salaire_min'])) {
            $builder->where('salaire_base >=', $filters['salaire_min']);
        }
        
        if (!empty($filters['salaire_max'])) {
            $builder->where('salaire_base <=', $filters['salaire_max']);
        }

        // Filtrer par expiration permis proche
        if (!empty($filters['permis_expire_soon'])) {
            $dateLimit = date('Y-m-d', strtotime('+30 days'));
            $builder->where('date_expiration_permis <=', $dateLimit);
        }
        
        return $builder->orderBy('created_at', 'DESC')->get()->getResult();
    }

    /**
     * Récupérer les chauffeurs actifs
     */
    public function get_active_chauffeurs()
    {
        return $this->where('statut', 'actif')
                   ->orderBy('nom', 'ASC')
                   ->findAll();
    }

    /**
     * Récupérer les chauffeurs disponibles (actifs + non assignés)
     */
    public function get_available_chauffeurs()
    {
        return $this->select('chauffeurs.*, 
                            (SELECT COUNT(*) FROM locations 
                             WHERE locations.chauffeur_id = chauffeurs.id 
                             AND locations.statut IN ("confirmee", "en_cours") 
                             AND NOW() BETWEEN locations.date_debut AND locations.date_fin) as locations_actives')
                   ->where('statut', 'actif')
                   ->having('locations_actives', 0)
                   ->orderBy('nom', 'ASC')
                   ->findAll();
    }

    /**
     * Recherche avancée
     */
    public function search_chauffeurs($keyword = '', $filters = [])
    {
        $builder = $this->builder();
        
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('nom', $keyword)
                    ->orLike('prenom', $keyword)
                    ->orLike('cnie', $keyword)
                    ->orLike('telephone', $keyword)
                    ->orLike('numero_permis', $keyword)
                    ->groupEnd();
        }
        
        // Appliquer les filtres
        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                if (!empty($value) && in_array($field, $this->allowedFields)) {
                    if ($field === 'nom' || $field === 'prenom') {
                        $builder->like($field, $value);
                    } else {
                        $builder->where($field, $value);
                    }
                }
            }
        }
        
        return $builder->orderBy('created_at', 'DESC')->get()->getResult();
    }

    /**
     * Statistiques des chauffeurs
     */
    public function get_statistics()
    {
        $total = $this->countAllResults();
        $actif = $this->where('statut', 'actif')->countAllResults();
        $inactif = $this->where('statut', 'inactif')->countAllResults();
        $suspendu = $this->where('statut', 'suspendu')->countAllResults();
        
        // Permis expirant dans les 30 jours
        $permis_expire_soon = $this->where('date_expiration_permis <=', date('Y-m-d', strtotime('+30 days')))
                                  ->where('date_expiration_permis >=', date('Y-m-d'))
                                  ->countAllResults();
        
        // Salaire moyen
        $avg_salaire = $this->selectAvg('salaire_base')->first();
        
        return [
            'total' => $total,
            'actif' => $actif,
            'inactif' => $inactif,
            'suspendu' => $suspendu,
            'permis_expire_soon' => $permis_expire_soon,
            'salaire_moyen' => $avg_salaire->salaire_base ?? 0,
        ];
    }

    /**
     * Récupérer les chauffeurs avec permis expirant bientôt
     */
    public function get_expiring_licenses($days = 30)
    {
        $dateLimit = date('Y-m-d', strtotime("+{$days} days"));
        
        return $this->where('date_expiration_permis <=', $dateLimit)
                   ->where('date_expiration_permis >=', date('Y-m-d'))
                   ->where('statut', 'actif')
                   ->orderBy('date_expiration_permis', 'ASC')
                   ->findAll();
    }

    /**
     * Récupérer les catégories de permis uniques
     */
    public function get_unique_license_categories()
    {
        return $this->select('categorie_permis')
                   ->distinct()
                   ->orderBy('categorie_permis', 'ASC')
                   ->findAll();
    }

    /**
     * Calculer l'âge d'un chauffeur
     */
    public function calculate_age($date_naissance)
    {
        $birthDate = new \DateTime($date_naissance);
        $today = new \DateTime('today');
        return $birthDate->diff($today)->y;
    }

    /**
     * Calculer l'ancienneté d'un chauffeur
     */
    public function calculate_seniority($date_embauche)
    {
        $hireDate = new \DateTime($date_embauche);
        $today = new \DateTime('today');
        $diff = $hireDate->diff($today);
        
        return [
            'years' => $diff->y,
            'months' => $diff->m,
            'days' => $diff->d,
            'total_days' => $today->diff($hireDate)->days
        ];
    }

    /**
     * Vérifier si le permis est valide
     */
    public function is_license_valid($chauffeur_id)
    {
        $chauffeur = $this->find($chauffeur_id);
        if (!$chauffeur) {
            return false;
        }
        
        return strtotime($chauffeur->date_expiration_permis) > strtotime('today');
    }

    /**
     * Récupérer les informations complètes d'un chauffeur avec ses relations
     */
    public function get_chauffeur_details($id)
    {
        $chauffeur = $this->find($id);
        if (!$chauffeur) {
            return null;
        }
        
        // Ajouter les informations calculées
        $chauffeur->age = $this->calculate_age($chauffeur->date_naissance);
        $chauffeur->anciennete = $this->calculate_seniority($chauffeur->date_embauche);
        $chauffeur->permis_valide = $this->is_license_valid($id);
        
        // Récupérer les documents associés
        $documentsModel = model('App\Models\Chauffeur_documents_model');
        $chauffeur->documents = $documentsModel->where('chauffeur_id', $id)->findAll();
        
        // Récupérer les locations récentes
        $locationsModel = model('App\Models\Locations_model');
        $chauffeur->locations_recentes = $locationsModel->where('chauffeur_id', $id)
                                                        ->orderBy('date_debut', 'DESC')
                                                        ->limit(5)
                                                        ->findAll();
        
        return $chauffeur;
    }
// Dans app/Models/Chauffeurs_model.php

    /**
     * Méthode générique pour récupérer les détails des chauffeurs.
     *
     * @param array $options Peut contenir des filtres comme 'id', 'statut', etc.
     * @return \CodeIgniter\Database\ResultInterface Résultat de la requête
     */
    public function get_details($options = [])
    {
        $builder = $this->select('*'); // Sélectionne toutes les colonnes

        // Exclut les enregistrements supprimés (soft delete)
        $builder->where($this->deletedField, 0);

        // Filtre optionnel par ID de chauffeur
        $id = get_array_value($options, "id");
        if ($id) {
            $builder->where('id', $id);
        }

        // Filtre optionnel par statut
        $statut = get_array_value($options, "statut");
        if ($statut) {
            $builder->where('statut', $statut);
        }

        // Retourne l'objet de requête, comme défini dans notre convention
        return $builder->get();
    }
    /**
     * Mettre à jour le statut d'un chauffeur
     */
    public function update_status($chauffeur_id, $new_status)
    {
        if (!in_array($new_status, ['actif', 'inactif', 'suspendu'])) {
            return false;
        }
        
        return $this->update($chauffeur_id, ['statut' => $new_status]);
    }
}