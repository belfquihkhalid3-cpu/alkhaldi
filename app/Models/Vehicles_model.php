<?php
namespace App\Models;

use CodeIgniter\Model;

class Vehicles_model extends Model
{
    protected $table            = 'vehicules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
      protected $deletedField  = 'deleted';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'marque', 'modele', 'annee', 'prix', 'kilometrage', 
        'carburant', 'transmission', 'couleur', 'description', 
        'statut', 'image'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'date_ajout';
    protected $updatedField  = 'date_modification';

    // Validation
    protected $validationRules = [
        'marque'       => 'required|min_length[2]|max_length[100]',
        'modele'       => 'required|min_length[2]|max_length[100]',
        'annee'        => 'required|integer|greater_than[1900]|less_than_equal_to[2026]',
        'prix'         => 'required|decimal|greater_than[0]',
        'kilometrage'  => 'required|integer|greater_than_equal_to[0]',
        'carburant'    => 'required|in_list[essence,diesel,hybride,electrique]',
        'transmission' => 'required|in_list[manuelle,automatique]',
        'couleur'      => 'required|min_length[2]|max_length[50]',
        'statut'       => 'in_list[disponible,vendu,reserve]',
    ];

    protected $validationMessages = [
        'marque' => [
            'required' => 'La marque est obligatoire',
            'min_length' => 'La marque doit contenir au moins 2 caractères',
            'max_length' => 'La marque ne peut pas dépasser 100 caractères',
        ],
        'modele' => [
            'required' => 'Le modèle est obligatoire',
            'min_length' => 'Le modèle doit contenir au moins 2 caractères',
            'max_length' => 'Le modèle ne peut pas dépasser 100 caractères',
        ],
        'annee' => [
            'required' => 'L\'année est obligatoire',
            'integer' => 'L\'année doit être un nombre entier',
            'greater_than' => 'L\'année doit être supérieure à 1900',
            'less_than_equal_to' => 'L\'année ne peut pas être supérieure à 2026',
        ],
        'prix' => [
            'required' => 'Le prix est obligatoire',
            'decimal' => 'Le prix doit être un nombre décimal',
            'greater_than' => 'Le prix doit être supérieur à 0',
        ],
        'couleur' => [
            'required' => 'La couleur est obligatoire',
            'min_length' => 'La couleur doit contenir au moins 2 caractères',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Méthode pour obtenir les règles de validation avec l'année dynamique
     */
    public function getValidationRules(array $options = []): array
    {
        $rules = $this->validationRules;
        
        // Règle dynamique pour l'année
        $currentYear = date('Y');
        $rules['annee'] = "required|integer|greater_than[1900]|less_than_equal_to[" . ($currentYear + 1) . "]";
        
        return $rules;
    }
// Dans app/Models/Vehicles_model.php

    /**
     * Méthode générique pour récupérer les détails des véhicules.
     *
     * @param array $options Peut contenir des filtres comme 'id', 'statut', etc.
     * @return object Résultat de la requête
     */
    public function get_details($options = [])
    {
        $builder = $this->select('*'); // Commence par sélectionner toutes les colonnes

        // Important: Toujours filtrer les enregistrements supprimés
        $builder->where($this->deletedField, 0);

        // Filtre par ID
        $id = get_array_value($options, "id");
        if ($id) {
            $builder->where('id', $id);
        }

        // Filtre par statut
        $statut = get_array_value($options, "statut");
        if ($statut) {
            $builder->where('statut', $statut);
        }

        // Exécute la requête
        return $builder->get(); // On retourne l'objet Query Result
    }
    /**
     * Validation personnalisée avec règles dynamiques
     */
    public function validateData($data)
    {
        $validation = \Config\Services::validation();
        $validation->setRules($this->getValidationRules());
        
        return $validation->run($data);
    }

    /**
     * Récupérer tous les véhicules avec filtres
     */
    public function get_all_vehicles($filters = [])
    {
        $builder = $this->builder();
        
        if (!empty($filters['marque'])) {
            $builder->like('marque', $filters['marque']);
        }
        
        if (!empty($filters['modele'])) {
            $builder->like('modele', $filters['modele']);
        }
        
        if (!empty($filters['carburant'])) {
            $builder->where('carburant', $filters['carburant']);
        }
        
        if (!empty($filters['statut'])) {
            $builder->where('statut', $filters['statut']);
        }
        
        if (!empty($filters['prix_min'])) {
            $builder->where('prix >=', $filters['prix_min']);
        }
        
        if (!empty($filters['prix_max'])) {
            $builder->where('prix <=', $filters['prix_max']);
        }
        
        return $builder->orderBy('date_ajout', 'DESC')->get()->getResult();
    }

    /**
     * Récupérer les véhicules disponibles
     */
    public function get_available_vehicles()
    {
        return $this->where('statut', 'disponible')
                   ->orderBy('date_ajout', 'DESC')
                   ->findAll();
    }

    /**
     * Recherche avancée
     */
    public function search_vehicles($keyword = '', $filters = [])
    {
        $builder = $this->builder();
        
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('marque', $keyword)
                    ->orLike('modele', $keyword)
                    ->orLike('couleur', $keyword)
                    ->orLike('description', $keyword)
                    ->groupEnd();
        }
        
        // Appliquer les filtres
        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                if (!empty($value) && in_array($field, $this->allowedFields)) {
                    $builder->where($field, $value);
                }
            }
        }
        
        return $builder->orderBy('date_ajout', 'DESC')->get()->getResult();
    }

    /**
     * Statistiques des véhicules
     */
    public function get_statistics()
    {
        $total = $this->countAllResults();
        $disponible = $this->where('statut', 'disponible')->countAllResults();
        $vendu = $this->where('statut', 'vendu')->countAllResults();
        $reserve = $this->where('statut', 'reserve')->countAllResults();
        
        return [
            'total' => $total,
            'disponible' => $disponible,
            'vendu' => $vendu,
            'reserve' => $reserve,
        ];
    }

    /**
     * Récupérer les marques uniques
     */
    public function get_unique_brands()
    {
        return $this->select('marque')
                   ->distinct()
                   ->orderBy('marque', 'ASC')
                   ->findAll();
    }
}