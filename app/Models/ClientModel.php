<?php
namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'client'; // Table name in lowercase
    protected $primaryKey = 'id_client';
    protected $allowedFields = ['nom', 'prenom', 'email', 'tel', 'mdp'];

    protected $validationRules = [
        'nom' => 'required|min_length[2]',
        'prenom' => 'required|min_length[2]',
        'email' => 'required|valid_email|is_unique[client.email]',
        'tel' => 'required|min_length[10]',
        'mdp' => 'required|min_length[6]'
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Cet email est déjà utilisé.'
        ],
        'tel' => [
            'min_length' => 'Le numéro de téléphone doit contenir au moins 10 chiffres.'
        ]
    ];

    protected $beforeInsert = ['normalizeEmail', 'hashPassword'];

    public function normalizeEmail(array $data)
    {
        if (isset($data['data']['email'])) {
            log_message('debug', 'Normalizing email: ' . $data['data']['email']);
            $data['data']['email'] = trim(strtolower($data['data']['email']));
            log_message('debug', 'Normalized email: ' . $data['data']['email']);
        }
        return $data;
    }

    public function hashPassword(array $data)
    {
        if (isset($data['data']['mdp'])) {
            log_message('debug', 'Hashing password for email: ' . $data['data']['email']);
            $data['data']['mdp'] = password_hash($data['data']['mdp'], PASSWORD_BCRYPT);
            log_message('debug', 'Hashed password: ' . $data['data']['mdp']);
        }
        return $data;
    }
}