<?php

namespace App\Models;

use CodeIgniter\Model;

class AdministradorModel extends Model
{
    protected $table = 'administradores';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nombre',
        'email',
        'password'
    ];

    // Fechas
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validación
    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[255]',
        'email' => 'required|valid_email|is_unique[administradores.email,id,{id}]',
        'password' => 'required|min_length[8]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no debe exceder los 255 caracteres'
        ],
        'email' => [
            'required' => 'El correo electrónico es obligatorio',
            'valid_email' => 'Debe proporcionar un correo electrónico válido',
            'is_unique' => 'Este correo electrónico ya está registrado'
        ],
        'password' => [
            'required' => 'La contraseña es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 8 caracteres'
        ]
    ];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected $skipValidation = false;

    /**
     * Hash de la contraseña antes de almacenarla
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }

        return $data;
    }

    /**
     * Verifica las credenciales del administrador
     */
    public function verificarCredenciales($email, $password)
    {
        $admin = $this->where('email', $email)->first();

        if (!$admin) {
            return false;
        }

        return password_verify($password, $admin['password']) ? $admin : false;
    }
}
