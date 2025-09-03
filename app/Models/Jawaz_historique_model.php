<?php

namespace App\Models;

use CodeIgniter\Model;

class Jawaz_historique_model extends Model
{
    protected $table = 'rise_jawaz_historique';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    
    protected $allowedFields = [
        'jawaz_id',
        'type_mouvement',
        'vehicle_id',
        'chauffeur_id',
        'solde_avant',
        'solde_apres',
        'date_mouvement',
        'motif',
        'created_by',
        'created_at'
    ];
    
    // Désactiver les timestamps automatiques pour éviter le problème de binding
    protected $useTimestamps = false;
    
    /**
     * Enregistre un mouvement dans l'historique
     */
    public function enregistrer_mouvement($jawaz_id, $type_mouvement, $data)
    {
        $historique_data = [
            'jawaz_id' => $jawaz_id,
            'type_mouvement' => $type_mouvement,
            'vehicle_id' => $data['vehicle_id'] ?? null,
            'chauffeur_id' => $data['chauffeur_id'] ?? null,
            'solde_avant' => $data['solde_avant'] ?? null,
            'solde_apres' => $data['solde'] ?? $data['solde_retour'] ?? null,
            'date_mouvement' => $data['date_retour'] ?? $data['date_affectation'] ?? date('Y-m-d H:i:s'),
            'motif' => $data['motif_retour'] ?? $data['evenement'] ?? null,
            'created_at' => date('Y-m-d H:i:s') // Ajouter manuellement le timestamp
        ];
        
        return $this->insert($historique_data);
    }
    
    /**
     * Récupère l'historique d'un badge avec jointures
     */
    public function get_historique_badge($jawaz_id)
    {
        return $this->db->table($this->table . ' h')
            ->select('h.*, 
                     CONCAT(v.marque, " ", v.modele, " (", v.numero_matricule, ")") as vehicle_name,
                     CONCAT(c.prenom, " ", c.nom) as chauffeur_name')
            ->join('rise_vehicules v', 'v.id = h.vehicle_id', 'left')
            ->join('rise_chauffeurs c', 'c.id = h.chauffeur_id', 'left')
            ->where('h.jawaz_id', $jawaz_id)
            ->orderBy('h.date_mouvement', 'DESC')
            ->get()
            ->getResult();
    }
}