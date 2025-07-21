<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

 
  <title>Formulario de Cita</title>
  <style>
    * {
      box-sizing: border-box;
    }
body {
  font-family: Arial, sans-serif;
  background: #f3f7fa;
  margin: 0;
  padding: 40px 20px;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  min-height: 100vh;
}
.form-container {
        background: #FFFFFF;
  padding: 30px;
  border-radius: 10px;
 box-shadow: 0 2px 16px rgba(0,0,0,0.08);
  max-width: 620px;
  width: 100%;
}

h2 {
  margin-bottom: 20px;
  font-size: 22px;
  border-bottom: 1px solid #ccc;
  padding-bottom: 10px;
  text-align: center;
}

form {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  width: 100%;
}

.form-group.half {
  width: 100%; /* Por defecto para móviles */
}

label {
  font-weight: bold;
  margin-bottom: 5px;
}

input[type="text"],
input[type="email"],
input[type="number"],
select {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 16px;
}

.error {
  color: red;
  font-size: 0.85em;
  margin-top: 5px;
}

.form-check {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 10px;
}

.form-check label {
  font-weight: normal;
}

.btn-submit {
  background-color: #8DA3E9; /* Color azul-violeta claro del botón */
  color: #fff; /* El color del texto sigue siendo blanco */
  border: none;
  padding: 12px 20px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 16px;
  margin-top: 10px;
  width: 100%;
  transition: background-color 0.3s ease; /* Añadido para una transición suave al pasar el ratón */
}

.btn-submit:hover {
  background-color: #748FE0; /* Un tono ligeramente más oscuro para el hover */
}

/* --- SECCIÓN CRÍTICA: ESTILOS PARA LA SUBIDA DE ARCHIVOS --- */

.upload-area {
    border: 2px dashed #ccc;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    background-color: #f8f9fa;
    transition: background-color 0.3s ease;
    /* --- ¡IMPORTANTE! PARA POSICIONAR EL INPUT INTERNO --- */
    position: relative; /* Permite que los elementos con position: absolute; dentro de él se posicionen correctamente */
    overflow: hidden; /* Oculta cualquier parte que se salga del contenedor */
}

.upload-area:hover {
    background-color: #e9ecef;
}

.custom-file-label {
    display: inline-block; /* Para que parezca un botón */
    margin-top: 10px;
    padding: 8px 16px;
    background-color: #8DA3E9; /* Color azul-violeta claro del botón "Ingresar" */
    color: white; /* El color del texto sigue siendo blanco */
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease; /* Añadido para una transición suave al pasar el ratón */
    /* --- ¡IMPORTANTE! PARA POSICIONAR EL INPUT INTERNO --- */
    position: relative; /* Permite que el input type="file" (que va DENTRO) se posicione absolutamente */
    overflow: hidden;    /* Oculta el input si se desborda (aunque lo haremos 100%) */
    z-index: 0;          /* Asegura que el label esté "debajo" del input invisible */
}

.custom-file-label:hover {
    background-color: #748FE0; /* Un tono ligeramente más oscuro para el hover, igual que el botón principal */
}


/* --- ¡ESTO ES LO MÁS CRÍTICO! OCULTA Y POSICIONA EL INPUT NATIVO --- */
/* Esta regla aplica solo al input type="file" que está ANIDADO dentro de un .custom-file-label */
.custom-file-label input[type="file"] {
    position: absolute; /* Saca el input del flujo normal del documento */
    top: 0;             /* Lo pega al borde superior de su padre (.custom-file-label) */
    left: 0;            /* Lo pega al borde izquierdo de su padre */
    width: 100%;        /* Lo expande para cubrir todo el ancho del .custom-file-label */
    height: 100%;       /* Lo expande para cubrir toda la altura del .custom-file-label */
    opacity: 0;         /* ¡LO HACE COMPLETAMENTE INVISIBLE! */
    cursor: pointer;    /* Mantiene el cursor de puntero, indicando que es clicable */
    z-index: 1;         /* ¡Lo coloca POR ENCIMA del contenido visible del .custom-file-label!
                           Así, el clic en tu botón visible golpea el input invisible. */
}

