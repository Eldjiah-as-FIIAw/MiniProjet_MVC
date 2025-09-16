<?php
namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'Admin';
    protected $primaryKey = 'id_admin';
    protected $allowedFields = ['nom', 'prenom', 'email', 'mdp'];

    protected $validationRules = [
        'nom' => 'required|min_length[2]',
        'prenom' => 'required|min_length[2]',
        'email' => 'required|valid_email|is_unique[Admin.email]',
        'mdp' => 'required|min_length[6]'
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Cet email est déjà utilisé.'
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
