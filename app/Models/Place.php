<?php
namespace App\Models;

use CodeIgniter\Model;

class Place extends Model
{
    protected $table = 'place';
    protected $primaryKey = 'id_place';
    protected $allowedFields = ['numero_place', 'etat', 'id_parking'];

    // Règles de validation
    protected $validationRules = [
        'numero_place' => 'required|min_length[1]',
        'etat' => 'required|in_list[libre,occupee]',
        'id_parking' => 'required|is_natural_no_zero'
    ];

    // Méthode pour récupérer les places libres d'un parking
    public function getPlacesLibres($id_parking)
    {
        return $this->where(['id_parking' => $id_parking, 'etat' => 'libre'])->findAll();
    }
}