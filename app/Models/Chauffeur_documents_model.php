<?php

namespace App\Models;

use CodeIgniter\Model;

class Chauffeur_documents_model extends Model
{
    // Nom de la table dans la base de données
    protected $table = 'rise_chauffeur_documents';

    // Clé primaire
    protected $primaryKey = 'id';

    // Activer la suppression douce (soft delete)
    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted';

    // Type de retour
    protected $returnType = 'object';

    // Champs autorisés à être insérés/mis à jour
    protected $allowedFields = [
        'chauffeur_id',
        'type_document',
        'nom_fichier',
        'chemin_fichier',
        'deleted'
    ];

    // Les timestamps ne sont pas gérés par le modèle
    protected $useTimestamps = false;

    // Règles de validation
    // La validation du fichier lui-même (taille, type) se fera dans le contrôleur
    protected $validationRules = [
        'id'            => 'numeric',
        'chauffeur_id'  => 'required|integer',
        'type_document' => 'required|in_list[cnie,permis,contrat,cv,autre]',
        'nom_fichier'   => 'required',
        'chemin_fichier'=> 'required'
    ];


    /**
     * Méthode générique pour récupérer les détails des documents.
     * Peut être utilisée pour lister tous les documents d'un chauffeur spécifique.
     *
     * @param array $options Peut contenir des filtres comme 'id', 'chauffeur_id'.
     * @return \CodeIgniter\Database\ResultInterface Résultat de la requête
     */
    public function get_details($options = [])
    {
        $documents_table = $this->table;
        $chauffeurs_table = 'rise_chauffeurs'; // Au cas où on voudrait le nom du chauffeur

        $builder = $this->db->table($documents_table);

        // Sélection des champs
        $builder->select("$documents_table.*, 
                          CONCAT($chauffeurs_table.prenom, ' ', $chauffeurs_table.nom) AS chauffeur_name");
        
        // Jointure avec la table des chauffeurs
        $builder->join($chauffeurs_table, "$chauffeurs_table.id = $documents_table.chauffeur_id", 'left');

        // Exclut les enregistrements supprimés
        $builder->where("$documents_table.deleted", 0);

        // Filtre par ID de document
        $id = get_array_value($options, "id");
        if ($id) {
            $builder->where("$documents_table.id", $id);
        }

        // Filtre par ID de chauffeur (le plus utilisé)
        $chauffeur_id = get_array_value($options, "chauffeur_id");
        if ($chauffeur_id) {
            $builder->where("$documents_table.chauffeur_id", $chauffeur_id);
        }

        // Retourne l'objet de requête (convention)
        return $builder->get();
    }
}