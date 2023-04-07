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
}