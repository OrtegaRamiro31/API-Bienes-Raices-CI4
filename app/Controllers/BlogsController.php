<?php

namespace App\Controllers;

use App\Models\BlogModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class BlogsController extends ResourceController
{
    use ResponseTrait;

    protected $blogModel;
    public function __construct()
    {
        $this->blogModel = new BlogModel();
    }
    //get all properties
    public function index()
    {
        $data['blogs'] = $this->blogModel
                        ->select('blogs.*, usuarios.nombre, usuarios.apellido')
                        ->join('usuarios', 'blogs.vendedores_id = usuarios.id')
                        ->orderBy('id', 'ASC')
                        ->findAll();
        return $this->respond($data);
    }

    public function show($id = null)
    {
        $data = $this->blogModel
                ->where('id', $id)
                ->first();
        if($data){
            return $this->respond($data);
        }
        return $this->failNotFound('No se encontró información con el id: '.$id);
    }
    public function create()
    {
        $validation = \Config\Services::validation();
        $validation->setRules($this->blogModel->validationRules);

        $data = [
            'titulo' => $this->request->getVar('titulo'),
            'descripcion' => $this->request->getVar('descripcion'),
            'fecha' => $this->request->getVar('fecha'),
            'imagen' => $this->request->getVar('imagen'),
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
        
        $this->blogModel->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Blog agregado correctamente'
            ]
        ];
        return $this->respondCreated($response);
    }

    public function update($id = null) {

    $validation = \Config\Services::validation();
    $validation->setRules($this->blogModel->validationRules);

    // Verificar ID
    if (is_null($id)) {
        $id = $this->request->getVar('id');
    }

    // Obtener los datos enviados desde el cliente
    $data = [
        'titulo' => $this->request->getVar('titulo'),
        'imagen' => $this->request->getVar('imagen'),
        'descripcion' => $this->request->getVar('descripcion'),
        'fecha' => $this->request->getVar('fecha'),
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
    $this->blogModel->where('id', $id)->set($data)->update();

    // Respuesta
    $updatedData = $this->blogModel->find($id);
    if ($updatedData) {
        $response = [
            'status' => 200, 
            'error' => null, 
            'messages' => [
                'success'=>'Blog actualizado correctamente.']
            ];
        return $this->respond($response);
    }
    return $this->failNotFound('No se pudo actualizar el blog con ID ' . $id);
    }

    public function delete($id = null){

        $blog = $this->blogModel->find($id);

        if($blog === null){
            $response = [
                'status'   => 404,
                'error'    => true,
                'messages' => [
                    'errors' => "Blog no encontrado"
                ]
            ];
            return $this->respond($response);
        }
        
        $delete = $this->blogModel->delete($id);

        if(!$delete) {
            return $this->failServerError('No pudo eliminarse el blog');
        }

        $response = [
            'status'   => 204,
            'error'    => null,
            'messages' => [
                'success' => 'Blog eliminado correctamente'
            ]
        ];
        return $this->respond($response);
        
    }
}