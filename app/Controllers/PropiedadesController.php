<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\PropiedadModel;

class PropiedadesController extends ResourceController
{
    use ResponseTrait;
    //get all properties
    public function index()
    {
        $model = new PropiedadModel();
        $data['propiedades'] = $model->orderBy('id', 'ASC')->findAll();
        return $this->respond($data);
    }

    public function show($id = null){
        $model = new PropiedadModel();
        $data = $model->where('id', $id)->first();
        if($data){
            return $this->respond($data);
        }
        return $this->failNotFound('No se encontró información con el id: '.$id);
    }

    public function create(){
        $model = new PropiedadModel();

        $validation = \Config\Services::validation();
        $validation->setRules($model->validationRules);

        $data = [
            'titulo' => $this->request->getVar('titulo'),
            'precio' => $this->request->getVar('precio'),
            'imagen' => $this->request->getVar('imagen'),
            'descripcion' => $this->request->getVar('descripcion'),
            'habitaciones' => $this->request->getVar('habitaciones'),
            'wc' => $this->request->getVar('wc'),
            'estacionamiento' => $this->request->getVar('estacionamiento'),
            'creado' => $this->request->getVar('creado'),
            'vendedores_id' => $this->request->getVar('vendedores_id'),
        ];
        
        if(!$validation->run($data)) {
            $response = [
                'status' => 400,
                'error' => true,
                'messages' => [
                    'errors' => $validation->getErrors()
                ],
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

    public function update($id = null)
{
    $model = new PropiedadModel();

    $validation = \Config\Services::validation();
    $validation->setRules($model->validationRules);

    // Verificar ID
    if (is_null($id)) {
        $id = $this->request->getVar('id');
    }

    // Obtener los datos enviados desde el cliente
    $data = [
        'titulo' => $this->request->getVar('titulo'),
        'precio' => $this->request->getVar('precio'),
        'imagen' => $this->request->getVar('imagen'),
        'descripcion' => $this->request->getVar('descripcion'),
        'habitaciones' => $this->request->getVar('habitaciones'),
        'wc' => $this->request->getVar('wc'),
        'estacionamiento' => $this->request->getVar('estacionamiento'),
        'creado' => $this->request->getVar('creado'),
        'vendedores_id' => $this->request->getVar('vendedores_id')
    ];

    
    // Ejecutar validación
    if(!$validation->run($data)){
        $response = [
          'status' => 400,
          'error' => true,
          'messages' => [
                'errors' => $validation->getErrors()
            ]  
        ];
        return $this->respond($response);
    }

    // Actualizar
    $model->where('id', $id)->set($data)->update();

    // Respuesta
    $updatedData = $model->find($id);
    if ($updatedData) {
        $response = [
            'status' => 200, 
            'error' => null, 
            'messages' => [
                'success'=>'Propiedad actualizada correctamente.']
            ];
        return $this->respond($response);
    }
    return $this->failNotFound('No se pudo actualizar la propiedad con ID ' . $id);
}
    
    public function delete($id=null) {
    
        $model = new PropiedadModel();

        $property = $model->find($id);

        if($property === null){
            $response = [
                'status'   => 404,
                'error'    => true,
                'messages' => [
                    'errors' => "Propiedad no encontrada"
                ]
            ];
            return $this->respond($response);
        }
        
        $delete = $model->delete($id);

        if(!$delete) {
            return $this->failServerError('No pudo eliminarse la propiedad');
        }

        $response = [
            'status'   => 204,
            'error'    => null,
            'messages' => [
                'success' => 'Propiedad eliminada correctamente'
            ]
        ];
        return $this->respond($response);
        
    }
}