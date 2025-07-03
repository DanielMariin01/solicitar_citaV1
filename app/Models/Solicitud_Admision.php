<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud_Admision extends Model
{
    // Declarar llave primaria
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
    public function eps()
    {
        return $this->belongsTo(EPS::class, 'fk_eps', 'id_eps');
    }
    
}
