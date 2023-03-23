<?php

    namespace App\Models;

    use  CodeIgniter\Model;

    class PropiedadesModel extends Model{
        protected $table = 'propiedades';
        protected $primaryKey = 'id';
        protected $allowedFields = ['titulo','precio','imagen','descripcion','habitaciones','wc','estacionamiento','creado','vendedores_id'];

        protected $validationRules = [
            'titulo' => 'required|min_length[5]',
            'precio' => 'required|numeric|min_length[3]|max_length[8]',
            'descripcion' => 'required|min_length[50]',
            'habitaciones' => 'required|integer|less_than[8]',
            'wc' => 'required|integer|less_than[8]',
            'estacionamiento' => 'required|integer|less_than[8]',
        ];
    }