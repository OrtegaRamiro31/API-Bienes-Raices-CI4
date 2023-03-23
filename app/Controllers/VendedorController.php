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
}