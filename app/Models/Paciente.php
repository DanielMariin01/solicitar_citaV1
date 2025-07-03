<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ciudad;

use App\Enums\SolicitudEstado; // Asegúrate de que esta línea esté presente
use App\Models\Procedimiento;

class Paciente extends Model

{
   
    // Indicar la tabla asociada al modelo
    protected $table = 'paciente';
    //declarar llave primaria
    protected $primaryKey = 'id_paciente';
    protected $fillable = [
        'nombre',
        'apellido',
        'tipo_identificacion',
        'numero_identificacion',
        'correo',
        'fk_ciudad',
        'procedimiento',
        'fk_eps',
        'celular',
        'historia_clinica',
        'autorizacion',
        'orden-medica',
        'estado',
        'observacion',
          
    ];

    // Relación con Ciudad
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'fk_ciudad', 'id_ciudad');
    }
    // Relación con EPS
    public function eps()
    {
        return $this->belongsTo(EPS::class, 'fk_eps', 'id_eps');
    }
    public function procedimiento()
{
    return $this->belongsTo(Procedimiento::class, 'fk_procedimiento', 'id_procedimiento');
}


    // Relación con Procedimiento
  
}