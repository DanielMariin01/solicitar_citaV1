<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Especialidad; 

class Procedimiento extends Model
{
    // Declarar llave primaria
    protected $table = 'procedimiento'; // Nombre de la tabla
    protected $primaryKey = 'id_procedimiento';
    protected $fillable = [
        'codigo',
        'nombre',
      
    ];
    // Relación con Paciente
   
  public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'fk_especialidad');
    }
}
