<?php

namespace App\Filament\Resources\AgendamientoResource\Pages;

use App\Filament\Agendar\Resources\SolicitudAgendamientoResource;
use App\Filament\Resources\AgendamientoResource;
use App\Mail\SolicitudActualizacionAgendaMail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Throwable;
use App\Mail\SolicitudEstadoActualizadoMail;
class CreateAgendamiento extends CreateRecord
{
    protected static string $resource = AgendamientoResource::class;



      protected function getRedirectUrl(): string
    {
        // Redirige al index (listado) de PacienteResource
        return SolicitudAgendamientoResource::getUrl('index');
    }

      protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(), // Mantiene el botón "Guardar Respuesta"
            // Elimina o comenta la línea de abajo para el botón "Crear y crear otro"
            // $this->getCreateAndCreateAnotherFormAction(), // Esto es lo que intentamos eliminar
            $this->getCancelFormAction(), // Mantiene el botón "Cancelar"
        ];
    }

         public function getTitle(): string
    {
        // Puedes devolver el título que quieras, sin la palabra "Crear".
        // Por ejemplo, el mismo que tu $modelLabel o algo diferente.
        return 'Agendar Cita';
        // O si quieres que tome el mismo nombre del $modelLabel del Resource:
        // return static::getResource()::getModelLabel();
    }

            protected function getCreateFormAction(): Actions\Action
    {
        return Actions\Action::make('create')
            ->label('Guardar') // <-- ¡Cambia el texto aquí!
            ->submit('create'); // Importante: debe mantener el 'submit' al mismo método que el original
    }



protected function afterCreate(): void
{
    $agendamiento = $this->record;
    $solicitudAdmision = $agendamiento->solicitudAdmision;

    if ($solicitudAdmision) {
        $nuevoEstado = $agendamiento->estado;
        $comentario = $agendamiento->comentario;

        // Actualizar el estado
        $solicitudAdmision->estado = $nuevoEstado;
        $solicitudAdmision->save();

        try {
            $paciente = $solicitudAdmision->paciente;

            if (!$paciente) {
                Log::error('Paciente no encontrado para la solicitud ID: ' . $solicitudAdmision->id_solicitud_admision);
                return;
            }

            try {
                $correo = Crypt::decryptString($paciente->correo);
            } catch (Throwable $e) {
                Log::error('Error desencriptando el correo del paciente: ' . $e->getMessage());
                $correo = $paciente->correo;
            }

            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                Log::warning("Correo inválido: $correo");
                return;
            }

            Mail::to($correo)->send(new SolicitudActualizacionAgendaMail($agendamiento, $nuevoEstado, $comentario));
            Log::info('Correo enviado correctamente al paciente desde afterCreate()');

        } catch (Throwable $e) {
            Log::error('Error al enviar el correo: ' . $e->getMessage());
        }

    } else {
        Log::warning('Solicitud de admisión no encontrada para el agendamiento ID: ' . $agendamiento->id);
    }
}

  

}
