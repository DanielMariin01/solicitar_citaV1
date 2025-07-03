<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Departamento;

class Ciudad extends Model
{
    // Declarar llave primaria
    protected $table = 'ciudad'; // Nombre de la tabla
    protected $primaryKey = 'id_ciudad';

    // Atributos que son asignables en masa
    protected $fillable = [
        'nombre',
        'fk_departamento', // Llave foránea al departamento

        
    ];
    

    // Relación con pacientes
    public function pacientes()
    {
        return $this->hasMany(Paciente::class, 'fk_ciudad', 'id_ciudad');   
    }
    // Relación con departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'fk_departamento', 'id_departamento'); 
    }
   
}
