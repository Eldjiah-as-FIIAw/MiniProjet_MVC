<?php
namespace App\Models;

use CodeIgniter\Model;

class Admin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $allowedFields = ['nom', 'prenom', 'email', 'mdp'];

    // Règles de validation
    protected $validationRules = [
        'nom' => 'required|min_length[3]',
        'prenom' => 'required|min_length[3]',
        'email' => 'required|valid_email|is_unique[admin.email]',
        'mdp' => 'required|min_length[6]'
    ];

    // Méthode pour vérifier un admin
    public function verifierAdmin($email, $mdp)
    {
        $admin = $this->where('email', $email)->first();
        if ($admin && password_verify($mdp, $admin['mdp'])) {
            return $admin;
        }
        return null;
    }
}