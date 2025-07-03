<?php

namespace App\Enums;

enum SolicitudEstado: string
{
    case PENDIENTE = 'pendiente';
    case PERTINENCIA_MEDICA = 'pertinencia_medica';
    case AGENDAR = 'agendar';

    case CANCELADA = 'cancelada';
    case FINALIZADA = 'finalizada';
 

    /**
     * Obtiene el nombre legible para el usuario de cada estado.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDIENTE => 'Pendiente',
            self::PERTINENCIA_MEDICA => 'pertinencia_medica',
            self::AGENDAR => 'agendar',
            self::CANCELADA => 'Cancelada',
            self::FINALIZADA => 'Finalizada',
    
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
            self::PENDIENTE => 'warning', // Amarillo
            self::PERTINENCIA_MEDICA => 'info', // Azul claro
            self::AGENDAR => 'success', // Verde
            self::CANCELADA => 'danger', // Rojo
            self::FINALIZADA => 'gray', // Gris
         
        };
    }
    
}