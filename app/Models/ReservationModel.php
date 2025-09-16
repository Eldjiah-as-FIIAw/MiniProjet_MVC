<?php
namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'Reservation';
    protected $primaryKey = 'id_reservation';
    protected $allowedFields = ['date_reservation', 'heure_debut', 'heure_fin', 'statut', 'id_client', 'id_place', 'validée_par'];

    protected $validationRules = [
        'date_reservation' => 'required|valid_date',
        'heure_debut' => 'required',
        'heure_fin' => 'required',
        'statut' => 'required|in_list[demandée,confirmée,annulée]',
        'id_client' => 'required|is_natural_no_zero',
        'id_place' => 'required|is_natural_no_zero'
    ];

    public function checkConflict($id_place, $date_reservation, $heure_debut, $heure_fin)
    {
        return $this->where('id_place', $id_place)
                    ->where('date_reservation', $date_reservation)
                    ->where('statut !=', 'annulée')
                    ->where('(heure_debut <= ?', $heure_fin)
                    ->where('heure_fin >= ?)', $heure_debut)
                    ->countAllResults() > 0;
    }

    public function getReservationsByClient($id_client)
    {
        return $this->where('id_client', $id_client)->findAll();
    }
}