/* Estilo para el span que mostrará el nombre del archivo seleccionado */
.file-name-display {
    display: block;      /* Hace que el span ocupe su propia línea */
    margin-top: 10px;    /* Espacio encima del nombre del archivo */
    font-size: 0.9em;    /* Tamaño de fuente más pequeño */
    color: #555;         /* Color de texto gris */
    word-break: break-all; /* Rompe palabras largas para que quepan */
}
.phone-icon {
    color: #8DA3E9; /* Un azul primario para el icono */
    margin-right: 8px; /* Un poco más de espacio */
    font-size: 1.2em; /* Puedes ajustar el tamaño si quieres que sea un poco más grande que el texto */
    vertical-align: middle; /* Para alinear el icono con el texto */
}
/* --- RESPONSIVE DESIGN --- */
@media (min-width: 768px) {
  .form-group.half {
    width: calc(50% - 10px);
  }
}
/* --- FIN DE CORRECCIONES PARA EL ÁREA DE SUBIDA DE ARCHIVOS --- */

/* ... Tus estilos para media queries, etc. ... */
@media (min-width: 768px) {
  .form-group.half {
    width: calc(50% - 10px);
  }
}
  </style>
</head>
<body>
  <div class="form-container">
        <img src="{{ asset('imagenes/logo.png') }}" alt="Logo" style="display:block; margin:0 auto 20px auto; max-width:160px;">
    <h2>Solicitar Cita</h2>
    <form action="{{ route('solicitar-cita.guardar') }}" method="POST" enctype="multipart/form-data" id="formularioCita">   
    @csrf
   <div class="form-group half">
    <label for="nombre" class="form-label">Nombre</label>
    <input 
        type="text" 
        name="nombre" 
        id="nombre" 
        placeholder="Ingrese su nombre"
        class="form-control @error('nombre') is-invalid @else @if(old('nombre')) is-valid @endif @enderror" 
        value="{{ old('nombre') }}" 
        required
    >
    @error('nombre')
        <div class="invalid-feedback">{{ $message }}</div>
    @else
        @if(old('nombre'))
            <div class="valid-feedback"></div>
        @endif
    @enderror
</div>

         <div class="form-group half">
    <label for="apellido" class="form-label">Apellido</label>
    <input 
        type="text" 
        name="apellido" 
        id="apellido" 
        placeholder="Ingrese su apellido"
        class="form-control @error('apellido') is-invalid @else @if(old('apellido')) is-valid @endif @enderror" 
        value="{{ old('apellido') }}" 
        required
    >
    @error('apellido')
        <div class="invalid-feedback">{{ $message }}</div>
    @else
        @if(old('apellido'))
            <div class="valid-feedback"></div>
        @endif
    @enderror
</div>

      <div class="form-group half">
        <label for="documentType">Tipo de documento</label>
        <select id="tipo_identificacion" name="tipo_identificacion" class="form-control @error('tipo_identificacion') is-invalid @else @if(old('tipo_identificacion')) is-valid @endif @enderror" required>
          <option value="">Seleccione</option>
          <option value="cc">Cédula</option>
          <option value="ce">Cédula Extranjera</option>
          <option value="ti">Tarjeta de Identidad</option>
        </select>
      </div>

<div class="form-group half">
    <label for="numero_identificacion" class="form-label">Número de documento</label>
    <input
        type="text"
        name="numero_identificacion"
        id="numero_identificacion"
        placeholder="Ingrese su numero de documento"
        class="form-control @error('numero_identificacion') is-invalid @else @if(old('numero_identificacion')) is-valid @endif @enderror"
        value="{{ old('numero_identificacion') }}"
        required
    >
    @error('numero_identificacion')
        <div class="invalid-feedback">{{ $message }}</div>
    @else
        @if(old('numero_identificacion'))
            <div class="valid-feedback"></div>
        @endif
    @enderror
    {{-- OPCIONAL: Puedes añadir un mensaje de ayuda aquí --}}
    <small id="numero-identificacion-help" class="form-text text-muted d-none">No se permiten puntos o comas.</small>
