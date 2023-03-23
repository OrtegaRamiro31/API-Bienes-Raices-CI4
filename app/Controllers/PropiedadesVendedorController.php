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
        $data['propiedades'] = $model
                                    ->select('propiedades.*, usuarios.nombre as nombre, usuarios.apellido as apellido')
                                    ->join('usuarios', 'usuarios.id = propiedades.vendedores_id')
                                    ->orderBy('propiedades.id', 'ASC')
                                    ->findAll();
        return $this->respond($data);
    }
}