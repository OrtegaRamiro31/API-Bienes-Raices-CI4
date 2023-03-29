<?php

namespace App\Controllers;

use App\Models\VendedorModel;
use CodeIgniter\RESTful\ResourceController;


class AuthController extends ResourceController
{

    public function login(){
        $model = new VendedorModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $user = $model->where("email", $email)->first();

        // Si el usuario no existe... esperamos de 1 a 5 segundos y enviamos la respuesta
        if(is_null($user)){
            $response = [
                'messages' => ['errors' => 'Credenciales inválidas'],
            ];
            sleep(rand(1,5));
            return $this->respond($response);
        }

        // Verificamos la contraseña. Devuelve true si coincide
        $passwordVerify = password_verify($password, $user['password']);

        // Si la contraseña es inválida... esperamos de 1 a 5 segundos y enviamos la respuesta
        if(!$passwordVerify){
            $response = [
                    'messages' => ['errors' => 'Credenciales inválidas']
            ];
            sleep(rand(1,5));
            return $this->respond($response);
        }

        // Enviamos una respuesta si el email y password son correctos
        $response = [
            'messages' => ['success' => 'Usuario logueado correctamente']
        ];
        return $this->respond($response);        
    }
}