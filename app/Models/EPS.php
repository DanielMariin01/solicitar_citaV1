<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EPS extends Model
{
    // Declarar llave primaria
    protected $table = 'tipo_eps'; // Nombre de la tabla
    protected $primaryKey = 'id_eps';

    // Atributos que son asignables en masa
    protected $fillable = [
        'codigo',
        'nombre', 
    ];


    public function pacientes()
    {
        return $this->hasMany(Paciente::class, 'fk_eps', 'id_eps');
    }
    
}
