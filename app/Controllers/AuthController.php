<?php

namespace App\Controllers;

use App\Models\VendedorModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends ResourceController
{
    protected $vendedorModel;

    public function __construct()
    {
        $this->vendedorModel = new VendedorModel();   
    }

    public function login(){

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $user = $this->vendedorModel->where("email", $email)->first();
        // $user = $model
        //         ->select('usuarios.*, rol.rol')
        //         ->join('rol', 'usuarios.rol_id = rol.id')
        //         ->where("email", $email)
        //         ->first();
        // Si el usuario no existe...
        if(is_null($user)){
            $response = [
                'status' => 401,
                'messages' => ['errors' => 'Credenciales inválidas'],
            ];
            // sleep(rand(1,5));
            return $this->respond($response);
        }
        $idUser = $user['id'];
        $idRoleUser = $user['rol_id'];


        // Verificamos la contraseña. Devuelve true si coincide
        $passwordVerify = password_verify($password, $user['password']);

        // Si la contraseña es inválida...
        if(!$passwordVerify){
            $response = [
                'status' => 401,
                'messages' => ['errors' => 'Credenciales inválidas']
            ];
            // sleep(rand(1,5));
            return $this->respond($response);
        }

        // JWT
        $key = $_ENV['JWT_SECRET'];
        $iat = time(); // Tomamos fecha y hora actual
        $exp = $iat + $_ENV['JWT_DURATION']; // Le sumamos 1 hora a $iat para la expiración

        $payload = array(
            'email' => $email,
            'iat' => $iat,
            'exp' => $exp
        );

        // Generamos el token
        $token = JWT::encode($payload, $key, 'HS256');

        // Enviamos una respuesta si el email y password son correctos
        $response = [
            'status' => 200,
            'messages' => ['success' => 'Usuario logueado correctamente'],
            'token' => $token,
            'id' => $idUser,
            'rol' => $idRoleUser
        ];
        return $this->respond($response);        
    }

    public function logout(){   

        $email = $this->request->getVar('email');
        $token = $this->request->getVar('token');
        $decode = JWT::decode($token, new Key(getenv('JWT_SECRET'), 'HS256'));
        if($decode->email === $email) {
            $response = [
                'status' => 200,
                'error' => false,
                'messages' => ['success' => 'Se ha cerrado la sesión corractamente']
            ];
            return $this->respond($response);
        }
        $response = [
            'status' => 401,
            'error' => true,
            'messages' => ['errors' => 'Ha ocurrido un problema al cerrar la sesión']
        ];
        return $this->respond($response);
    }
}