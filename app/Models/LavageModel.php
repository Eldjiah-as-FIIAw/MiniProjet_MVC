<?php
namespace App\Models;

use CodeIgniter\Model;

class LavageModel extends Model
{
    protected $table = 'Lavage';
    protected $primaryKey = 'id_lavage';
    protected $allowedFields = ['id_reservation', 'type_lavage', 'heure_debut', 'heure_fin', 'statut'];

    protected $validationRules = [
        'id_reservation' => 'required|is_natural_no_zero',
        'type_lavage' => 'required|in_list[intérieur,extérieur,complet]',
        'heure_debut' => 'required',
        'heure_fin' => 'required',
        'statut' => 'required|in_list[demandée,en cours,terminée]'
    ];

    public function checkConflict($id_reservation, $heure_debut, $heure_fin)
    {
        return $this->where('id_reservation', $id_reservation)
                    ->where('(heure_debut <= ?', $heure_fin)
                    ->where('heure_fin >= ?)', $heure_debut)
                    ->where('statut !=', 'terminée')
                    ->countAllResults() > 0;
    }
}