<?php
namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'Client';
    protected $primaryKey = 'id_client';
    protected $allowedFields = ['nom', 'prenom', 'email', 'tel', 'mdp'];

    protected $validationRules = [
        'nom' => 'required|min_length[2]',
        'prenom' => 'required|min_length[2]',
        'email' => 'required|valid_email|is_unique[Client.email]',
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

    protected $beforeInsert = ['hashPassword'];

    public function hashPassword(array $data)
    {
        if (isset($data['data']['mdp'])) {
            $data['data']['mdp'] = password_hash($data['data']['mdp'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}
