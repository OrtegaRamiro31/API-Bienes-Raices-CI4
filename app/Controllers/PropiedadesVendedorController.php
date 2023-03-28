<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\PropiedadModel;

class PropiedadesVendedorController extends ResourceController
{
    use ResponseTrait;
    public function index()
    {
        $model = new PropiedadModel();
        $data['propiedadesVendedor'] = $model
                                    ->select('propiedades.*,  usuarios.nombre as nombre, usuarios.apellido as apellido,
                                    usuarios.telefono, usuarios.email, usuarios.rol_id')
                                    ->join('usuarios', 'usuarios.id = propiedades.vendedores_id')
                                    ->orderBy('propiedades.id', 'ASC')
                                    ->findAll();
        return $this->respond($data);
    }

    public function show($id = null){
        $model = new PropiedadModel();
        $data['propiedades'] = $model
                                    ->select('propiedades.*, usuarios.nombre as nombre, usuarios.apellido as apellido')
                                    ->join('usuarios', 'usuarios.id = propiedades.vendedores_id')
                                    ->orderBy('propiedades.id', 'ASC')
                                    ->find($id);
        if($data){
            return $this->respond($data);
        }
        return $this->failNotFound('No Data Found With id '.$id);
    }
}