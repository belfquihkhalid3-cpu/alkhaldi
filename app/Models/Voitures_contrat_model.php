<?php

namespace App\Models;

use CodeIgniter\Model;

class Voitures_contrat_model extends Model
{
    protected $table = 'voitures_contrat';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType = 'object';

    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted';

    // Champs autorisés pour la sauvegarde. 'id' est inclus pour permettre les mises à jour.
    protected $allowedFields = [
        'id', 'marque', 'modele', 'immatriculation', 'statut', 'deleted'
    ];

    // Timestamps gérés par la base de données
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = ''; // Pas de champ 'updated_at'

    /**
     * Méthode pour récupérer les détails des voitures.
     *
     * @param array $options
     * @return \CodeIgniter\Database\ResultInterface
     */
    public function get_details($options = [])
    {
        $voitures_table = $this->table;
        $builder = $this->db->table($voitures_table);

        // Filtre pour ne pas afficher les éléments supprimés
        $builder->where("$voitures_table.deleted", 0);

        $id = get_array_value($options, "id");
        if ($id) {
            $builder->where("$voitures_table.id", $id);
        }

        $statut = get_array_value($options, "statut");
        if ($statut) {
            $builder->where("$voitures_table.statut", $statut);
        }

        // Ajoute un champ 'title' pour les listes déroulantes, utile plus tard
        $builder->select("$voitures_table.*, CONCAT(marque, ' ', modele, ' (', immatriculation, ')') AS title");

        return $builder->get();
    }
}