</div>
<div class="form-group half">
    <label for="celular" class="form-label">Celular</label>
    <input
        type="text"
        name="celular"
        id="celular"
        placeholder="Ingrese su numero de celular"
        class="form-control @error('celular') is-invalid @else @if(old('celular')) is-valid @endif @enderror"
        value="{{ old('celular') }}"
        required
        maxlength="10" {{-- Esto es un límite HTML básico, el JS será más estricto --}}
    >
    @error('celular')
        <div class="invalid-feedback">{{ $message }}</div>
    @else
        @if(old('celular'))
            <div class="valid-feedback"></div>
        @endif
    @enderror
    {{-- OPCIONAL: Mensaje de ayuda para el celular --}}
    <small id="celular-help" class="form-text text-muted d-none">Debe contener 10 dígitos numéricos.</small>
</div>

 <div class="form-group half">
    <label for="correo" class="form-label">Correo</label>
    <input
        type="email" {{-- CAMBIADO DE "text" A "email" --}}
        name="correo"
        id="correo"
        placeholder="Ingrese su correo electrónico"
        class="form-control @error('correo') is-invalid @else @if(old('correo')) is-valid @endif @enderror"
        value="{{ old('correo') }}"
        required
    >
    @error('correo')
        <div class="invalid-feedback">{{ $message }}</div>
    @else
        @if(old('correo'))
            <div class="valid-feedback"></div>
        @endif
    @enderror
    {{-- Opcional: Mensaje de ayuda si quieres ser más explícito --}}
    <small id="correo-help" class="form-text text-muted d-none">Debe ser un correo electrónico válido (ej. usuario@ejemplo.com).</small>
</div>

<div class="form-group half">
    <label for="id_ciudad" class="form-label">Seleccione su ciudad</label>
    <select 
        name="id_ciudad" 
        id="id_ciudad" 
        class="form-select @error('id_ciudad') is-invalid @else @if(old('id_ciudad')) is-valid @endif @enderror" 
        required
    >
        <option value="">-- Seleccione --</option>
        @foreach($ciudades as $ciudad)
            <option value="{{ $ciudad->id_ciudad}}" {{ old('id_ciudad') == $ciudad->id_ciudad ? 'selected' : '' }}>
                {{ $ciudad->nombre }}
            </option>
        @endforeach
    </select>
    @error('id_ciudad')
        <div class="invalid-feedback">{{ $message }}</div>
    @else
       
    @enderror
</div>
 

<div class="form-group half">
    <label for="id_eps" class="form-label">Seleccione su EPS</label>
    <select 
        name="id_eps" 
        id="id_eps" 
        class="form-select @error('id_eps') is-invalid @else @if(old('id_eps')) is-valid @endif @enderror" 
        required
    >
        <option value="">-- Seleccione --</option>
        @foreach($eps as $eps)
            <option value="{{ $eps->id_eps}}" {{ old('id_eps') == $ciudad->id_ciudad ? 'selected' : '' }}>
                {{ $eps->nombre }}
            </option>
        @endforeach
    </select>
    @error('id_eps')
        <div class="invalid-feedback">{{ $message }}</div>
    @else
      
    @enderror
</div>

<div class="form-group">
    <label for="id_procedimiento" class="form-label">Procedimiento</label>
    {{-- Asegúrate de que el `id` y `name` sean correctos --}}
    <select 
        name="id_procedimiento" 
        id="id_procedimiento" 
        class="form-select @error('id_procedimiento') is-invalid @else @if(old('id_procedimiento')) is-valid @endif @enderror"
        required
    >
        <option value="">Busque un procedimiento</option>
        {{-- Esta parte es opcional pero útil si hay errores de validación. --}}
        @if(old('id_procedimiento'))
            @php
                // Debes obtener el procedimiento de la base de datos para mostrarlo aquí.
                $procedimientoOld = App\Models\Procedimiento::find(old('id_procedimiento'));
            @endphp
            @if($procedimientoOld)
                <option value="{{ $procedimientoOld->id_procedimiento }}" selected>
                    {{ $procedimientoOld->nombre }}
                </option>
            @endif
        @endif
    </select>
    @error('id_procedimiento')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


