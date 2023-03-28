<?php

    namespace App\Models;

    use  CodeIgniter\Model;

    class VendedorModel extends Model {
        protected $table = 'usuarios';
        protected $primaryKey = 'id';
        protected $allowedFields = ['nombre', 'apellido', 'telefono', 'email', 'password', 'rol_id'];

        protected $validationRules = [
            'nombre' => 'required|min_length[3]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u]',
            'apellido' => 'required|min_length[3]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u]',
            'telefono' => 'required|integer|min_length[10]|max_length[10]|regex_match[/[1-9]{1}[0-9]{9}/]',
            'email' => 'required|valid_emails|min_length[10]|max_length[40]',
            'password' => 'required|min_length[8]',
            'rol_id' => 'required|integer',
        ];
    }