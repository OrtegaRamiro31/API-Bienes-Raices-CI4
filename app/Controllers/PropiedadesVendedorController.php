<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\PropiedadModel;
use App\Models\VendedorModel;

class PropiedadesVendedorController extends ResourceController
{
    use ResponseTrait;

    protected $propiedadModel;
    protected $vendedorModel;

    public function __construct()
    {
        $this->propiedadModel = new PropiedadModel();
        $this->vendedorModel = new VendedorModel();
    }

    public function index()
    {
        $model = new PropiedadModel();
        $data['propiedadesVendedor'] = $model
                                    ->select('propiedades.*,  usuarios.nombre as nombre, usuarios.apellido as apellido,
                                    usuarios.telefono, usuarios.email, usuarios.rol_id')
                                    ->join('usuarios', 'usuarios.id = propiedades.vendedores_id', 'left')
                                    ->orderBy('propiedades.id', 'ASC')
                                    ->findAll();
        return $this->respond($data);
    }

    /**
     * Función que obtiene los registros de propiedades en base a un id
     * Sino no se cumple nada de lo anterior, retorna un failNotFound
     * 
     * @param string $id Id del usuario que se recibe en el endpoint
     * 
     * @return ResponseInterface|string Si obtiene registros, retorna ResponseInterface(json); sino encuentra nada, retorna string.
     */
    public function show($id = null) {
        $vendedor = $this->vendedorModel
                                ->select('rol_id')
                                ->where('id', $id)
                                ->first();
        if(!$vendedor){
            return $this->failNotFound('No se encontró información');
        }
        $data = $this->getPropiedades($vendedor['rol_id'], $id);

        if(!$data){
            return $this->failNotFound('No se encontró información');
        }
        return $this->respond($data);
    }

    /**
     * Funcion que obtiene las propiedades en base al rol del usuario.
     * Si un usuario tiene el rol 1 (propietario), trae todos los registros de propiedades.
     * Si un usuario tiene el rol 2 (vendedor), trae solo los registros de propiedades de ese vendedor.
     * 
     * @param string $rol_id id del rol del usuario
     * @param string $id id correspondiente al usuario
     * 
     * @return array Retorna un array con la información correspondiente a los registros del usuario.
     * Retorna un array vacío en caso de no cumplirse ninguna condición.
     */
    protected function getPropiedades($rol_id, $id){
        if(intval($rol_id) === 1){
            return $this->propiedadModel
                ->select('propiedades.*,  usuarios.nombre as nombre, usuarios.apellido as apellido,
                usuarios.telefono, usuarios.email, usuarios.rol_id')
                ->join('usuarios', 'usuarios.id = propiedades.vendedores_id', 'left')
                ->orderBy('propiedades.id', 'ASC')
                ->findAll();
        }

        if(intval($rol_id) === 2){
            return $this->propiedadModel
                ->select('propiedades.*, usuarios.nombre as nombre, usuarios.apellido as apellido')
                ->join('usuarios', 'propiedades.vendedores_id = usuarios.id')
                ->where('usuarios.id', $id)
                ->get()
                ->getResultArray();
        }

        return [];
    }
    
}