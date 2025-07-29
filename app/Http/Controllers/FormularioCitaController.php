<?php

namespace App\Http\Controllers;

use App\Mail\SolicitudConfirmacionMail;
use App\Models\Ciudad;
use App\Models\EPS;
use App\Models\Paciente;
use App\Models\Procedimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitudConfirmationMail;
class FormularioCitaController extends Controller
{
public function mostrarFormulario()
{
    $ciudades = Ciudad::all();
    $eps = EPS::all();

    

    return view('solicitarcitas.formulario', compact('ciudades', 'eps'));
    
}


public function guardar(Request $request)
{
    // Validar datos y archivos
    $request->validate([
        'nombre'           => 'required|string|max:255',
        'apellido'           => 'required|string|max:255',
        'tipo_identificacion' => 'required|string|max:255',
        'numero_identificacion' => 'required|string|max:255',
        'correo'            => 'required|email',
        'id_ciudad'        => 'required|exists:ciudad,id_ciudad',
        'procedimiento'      => 'nullable|string|max:1000',
        'id_eps'           => 'required|exists:tipo_eps,id_eps',
        'celular'          => 'required|string|max:255',
        'historia_clinica' => 'required|mimes:pdf,jpg,jpeg|max:2048',
        'autorizacion'     => 'nullable|mimes:pdf,jpg,jpeg|max:2048',
        'orden_medica'     => 'required|mimes:pdf,jpg,jpeg|max:2048',
        'estado'           => 'nullable|string|max:255',
        'observacion'      => 'nullable|string|max:1000',
     'acepto_terminos' => ['required', 'accepted'], // <-- Aquí termina el array de reglas
], [ // <-- Aquí comienza el array de mensajes personalizados
    // Mensajes personalizados para tus reglas

    'acepto_terminos.required' => 'Debes aceptar los términos.',
    'acepto_terminos.accepted' => 'Debes aceptar los términos y condiciones.',

    ]);

    // Guardar archivos
    $historiaPath = $request->file('historia_clinica')->store('archivos/historia_clinica', 'public');
 $autorizacionPath = null;
if ($request->hasFile('autorizacion')) {
    $autorizacionPath = $request->file('autorizacion')->store('archivos/autorizacion', 'public');
}
    $ordenPath = $request->file('orden_medica')->store('archivos/orden_medica', 'public');

    // Crear la solicitud
    $solicitud = new Paciente();
    $solicitud->nombre           = Crypt::encryptString($request->nombre);
    $solicitud->apellido         = Crypt::encryptString($request->apellido);
    $solicitud->tipo_identificacion = Crypt::encryptString($request->tipo_identificacion);
    $solicitud->numero_identificacion = Crypt::encryptString($request->numero_identificacion);
    $solicitud->correo           = Crypt::encryptString($request->correo);
    $solicitud->fk_ciudad        = $request->id_ciudad;
    $solicitud->fk_procedimiento    = $request->id_procedimiento;
    $solicitud->fk_eps           = $request->id_eps;
    $solicitud->celular          = Crypt::encryptString($request->celular);
    $solicitud->observacion      = Crypt::encryptString($request->observacion);
    $solicitud->estado           = 'pendiente'; // Estado por defecto

    // Guardar rutas de archivos
    $solicitud->historia_clinica = $historiaPath;
    $solicitud->autorizacion     = $autorizacionPath;
    $solicitud->orden_medica     = $ordenPath;

    $solicitud->save();

   // return redirect()->back()->with('success', '¡Tu solicitud fue enviada correctamente!');

   // $recipientEmail = Crypt::decryptString($solicitud->correo); // Desencriptar el correo
        //Log::info('Correo del paciente para envío de confirmación de solicitud desde controlador: ' . ($recipientEmail ?: 'NULL o Vacío'));
 if ($solicitud && $solicitud->id_paciente) { // Verificamos que $paciente no sea null y tenga ID
            $recipientEmail = Crypt::decryptString($solicitud->correo);
            $solicitud = Paciente::with('procedimiento')->find($solicitud->id_paciente);

            if ($recipientEmail && filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($recipientEmail)->send(new SolicitudConfirmacionMail($solicitud));
                    Log::info('Correo de confirmación de SOLICITUD enviado exitosamente a: ' . $recipientEmail . ' (desde FormularioCitaController)');
                } catch (\Exception $e) {
                    Log::error('ERROR al enviar correo de confirmación de SOLICITUD para paciente ID: ' . $solicitud->id_paciente . '. Error: ' . $e->getMessage());
                    Log::error('Stack Trace del error de correo (FormularioCitaController): ' . $e->getTraceAsString());
                }
            } else {
                Log::warning('No se pudo enviar correo de confirmación de SOLICITUD: Correo del paciente no válido o no encontrado para paciente ID: ' . $solicitud->id_paciente);
            }
        } else {
            Log::error('No se pudo enviar correo: Paciente no creado o sin ID después de intentar guardar.');
        }

return redirect()->back()->with('success', '¡Tu solicitud fue enviada correctamente!');

}
 public function buscar(Request $request)
    {
        // Obtiene el término de búsqueda del parámetro 'term' enviado por Select2
        $searchTerm = $request->input('term');

        // Realiza la consulta a la base de datos.
        // Busca en las columnas 'nombre' o 'codigo' usando un LIKE
        $procedimientos = Procedimiento::query()
            ->where('nombre', 'like', '%' . $searchTerm . '%')
           // ->orWhere('codigo', 'like', '%' . $searchTerm . '%') // Asume que tienes una columna 'codigo'
            ->select('id_procedimiento as id', 'nombre as text') // **¡CRUCIAL! Mapea tus columnas a 'id' y 'text'**
            ->limit(20) // Limita el número de resultados para un mejor rendimiento
            ->get();

        // Devuelve los resultados en el formato JSON que Select2 espera
        return response()->json([
            'procedimientos' => $procedimientos, // <-- La clave debe ser 'procedimientos'
            'total_count' => count($procedimientos) // Opcional
        ]);
    }
    

}
