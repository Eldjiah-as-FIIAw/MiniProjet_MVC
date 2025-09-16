
<?php
namespace App\Controllers;

use App\Models\ReservationModel;
use App\Models\PlaceModel;
use App\Models\ParkingModel;

class ReservationController extends BaseController
{
    protected $reservationModel;
    protected $placeModel;
    protected $parkingModel;

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
        $this->placeModel = new PlaceModel();
        $this->parkingModel = new ParkingModel();
    }

    public function add($id_place)
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'date_reservation' => $this->request->getPost('date_reservation'),
                'heure_debut' => $this->request->getPost('heure_debut'),
                'heure_fin' => $this->request->getPost('heure_fin'),
                'id_client' => session()->get('client_id'),
                'id_place' => $id_place,
                'statut' => 'demandée'
            ];

            if (!$this->reservationModel->checkConflict($id_place, $data['date_reservation'], $data['heure_debut'], $data['heure_fin'])) {
                if ($this->reservationModel->insert($data)) {
                    $this->placeModel->update($id_place, ['etat' => 'réservé']);
                    $this->parkingModel->updateAvailablePlaces($this->placeModel->find($id_place)['id_parking']);
                    return redirect()->to('/client/dashboard')->with('success', 'Réservation effectuée.');
                }
            }
            return view('reservation/form', ['errors' => ['Conflit d\'horaire ou erreur.'], 'id_place' => $id_place]);
        }

        return view('reservation/form', ['id_place' => $id_place]);
    }
}
