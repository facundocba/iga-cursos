<?php

namespace App\Models;

use CodeIgniter\Model;

class CursoModel extends Model
{
    protected $table = 'cursos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nombre',
        'descripcion',
        'precio',
        'detalle',
        'imagen'
    ];

    // Fechas
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validación
    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[255]',
        'descripcion' => 'required|min_length[10]',
        'precio' => 'required|numeric',
        'detalle' => 'required|min_length[10]',
        'imagen' => 'permit_empty|max_length[255]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre del curso es obligatorio',
            'min_length' => 'El nombre del curso debe tener al menos 3 caracteres',
            'max_length' => 'El nombre del curso no debe exceder los 255 caracteres'
        ],
        'descripcion' => [
            'required' => 'La descripción del curso es obligatoria',
            'min_length' => 'La descripción debe tener al menos 10 caracteres'
        ],
        'precio' => [
            'required' => 'El precio del curso es obligatorio',
            'numeric' => 'El precio debe ser un valor numérico'
        ],
        'detalle' => [
            'required' => 'El detalle del curso es obligatorio',
            'min_length' => 'El detalle debe tener al menos 10 caracteres'
        ]
    ];

    protected $skipValidation = false;
}
