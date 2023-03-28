<?php

namespace App\Controllers;

use App\Models\RolesModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class RolesController extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $model = new RolesModel();
        $data['roles'] = $model->orderBy('id', 'ASC')->findAll();
        return $this->respond($data);
    }

}