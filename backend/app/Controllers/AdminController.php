<?php

namespace App\Controllers;

use App\Models\AdministradorModel;
use CodeIgniter\RESTful\ResourceController;

class AdminController extends ResourceController
{
    protected $format = 'json';
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdministradorModel();
    }

    /**
     * Autenticar administrador
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function login()
    {
        $data = $this->request->getJSON(true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->fail('Email y contraseña son requeridos');
        }

        $admin = $this->adminModel->verificarCredenciales($data['email'], $data['password']);

        if (!$admin) {
            return $this->failUnauthorized('Credenciales incorrectas');
        }

        // En un sistema real, aquí generaríamos un token JWT
        $token = $this->generarToken($admin);

        return $this->respond([
            'status' => 200,
            'message' => 'Inicio de sesión exitoso',
            'token' => $token,
            'admin' => [
                'id' => $admin['id'],
                'nombre' => $admin['nombre'],
                'email' => $admin['email']
            ]
        ]);
    }

    /**
     * Verificar token de autenticación
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function verificarToken()
    {
        // En un sistema real, aquí verificaríamos el token JWT
        $token = $this->request->getHeaderLine('Authorization');

        if (!$token) {
            return $this->failUnauthorized('Token no proporcionado');
        }

        // Simulamos la verificación de token
        return $this->respond([
            'status' => 200,
            'message' => 'Token válido',
            'isValid' => true
        ]);
    }

    /**
     * Genera un token simple (en un sistema real usaríamos JWT)
     * 
     * @param array $admin Datos del administrador
     * @return string Token generado
     */
    private function generarToken($admin)
    {
        // En un sistema real, usaríamos una biblioteca JWT
        return base64_encode(json_encode([
            'id' => $admin['id'],
            'email' => $admin['email'],
            'exp' => time() + 3600 // 1 hora de expiración
        ]));
    }
}
