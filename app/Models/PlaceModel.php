<?php
namespace App\Models;

use CodeIgniter\Model;

class PlaceModel extends Model
{
    protected $table = 'Place';
    protected $primaryKey = 'id_place';
    protected $allowedFields = ['numero_place', 'etat', 'id_parking'];

    protected $validationRules = [
        'numero_place' => 'required|min_length[1]',
        'etat' => 'required|in_list[libre,réservé,occupé]',
        'id_parking' => 'required|is_natural_no_zero'
    ];

    public function getAvailablePlaces($id_parking)
    {
        return $this->where(['id_parking' => $id_parking, 'etat' => 'libre'])->findAll();
    }
}
