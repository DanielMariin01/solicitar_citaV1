<?php

namespace App\Enums;

enum SolicitudEstadoAdmision: string
{
 
    case PERTINENCIA_MEDICA = 'pertinencia_medica';
    case AGENDAR = 'agendar';

    case CANCELADA = 'cancelada';
   
 

    /**
     * Obtiene el nombre legible para el usuario de cada estado.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
           
            self::PERTINENCIA_MEDICA => 'pertinencia_medica',
            self::AGENDAR => 'agendar',
            self::CANCELADA => 'Cancelada',
          
    
        };
    }

    /**
     * Obtiene el color asociado a cada estado (útil para Filament).
     *
     * @return string|array|null
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
         
            self:: PERTINENCIA_MEDICA => 'info', // Azul claro
            self::AGENDAR => 'success', // Verde
            self::CANCELADA => 'danger', // Rojo
          
         
        };
    }
    
}