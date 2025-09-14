<?php
    namespace App\Controllers;
    use use App\Models\DataStructModel;

    class AdminCtrl extends Admin
    {
        public function ajout():void
    {
        $model = new Admin();
        $data=[
            'nom'=>$this->request->getPost('nom'),
            'prenom' =>$this->request->getPost('prenom'),
            'email'=>$this->request->getPost('email'),
            'mdp'=>$this->request->getPost('mdp')
        
        ];
        $model->insert($data);
        return redirection;
    }
    }
    
?>