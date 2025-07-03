<?php

namespace App\Filament\Resources\SolicitudAgendamientoResource\Pages;

use App\Filament\AdminMedico\Resources\SolicitudAdmisionResource;
use App\Filament\Agendar\Resources\SolicitudAgendamientoResource as ResourcesSolicitudAgendamientoResource;
use App\Filament\Resources\SolicitudAgendamientoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Mail\SolicitudEstadoActualizadoMail; // Asegúrate de importar tu Mailable
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Throwable;

class EditSolicitudAgendamiento extends EditRecord
{
    protected static string $resource = SolicitudAgendamientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
protected function mutateFormDataBeforeSave(array $data): array
{
    $record = $this->getRecord();

    $old_estado = $record->estado;
    $old_comentario = $record->comentario;

    $new_estado = $data['estado'] ?? null;
    $new_comentario = $data['comentario'] ?? null;

    Log::info('Filament Edit: Intentando guardar Solicitud_Admision ID: ' . $record->id_solicitud_admision);
    Log::debug('Valores Antiguos: Estado: ' . $old_estado . ', Comentario: ' . $old_comentario);
    Log::debug('Nuevos Valores: Estado: ' . $new_estado . ', Comentario: ' . $new_comentario);

    // *** LÍNEA AÑADIDA PARA DEPURACIÓN ***
    $condicionEsVerdadera = ($old_estado !== $new_estado || $old_comentario !== $new_comentario);
    Log::info('Resultado de la condición de cambio: ' . ($condicionEsVerdadera ? 'TRUE' : 'FALSE'));

    if ($condicionEsVerdadera) {
        Log::info('CAMBIO DETECTADO en Filament: Estado o comentario actualizados.');

        try {
            $paciente = $record->paciente;
            if (!$paciente) {
                Log::error('ERROR (Filament): Paciente asociado no encontrado para Solicitud ID: ' . $record->id_solicitud_admision);
                return $data;
            }

            $pacienteEmail = '';
            try {
                $pacienteEmail = Crypt::decryptString($paciente->correo);
            } catch (Throwable $e) {
                Log::error('ERROR DESENCRIPTANDO CORREO en Filament Edit. Error: ' . $e->getMessage());
                $pacienteEmail = $paciente->correo;
            }

            if (empty($pacienteEmail) || !filter_var($pacienteEmail, FILTER_VALIDATE_EMAIL)) {
                Log::warning('ADVERTENCIA (Filament): Correo no válido/vacío para Solicitud ID: ' . $record->id_solicitud_admision);
                return $data;
            }

            Mail::to($pacienteEmail)->queue(new SolicitudEstadoActualizadoMail($record, $new_estado, $new_comentario));
            Log::info('Correo de actualización de estado ENCOLADO exitosamente (desde Filament Edit).');

        } catch (Throwable $e) {
            Log::error('ERROR general al encolar correo (desde Filament Edit). Error: ' . $e->getMessage());
        }
    } else {
        Log::info('No hay cambios en el estado o comentario. No se envía correo desde Filament.');
    }

    return $data;
}
      protected function getRedirectUrl(): string
    {
        // Redirige al index (listado) de PacienteResource
        return ResourcesSolicitudAgendamientoResource::getUrl('index');
    }
}
