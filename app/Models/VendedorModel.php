<?php

    namespace App\Models;

    use  CodeIgniter\Model;

    class VendedorModel extends Model {
        protected $table = 'usuarios';
        protected $primaryKey = 'id';
        protected $allowedFields = ['nombre', 'apellido', 'telefono', 'email', 'password', 'rol_id'];
    }