<?php

namespace App\Controllers\API;

use App\Models\EstudianteModel;
use CodeIgniter\RESTful\ResourceController;
use DateTime;

class Estudiantes extends ResourceController
{
    public function __construct()
    {
        $this->model = $this->setModel(new EstudianteModel());
    }
    public function index()
    {
        try {
            $estudiantes = $this->model->findAll();
            return $this->respond($estudiantes);

        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }

    }

    public function create() {
        //return $this->failServerError('Aqui entra');
        try{

            $estudiante = $this->request->getJSON();
            //return $this->respond($estudiante);
            
            $inserto = $this->model->insert($estudiante);
            if($inserto):
                $estudiante->id = $this->model->insertID();
                return $this->respondCreated($estudiante);
            else:
                return $this->failValidationErrors($this->model->validation->listErrors());
            endif;
        } catch (\Exception $e) {
            //return $this->failServerError('Ha ocurrido un error en el servidor');
            return $this->failServerError($e->getMessage());
        }
    }

    public function edit($id = null)
    {
        try {
            if ($id == null) {
                return $this->failNotFound('Id no encontrado');
            }

            $estudiante = $this->model->find($id);
            if ($estudiante == null) {
                return $this->failNotFound('No se encontro el estudiante');
            }

            $estudiante['edad'] = $this->calcularEdad($estudiante['fecha_nacimiento']);
            return $this->respond($estudiante);
        } catch (\Exception $e) {
            //return $this->failServerError('Ha ocurrido un error en el servidor');
            return $this->failServerError($e->getMessage());
        }
    }

    public function update($id = null)
    {
        try {

            if ($id == null) {
                return $this->failNotFound('Id no encontrado');
            }

            $estudianteVerificado = $this->model->find($id);
            if ($estudianteVerificado == null) {
                return $this->failNotFound('No se encontro el estudiante');
            }

            $estudiante = $this->request->getJSON();
            if ($this->model->update($id, $estudiante)) :
                $estudiante->id = $id;
                return $this->respondUpdated($estudiante);
            else :
                return $this->failValidationErrors($this->model->validation->listErrors());
            endif;
        } catch (\Exception $e) {
            //return $this->failServerError('Ha ocurrido un error en el servidor');
            return $this->failServerError($e->getMessage());
        }
    }

    public function delete($id = null)
    {
        try {
            if ($id == null) {
                return $this->failNotFound('Id no encontrado');
            }

            $estudianteVerificado = $this->model->find($id);
            if ($estudianteVerificado == null) {
                return $this->failNotFound('No se encontro el estudiante');
            }

            if ($this->model->delete($id)) :
                return $this->respondDeleted($estudianteVerificado);
            else :
                return $this->failServerError('No se ha podido eliminar el registro');
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
            //return $this->failServerError($e->getMessage());
        }
    }

    public function calcularEdad($fecha_nacimiento)
    {
        $nacimiento = new DateTime($fecha_nacimiento);
        $ahora = new DateTime(date("Y-m-d"));
        $diferencia = $ahora->diff($nacimiento);
        return $diferencia->format("%y");
    }
}
