<?php
namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\ReservationModel;
use App\Models\ParkingModel;
use App\Models\LavageModel;

class ClientController extends BaseController
{
    protected $clientModel;
    protected $reservationModel;
    protected $parkingModel;
    protected $lavageModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->reservationModel = new ReservationModel();
        $this->parkingModel = new ParkingModel();
        $this->lavageModel = new LavageModel();
    }

    public function register()
    {
        if ($this->request->getMethod(true) === 'POST') {
            $data = [
                'nom' => $this->request->getPost('nom'),
                'prenom' => $this->request->getPost('prenom'),
                'email' => $this->request->getPost('email'),
                'tel' => $this->request->getPost('tel'),
                'mdp' => $this->request->getPost('mdp')
            ];

            // Debugging: log the submitted data
            log_message('debug', 'Register data: ' . json_encode($data));

            if ($this->clientModel->insert($data)) {
                log_message('debug', 'Registration successful for email: ' . $data['email']);
                return redirect()->to('/client/login')->with('success', 'Inscription réussie.');
            } else {
                $errors = $this->clientModel->errors();
                log_message('error', 'Registration errors: ' . json_encode($errors));
                return view('client/register', ['errors' => $errors]);
            }
        }
        return view('client/register');
    }

    public function login()
    {
        if ($this->request->getMethod(true) === 'POST') {
            $email = $this->request->getPost('email');
            $mdp = $this->request->getPost('mdp');

            // Debugging: log login attempt
            log_message('debug', 'Login attempt for email: ' . $email);

            $client = $this->clientModel->where('email', $email)->first();
            if ($client && password_verify($mdp, $client['mdp'])) {
                session()->set('client_id', $client['id_client']);
                log_message('debug', 'Login successful for email: ' . $email);
                return redirect()->to('/client/dashboard');
            }
            log_message('error', 'Login failed for email: ' . $email);
            return view('client/login', ['error' => 'Email ou mot de passe incorrect']);
        }
        return view('client/login');
    }

    public function dashboard()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        $client_id = session()->get('client_id');
        $reservations = $this->reservationModel
            ->select('Reservation.*, Parking.nom_parking, Place.numero_place')
            ->join('Place', 'Place.id_place = Reservation.id_place')
            ->join('Parking', 'Parking.id_parking = Place.id_parking')
            ->where('Reservation.id_client', $client_id)
            ->findAll();

        $suggestions = [];
        $parkings_disponibles = $this->parkingModel->getAvailableParkings();
        if (!empty($parkings_disponibles)) {
            $suggestions[] = "Réservez une place dans les parkings suivants : " . implode(', ', array_column($parkings_disponibles, 'nom_parking'));
        }

        return view('client/dashboard', [
            'reservations' => $reservations,
            'suggestions' => $suggestions
        ]);
    }

    public function requestLavage()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        if ($this->request->getMethod(true) === 'POST') {
            $data = [
                'id_reservation' => $this->request->getPost('id_reservation'),
                'type_lavage' => $this->request->getPost('type_lavage'),
                'heure_debut' => $this->request->getPost('heure_debut'),
                'heure_fin' => $this->request->getPost('heure_fin'),
                'statut' => 'demandée'
            ];

            // Debugging: log lavage data
            log_message('debug', 'Lavage request data: ' . json_encode($data));

            if (!$this->lavageModel->checkConflict($data['id_reservation'], $data['heure_debut'], $data['heure_fin'])) {
                if ($this->lavageModel->insert($data)) {
                    log_message('debug', 'Lavage request successful');
                    return redirect()->to('/client/dashboard')->with('success', 'Demande de lavage enregistrée.');
                }
                log_message('error', 'Lavage insertion failed: ' . json_encode($this->lavageModel->errors()));
                return view('client/lavage', ['errors' => ['Conflit d\'horaire ou erreur.']]);
            }
            return view('client/lavage', ['errors' => ['Conflit d\'horaire détecté.']]);
        }

        $reservations = $this->reservationModel->where('id_client', session()->get('client_id'))->findAll();
        return view('client/lavage', ['reservations' => $reservations]);
    }
}