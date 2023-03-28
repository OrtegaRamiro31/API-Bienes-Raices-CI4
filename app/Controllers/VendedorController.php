<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\VendedorModel;

class VendedorController extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $model = new VendedorModel();
        $data['usuarios'] = $model->orderBy('id', 'ASC')->findAll();
        return $this->respond($data);
    }

    public function create(){
        $model = new VendedorModel();

        $validation = \Config\Services::validation();
        $validation->setRules($model->validationRules);

        $validation->setRule('rol_id', 'rol', 'required|integer');
        $data = [
            'nombre' => $this->request->getVar('nombre'),
            'apellido' => $this->request->getVar('apellido'),
            'telefono' => $this->request->getVar('telefono'),
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
            'rol_id' => $this->request->getVar('rol_id'),
        ];
        if(!$validation->run($data)) {
            $response = [
                'status' => 400,
                'error' => true,
                'messages' => ['errors' => $validation->getErrors()],
            ];
            return $this->respond($response, 400);
        }
        
        $model->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Propiedad agregada correctamente'
            ]
        ];
        return $this->respondCreated($response);
    }
    public function show($id = null){
        $model = new VendedorModel();
        $data['vendedores_roles'] = $model
                                    ->select('usuarios.*, rol.rol')
                                    ->join('rol', 'usuarios.rol_id = rol.id')
                                    ->find($id);
        if($data){
            return $this->respond($data);
        }
        return $this->failNotFound('No Data Found With id '.$id);
    }
}