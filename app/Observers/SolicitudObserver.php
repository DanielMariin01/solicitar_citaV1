<?php

namespace App\Observers;

use App\Models\Solicitud_Admision; // Asegúrate de que este modelo exista y esté correctamente importado
use App\Models\Paciente; // Asegúrate de que este modelo exista y esté correctamente importado
use App\Mail\SolicitudEstadoActualizadoMail; // Asegúrate de que el Mailable esté correctamente importado
use Illuminate\Support\Facades\Mail; // Para la fachada Mail
use Illuminate\Support\Facades\Crypt; // Para desencriptar en el observer (ej. el correo del paciente)
use Illuminate\Support\Facades\Log; // Para los logs de depuración
use Throwable; // Para capturar cualquier tipo de excepción

class SolicitudObserver
{
    /**
     * Handle the Solicitud_Admision "created" event.
     * Este método se ejecuta cuando se crea una nueva Solicitud_Admision.
     */
    public function created(Solicitud_Admision $solicitudAdmision): void
    {
        Log::info('Solicitud_Admision creada (Observer): ' . $solicitudAdmision->id_solicitud_admision);

        try {
            // Cargar el paciente asociado a esta Solicitud_Admision
            // Asegúrate de que la relación `paciente()` en el modelo Solicitud_Admision es correcta y está definida.
            $paciente = $solicitudAdmision->paciente;

            if (!$paciente) {
                Log::error('ERROR: Paciente asociado no encontrado para Solicitud_Admision ID: ' . $solicitudAdmision->id_solicitud_admision);
                return; // Detiene el proceso si no hay paciente
            }

            $pacienteEmail = '';
            try {
                // Intenta desencriptar el correo del paciente.
                // Si falla, usa el valor original (que estaría encriptado) y loguea el error.
                $pacienteEmail = Crypt::decryptString($paciente->correo);
            } catch (Throwable $e) {
                Log::error('ERROR DESENCRIPTANDO CORREO en SolicitudObserver (created) para Paciente ID: ' . $paciente->id_paciente . '. Error: ' . $e->getMessage() . '. Usando correo RAW: ' . $paciente->correo);
                $pacienteEmail = $paciente->correo; // Opcional: si el correo es la razón del fallo, puedes intentar usar el correo encriptado para depuración, pero no es funcional. Mejor usar un valor por defecto o detener.
            }

            Log::debug('Intentando enviar correo de actualización (desde created) a: ' . $pacienteEmail . ' para Solicitud ID: ' . $solicitudAdmision->id_solicitud_admision);

            // Validación básica del correo electrónico antes de intentar enviar
            if (empty($pacienteEmail) || !filter_var($pacienteEmail, FILTER_VALIDATE_EMAIL)) {
                Log::warning('ADVERTENCIA: Correo electrónico del paciente no válido o vacío para Solicitud_Admision ID: ' . $solicitudAdmision->id_solicitud_admision . ' y Paciente ID: ' . $paciente->id_paciente . '. Correo: ' . ($pacienteEmail ?: 'Vacío/Nulo'));
                return; // Detiene el envío si el correo no es válido
            }

            // *** Punto crucial: Inicializa el Mailable con los tres parámetros esperados por su constructor ***
            Mail::to($pacienteEmail)->send(new SolicitudEstadoActualizadoMail(
                $solicitudAdmision,
                $solicitudAdmision->estado,       // Pasar el estado actual de la solicitud
                $solicitudAdmision->comentario    // Pasar el comentario de la solicitud
            ));

            Log::info('Correo de actualización de estado enviado exitosamente (desde created) para Solicitud_Admision ID: ' . $solicitudAdmision->id_solicitud_admision . ' a ' . $pacienteEmail . '. Nuevo estado: ' . $solicitudAdmision->estado);

        } catch (Throwable $e) {
            // Captura cualquier error general durante el proceso de envío del correo
            Log::error('ERROR general al enviar correo de actualización de estado (desde created) para Solicitud_Admision ID: ' . ($solicitudAdmision->id_solicitud_admision ?? 'N/A') . '. Error: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Solicitud_Admision "updated" event.
     * Actualmente, este método solo loguea una advertencia, ya que el flujo principal de correo
     * para solicitudes se maneja en el método 'created'.
     */
    public function updated(Solicitud_Admision $solicitudAdmision): void
    {
        Log::info('Solicitud_Admision actualizada (Observer): ' . $solicitudAdmision->id_solicitud_admision . '. (Este evento no debería ser el principal para el envío de correos en tu flujo actual)');
    }

    // Métodos para otros eventos de Eloquent, vacíos por simplicidad
    public function deleted(Solicitud_Admision $solicitudAdmision): void {}
    public function restored(Solicitud_Admision $solicitudAdmision): void {}
    public function forceDeleted(Solicitud_Admision $solicitudAdmision): void {}
}