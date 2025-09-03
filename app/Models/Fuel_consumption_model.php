<?php
namespace App\Models;
use CodeIgniter\Model;

class Fuel_consumption_model extends Model
{
    protected $table = 'fuel_consumption';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'fuel_card_id', 'vehicle_id', 'chauffeur_id', 'km_compteur',
        'quantite_litre', 'montant', 'date_plein', 'station_service'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted';
}