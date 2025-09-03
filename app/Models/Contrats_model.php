<?php

namespace App\Models;

use CodeIgniter\Model;

class Contrats_model extends Model
{
    protected $table = 'contrats';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType = 'object';

    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted';

    protected $allowedFields = [
        'id', 'numero_contrat', 'client_id', 'voiture_id', 'date_depart', 'date_retour_prevue', 
        'date_retour_effective', 'lieu_depart', 'lieu_retour', 'km_depart', 'km_retour', 
        'carburant_depart', 'carburant_retour', 'deuxieme_conducteur_nom', 
        'deuxieme_conducteur_permis_numero', 'deuxieme_conducteur_permis_delivre_le', 
        'nombre_jours', 'prix_jour', 'frais_km_supplementaire', 'frais_autres', 
        'total_paye', 'total_final', 'notes_etat_vehicule_depart', 'notes_etat_vehicule_retour', 
        'statut', 'caution', 'avance', 'mode_paiement', 'deleted'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    public function get_details($options = [])
    {
        $contrats_table = $this->table;
        $clients_table = 'clients_contrat';
        $voitures_table = 'voitures_contrat';

        $builder = $this->db->table($contrats_table);

        // CORRECTION: On sélectionne tous les champs nécessaires pour le PDF
        $builder->select([
            "$contrats_table.*",
            "$clients_table.nom as client_nom",
            "$clients_table.prenom as client_prenom",
            "$clients_table.nationalite as client_nationalite",
            "$clients_table.adresse_maroc as client_adresse_maroc",
            "$clients_table.adresse_etranger as client_adresse_etranger",
            "$clients_table.cin_numero as client_cin_numero",
            "$clients_table.cin_delivre_le as client_cin_delivre_le",
            "$clients_table.passeport_numero as client_passeport_numero",
            "$clients_table.passeport_delivre_le as client_passeport_delivre_le",
            "$clients_table.permis_numero as client_permis_numero",
            "$clients_table.permis_delivre_le as client_permis_delivre_le",
            "$voitures_table.marque as voiture_marque",
            "$voitures_table.modele as voiture_modele",
            "$voitures_table.immatriculation as voiture_immatriculation"
        ]);

        $builder->join($clients_table, "$clients_table.id = $contrats_table.client_id", 'left');
        $builder->join($voitures_table, "$voitures_table.id = $contrats_table.voiture_id", 'left');

        $builder->where("$contrats_table.deleted", 0);

        $id = get_array_value($options, "id");
        if ($id) {
            $builder->where("$contrats_table.id", $id);
        }

        return $builder->get();
    }
}
