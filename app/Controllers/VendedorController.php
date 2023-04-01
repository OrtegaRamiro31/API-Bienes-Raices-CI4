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
        
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $model->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Vendedor(a) agregado(a) correctamente'
            ]
        ];
        return $this->respondCreated($response);
    }
    public function show($id = null){
        $model = new VendedorModel();
        
        $data['vendedor'] = $model->where('id', $id)->first();

        if($data){
            return $this->respond($data);
        }
        return $this->failNotFound('No Data Found With id '.$id);
    }


    public function showAll($id = null){
        $model = new VendedorModel();

        $data['vendedores_roles'] = $model
                                    ->select('usuarios.*, rol.rol')
                                    ->join('rol', 'usuarios.rol_id = rol.id')
                                    ->orderBy('usuarios.id')
                                    ->findAll();
        
        if($data){
            return $this->respond($data);
        }
        return $this->failNotFound('No Data Found With id '.$id);
    }

    public function update($id = null)
    {
        $model = new VendedorModel();
        $validation = \Config\Services::validation();
        $validation->setRules($model->validationRules);
    
        // Verificar ID
        if (is_null($id)) {
            $id = $this->request->getVar('id');
        }
    
        // Obtener los datos enviados desde el cliente
        $data = [
            'nombre' => $this->request->getVar('nombre'),
            'apellido' => $this->request->getVar('apellido'),
            'telefono' => $this->request->getVar('telefono'),
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
            'rol_id' => $this->request->getVar('rol_id'),
        ];
    
        
        // Ejecutar validación
        if(!$validation->run($data)) {
            $response = [
                'status' => 400,
                'error' => true,
                'messages' => ['errors' => $validation->getErrors()],
            ];
            return $this->respond($response, 400);
        }
        // Hasheamos el password
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        
        // Actualizar
        $model->where('id', $id)->set($data)->update();
    
        // Respuesta
        $updatedData = $model->find($id);
        if ($updatedData) {
            return $this->respond(
                ['status' => 200, 
                'error' => false,
                'messages' => ['success'=>'Vendedor actualizado correctamente.']]);
        }
        return $this->failNotFound('No se pudo actualizar el vendedor con ID ' . $id);
    }

    public function delete($id=null) {
    
        $model = new VendedorModel();

        $seller = $model->find($id);

        if($seller === null){
            $response = [
                'status'   => 404,
                'error'    => true,
                'messages' => [
                    'errors' => "Vendedor no encontrado"
                ]
            ];
            return $this->respond($response);
        }
        
        $delete = $model->delete($id);

        if(!$delete) {
            return $this->failServerError('No pudo eliminarse el vendedor');
        }

        $response = [
            'status'   => 204,
            'error'    => null,
            'messages' => [
                'success' => 'Vendedor eliminado correctamente'
            ]
        ];
        return $this->respond($response);
        
    }
}