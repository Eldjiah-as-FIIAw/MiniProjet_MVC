<?php
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'HomeController::index');
$routes->get('admin/connexion', 'AdminController::connexion');
$routes->post('admin/connexion', 'AdminController::connexion');
$routes->get('admin/dashboard', 'AdminController::dashboard');
$routes->get('clients/inscription', 'ClientController::inscription');
$routes->post('clients/inscription', 'ClientController::inscription');
$routes->get('clients/connexion', 'ClientController::connexion');
$routes->post('clients/connexion', 'ClientController::connexion');
$routes->get('clients/tableau_de_bord', 'ClientController::tableauDeBord');
$routes->get('parkings', 'ParkingController::index');
$routes->get('parkings/(:num)/places', 'ParkingController::voirPlaces/$1');
$routes->get('reservations/ajouter/(:num)', 'ReservationController::ajouter/$1');
$routes->post('reservations/ajouter/(:num)', 'ReservationController::ajouter/$1');
$routes->get('reservations/verifier/(:num)', 'ReservationController::verifierDisponibilite/$1');
$routes->get('logout', 'AuthController::logout');