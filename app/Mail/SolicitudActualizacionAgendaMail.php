<?php

namespace App\Mail;

use App\Models\Agendamiento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log; // Para los logs de depuración
use Throwable;
use Illuminate\Mail\Mailables\Address; 
use Illuminate\Support\Carbon;
use Illuminate\Mail\Mailables\Attachment;
class SolicitudActualizacionAgendaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Agendamiento $agendamiento;
    public $comentario;
    public $estadoNuevo;
 

    public $solicitudAdmision;
    /**
     * Create a new message instance.
     */
    public function __construct(Agendamiento $agendamiento, $estadoNuevo, $comentario)
    {
        
    $this->agendamiento = $agendamiento;
    $this->estadoNuevo = $estadoNuevo;
    $this->comentario = $comentario;
    $this->solicitudAdmision = $agendamiento->solicitudAdmision;


    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
         $nombreCompleto = 'Paciente Desconocido'; // Valor por defecto

    try {
        // Accede al paciente a través de la relación: $agendamiento → solicitudAdmision → paciente
        $nombre = Crypt::decryptString($this->agendamiento->solicitudAdmision->paciente->nombre);
        $apellido = Crypt::decryptString($this->agendamiento->solicitudAdmision->paciente->apellido);
        $nombreCompleto = $nombre . ' ' . $apellido;

        Log::debug("MAILABLE - Nombre completo desencriptado para asunto: " . $nombreCompleto);
    } catch (Throwable $e) {
        Log::error("MAILABLE - ERROR al desencriptar nombre/apellido: " . $e->getMessage() .
            " | Encriptado (nombre): " . ($this->agendamiento->solicitudAdmision->paciente->nombre ?? 'N/A') .
            " | Encriptado (apellido): " . ($this->agendamiento->solicitudAdmision->paciente->apellido ?? 'N/A'));
    }

    return new Envelope(
        subject: 'Tu solicitud de cita ha sido agendada- ' . $nombreCompleto,
        from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
    );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
       $paciente = $this->agendamiento->solicitudAdmision->paciente;
       $sede = $this->agendamiento->sede;

    $pacienteNombre = 'N/A';
    $pacienteApellido = 'N/A';
    $pacienteIdentificacion = 'N/A';
    $pacienteCorreo = 'N/A';
    $pacienteCelular = 'N/A';
        $procedimiento = Null;
    $sedeNombre = 'No disponible';
$sedeDireccion = 'No disponible';
$ciudadNombre = 'No disponible';

if ($sede) {
    $sedeNombre = $sede->nombre ?? 'No disponible';
    $sedeDireccion = $sede->direccion ?? 'No disponible';

    if ($sede->ciudad) {
        $ciudadNombre = $sede->ciudad->nombre ?? 'No disponible';
    }
}

    try {
        $pacienteNombre = Crypt::decryptString($paciente->nombre);
    } catch (Throwable $e) {
        Log::error("ERROR desencriptando nombre: " . $e->getMessage());
        $pacienteNombre = 'ERROR NOMBRE';
    }

    try {
        $pacienteApellido = Crypt::decryptString($paciente->apellido);
    } catch (Throwable $e) {
        Log::error("ERROR desencriptando apellido: " . $e->getMessage());
        $pacienteApellido = 'ERROR APELLIDO';
    }

    try {
        $pacienteIdentificacion = Crypt::decryptString($paciente->numero_identificacion);
    } catch (Throwable $e) {
        Log::error("ERROR desencriptando identificación: " . $e->getMessage());
        $pacienteIdentificacion = 'ERROR ID';
    }

    try {
        $pacienteCorreo = Crypt::decryptString($paciente->correo);
    } catch (Throwable $e) {
        Log::error("ERROR desencriptando correo: " . $e->getMessage());
        $pacienteCorreo = 'ERROR CORREO';
    }

    try {
        $pacienteCelular = Crypt::decryptString($paciente->celular);
    } catch (Throwable $e) {
        Log::error("ERROR desencriptando celular: " . $e->getMessage());
        $pacienteCelular = 'ERROR CELULAR';
    }


if ($paciente && !empty($paciente->procedimiento)) {
    try {
       $procedimiento = $paciente->procedimiento->nombre;
    } catch (\Exception $e) {
        Log::error("ERROR al desencriptar el procedimiento del paciente: " . $e->getMessage());
        $procedimiento = 'No disponible';
    }
}
    Log::debug("MAIL - Variables para la vista:");
    Log::debug("Nombre: $pacienteNombre $pacienteApellido | ID: $pacienteIdentificacion | Fecha cita: " . $this->agendamiento->fecha . " " . $this->agendamiento->hora);


$horaFormateada = Carbon::parse($this->agendamiento->hora)->format('g:i A');
    $fechaFormateada = Carbon::parse($this->agendamiento->fecha)->format('d/m/Y'); // opcional



    return new Content(
        view: 'emails.solicitud-actualizacion-agenda',
        with: [
            'pacienteNombre'        => $pacienteNombre,
            'pacienteApellido'      => $pacienteApellido,
            'pacienteIdentificacion'=> $pacienteIdentificacion,
            'pacienteCorreo'        => $pacienteCorreo,
            'pacienteCelular'       => $pacienteCelular,
            'fecha'             => $fechaFormateada,
            'hora'              => $horaFormateada,
            'comentario'            => $this->comentario,
            'estadoNuevo'           => $this->estadoNuevo,
            'idAgendamiento'        => $this->agendamiento->id_agendamiento,
            'idSolicitud' => $this->solicitudAdmision->id_solicitud_admision,
            'procedimiento' => $procedimiento,
                  'sedeNombre'            => $sedeNombre,
        'sedeDireccion'         => $sedeDireccion,
        'ciudadNombre'          => $ciudadNombre,
        ]
    );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
public function attachments(): array
{
    $agendamiento = $this->agendamiento;

    $adjuntos = [];

    // Adjuntar archivo de preparación
    if ($agendamiento->preparacion) {
        $adjuntos[] = Attachment::fromPath(
            storage_path('app/archivos/' . $agendamiento->preparacion)
        )->as('preparacion.pdf')->withMime('application/pdf');
    }

    // Adjuntar archivo de recordatorio
    if ($agendamiento->recordatorio) {
        $adjuntos[] = Attachment::fromPath(
            storage_path('app/archivos/' . $agendamiento->recordatorio)
        )->as('recordatorio.pdf')->withMime('application/pdf');
    }

    return $adjuntos;
}
}
