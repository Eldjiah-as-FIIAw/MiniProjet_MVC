<?php
namespace App\Models;

use CodeIgniter\Model;

class Client extends Model
{
    protected $table = 'client';
    protected $primaryKey = 'id_client';
    protected $allowedFields = ['nom', 'prenom', 'email', 'tel', 'mdp'];

    // Règles de validation
    protected $validationRules = [
        'nom' => 'required|min_length[3]',
        'prenom' => 'required|min_length[3]',
        'email' => 'required|valid_email|is_unique[client.email]',
        'tel' => 'required|min_length[10]',
        'mdp' => 'required|min_length[6]'
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Cet email est déjà utilisé.'
        ]
    ];

    // Méthode pour vérifier un client
    public function verifierClient($email, $mdp)
    {
        $client = $this->where('email', $email)->first();
        if ($client && password_verify($mdp, $client['mdp'])) {
            return $client;
        }
        return null;
    }
}