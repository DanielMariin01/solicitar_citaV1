<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ComponentArchivo extends Component
{
    /**
     * Declara la variable $inputId como una PROPIEDAD PÚBLICA.
     * Esto es crucial: Laravel la pasará automáticamente a la vista Blade.
   
     */


    /**
     * El constructor es el lugar donde el componente "recibe" las variables.
     * Le decimos que esperamos un $inputId al crear el componente.
  
     */
    public function __construct()
    {

    }

    /**
     * Este método le dice a Laravel qué vista Blade debe renderizar para este componente.
     */
    public function render(): View|Closure|string
    {
        // La vista 'components.component-archivo' recibirá automáticamente $inputId
        // porque es una propiedad pública de esta clase.
        return view('components.component-archivo');
    }
}