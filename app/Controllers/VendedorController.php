<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\VendedorModel;

class VendedorController extends ResourceController
{
    use ResponseTrait;

    protected $vendedorModel;

    public function __construct()
    {
        $this->vendedorModel = new VendedorModel();
    }

    public function index()
    {
        
        $data['usuarios'] = $this->vendedorModel->orderBy('id', 'ASC')->findAll();
        return $this->respond($data);
    }

    public function show($id = null){
        $rol = $this->vendedorModel
                    ->select('rol_id')
                    ->first();
                   
                                

        $data['vendedor'] = $this->vendedorModel
                            ->select('usuarios.*, rol.id, rol.rol')
                            ->join('rol', 'usuarios.rol_id = rol.id')
                            ->where('usuarios.id', $id)
                            ->findAll();

        return $this->respond($data);
    }

    public function create(){

        $validation = \Config\Services::validation();
        $validation->setRules($this->vendedorModel->validationRules);

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
        
        $this->vendedorModel->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Vendedor(a) agregado(a) correctamente'
            ]
        ];
        return $this->respondCreated($response);
    }

     /**
     * Función que obtiene los vendedores en base al rol.
     * Si un usuario tiene el rol 1 (Propietario), responde con todos los vendedores
     * Si un usuario tiene el rol 2 (vendedor), response con solo ese vendedor
     * Sino se cumple ninguna condición, retornamos un mensaje de error
     * 
     * @param string $id id del usuario
     * 
     * @return ResponseInterface&Json Devuelve un JSON con los registros o el mensaje de error
     */
    public function showSellerRole($id = null){       
        $sellerRoleId = $this->vendedorModel
        ->select('usuarios.rol_id')
        ->join('rol', 'usuarios.rol_id = rol.id')
        ->where('usuarios.id', $id)
        ->first();

        if(is_null($sellerRoleId)){
            $response = [
                'status' => 404,
                'error' => true,
                'messages' => ['error' => 'Usuario o rol incorrectos'],
            ];
            return $this->respond($response);
        }

        $roleId = $sellerRoleId['rol_id'];

        if(intval($roleId) === 1){
            $vendedores['vendedores'] = $this->vendedorModel
                ->select('usuarios.*, rol.rol')
                ->join('rol', 'usuarios.rol_id = rol.id')
                ->orderBy('usuarios.id')
                ->findAll();
        }
        elseif(intval($roleId) == 2 || intval($roleId) == 3){
            $vendedores['vendedores'] = $this->vendedorModel
            ->select('usuarios.*, rol.rol')
            ->join('rol', 'usuarios.rol_id = rol.id')
            ->where('usuarios.id', $id)
            ->findAll();
        } 


        if(!isset($vendedores)){
            $response = [
                'status' => 404,
                'error' => true,
                'messages' => ['error' => 'No se ha encontrado información'],
            ];
            return $this->respond($response);
        }
        return $this->respond($vendedores);

    }


    public function showAll($id = null){
        
        $data['vendedores_roles'] = $data['vendedores_roles'] = $this->vendedorModel
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
        
        $validation = \Config\Services::validation();
        $validation->setRules($this->vendedorModel->validationRules);
    
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
        $this->vendedorModel->where('id', $id)->set($data)->update();
    
        // Respuesta
        $updatedData = $this->vendedorModel->find($id);
        if ($updatedData) {
            return $this->respond(
                ['status' => 200, 
                'error' => false,
                'messages' => ['success'=>'Vendedor actualizado correctamente.']]);
        }
        return $this->failNotFound('No se pudo actualizar el vendedor con ID ' . $id);
    }

    public function delete($id=null) {
    
        

        $seller = $this->vendedorModel->find($id);

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
        
        $delete = $this->vendedorModel->delete($id);

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