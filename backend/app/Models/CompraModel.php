<?php

namespace App\Models;

use CodeIgniter\Model;

class CompraModel extends Model
{
    protected $table = 'compras';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'cliente_id',
        'curso_id',
        'fecha_compra',
        'estado'
    ];

    // Fechas
    protected $dateFormat = 'datetime';
    protected $createdField = 'fecha_compra';

    // Validación
    protected $validationRules = [
        'cliente_id' => 'required|integer|is_not_unique[clientes.id]',
        'curso_id' => 'required|integer|is_not_unique[cursos.id]',
        'estado' => 'permit_empty|in_list[pendiente,completada,cancelada]'
    ];

    protected $validationMessages = [
        'cliente_id' => [
            'required' => 'El ID del cliente es obligatorio',
            'integer' => 'El ID del cliente debe ser un número entero',
            'is_not_unique' => 'El cliente especificado no existe'
        ],
        'curso_id' => [
            'required' => 'El ID del curso es obligatorio',
            'integer' => 'El ID del curso debe ser un número entero',
            'is_not_unique' => 'El curso especificado no existe'
        ],
        'estado' => [
            'in_list' => 'El estado debe ser pendiente, completada o cancelada'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Obtiene las compras con información del cliente y curso
     */
    public function getComprasConDetalles($clienteId = null)
    {
        $builder = $this->db->table('compras c')
            ->select('c.id, c.fecha_compra, c.estado, cl.nombre as cliente_nombre, cl.email as cliente_email, cl.telefono as cliente_telefono, cu.nombre as curso_nombre, cu.precio as curso_precio')
            ->join('clientes cl', 'cl.id = c.cliente_id')
            ->join('cursos cu', 'cu.id = c.curso_id')
            ->orderBy('c.fecha_compra', 'DESC');

        if ($clienteId) {
            $builder->where('c.cliente_id', $clienteId);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene estadísticas de compras para el administrador
     */
    public function getEstadisticasCompras()
    {
        // Total de compras por curso
        $comprasPorCurso = $this->db->table('compras c')
            ->select('cu.nombre, COUNT(c.id) as total_compras, SUM(cu.precio) as ingresos_totales')
            ->join('cursos cu', 'cu.id = c.curso_id')
            ->groupBy('cu.id')
            ->get()->getResultArray();

        // Total de compras generales
        $totalCompras = $this->countAll();

        // Ingresos totales
        $ingresosTotales = $this->db->table('compras c')
            ->select('SUM(cu.precio) as total')
            ->join('cursos cu', 'cu.id = c.curso_id')
            ->get()->getRow()->total;

        return [
            'compras_por_curso' => $comprasPorCurso,
            'total_compras' => $totalCompras,
            'ingresos_totales' => $ingresosTotales
        ];
    }

    /**
     * Verifica si un cliente ya compró un curso específico
     */
    public function yaComprado($clienteId, $cursoId)
    {
        return $this->where([
            'cliente_id' => $clienteId,
            'curso_id' => $cursoId
        ])->countAllResults() > 0;
    }
}
