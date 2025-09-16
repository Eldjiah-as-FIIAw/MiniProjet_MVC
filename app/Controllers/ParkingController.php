<?php
namespace App\Controllers;

use App\Models\Parking;
use App\Models\Place;

class ParkingController extends BaseController
{
    public function index()
    {
        $model = new Parking();
        $data['parkings'] = $model->getParkingsDisponibles();
        return view('parkings/liste', $data);
    }

    public function voirPlaces($id_parking)
    {
        $placeModel = new Place();
        $data['places'] = $placeModel->getPlacesLibres($id_parking);
        $data['parking'] = (new Parking())->find($id_parking);
        return view('parkings/places', $data);
    }
    
}