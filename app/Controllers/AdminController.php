<?php
namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\ReservationModel;
use App\Models\ParkingModel;
use App\Models\LavageModel;

class AdminController extends BaseController
{
    protected $adminModel;
    protected $reservationModel;
    protected $parkingModel;
    protected $lavageModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->reservationModel = new ReservationModel();
        $this->parkingModel = new ParkingModel();
        $this->lavageModel = new LavageModel();
    }

    public function login()
    {
        if ($this->request->getMethod(true) === 'POST') {
            $email = $this->request->getPost('email');
            $mdp = $this->request->getPost('mdp');

            // Debugging: log login attempt
            log_message('debug', 'Admin login attempt for email: ' . $email);

            $admin = $this->adminModel->where('email', $email)->first();
            if ($admin && password_verify($mdp, $admin['mdp'])) {
                session()->set('admin_id', $admin['id_admin']);
                log_message('debug', 'Admin login successful for email: ' . $email);
                return redirect()->to('/admin/dashboard');
            }
            log_message('error', 'Admin login failed for email: ' . $email);
            return view('admin/login', ['error' => 'Email ou mot de passe incorrect']);
        }
        return view('admin/login');
    }

    public function dashboard()
    {
        if (!session()->has('admin_id')) {
            return redirect()->to('/admin/login');
        }

        $meilleurs_clients = $this->reservationModel
            ->select('Client.nom, Client.prenom, Client.email, COUNT(Reservation.id_reservation) as nb_reservations')
            ->join('Client', 'Client.id_client = Reservation.id_client')
            ->groupBy('Client.id_client')
            ->orderBy('nb_reservations', 'DESC')
            ->findAll(5);

        $stats_parkings = $this->reservationModel
            ->select('Parking.nom_parking, COUNT(Reservation.id_reservation) as total_reservations')
            ->join('Place', 'Place.id_place = Reservation.id_place')
            ->join('Parking', 'Parking.id_parking = Place.id_parking')
            ->groupBy('Parking.id_parking')
            ->orderBy('total_reservations', 'DESC')
            ->findAll();

        $suggestions = [];
        $parkings_peu_utilises = $this->parkingModel->getAvailableParkings();
        if (!empty($parkings_peu_utilises)) {
            $suggestions[] = "Promouvoir les parkings suivants avec beaucoup de places disponibles : " . implode(', ', array_column($parkings_peu_utilises, 'nom_parking'));
        }
        if (!empty($meilleurs_clients)) {
            $suggestions[] = "Envoyer une offre promotionnelle aux clients suivants : " . implode(', ', array_column($meilleurs_clients, 'email'));
        }

        $lavages_stats = $this->lavageModel
            ->select('type_lavage, COUNT(*) as total')
            ->groupBy('type_lavage')
            ->findAll();

        return view('admin/dashboard', [
            'meilleurs_clients' => $meilleurs_clients,
            'stats_parkings' => $stats_parkings,
            'suggestions' => $suggestions,
            'lavages_stats' => $lavages_stats
        ]);
    }

    public function manageReservations()
    {
        if (!session()->has('admin_id')) {
            return redirect()->to('/admin/login');
        }

        $reservations = $this->reservationModel
            ->select('Reservation.*, Client.nom as client_nom, Place.numero_place, Parking.nom_parking')
            ->join('Client', 'Client.id_client = Reservation.id_client')
            ->join('Place', 'Place.id_place = Reservation.id_place')
            ->join('Parking', 'Parking.id_parking = Place.id_parking')
            ->findAll();

        return view('admin/reservations', ['reservations' => $reservations]);
    }

    public function confirmReservation($id_reservation)
    {
        if (!session()->has('admin_id')) {
            return redirect()->to('/admin/login');
        }

        $this->reservationModel->update($id_reservation, [
            'statut' => 'confirmée',
            'validée_par' => session()->get('admin_id')
        ]);
        return redirect()->to('/admin/reservations')->with('success', 'Réservation confirmée.');
    }
}