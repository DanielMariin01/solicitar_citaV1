<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamiento extends Model
{
    protected $table = 'agendamiento'; // Nombre de la tabla
    protected $primaryKey = 'id_agendamiento';

    // Atributos que son asignables en masa
    protected $fillable = [
        'fecha',
        'hora',
        'comentario',
        'fk_solicitud_admision',
        'estado',
        'fk_sede',
        

    ];

    // Relación con Solicitud_admision
      public function solicitudAdmision()
{
    return $this->belongsTo(Solicitud_Admision::class, 'fk_solicitud_admision', 'id_solicitud_admision');
}
    // Relación con Sede
    public function sede()
    {
        return $this->belongsTo(Sede::class, 'fk_sede', 'id_sede');
    }

    // Otros métodos y relaciones según sea necesario

}
