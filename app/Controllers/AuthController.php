<?php

namespace App\Controllers;

use App\Models\StudentsModel;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    use ResponseTrait;

    private $secretKey = 'seu_secreto_aqui';
    protected $modelName = 'App\Models\StudentsModel';

    public function login()
    {
        $email = $this->request->getJSON()->email;
        $password = $this->request->getJSON()->password;
      
        if ($this->isValidCredentials($email, $password)) {
            $user = $this->getUserByEmail($email);
            $token = $this->generateToken($user['id']);

            return $this->respond(['token' => $token, 'user' => $user]);
        }
        return $this->failUnauthorized('Credenciais inválidas');
    }

    public function me()
    {
        $token = $this->request->getHeaderLine('Authorization');
        try {
            $decoded = JWT::decode($token, $this->secretKey, ['HS256']);
            return $this->respond(['user' => $decoded->sub]);
        } catch (\Exception $e) {
            return $this->failUnauthorized($e->getMessage());
        }
    }

    public function getLoggedUser() {

    }
    private function isValidCredentials($email, $password)
    {
        $student = new StudentsModel();
        $user = $student->where('email', $email)->first();
        return $user && password_verify($password, $user['password']);
    }

    private function getUserByEmail($email)
    {
        $student = new StudentsModel();
        $user = $student->where('email', $email)->first();
        return $user;
    }

    private function generateToken($userId)
    {
        $key = getenv('JWT_SECRET');

        $payload = [
            "iss" => "your_issuer",
            "aud" => "your_audience",
            "iat" => time(),
            "exp" => time() + 3600,
            "data" => [
                "userId" => $userId
            ]
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

}