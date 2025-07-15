<?php

use App\Http\Controllers\FormularioCitaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Response;

use App\Livewire\Formulario;
 // Asegúrate de importar tu componente Livewire
Route::get('/', function () {
    return redirect()->route('solicitarcitas.formulario');
});

// 1. Ruta para MOSTRAR el formulario de cita (método GET)
// Esta ruta es la única que debe usar la URL '/solicitar-cita' con el método GET.
Route::get('/formulario', [FormularioCitaController::class, 'mostrarFormulario'])->name('solicitarcitas.formulario');

// 2. Ruta para GUARDAR los datos del formulario (método POST)
// Esta ruta es para enviar los datos, no para mostrar el formulario.
Route::post('/solicitarcita', [FormularioCitaController::class, 'guardar'])->name('solicitar-cita.guardar');

// 3. Ruta de la API AJAX para buscar procedimientos (método GET)
// Esta ruta usa una URL diferente para evitar conflictos.
// La URL es '/api/procedimientos/buscar'.
Route::get('/api/procedimientos/buscar', [FormularioCitaController::class, 'buscar'])->name('api.procedimientos.buscar');


Route::get('/solicitarcita/ver-archivo/{tipo}/{archivo}', function ($tipo, $archivo) {
    if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $archivo)) {
        abort(403, 'Nombre de archivo no válido.');
    }

    $path = storage_path('app/public/archivos/' . $tipo . '/' . $archivo);

    if (!file_exists($path)) {
        abort(404, 'Archivo no encontrado.');
    }

    return Response::file($path);
});




