<?php

namespace App\Controllers;

use App\Models\CursoModel;
use CodeIgniter\RESTful\ResourceController;

class CursosController extends ResourceController
{
    protected $format = 'json';
    protected $cursoModel;

    public function __construct()
    {
        $this->cursoModel = new CursoModel();
    }

    /**
     * Obtener todos los cursos
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function index()
    {
        $cursos = $this->cursoModel->findAll();

        return $this->respond([
            'status' => 200,
            'data' => $cursos
        ]);
    }

    /**
     * Obtener un curso específico
     * 
     * @param int $id ID del curso
     * @return \CodeIgniter\HTTP\Response
     */
    public function show($id = null)
    {
        $curso = $this->cursoModel->find($id);

        if (!$curso) {
            return $this->failNotFound('Curso no encontrado');
        }

        return $this->respond([
            'status' => 200,
            'data' => $curso
        ]);
    }

    /**
     * Crear un nuevo curso (solo para administradores)
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function create()
    {
        // Aquí se implementaría una verificación de autenticación de admin

        $data = $this->request->getJSON(true);

        if (!$this->cursoModel->validate($data)) {
            return $this->failValidationErrors($this->cursoModel->errors());
        }

        $id = $this->cursoModel->insert($data);

        if (!$id) {
            return $this->fail('Error al crear el curso');
        }

        $curso = $this->cursoModel->find($id);

        return $this->respondCreated([
            'status' => 201,
            'message' => 'Curso creado exitosamente',
            'data' => $curso
        ]);
    }

    /**
     * Actualizar un curso existente (solo para administradores)
     * 
     * @param int $id ID del curso
     * @return \CodeIgniter\HTTP\Response
     */
    public function update($id = null)
    {
        // Aquí se implementaría una verificación de autenticación de admin

        $curso = $this->cursoModel->find($id);

        if (!$curso) {
            return $this->failNotFound('Curso no encontrado');
        }

        $data = $this->request->getJSON(true);

        if (!$this->cursoModel->update($id, $data)) {
            return $this->fail($this->cursoModel->errors());
        }

        $curso = $this->cursoModel->find($id);

        return $this->respond([
            'status' => 200,
            'message' => 'Curso actualizado exitosamente',
            'data' => $curso
        ]);
    }

    /**
     * Eliminar un curso (solo para administradores)
     * 
     * @param int $id ID del curso
     * @return \CodeIgniter\HTTP\Response
     */
    public function delete($id = null)
    {
        // Aquí se implementaría una verificación de autenticación de admin

        $curso = $this->cursoModel->find($id);

        if (!$curso) {
            return $this->failNotFound('Curso no encontrado');
        }

        if (!$this->cursoModel->delete($id)) {
            return $this->fail('Error al eliminar el curso');
        }

        return $this->respondDeleted([
            'status' => 200,
            'message' => 'Curso eliminado exitosamente',
            'id' => $id
        ]);
    }
}
