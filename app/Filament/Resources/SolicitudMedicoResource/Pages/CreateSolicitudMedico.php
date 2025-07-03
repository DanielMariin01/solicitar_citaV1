<?php

namespace App\Filament\Resources\SolicitudMedicoResource\Pages;

use App\Filament\Resources\SolicitudMedicoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateSolicitudMedico extends CreateRecord
{
    protected static string $resource = SolicitudMedicoResource::class;
// app/Filament/Resources/SolicitudMedicoResource/Pages/CreateSolicitudMedico.php
protected function afterCreate(): void
{
    // 1. Obtiene la Solicitud_Medico (que está en tabla solicitud_admisiones) recién creada
    $solicitudMedico = $this->record;

    // 2. Obtiene el Paciente relacionado con esa Solicitud_Medico
    $paciente = $solicitudMedico->paciente;

    // 3. Si se encontró un paciente...
    if ($paciente) {
        // 4. Toma el estado de la Solicitud_Medico (ej. 'agendar')
        $nuevoEstadoPaciente = $solicitudMedico->estado;

        // 5. Asigna ese estado al *Paciente*
        $paciente->estado = $nuevoEstadoPaciente;

        // 6. Guarda los cambios en la tabla *paciente* de la base de datos
        $paciente->save();
    } else {
        Log::warning('Paciente no encontrado...');
    }
}
}
