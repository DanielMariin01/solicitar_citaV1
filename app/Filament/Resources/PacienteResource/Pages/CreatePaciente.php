<?php

namespace App\Filament\Resources\PacienteResource\Pages;

use App\Filament\Resources\PacienteResource;
use App\Mail\SolicitudConfirmacionMail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail; // Importa la Facade Mail
use App\Mail\SolicitudConfirmationMail; // Importa tu NUEVA Mailable

class CreatePaciente extends CreateRecord
{
    protected static string $resource = PacienteResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // 1. Crear el registro de la solicitud
        $solicitud = static::getModel()::create($data);

        // 2. Obtener el correo del paciente asociado a la solicitud
        // Asumo que el modelo Solicitud_Admision tiene una relación con Paciente
        // y que el email del paciente está en paciente->correo_electronico
        if ($solicitud->paciente && $solicitud->paciente->correo) {
            Mail::to($solicitud->paciente->correo)->send(new SolicitudConfirmacionMail($solicitud));
        } 

        return $solicitud;
    }
}
