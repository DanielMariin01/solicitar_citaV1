<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{

protected $table = 'sede';
protected $primaryKey = 'id_sede';

    // Atributos que son asignables en masa
    protected $fillable = [
        'nombre',
        'direccion',
        'fk_ciudad',
    ];

    // Relación con Agendamiento
    public function agendamientos()
    {
        return $this->hasMany(Agendamiento::class, 'fk_sede', 'id_sede');
    }
    // Relación con Ciudad
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'fk_ciudad', 'id_ciudad');   
    }
 

    
}
