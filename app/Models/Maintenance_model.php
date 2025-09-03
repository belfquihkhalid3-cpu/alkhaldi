<?php

namespace App\Models;

use CodeIgniter\Model;

class Maintenance_model extends Model
{
    // Nom de la table dans la base de données
    protected $table = 'rise_maintenance';

    // Clé primaire de la table
    protected $primaryKey = 'id';

    // Activer la suppression "douce" (soft delete)
    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted';

    // Type de retour des résultats
    protected $returnType = 'object';

    // Champs autorisés à être insérés/mis à jour
    protected $allowedFields = [
        'vehicle_id',
        'type_maintenance',
        'km_maintenance',
        'date_maintenance',
        'cout',
        'description',
        'prochaine_maintenance_km',
        'prochaine_maintenance_date',
        'garage',
        'deleted'
    ];

    // Les timestamps ne sont pas gérés par le modèle car la BDD s'en charge
    protected $useTimestamps = false;

    // Règles de validation pour les formulaires
    protected $validationRules = [
        'vehicle_id'        => 'required|integer',
        'type_maintenance'  => 'required|in_list[vidange,filtre_huile,filtre_air,filtre_carburant,autre]',
        'km_maintenance'    => 'required|integer',
        'date_maintenance'  => 'required|valid_date',
        'cout'              => 'numeric'
    ];


    /**
     * Méthode générique pour récupérer les détails des maintenances.
     * Cette méthode fait une jointure avec la table des véhicules.
     *
     * @param array $options Peut contenir des filtres comme 'id', 'vehicle_id', etc.
     * @return object Résultat de la requête
     */
    public function get_details($options = [])
    {
        // Alias pour les noms de table pour plus de clarté
        $maintenance_table = $this->table;
        $vehicles_table = 'rise_vehicules'; // La table à joindre

        // Utilisation du Query Builder pour construire la requête
        $builder = $this->db->table($maintenance_table);

        // Sélection des champs nécessaires
        // On prend tous les champs de la maintenance et quelques champs utiles du véhicule
        $builder->select("$maintenance_table.*, 
                          $vehicles_table.marque AS vehicle_marque, 
                          $vehicles_table.modele AS vehicle_modele,
                          $vehicles_table.numero_matricule AS vehicle_matricule");

        // Jointure LEFT JOIN pour lier la maintenance à son véhicule
        // LEFT JOIN est utilisé pour que la maintenance s'affiche même si le véhicule a été supprimé
        $builder->join($vehicles_table, "$vehicles_table.id = $maintenance_table.vehicle_id", 'left');

        // Filtre de base pour exclure les enregistrements supprimés (soft delete)
        $builder->where("$maintenance_table.deleted", 0);

        // -- Filtres optionnels --

        // Filtre par ID de maintenance
        $id = get_array_value($options, "id");
        if ($id) {
            $builder->where("$maintenance_table.id", $id);
        }

        // Filtre par ID de véhicule (pour voir l'historique d'un seul véhicule)
        $vehicle_id = get_array_value($options, "vehicle_id");
        if ($vehicle_id) {
            $builder->where("$maintenance_table.vehicle_id", $vehicle_id);
        }
        
        // Filtre par date de début (pour les rapports)
        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $builder->where("date_maintenance >=", $start_date);
        }

        // Filtre par date de fin (pour les rapports)
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $builder->where("date_maintenance <=", $end_date);
        }

      // Nouvelle version à la fin de get_details()
return $builder->get();
    }
}