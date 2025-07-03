<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

abstract class Controller
{
    public function store(Request $request)
{
    $messages = [
        'correo.email' => 'El formato del correo electrónico no es válido.', // Mensaje personalizado
        'correo.required' => 'El correo es obligatorio.',
        // ... otros mensajes personalizados
    ];

    $request->validate([
        'correo' => 'required|email|unique:users',
        // ... otras reglas
    ], $messages);

    // ... Lógica para guardar los datos
}
}
