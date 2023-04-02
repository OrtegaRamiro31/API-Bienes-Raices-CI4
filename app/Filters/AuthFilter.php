<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $key = $_ENV['JWT_SECRET'];
        $header = $request->getHeaderLine("Authorization");
        $token = null;
        if(!empty($header)){
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }
        
        if(is_null($token) || empty($token)){
            $response = service('response');
            $respondBody = [
                'status' => 401,
                'messages' => ['errors' => 'Accesso Denegados'],
                'error' => true
            ];
            $response->setJSON($respondBody);
            $response->setStatusCode(401);
            return $response;
        }
        
        try{
            $decode = JWT::decode($token, new Key($key, 'HS256'));
        }
        catch(SignatureInvalidException $ex) {
            $response = service('response');
            $respondBody = [
                'status' => 401,
                'messages' => ['errors' => 'La sesión ha tenido un problema. Comuníquese con el departamente de TI.'],
                'error' => true,
                'typeError' => "SignatureInvalidException"
            ];
            $response->setJSON($respondBody);
            $response->setStatusCode(401);
            return $response;
        }
        catch(ExpiredException $ex){
            $response = service('response');
            $respondBody = [
                'status' => 401,
                'messages' => ['errors' => 'La sesión ha expirado'],
                'error' => true,
                'typeError' => 'ExpiredException'
            ];
            $response->setJSON($respondBody);
            $response->setStatusCode(401);
            return $response;
        }

        catch(Exception $ex) {
            $response = service('response');
            $respondBody = [
                'status' => 401,
                'messages' => ['errors' => 'Accesso Denegado'.$ex->getMessage()],
                'error' => true,
                'typeError' => 'Exception'
            ];
            $response->setJSON($respondBody);
            $response->setStatusCode(401);
            return $response;
        }

    }


    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
