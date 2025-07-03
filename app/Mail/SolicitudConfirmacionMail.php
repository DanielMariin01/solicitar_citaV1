<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
// NO USES Solicitud_Admision aquí, usa Paciente porque es lo que estás guardando
use App\Models\Paciente; // CAMBIO: Importa el modelo Paciente
use Illuminate\Support\Facades\Crypt; // Necesario para desencriptar en la Mailable

class SolicitudConfirmacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public Paciente $paciente; // CAMBIO: La propiedad ahora es de tipo Paciente

    public function __construct(Paciente $paciente) // CAMBIO: El constructor recibe un Paciente
    {
        $this->paciente = $paciente;
    }

    public function envelope(): Envelope
    {
        // Puedes desencriptar el nombre aquí para el asunto
        $nombreCompleto = Crypt::decryptString($this->paciente->nombre) . ' ' . Crypt::decryptString($this->paciente->apellido);
        return new Envelope(
            subject: 'Confirmación de Envío de tu Solicitud de Cita - ' . $nombreCompleto,
            from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.solicitud-confirmacion',
            with: [
                // CAMBIO: Pasa los datos del paciente (desencriptados) a la vista
                'pacienteNombre' => Crypt::decryptString($this->paciente->nombre),
                'pacienteApellido' => Crypt::decryptString($this->paciente->apellido),
                'pacienteTipo_identificacion' => Crypt::decryptString($this->paciente->tipo_identificacion),
                'pacienteIdentificacion' => Crypt::decryptString($this->paciente->numero_identificacion),
                'pacienteCelular' => Crypt::decryptString($this->paciente->celular),
                'pacienteCorreo' => Crypt::decryptString($this->paciente->correo),
                'pacienteProcedimiento' => $this->paciente->procedimiento->nombre ?? 'Sin asignar',
                'pacienteObservacion' => Crypt::decryptString($this->paciente->observacion),
                'estado' => $this->paciente->estado, // Estado no está encriptado, según tu tabla
                'idPaciente' => $this->paciente->id_paciente, // Usa el ID real del paciente
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}