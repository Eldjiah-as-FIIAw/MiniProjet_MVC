<?php
namespace App\Models;

use CodeIgniter\Model;

class Reservation extends Model
{
    protected $table = 'reservation';
    protected $primaryKey = 'id_reservation';
    protected $allowedFields = ['date_reservation', 'heure_debut', 'heure_fin', 'statut', 'id_client', 'id_place'];

    // Règles de validation
    protected $validationRules = [
        'date_reservation' => 'required|valid_date',
        'heure_debut' => 'required',
        'heure_fin' => 'required',
        'statut' => 'required|in_list[en_attente,confirmee,annulee]',
        'id_client' => 'required|is_natural_no_zero',
        'id_place' => 'required|is_natural_no_zero'
    ];

    // Méthode pour vérifier les réservations d'un client
    public function getReservationsByClient($id_client)
    {
        return $this->where('id_client', $id_client)->findAll();
    }
}