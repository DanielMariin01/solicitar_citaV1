<?php

namespace App\Filament\Admisiones\Resources\SolicitudAdmisionResource\Pages;

use App\Filament\Admisiones\Resources\SolicitudAdmisionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Filament\Resources\PacienteResource;

class CreateSolicitudAdmision extends CreateRecord
{
    protected static string $resource = SolicitudAdmisionResource::class;



        public function getTitle(): string
    {
        // Puedes devolver el título que quieras, sin la palabra "Crear".
        // Por ejemplo, el mismo que tu $modelLabel o algo diferente.
        return 'Responder Solicitud';
        // O si quieres que tome el mismo nombre del $modelLabel del Resource:
        // return static::getResource()::getModelLabel();
    }
        protected function getCreateFormAction(): Actions\Action
    {
        return Actions\Action::make('create')
            ->label('Guardar') // <-- ¡Cambia el texto aquí!
            ->submit('create'); // Importante: debe mantener el 'submit' al mismo método que el original
    }

    // **¡AÑADE ESTE MÉTODO PARA ELIMINAR EL BOTÓN "CREAR Y CREAR OTRO"!**
    // Si este método devuelve null, el botón no se mostrará.
  public function getCreateAndCreateAnotherFormAction(): Actions\Action | null
    {
        return null; // <--- Esta es la clave para eliminar el botón.
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
   protected function afterCreate(): void
    {
        // $this->record es la instancia de la SolicitudAdmisiones que acaba de ser creada.
        $solicitudAdmision = $this->record;

        $paciente = $solicitudAdmision->paciente; // Accede al paciente relacionado

        if ($paciente) {
            // **¡Aquí está la clave!** Toma el valor del estado de la solicitud recién creada.
            $nuevoEstadoPaciente = $solicitudAdmision->estado;

            // Ahora, actualiza el paciente con ese estado.
            // Asegúrate de que 'estado' es una columna válida en tu tabla 'pacientes'
            // y que está en el $fillable de tu modelo Paciente.
            $paciente->estado = $nuevoEstadoPaciente; // Asigna el estado de la solicitud
            $paciente->save(); // Guarda los cambios en el paciente
        } else {
            Log::warning('Paciente no encontrado para la solicitud de admisión ID: ' . $solicitudAdmision->id);
        }
    }

      protected function getRedirectUrl(): string
    {
        // Redirige al index (listado) de PacienteResource
        return PacienteResource::getUrl('index');
    }


}
