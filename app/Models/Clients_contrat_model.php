<?php

namespace App\Models;

use CodeIgniter\Model;

class Clients_contrat_model extends Model
{
    protected $table = 'clients_contrat';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType = 'object';

    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted';

    // Champs autorisés pour la sauvegarde, incluant 'id' pour les mises à jour
    protected $allowedFields = [
        'id', 'nom', 'prenom', 'cin_numero', 'cin_delivre_le', 'passeport_numero', 
        'passeport_delivre_le', 'permis_numero', 'permis_delivre_le', 'nationalite', 
        'adresse_maroc', 'adresse_etranger', 'telephone', 'deleted'
    ];

    // Timestamps gérés par la base de données
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    /**
     * Méthode pour récupérer les détails des clients.
     *
     * @param array $options
     * @return \CodeIgniter\Database\ResultInterface
     */
    public function get_details($options = [])
    {
        $clients_table = $this->table;
        $builder = $this->db->table($clients_table);

        // Filtre pour ne pas afficher les éléments supprimés
        $builder->where("$clients_table.deleted", 0);

        $id = get_array_value($options, "id");
        if ($id) {
            $builder->where("$clients_table.id", $id);
        }

        // Ajoute un champ 'title' pour les listes déroulantes
        $builder->select("$clients_table.*, CONCAT(prenom, ' ', nom) AS title");

        return $builder->get();
    }
}
