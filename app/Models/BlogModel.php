<?php

    namespace App\Models;

    use  CodeIgniter\Model;

    class BlogModel extends Model {
        protected $table = 'blogs';
        protected $primaryKey = 'id';
        protected $allowedFields = ['titulo', 'descripcion', 'fecha', 'imagen', 'vendedores_id'];
    }