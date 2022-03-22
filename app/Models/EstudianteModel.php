<?php namespace App\Models;

use CodeIgniter\Model;

class EstudianteModel extends Model
{
    protected $table    ='estudiante';
    protected $primaryKey = 'no_control';


    protected $returnType = 'array';
    protected $allowedFields = ['no_control', 'nombre', 'apellidos', 'fecha_nacimiento', 'semestre_actual', 'carrera', 'especialidad'];//Aqui no manejamos las fechas

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'no_control' => 'required|integer|max_length[10]|is_unique[estudiante.no_control]',
        'nombre' => 'required|alpha_space|min_length[3]|max_length[45]',
        'apellidos' => 'required|alpha_space|min_length[3]|max_length[45]',
        'fecha_nacimiento' => 'required|valid_date[Y-m-d]',
        'semestre_actual' => 'required|integer|max_length[2]',
        'carrera' => 'required|alpha_space|min_length[5]|max_length[45]',
        'especialidad' => 'required|alpha_space|min_length[10]|max_length[150]'
    ];

    protected $skipValidation = false;//indica que las validaciones no sean ignoradas
}