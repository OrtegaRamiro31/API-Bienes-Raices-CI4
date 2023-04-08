<?php

    namespace App\Models;

    use  CodeIgniter\Model;

    class BlogModel extends Model {
        protected $table = 'blogs';
        protected $primaryKey = 'id';
        protected $allowedFields = ['titulo', 'descripcion', 'fecha', 'imagen', 'vendedores_id'];

        protected $validationRules = [
            'titulo' => 'required|min_length[5]',
            'descripcion' => 'required|min_length[50]',
            'imagen' => 'required',
            'fecha' => 'required',
            'vendedores_id' => 'required',
        ];

    }