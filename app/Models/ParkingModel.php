<?php
namespace App\Models;

use CodeIgniter\Model;

class ParkingModel extends Model
{
    protected $table = 'Parking';
    protected $primaryKey = 'id_parking';
    protected $allowedFields = ['nom_parking', 'adresse', 'nb_place'];

    protected $validationRules = [
        'nom_parking' => 'required|min_length[3]',
        'adresse' => 'required|min_length[5]',
        'nb_place' => 'required|is_natural_no_zero'
    ];

    public function getAvailableParkings()
    {
        return $this->where('nb_place >', 0)->findAll();
    }

    public function updateAvailablePlaces($id_parking)
    {
        $places = $this->db->table('Place')
            ->where('id_parking', $id_parking)
            ->where('etat', 'libre')
            ->countAllResults();

        $this->update($id_parking, ['nb_place' => $places]);
    }
}
