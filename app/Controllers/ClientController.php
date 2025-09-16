<?php
namespace App\Controllers;

use App\Models\Client;

class ClientController extends BaseController
{
    public function inscription()
    {
        if ($this->request->getMethod() === 'post') {
            $model = new Client();
            $data = $this->request->getPost(['nom', 'prenom', 'email', 'tel', 'mdp']);
            $data['mdp'] = password_hash($data['mdp'], PASSWORD_DEFAULT);
            if ($model->insert($data)) {
                return redirect()->to('/clients/connexion');
            }
            return view('clients/inscription', ['errors' => $model->errors()]);
        }
        return view('clients/inscription');
    }
    public function connexion()
{
    if ($this->request->getMethod() === 'post') {
        $model = new Client();
        $email = $this->request->getPost('email');
        $mdp = $this->request->getPost('mdp');
        $client = $model->verifierClient($email, $mdp);
        if ($client) {
            session()->set('client', $client);
            return redirect()->to('/clients/tableau_de_bord');
        }
        return view('clients/connexion', ['error' => 'Email ou mot de passe incorrect']);
    }
    return view('clients/connexion');
}
public function tableauDeBord()
{
    $reservationModel = new Reservation();
    $parkingModel = new Parking();
    $client_id = session()->get('client')['id_client'];

    // Récupérer les réservations du client
    $reservations = $reservationModel->select('reservation.*, parking.nom_parking, place.numero_place')
        ->join('place', 'place.id_place = reservation.id_place')
        ->join('parking', 'parking.id_parking = place.id_parking')
        ->where('reservation.id_client', $client_id)
        ->findAll();

    // Générer des suggestions
    $suggestions = [];
    $parkings_disponibles = $parkingModel->getParkingsDisponibles();
    if (!empty($parkings_disponibles)) {
        $suggestions[] = "Réservez une place dans les parkings suivants : " . implode(', ', array_column($parkings_disponibles, 'nom_parking'));
    }

    return view('clients/tableau_de_bord', [
        'reservations' => $reservations,
        'suggestions' => $suggestions
    ]);
}
}