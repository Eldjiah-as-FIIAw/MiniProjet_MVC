<?php
namespace App\Models;

use CodeIgniter\Model;

class Parking extends Model
{
    protected $table = 'parking';
    protected $primaryKey = 'id_parking';
    protected $allowedFields = ['nb_place', 'nom_parking', 'adresse'];

    // Règles de validation
    protected $validationRules = [
        'nb_place' => 'required|is_natural_no_zero',
        'nom_parking' => 'required|min_length[3]',
        'adresse' => 'required|min_length[5]'
    ];

    // Méthode pour récupérer les parkings avec des places disponibles
    public function getParkingsDisponibles()
    {
        return $this->where('nb_place >', 0)->findAll();
    }
}