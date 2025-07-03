<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud_Medico extends Model
{
  protected $table = 'solicitud_admisiones'; // Nombre de la tabla
    
    protected $primaryKey = 'id_solicitud_admision';

    // Atributos que son asignables en masa
    protected $fillable = [
        'fk_paciente',
        'estado',
        'comentario',
        'hora',
        'fecha',
    ];

    // Relación con Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'fk_paciente', 'id_paciente');
    }
    
}
