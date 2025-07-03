<?php

namespace App\Filament\Resources\SolicitudAdmisionResource\Pages;

use App\Filament\Resources\SolicitudAdmisionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Mail\SolicitudEstadoActualizadoMail; // Asegúrate de importar tu Mailable
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Database\Eloquent\Model;
class EditSolicitudAdmision extends EditRecord
{
    protected static string $resource = SolicitudAdmisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
protected function mutateFormDataBeforeSave(array $data): array
{
    // *** PRUEBA DE DEPURACIÓN 1: ¿Se ejecuta este hook pre-guardado? ***
    dd('1. mutateFormDataBeforeSave se ejecutó.', $data);

    // ... (El resto de la lógica que tenías) ...
    return $data;
}

protected function handleRecordUpdated(Model $record): Model
{
    // *** PRUEBA DE DEPURACIÓN 2: ¿Se ejecuta este hook post-guardado? ***
    dd('2. handleRecordUpdated se ejecutó.', $record->toArray());

    // ... (El resto de la lógica que tenías) ...
    return $record;
}
}
