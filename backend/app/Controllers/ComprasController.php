<?php

namespace App\Controllers;

use App\Models\CompraModel;
use App\Models\ClienteModel;
use App\Models\CursoModel;
use CodeIgniter\RESTful\ResourceController;

class ComprasController extends ResourceController
{
    protected $format = 'json';
    protected $compraModel;
    protected $clienteModel;
    protected $cursoModel;

    public function __construct()
    {
        $this->compraModel = new CompraModel();
        $this->clienteModel = new ClienteModel();
        $this->cursoModel = new CursoModel();
    }

    /**
     * Obtener compras de un cliente por su email
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function misCompras()
    {
        $email = $this->request->getVar('email');

        if (!$email) {
            return $this->fail('Email no proporcionado');
        }

        $cliente = $this->clienteModel->where('email', $email)->first();

        if (!$cliente) {
            return $this->respond([
                'status' => 200,
                'data' => []
            ]);
        }

        $compras = $this->compraModel->getComprasConDetalles($cliente['id']);

        return $this->respond([
            'status' => 200,
            'data' => $compras
        ]);
    }

    /**
     * Obtener todas las compras (solo para administradores)
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function index()
    {
        // Aquí se implementaría una verificación de autenticación de admin

        $compras = $this->compraModel->getComprasConDetalles();

        return $this->respond([
            'status' => 200,
            'data' => $compras
        ]);
    }

    /**
     * Obtener estadísticas de compras (solo para administradores)
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function estadisticas()
    {
        // Aquí se implementaría una verificación de autenticación de admin

        $estadisticas = $this->compraModel->getEstadisticasCompras();

        return $this->respond([
            'status' => 200,
            'data' => $estadisticas
        ]);
    }

    /**
     * Registrar una nueva compra
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function create()
    {
        $json = $this->request->getJSON();

        // Registramos la información en el log para depuración
        log_message('info', 'Datos recibidos en ComprasController::create: ' . json_encode($json));

        // Verificamos si hay datos JSON
        if (!$json) {
            log_message('error', 'No se recibieron datos JSON en la solicitud');
            return $this->fail('No se recibieron datos en la solicitud');
        }

        $data = $json;

        if (!isset($data->cliente) || !isset($data->curso_id)) {
            log_message('error', 'Datos incompletos: falta cliente o curso_id');
            return $this->fail('Datos incompletos: falta cliente o curso_id');
        }

        // Verificar si el curso existe
        $curso = $this->cursoModel->find($data->curso_id);
        if (!$curso) {
            log_message('error', 'Curso no encontrado: ' . $data->curso_id);
            return $this->failNotFound('Curso no encontrado');
        }

        // Crear o actualizar cliente
        $clienteData = (array)$data->cliente;
        $cliente = $this->clienteModel->findOrCreate($clienteData);

        // Verificar si ya compró este curso
        if ($this->compraModel->yaComprado($cliente['id'], $data->curso_id)) {
            log_message('info', 'El cliente ya compró este curso anteriormente');
            return $this->fail('Ya has comprado este curso anteriormente');
        }

        // Registrar la compra
        $compraData = [
            'cliente_id' => $cliente['id'],
            'curso_id' => $data->curso_id,
            'estado' => 'completada'
        ];

        $id = $this->compraModel->insert($compraData);

        if (!$id) {
            log_message('error', 'Error al registrar la compra: ' . json_encode($this->compraModel->errors()));
            return $this->fail('Error al registrar la compra: ' . json_encode($this->compraModel->errors()));
        }

        $compra = $this->compraModel->getComprasConDetalles($cliente['id']);

        return $this->respondCreated([
            'status' => 201,
            'message' => 'Compra registrada exitosamente',
            'data' => $compra
        ]);
    }

    /**
     * Cambiar el estado de una compra (solo para administradores)
     * 
     * @param int $id ID de la compra
     * @return \CodeIgniter\HTTP\Response
     */
    public function actualizarEstado($id = null)
    {
        // Aquí se implementaría una verificación de autenticación de admin

        $compra = $this->compraModel->find($id);

        if (!$compra) {
            return $this->failNotFound('Compra no encontrada');
        }

        $estado = $this->request->getVar('estado');

        if (!in_array($estado, ['pendiente', 'completada', 'cancelada'])) {
            return $this->fail('Estado no válido');
        }

        if (!$this->compraModel->update($id, ['estado' => $estado])) {
            return $this->fail('Error al actualizar el estado de la compra');
        }

        return $this->respond([
            'status' => 200,
            'message' => 'Estado de compra actualizado exitosamente',
            'id' => $id,
            'estado' => $estado
        ]);
    }
}
