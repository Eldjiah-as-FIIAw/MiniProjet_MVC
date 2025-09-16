<?php
namespace App\Controllers;

use App\Models\ParkingModel;
use App\Models\PlaceModel;

class ParkingController extends BaseController
{
    protected $parkingModel;
    protected $placeModel;

    public function __construct()
    {
        $this->parkingModel = new ParkingModel();
        $this->placeModel = new PlaceModel();
    }

    public function index()
    {
        $data['parkings'] = $this->parkingModel->getAvailableParkings();
        return view('parking/list', $data);
    }

    public function viewPlaces($id_parking)
    {
        $data['places'] = $this->placeModel->getAvailablePlaces($id_parking);
        $data['parking'] = $this->parkingModel->find($id_parking);
        return view('parking/places', $data);
    }
}
