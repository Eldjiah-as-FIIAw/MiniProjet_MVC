<?php
    namespace App\Models;
    // utiliser bibli 
    use CodeIgniter\Model;

    class Client extends Model
    {
        //Appel des table
        protected $table="client";
        protected $primarykey="id_client";
        protected $allowdFields=['nom', 'prenom','email','tel' ,'mdp'];
    }
    class Admin extends Model
    {
                //Appel des table
        protected $table="admin";
        protected $primarykey="id_admin";
        protected $allowdFields=['nom', 'prenom','email' ,'mdp'];
    }
    class Parking extends  Model
    {
                  //Appel des table
        protected $table="parking";
        protected $primarykey="id_parking";
        protected $allowdFields=['nb_place', 'nom_parking','addresse'];
        
    }
    class Reservation extends Model 
    {
                          //Appel des table
        protected $table="reservation";
        protected $primarykey="id_reservation";
        protected $allowdFields=['date_reservation', 'heure_debut','heure_fin', 'statut', 'id_client', 'id_place'];
                
    }
    
    class Place extends Model 
    {
        protected $table="place";
        protected $primarykey="id_place";
        protected $allowdFields=['numero_place', 'etat','id_parking', 'statut'];
      
    }
    