<?php
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Route par défaut pour l'URL racine
$routes->get('/', 'HomeController::index');

// Routes existantes
$routes->get('client/register', 'ClientController::register');
$routes->post('client/register', 'ClientController::register');
$routes->get('client/login', 'ClientController::login');
$routes->post('client/login', 'ClientController::login');
$routes->get('client/dashboard', 'ClientController::dashboard');
$routes->get('client/lavage', 'ClientController::requestLavage');
$routes->post('client/lavage', 'ClientController::requestLavage');
$routes->get('admin/login', 'AdminController::login');
$routes->post('admin/login', 'AdminController::login');
$routes->get('admin/dashboard', 'AdminController::dashboard');
$routes->get('admin/reservations', 'AdminController::manageReservations');
$routes->get('admin/reservations/confirm/(:num)', 'AdminController::confirmReservation/$1');
$routes->get('parking', 'ParkingController::index');
$routes->get('parking/places/(:num)', 'ParkingController::viewPlaces/$1');
$routes->get('reservation/add/(:num)', 'ReservationController::add/$1');
$routes->post('reservation/add/(:num)', 'ReservationController::add/$1');
$routes->get('logout', function() {
    session()->destroy();
    return redirect()->to('/client/login');
});