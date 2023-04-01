<?php

namespace App\Controllers;

use App\Models\RolesModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class RolesController extends ResourceController
{
    use ResponseTrait;

    protected $rolesModel;
    public function __construct()
    {
        $this->rolesModel = new RolesModel();
    }
    public function index()
    {
        $model = new RolesModel();
        $data['roles'] = $model->orderBy('id', 'ASC')->findAll();
        return $this->respond($data);
    }

    /**
     * Obtiene los roles correspondientes al rol del usuario
     * Si el usuario tiene como rol 1 (Propietario), obtiene todos los roles.
     * Si el usuario tiene como rol 2 (Vendedor), obtiene solo su propio rol.
     * Sino se cumple ninguna condición, se retorna un JSON con información
     * 
     * @param string $id Id del rol del vendedor
     * 
     * @return ResponseInterface&Json Retorna roles correspondientes
     */
    // Obtenemos ID de Vendedor
    public function getSellerRoles($id = null){
        if(is_null($id)){
            $response = [
                'status' => 404,
                'error' => true,
                'messages' => ['error' => 'Rol incorrecto'],
            ];
            return $this->respond($response);
        }

        if(intval($id) === 1){
            $sellerRolesId = $this->rolesModel
                ->select()
                ->findAll();
                return $this->respond($sellerRolesId);
        }

        $sellerRolesId = $this->rolesModel
        ->where('id', $id)
        ->findAll();

        if(is_null($sellerRolesId) || empty($sellerRolesId)){
            $response = [
                'status' => 404,
                'error' => true,
                'messages' => ['error' => 'Rol incorrecto'],
            ];
            return $this->respond($response);
        }

        return $this->respond($sellerRolesId);
    }
}