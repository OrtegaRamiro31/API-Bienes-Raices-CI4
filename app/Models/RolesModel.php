<?php

    namespace App\Models;

    use  CodeIgniter\Model;

    class VendedorModel extends Model {
        protected $table = 'rol';
        protected $primaryKey = 'id';
        protected $allowedFields = ['rol'];
    }