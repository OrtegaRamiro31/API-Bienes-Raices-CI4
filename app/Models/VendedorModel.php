<?php

    namespace App\Models;

    use  CodeIgniter\Model;

    class VendedorModel extends Model {
        protected $table = 'usuarios';
        protected $primaryKey = 'id';
        protected $allowedFields = ['nombre', 'apellido', 'telefono', 'email', 'password', 'rol_id'];

        protected $validationRules = [
            'nombre' => 'required|min_length[3]|alpha',
            'apellido' => 'required|min_length[3]|alpha',
            'telefono' => 'required|integer|min_length[10]',
            'email' => 'required|min_length[10]|max_length[30]',
            'password' => 'required|min_length[8]',
            'rol_id' => 'required|integer',
        ];
    }