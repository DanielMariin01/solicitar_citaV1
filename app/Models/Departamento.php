<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    // Declarar llave primaria
    protected $table = 'departamento'; // Nombre de la tabla
    protected $primaryKey = 'id_departamento';

    // Atributos que son asignables en masa
    protected $fillable = [
        'codigo',
        'nombre', // Código del departamento
    ];

    // Relación con ciudades
    public function ciudades()
    {
        return $this->hasMany(Ciudad::class, 'fk_departomento', 'id_departamento');
    }
}
