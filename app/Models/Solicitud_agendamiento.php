<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud_agendamiento extends Model
{
     protected $table = 'agendamiento'; // Nombre de la tabla
    
    protected $primaryKey = 'id_agendamiento';

    // Atributos que son asignables en masa
    protected $fillable = [
        'fecha',
        'hora',
        'comentario',
        'estado',
        'archivo',
    
    ];


   
}
