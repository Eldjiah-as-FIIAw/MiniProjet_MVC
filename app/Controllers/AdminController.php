<?php
namespace App\Controllers;

use App\Models\Client;
use App\Models\Reservation;
use App\Models\Parking;
use App\Models\Admin;

class AdminController extends BaseController
{
    public function connexion()
    {
        if ($this->request->getMethod() === 'post') {
            $model = new Admin();
            $email = $this->request->getPost('email');
            $mdp = $this->request->getPost('mdp');
            $admin = $model->verifierAdmin($email, $mdp);
            if ($admin) {
                session()->set('admin', $admin);
                return redirect()->to('/admin/dashboard');
            }
            return view('admin/connexion', ['error' => 'Email ou mot de passe incorrect']);
        }
        return view('admin/connexion');
    }

    public function dashboard()
    {
        if (!session()->has('admin')) {
            return redirect()->to('/admin/connexion');
        }

        $reservationModel = new Reservation();
        $parkingModel = new Parking();

        // Récupérer les meilleurs clients
        $meilleurs_clients = $reservationModel->select('client.nom, client.prenom, client.email, COUNT(reservation.id_reservation) as nb_reservations')
            ->join('client', 'client.id_client = reservation.id_client')
            ->groupBy('client.id_client')
            ->orderBy('nb_reservations', 'DESC')
            ->findAll(5);

        // Générer des suggestions
        $suggestions = [];
        $parkings_peu_utilises = $parkingModel->where('nb_place >', 10)->findAll();
        if (!empty($parkings_peu_utilises)) {
            $suggestions[] = "Promouvoir les parkings suivants avec beaucoup de places disponibles : " . implode(', ', array_column($parkings_peu_utilises, 'nom_parking'));
        }
        $suggestions[] = "Envoyer une offre promotionnelle aux clients ayant effectué plus de 3 réservations.";

        return view('admin/dashboard', [
            'meilleurs_clients' => $meilleurs_clients,
            'suggestions' => $suggestions
        ]);
    }
}