<div class="form-group">
    <label for="observacion" class="form-label">Observación</label>
    <textarea 
        name="observacion" 
        id="observacion" 
        rows="4"
        placeholder="Ingrese aquí cualquier comentario adicional. Por ejemplo: si su examen requiere sedación."
        class="form-control @error('observacion') is-invalid @else @if(old('observacion')) is-valid @endif @enderror"
    >{{ old('observacion') }}</textarea>
    @error('observacion')
        <div class="invalid-feedback">{{ $message }}</div>
    @else
        @if(old('observacion'))
            <div class="valid-feedback"></div>
        @endif
    @enderror
</div>
@php
    // Define los tipos de archivo aceptados para todos los campos si son los mismos
    $acceptedFileTypes = '.pdf,.jpg';
@endphp

{{-- Campo para HISTORIA CLÍNICA --}}
{{-- Aquí le pasamos 'historia_clinica' como $id y $name --}}
@include('components.component-archivo', [
    'id' => 'historia_clinica', // Se asignará como ID del input y en el 'for' del label
    'name' => 'historia_clinica', // Se asignará como nombre del input (Laravel lo usará para $request->historia_clinica)
    'label' => 'Historia Clínica',
    'helpText' => 'Suba aquí la historia clínica',
    'descriptionText' =>  "Es un documento con información sobre tu estado de salud y tratamientos previos.",
    'multiple' => false, // O true, según necesites
    'required' => true,
    'accept' => $acceptedFileTypes,
 
])

@include('components.component-archivo', [
    'id' => 'autorizacion', // Se asignará como ID del input y en el 'for' del label
    'name' => 'autorizacion', // Se asignará como nombre del input (Laravel lo usará para $request->historia_clinica)
    'label' => 'Autorización',
    'helpText' => 'Suba aquí la autorización',
    'multiple' => false, // O true, según necesites
    'required' => true,
    'accept' => $acceptedFileTypes,
    'descriptionText' =>  "Documento de tu aseguradora (EPS/Medicina Prepagada) que aprueba el servicio médico.",
])


@include('components.component-archivo', [
    'id' => 'orden_medica', // Se asignará como ID del input y en el 'for' del label
    'name' => 'orden_medica', // Se asignará como nombre del input (Laravel lo usará para $request->historia_clinica)
    'label' => 'Orden Médica',
    'helpText' => 'Suba aquí la orden médica',
    'multiple' => false, // O true, según necesites
    'required' => true,
    'accept' => $acceptedFileTypes,
     'descriptionText' =>  "Es el documento emitido por tu médico que solicita un procedimiento o examen.",
])
      <!--<div class="form-group">
        <!--<div class="form-check">
          <!--<input type="checkbox" id="terms" />
          <!--<label for="terms">Acepto términos y condiciones</label>
        <!--</div>
        <!--<div class="error">Debes aceptar antes de continuar.</div>
      <!--</div>--->
<div class="mb-4">
    <input type="checkbox" id="acepto_terminos" name="acepto_terminos" required class="mr-2">
    <label for="acepto_terminos" class="text-sm text-gray-700">
  Acepto política y tratamiento de datos personales
    </label>
    @error('acepto_terminos')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
      <button type="submit" class="btn-submit" id="btnSubmitForm">Solicitar Cita</button>
   <div class="contact-info">
    <h3>Líneas de Atención</h3>
    <p><i class="bi bi-telephone-fill phone-icon"></i> <strong>Radiólogos Asociados:</strong> CITAS: (606) 340 23 33</p>
    <p><i class="bi bi-telephone-fill phone-icon"></i> <strong>CEDICAF:</strong> CITAS: (606) 340 2111</p>
    <p><i class="bi bi-telephone-fill phone-icon"></i> <strong>DIAXME:</strong> CITAS: (608) 6836182</p>
</div>
   <div class="mt-8 text-center text-gray-500 text-[10px]"> {{-- Cambiado de 'text-xs' a 'text-[10px]' --}}
        &copy; Creado por Daniel Stiven Marín
    </div>
    </form>
  </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>
