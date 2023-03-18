<?php

    namespace App\Models;

    use  CodeIgniter\Model;

    class PropiedadesModel extends Model{
        protected $table = 'propiedades';
        protected $primaryKey = 'id';
        protected $allowedFields = ['titulo','precio','imagen','descripcion','habitaciones','wc','estacionamiento','creado','vendedores_id'];


    }