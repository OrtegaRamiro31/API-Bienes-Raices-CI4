<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\PropiedadesModel;

class PropiedadesController extends ResourceController
{
    use ResponseTrait;
    //get all properties
    public function index()
    {
        $model = new PropiedadesModel();
        $data['propiedades'] = $model->orderBy('id', 'ASC')->findAll();
        return $this->respond($data);
    }

    public function show($id = null){
        $model = new PropiedadesModel();
        $data = $model->where('id', $id)->first();
        if($data){
            return $this->respond($data);
        }
        return $this->failNotFound('No Data Found With id '.$id);
    }

    public function create(){
        $model = new PropiedadesModel();
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

    // public function update($id = null){
    //     $model = new PropiedadesModel();
    //     $data = [
    //         'titulo' => $this->request->getVar('titulo'),
    //         'precio' => $this->request->getVar('precio'),
    //         'imagen' => $this->request->getVar('imagen'),
    //         'descripcion' => $this->request->getVar('descripcion'),
    //         'habitaciones' => $this->request->getVar('habitaciones'),
    //         'wc' => $this->request->getVar('wc'),
    //         'estacionamiento' => $this->request->getVar('estacionamiento'),
    //         'creado' => $this->request->getVar('creado'),
    //         'vendedores_id' => $this->request->getVar('vendedores_id'),
    //     ];
    //     $model->update($id,$data);
    //     $response = [
    //         'status'   => 200,
    //         'error'    => null,
    //         'messages' => [
    //             'success' => 'Propiedad actualizada correctamente'
    //         ]
    //     ];
    //     return $this->respond($response);
    // }
    public function update($id = null){
        $model = model(PropiedadesModel::class);
        if(is_null($id)){
            $id = $this->request->getVar('id');
            var_dump($id);
        }
        $data = ['titulo' => $this->request->getVar('titulo'),
                'precio' => $this->request->getVar('precio'),
                'imagen' => $this->request->getVar('imagen'),
                'descripcion' => $this->request->getVar('descripcion'),
                'habitaciones' => $this->request->getVar('habitaciones'),
                'wc' => $this->request->getVar('wc'),
                'estacionamiento' => $this->request->getVar('estacionamiento'),
                'creado' => $this->request->getVar('creado'),
                'vendedores_id' => $this->request->getVar('vendedores_id')
            ];

            $model->where('id',$id)->set($data)->update($id,$data);
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Propiedad actualizada correctamente'
                    ]
                ];
                return $this->respondUpdated($response);
    }
    
    public function delete($id=null) {
    
        $model = new PropiedadesModel();
        $data = $model->where('id', $id)->delete($id);
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Propiedad eliminada correctamente'
                ]
            ];
            return $this->respondDeleted($response);
        }
        return $this->failNotFound('Propiedad no encontrada');
    }
}