<script>
        const URL_BUSCAR_PROCEDIMIENTOS = "{{ route('api.procedimientos.buscar') }}";
    // Este es un ÚNICO bloque que se ejecuta cuando el DOM está listo.
    document.addEventListener('DOMContentLoaded', function() {
        
        // ================================================================
        // 1. Inicialización de Select2 para selectores sin búsqueda AJAX
        // ================================================================
        // Asegúrate de que los IDs coincidan con tu HTML
$('#id_ciudad').select2({
    width: '100%'
});
     $('#id_eps').select2({
    width: '100%'
});
$('#tipo_identificacion').select2({ // Si lo tienes
    width: '100%'
});



        // ================================================================
        // 2. Inicialización de Select2 con búsqueda remota (AJAX) para PROCEDIMIENTOS
        // ================================================================
        $('#id_procedimiento').select2({
            placeholder: 'Busque el procedimiento a realizar',
            minimumInputLength: 3,
             width: '100%', // Comienza a buscar después de 3 caracteres
            language: {
                inputTooShort: function(args) {
                    var remainingChars = args.minimum - args.input.length;
                    return 'Por favor, ingrese ' + remainingChars + ' o más caracteres para buscar.';
                },
                noResults: function() {
                    return 'No se encontraron resultados. Por favor, intente con otra búsqueda o comuníquese con nuestras líneas de atención.';
                },
                searching: function() {
                    return 'Buscando...';
                }
            },
            // ¡Esta es la configuración AJAX que llama a tu controlador!
            ajax: {
                url: URL_BUSCAR_PROCEDIMIENTOS,
                dataType: 'json',
                delay: 250, // Espera 250ms después de que el usuario deja de escribir
                data: function(params) {
                    // Parámetros que se envían al backend
                    return {
                        term: params.term, // El texto que el usuario ha escrito
                        page: params.page
                    };
                },
                processResults: function(data, params) {
                    // La respuesta del servidor (la que viste en la imagen) es 'data'.
                    // Select2 espera que el array de resultados esté en una clave llamada 'results'.
                    // Tu servidor devuelve la clave 'procedimientos'.
                    // Aquí hacemos el mapeo.
                    
                    // console.log(data); // Puedes descomentar esto para ver la respuesta en la consola del navegador
                    
                    return {
                        results: data.procedimientos, // Mapea tu array 'procedimientos' a 'results'
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            }
        });
        
        // ================================================================
        // 3. Lógica para mostrar el nombre de los archivos subidos
        // ================================================================
        document.querySelectorAll('.custom-file-label input[type="file"]').forEach(inputElement => {
            inputElement.addEventListener('change', function(event) {
                const inputId = this.id;
                const fileNameDisplay = document.getElementById(`${inputId}-file-name`);

                if (this.files.length > 0) {
                    if (this.multiple) {
                        fileNameDisplay.textContent = Array.from(this.files).map(file => file.name).join(', ');
                    } else {
                        fileNameDisplay.textContent = this.files[0].name;
                    }
                } else {
                    fileNameDisplay.textContent = 'Ningún archivo seleccionado';
                }
            });
        });

      const numeroIdentificacionInput = document.getElementById('numero_identificacion');
const numeroIdentificacionHelpText = document.getElementById('numero-identificacion-help'); // Si añadiste el small tag opcional

if (numeroIdentificacionInput) {
    numeroIdentificacionInput.addEventListener('input', function() {
        const originalValue = this.value;
        // Reemplaza CUALQUIER COSA QUE NO SEA UN DÍGITO (0-9) con una cadena vacía
        const cleanedValue = originalValue.replace(/[^0-9]/g, '');

        if (originalValue !== cleanedValue) {
            this.value = cleanedValue;
            // Mostrar mensaje de ayuda si había caracteres no permitidos
            if (numeroIdentificacionHelpText) {
                numeroIdentificacionHelpText.classList.remove('d-none');
                numeroIdentificacionHelpText.textContent = 'Solo se permiten dígitos numéricos';
                numeroIdentificacionHelpText.style.color = 'orange'; // Resaltar el aviso
            }
        } else {
            // Ocultar mensaje si el valor es válido o se ha limpiado y no hay errores
            if (numeroIdentificacionHelpText && cleanedValue.length > 0) { // Ocultar solo si hay algo escrito
                numeroIdentificacionHelpText.classList.add('d-none');
                numeroIdentificacionHelpText.textContent = ''; // Limpiar el texto
            }
        }
    });

    // Ocultar el mensaje si el campo se borra o se pierde el foco y está vacío
    numeroIdentificacionInput.addEventListener('blur', function() {
        if (numeroIdentificacionHelpText && this.value === '') {
            numeroIdentificacionHelpText.classList.add('d-none');
        }
    });
}


// NUEVO CÓDIGO - Lógica para el campo Celular: solo 10 dígitos numéricos
// ================================================================
const celularInput = document.getElementById('celular');
const celularHelpText = document.getElementById('celular-help');
const MAX_DIGITOS_CELULAR = 10;

if (celularInput) {
    celularInput.addEventListener('input', function() {
        const originalValue = this.value;
        // 1. Elimina cualquier caracter que no sea un dígito (0-9)
        let cleanedValue = originalValue.replace(/[^0-9]/g, '');

        // 2. Limita la longitud a MAX_DIGITOS_CELULAR
        if (cleanedValue.length > MAX_DIGITOS_CELULAR) {
            cleanedValue = cleanedValue.substring(0, MAX_DIGITOS_CELULAR);
        }

        // Actualiza el valor del input si hubo cambios
        if (this.value !== cleanedValue) {
            this.value = cleanedValue;
        }

        // 3. Muestra/oculta el mensaje de ayuda
        if (cleanedValue.length > 0 && cleanedValue.length !== MAX_DIGITOS_CELULAR) {
            if (celularHelpText) {
                celularHelpText.classList.remove('d-none');
                celularHelpText.textContent = `El número debe tener ${MAX_DIGITOS_CELULAR} dígitos. (Llevas ${cleanedValue.length})`;
                celularHelpText.style.color = 'orange'; // Resaltar el aviso
            }
        } else if (cleanedValue.length === MAX_DIGITOS_CELULAR) {
            if (celularHelpText) {
                celularHelpText.classList.add('d-none'); // Ocultar si la longitud es correcta
                celularHelpText.textContent = '';
            }
        } else { // Campo vacío
            if (celularHelpText) {
                celularHelpText.classList.add('d-none');
                celularHelpText.textContent = '';
            }
        }
    });

    // Ocultar el mensaje si el campo se borra o se pierde el foco y está vacío
    celularInput.addEventListener('blur', function() {
        if (celularHelpText && this.value === '') {
            celularHelpText.classList.add('d-none');
        }
    });
}

// ================================================================
// NUEVO CÓDIGO (Opcional) - Lógica para el campo Correo Electrónico
// ================================================================
const correoInput = document.getElementById('correo');
const correoHelpText = document.getElementById('correo-help');

if (correoInput && correoHelpText) {
    correoInput.addEventListener('input', function() {
        // La validación nativa de HTML5 con type="email" es bastante buena.
        // Aquí solo actualizamos el mensaje de ayuda si el campo está vacío
        // o si queremos reforzar el patrón.
        if (this.value.length === 0) {
            correoHelpText.classList.remove('d-none');
            correoHelpText.textContent = 'Ingrese su correo electrónico.';
            correoHelpText.style.color = '#6c757d'; // Color de texto normal
        } else if (!correoInput.checkValidity()) { // checkValidity() usa la validación nativa del navegador
            correoHelpText.classList.remove('d-none');
            correoHelpText.textContent = 'Por favor, ingrese un formato de correo electrónico válido.';
            correoHelpText.style.color = 'orange';
        } else {
            correoHelpText.classList.add('d-none');
            correoHelpText.textContent = '';
        }
    });

    correoInput.addEventListener('blur', function() {
        if (correoHelpText && this.value === '') {
            correoHelpText.classList.add('d-none');
        }
    });
}

const formCita = document.getElementById('formularioCita'); // Asegúrate de que tu formulario tiene id="formCita"
const btnSubmitForm = document.getElementById('btnSubmitForm'); // Asegúrate de que tu botón tiene id="btnSubmitForm"

if (formCita && btnSubmitForm) {
    formCita.addEventListener('submit', function() {
        // Deshabilitar el botón para evitar múltiples envíos
        btnSubmitForm.disabled = true;
        // Cambiar el texto y añadir un spinner de Bootstrap
        btnSubmitForm.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Enviando solicitud...
        `;
    });
}

    }); // Cierre del ÚNICO listener DOMContentLoaded
     @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Solicitud enviada!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 4000
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '{{ session('error') }}',
            showConfirmButton: true
        });
        @endif

    </script>
</body>
</html>
