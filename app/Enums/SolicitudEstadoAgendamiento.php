<?php

namespace App\Enums;

enum SolicitudEstadoAgendamiento: string
{

    case FINALIZADA = 'finalizada';
 
 

    /**
     * Obtiene el nombre legible para el usuario de cada estado.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
        
            self::FINALIZADA => 'finalizada',
           
         
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

            
              self::FINALIZADA => 'gray', // Gris
     
         
        };
    }
    
}