<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Aunque no uses Queueable->queue() explícitamente, es buena práctica si la clase lo usa
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Solicitud_Admision; // Asegúrate de que este modelo exista y esté correctamente importado
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log; // Para los logs de depuración
use Throwable; // Para capturar cualquier tipo de excepción
use Illuminate\Mail\Mailables\Address; // Para usar la clase Address en el from

class SolicitudEstadoActualizadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public Solicitud_Admision $solicitudAdmision;
    public $estadoNuevo;
    public $comentario;

    /**
     * Create a new message instance.
     * El constructor recibe la solicitud completa, el nuevo estado y un comentario.
     */
    public function __construct(Solicitud_Admision $solicitudAdmision, $estadoNuevo, $comentario)
    {
        $this->solicitudAdmision = $solicitudAdmision;
        $this->estadoNuevo = $estadoNuevo;
        $this->comentario = $comentario;

        // Log para depuración inicial del mailable
        Log::debug("MAILABLE - Instancia creada para Solicitud ID: " . $solicitudAdmision->id_solicitud_admision .
                    " | Estado Nuevo: " . $estadoNuevo .
                    " | Comentario: " . $comentario);
    }

    /**
     * Get the message envelope. Define el remitente y el asunto del correo.
     */
    public function envelope(): Envelope
    {
        $nombreCompleto = 'Paciente Desconocido'; // Valor por defecto si falla la desencriptación

        try {
            // Se asume que $this->solicitudAdmision->paciente es una relación cargada
            $nombre = Crypt::decryptString($this->solicitudAdmision->paciente->nombre);
            $apellido = Crypt::decryptString($this->solicitudAdmision->paciente->apellido);
            $nombreCompleto = $nombre . ' ' . $apellido;
            Log::debug("MAILABLE - Nombre completo desencriptado para asunto: " . $nombreCompleto);
        } catch (Throwable $e) {
            // Captura cualquier error durante la desencriptación y lo loguea
            Log::error("MAILABLE - ERROR al desencriptar nombre/apellido para el asunto: " . $e->getMessage() .
                        " | Valor encriptado (nombre): " . (isset($this->solicitudAdmision->paciente->nombre) ? $this->solicitudAdmision->paciente->nombre : 'N/A') .
                        " | Valor encriptado (apellido): " . (isset($this->solicitudAdmision->paciente->apellido) ? $this->solicitudAdmision->paciente->apellido : 'N/A'));
        }

        return new Envelope(
            subject: 'Actualización del Estado de tu Solicitud- ' . $nombreCompleto,
            // Asegúrate de que MAIL_FROM_ADDRESS y MAIL_FROM_NAME estén configurados en tu .env
            from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
        );
    }

    /**
     * Get the message content definition. Define la vista y las variables para el correo.
     */
    public function content(): Content
    {
        $paciente = $this->solicitudAdmision->paciente; // Accede al objeto Paciente relacionado

        // Inicializa todas las variables con valores por defecto en caso de error o ausencia
        $pacienteNombre = 'N/A';
        $pacienteApellido = 'N/A';
        $pacienteIdentificacion = 'N/A';
        $pacienteCorreo = 'N/A';
        $pacienteCelular = 'N/A';
        $comentarioSolicitud = 'Sin comentario'; // Valor por defecto para el comentario

        // Intentos de desencriptación para cada campo del paciente, con manejo de errores
        try {
            $pacienteNombre = Crypt::decryptString($paciente->nombre);
        } catch (Throwable $e) {
            Log::error("MAILABLE - ERROR desencriptando nombre: " . $e->getMessage() . " | Valor: " . (isset($paciente->nombre) ? $paciente->nombre : 'N/A'));
            $pacienteNombre = 'ERROR NOMBRE';
        }
        try {
            $pacienteApellido = Crypt::decryptString($paciente->apellido);
        } catch (Throwable $e) {
            Log::error("MAILABLE - ERROR desencriptando apellido: " . $e->getMessage() . " | Valor: " . (isset($paciente->apellido) ? $paciente->apellido : 'N/A'));
            $pacienteApellido = 'ERROR APELLIDO';
        }
        try {
            $pacienteIdentificacion = Crypt::decryptString($paciente->numero_identificacion);
        } catch (Throwable $e) {
            Log::error("MAILABLE - ERROR desencriptando identificacion: " . $e->getMessage() . " | Valor: " . (isset($paciente->numero_identificacion) ? $paciente->numero_identificacion : 'N/A'));
            $pacienteIdentificacion = 'ERROR ID';
        }
        try {
            $pacienteCorreo = Crypt::decryptString($paciente->correo);
        } catch (Throwable $e) {
            Log::error("MAILABLE - ERROR desencriptando correo: " . $e->getMessage() . " | Valor: " . (isset($paciente->correo) ? $paciente->correo : 'N/A'));
            $pacienteCorreo = 'ERROR CORREO';
        }
        try {
            $pacienteCelular = Crypt::decryptString($paciente->celular);
        } catch (Throwable $e) {
            Log::error("MAILABLE - ERROR desencriptando celular: " . $e->getMessage() . " | Valor: " . (isset($paciente->celular) ? $paciente->celular : 'N/A'));
            $pacienteCelular = 'ERROR CELULAR';
        }

        // El comentario se pasa directamente, asumiendo que no está encriptado en la BD
        $comentarioSolicitud = $this->comentario;

        // Logs finales antes de pasar a la vista, para verificar los valores ya procesados
        Log::debug("MAILABLE - Variables FINALES para la vista:");
        Log::debug(" - pacienteNombre: " . $pacienteNombre);
        Log::debug(" - pacienteApellido: " . $pacienteApellido);
        Log::debug(" - pacienteIdentificacion: " . $pacienteIdentificacion);
        Log::debug(" - pacienteCorreo: " . $pacienteCorreo);
        Log::debug(" - pacienteCelular: " . $pacienteCelular);
        Log::debug(" - comentarioSolicitud: " . $comentarioSolicitud);
        Log::debug(" - idSolicitud: " . $this->solicitudAdmision->id_solicitud_admision);
        Log::debug(" - estado: " . $this->solicitudAdmision->estado);
        Log::debug(" - estadoNuevo: " . $this->estadoNuevo);


        return new Content(
            view: 'emails.solicitud-estado-actualizado', // Asegúrate de que esta vista exista en resources/views/emails/
            with: [
                'pacienteNombre'        => $pacienteNombre,
                'pacienteApellido'      => $pacienteApellido,
                'pacienteIdentificacion' => $pacienteIdentificacion,
                'pacienteCorreo'        => $pacienteCorreo,
                'pacienteCelular'       => $pacienteCelular,
                'idSolicitud'           => $this->solicitudAdmision->id_solicitud_admision,
                'estado'                => $this->estadoNuevo,
                'comentarioSolicitud'   => $comentarioSolicitud,
                'estadoNuevo'           => $this->estadoNuevo,
                'idSolicitud' => $this->solicitudAdmision->id_solicitud_admision,  
                // Si tienes más campos en la vista, asegúrate de añadirlos aquí con su lógica de desencriptación
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return []; // No hay adjuntos por defecto
    }
}