<?php

namespace App\Models;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\Model;

class Archivos_Pacientes extends Model
{
    // Declarar llave primaria
    protected $primaryKey = 'id_archivo_paciente';

    // Atributos que son asignables en masa
    protected $fillable = [
        'nombre',
        'tipo',
        'ruta_archivo',
        'fecha_subida',
        'fk_paciente',
    ];

    // Relación con Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'fk_paciente', 'id_paciente');
    }
     public function getUrlAttribute()
    {
        return Storage::url($this->ruta_archivo);
    }
}
