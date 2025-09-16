<?php
namespace App\Controllers;

use App\Models\Reservation;
use App\Models\Place;

class ReservationController extends BaseController
{
    public function ajouter($id_place)
    {
        if ($this->request->getMethod() === 'post') {
            $model = new Reservation();
            $data = $this->request->getPost(['date_reservation', 'heure_debut', 'heure_fin', 'id_client']);
            $data['id_place'] = $id_place;
            $data['statut'] = 'en_attente';
            if ($model->insert($data)) {
                // Mettre à jour l'état de la place
                $placeModel = new Place();
                $placeModel->update($id_place, ['etat' => 'occupee']);
                return redirect()->to('/reservations');
            }
            return view('reservations/formulaire', ['errors' => $model->errors()]);
        }
        return view('reservations/formulaire', ['id_place' => $id_place]);
    }
}