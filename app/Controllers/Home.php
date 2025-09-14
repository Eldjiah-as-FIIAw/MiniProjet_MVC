<?php

namespace App\Controllers;
use App\Models\DataStructModel;
class Home extends BaseController
{
    public function index(): string
    {
        $clientModel= new Client();
        // appellation la table
        $data['client']= $clientModel->findAll();
        return view('liste', $data);
    }
    public function ajout():void
    {
        $model = new UserModel();
        $data=[
            'nom'=>$this->request->getPost('nom'),
            'prenom' =>$this->request->getPost('prenom'),
            'email'=>$this->request->getPost('email')
            'tel'=>$this->request->getPost('tel')
            'mdp'=>$this->request->getPost('mdp')
        
        ];
        $model->insert($data);
        return redirection;
    }
    // public function nouv(): string{
    //     return view('nouveau');
    // }
}
