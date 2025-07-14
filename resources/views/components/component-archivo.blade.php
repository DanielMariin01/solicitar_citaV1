

@props([
    'label',
    'id',
    'name',
    'helpText',
    'multiple' => false,
    'required' => false,
    'accept' => null,
    'descriptionText' => null, // <--- ¡Asegúrate de que esta línea esté aquí!
])

<div class="form-group full">
    <label class="form-label">{{ $label }}</label>
 @if ($descriptionText)
        <small class="form-text text-muted mb-2">
            {{ $descriptionText }}
        </small>
    @endif
    <div class="upload-area" id="{{ $id }}-area">
        <p><strong>{{ $helpText }}</strong></p>
     

        {{-- Este es el botón VISIBLE y ESTILIZADO --}}
        {{-- ¡El input DEBE IR DENTRO DE ESTE LABEL! --}}
        <label for="{{ $id }}" class="custom-file-label">
            Seleccionar archivo{{ $multiple ? 's' : '' }}
            <input
                type="file"
                name="{{ $name }}{{ $multiple ? '[]' : '' }}"
                id="{{ $id }}"
                {{ $multiple ? 'multiple' : '' }}
                {{ $required ? 'required' : '' }}
                {{ $accept ? 'accept=' . $accept : '' }}
                class="@error($name) is-invalid @else @if(old($name)) is-valid @endif @enderror"
            >
        </label>

        {{-- Este span es para mostrar el nombre del archivo con JavaScript --}}
        <span class="file-name-display mt-2" id="{{ $id }}-file-name">Ningún archivo seleccionado</span>

        @error($name)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @else
            @if(old($name))
                <div class="valid-feedback d-block">¡Archivo cargado correctamente!</div>
            @endif
        @enderror
    </div>
</div>
