<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nombre',
        'email',
        'telefono'
    ];

    // Fechas
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validación
    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[255]',
        'email' => 'required|valid_email|is_unique[clientes.email,id,{id}]',
        'telefono' => 'required|min_length[6]|max_length[20]'
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
        'telefono' => [
            'required' => 'El número de teléfono es obligatorio',
            'min_length' => 'El número de teléfono debe tener al menos 6 caracteres',
            'max_length' => 'El número de teléfono no debe exceder los 20 caracteres'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Busca un cliente por su email o crea uno nuevo si no existe
     */
    public function findOrCreate($data)
    {
        $cliente = $this->where('email', $data['email'])->first();

        if (!$cliente) {
            $id = $this->insert($data);
            return $this->find($id);
        }

        return $cliente;
    }
}
