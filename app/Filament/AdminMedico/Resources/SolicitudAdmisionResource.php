<?php

namespace App\Filament\AdminMedico\Resources;

use App\Filament\AdminMedico\Resources\SolicitudAdmisionResource\Pages;
use App\Filament\AdminMedico\Resources\SolicitudAdmisionResource\RelationManagers;
use App\Models\Solicitud_Admision;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\Crypt;
use App\Enums\SolicitudEstado;
use App\Models\Paciente;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;

class SolicitudAdmisionResource extends Resource
{
    protected static ?string $model = Solicitud_Admision::class;


    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
      protected static ?string $navigationGroup = 'Respuesta Solicitudes';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Respuesta Solicitudes';
     protected static ?string $modelLabel = 'Historial de solicitudes';
         public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning'; // Otro color para diferenciarlos
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Forms\Components\Select::make('estado')
                ->required()
                ->options([
                
                    'enviada_a_medico' => 'Enviada_a_Medico',
                    'agendar' => 'Agendar', // <-- Asegúrate que esta clave sea 'agendar' (string)
                    'cancelada' => 'Cancelada',
               
                ])
                ->native(false),
                    
                Forms\Components\TextInput::make('comentario')
                    ->label('Comentario')
                    ->maxLength(1000),

                
                 Select::make('fk_paciente') // Usa Select si quieres que sea un desplegable de pacientes
                    ->label('Paciente Asociado') // Etiqueta para el formulario
                    ->relationship('paciente', 'nombre') // 'paciente' es el método de relación en tu modelo SolicitudAdmisiones
                                                                  // 'nombre_completo' es la columna que quieres mostrar en el desplegable
                                                                  // (si no tienes 'nombre_completo', usa 'nombre' o el campo que identifique al paciente)
                    ->required() // Si es un campo obligatorio
                    ->default(fn () => request()->query('fk_paciente')) // <-- ¡AQUÍ SE PRE-RELLENA DESDE LA URL!
                    ->disabled() // Hace que el campo sea visible pero no editable por el usuario
                    ->dehydrated(true)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_solicitud_admision')
                    ->label('ID Solicitud')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('paciente.nombre') // Asegúrate de que 'nombre_completo' sea un campo válido
                    ->label('Nombre')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => Crypt::decryptString($state)),
                Tables\Columns\TextColumn::make('paciente.apellido') // Asegúrate de que 'nombre_completo' sea un campo válido
                    ->label('Apellido')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => Crypt::decryptString($state)),
                Tables\Columns\TextColumn::make('paciente.tipo_identificacion') // Asegúrate de que 'nombre_completo' sea un campo válido
                    ->label('Tipo de Identificación')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => Crypt::decryptString($state)),
                Tables\Columns\TextColumn::make('paciente.numero_identificacion') // Asegúrate de que 'nombre_completo' sea un campo válido
                    ->label('Número de Identificación')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => Crypt::decryptString($state)),
                    
                 Tables\Columns\TextColumn::make('paciente.procedimiento.nombre')
                    ->label('Procedimiento')
                    ->limit(1000)
                    ->extraHeaderAttributes([
                        'style' => 'min-width: 200px;', // Establece un ancho mínimo para el encabezado
                    ])
                    ->extraAttributes([
                        'class' => 'whitespace-normal', // Asegura que el texto se envuelva (aunque ->wrap() ya lo hace)
                        'style' => 'max-width: 300px; word-break: break-word;', // Ayuda con palabras muy largas
                    ]), 
               
                Tables\Columns\TextColumn::make('paciente.historia_clinica') // <-- ¡Aquí usas la notación de punto!
                    ->label('Historia Clínica')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) { // Usar empty para cubrir null, cadenas vacías o 0
                            return '<span class="text-gray-500 italic">No disponible</span>';
                        }

                        $url = asset('storage/' . $state);
                        $ext = strtolower(pathinfo($state, PATHINFO_EXTENSION));

                        // Usar 'currentColor' para que el SVG tome el color del texto del enlace
                        $icon = match ($ext) {
                            'pdf' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M19 2H8a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h8l5-5V4a2 2 0 0 0-2-2z"/></svg>',
                            'png', 'jpg', 'jpeg', 'gif' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M19 2H5a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm-1.5 15h-11L6 14.5 9 12l2.5 2.5L15 11z"/></svg>',
                            default => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>',
                        };

                        // Clases de Filament para un buen estilo de enlace
                        return '<a href="' . $url . '" target="_blank" class="flex items-center gap-2 text-primary-600 hover:text-primary-700 font-semibold">' . $icon . 'Visualizar                                                                                                                                                                                                                                                                                                                                                                               </a>';
                    })
                    ->html(),
                
                    Tables\Columns\TextColumn::make('paciente.autorizacion') // <-- ¡Aquí usas la notación de punto!
                    ->label('Autorizacion')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) { // Usar empty para cubrir null, cadenas vacías o 0
                            return '<span class="text-gray-500 italic">No disponible</span>';
                        }

                        $url = asset('storage/' . $state);
                        $ext = strtolower(pathinfo($state, PATHINFO_EXTENSION));

                        // Usar 'currentColor' para que el SVG tome el color del texto del enlace
                        $icon = match ($ext) {
                            'pdf' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M19 2H8a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h8l5-5V4a2 2 0 0 0-2-2z"/></svg>',
                            'png', 'jpg', 'jpeg', 'gif' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M19 2H5a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm-1.5 15h-11L6 14.5 9 12l2.5 2.5L15 11z"/></svg>',
                            default => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>',
                        };

                        // Clases de Filament para un buen estilo de enlace
                        return '<a href="' . $url . '" target="_blank" class="flex items-center gap-2 text-primary-600 hover:text-primary-700 font-semibold">' . $icon . 'Visualizar                                                                                                                                                                                                                                                                                                                                                                               </a>';
                    })
                    ->html(),


                    Tables\Columns\TextColumn::make('paciente.orden_medica') // <-- ¡Aquí usas la notación de punto!
                    ->label('Orden medica')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) { // Usar empty para cubrir null, cadenas vacías o 0
                            return '<span class="text-gray-500 italic">No disponible</span>';
                        }

                        $url = asset('storage/' . $state);
                        $ext = strtolower(pathinfo($state, PATHINFO_EXTENSION));

                        // Usar 'currentColor' para que el SVG tome el color del texto del enlace
                        $icon = match ($ext) {
                            'pdf' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M19 2H8a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h8l5-5V4a2 2 0 0 0-2-2z"/></svg>',
                            'png', 'jpg', 'jpeg', 'gif' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M19 2H5a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm-1.5 15h-11L6 14.5 9 12l2.5 2.5L15 11z"/></svg>',
                            default => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>',
                        };

                        // Clases de Filament para un buen estilo de enlace
                        return '<a href="' . $url . '" target="_blank" class="flex items-center gap-2 text-primary-600 hover:text-primary-700 font-semibold">' . $icon . 'Visualizar                                                                                                                                                                                                                                                                                                                                                                               </a>';
                    })
                    ->html(),

            
                BadgeColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state) => SolicitudEstado::from($state)->label())
                    ->color(fn (string $state) => SolicitudEstado::from($state)->getColor())
                    ->formatStateUsing(function ($state) {                                                                                           
                        try {
                            return SolicitudEstado::from($state)->label();
                        } catch (\ValueError $e) {
                            return $state;
                        }
                    }),





                
                //Tables\Columns\TextColumn::make('estado')
                    //->label('Estado de Solicitud')
                    //->enum(SolicitudEstado::asSelectArray()), // Muestra el estado como texto legible
                Tables\Columns\TextColumn::make('comentario')
                    ->label('Comentario')
                    ->limit(1000)
                    ->extraHeaderAttributes([
                        'style' => 'min-width: 200px;', // Establece un ancho mínimo para el encabezado
                    ])
                    ->extraAttributes([
                        'class' => 'whitespace-normal', // Asegura que el texto se envuelva (aunque ->wrap() ya lo hace)
                        'style' => 'max-width: 300px; word-break: break-word;', // Ayuda con palabras muy largas
                    ]), 
                //agregar columna created_at y updated_at
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i:s'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Fecha de Actualización')
                    ->dateTime('d/m/Y H:i:s'),
       
    
          
            ])
             ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10, 25, 50, 100])
            ->filters([
                 Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        \App\Enums\SolicitudEstado::PENDIENTE->value => 'Pendiente',
                        \App\Enums\SolicitudEstado::AGENDAR->value => 'agendar',
                        \App\Enums\SolicitudEstado::CANCELADA->value => 'Cancelada',
                        \App\Enums\SolicitudEstado::PERTINENCIA_MEDICA->value => 'Pertinencia medica',
                        \App\Enums\SolicitudEstado::FINALIZADA->value => 'Finalizada',
                    ])
                    ->searchable(),
                   Tables\Filters\SelectFilter::make('id_eps')
                    ->label('EPS')
                  ->relationship('eps', 'nombre'),
           Tables\Filters\SelectFilter::make('paciente.numero_identificacion')
                    ->label('Número de Identificación')
                    ->placeholder('Buscar o seleccionar número')
                    // Deshabilitamos la carga inicial de todas las opciones.
                    // Las opciones se cargarán dinámicamente con getSearchResultsUsing().
                    ->options(function (): array {
                        // Devolvemos un array vacío al inicio, las opciones se llenarán con la búsqueda.
                        // Si quieres que el valor actualmente seleccionado aparezca, podrías cargar solo ese.
                        return [];
                    })
                    ->searchable() // Habilita la barra de búsqueda dentro del select
                    ->getSearchResultsUsing(function (string $search): array {
                        if (empty($search)) {
                            return []; // No mostrar resultados si no hay búsqueda
                        }

                        // **¡Advertencia de Rendimiento: Todavía desencripta en PHP!**
                        // Esta búsqueda es sobre una colección de Pacientes, no a nivel de DB directamente en el cifrado.
                        // Limita los resultados para evitar sobrecargar.
                        $results = Paciente::all() // O Paciente::limit(200)->get() para limitar la carga inicial
                            ->filter(function (Paciente $paciente) use ($search) {
                                if (empty($paciente->numero_identificacion)) {
                                    return false;
                                }
                                return str_contains(
                                    strtolower(Crypt::decryptString($paciente->numero_identificacion)),
                                    strtolower($search)
                                );
                            })
                            ->take(50) // Limitar el número de resultados mostrados en el desplegable
                            ->mapWithKeys(function ($paciente) {
                                return [$paciente->numero_identificacion => Crypt::decryptString($paciente->numero_identificacion)];
                            })
                            ->toArray();

                        return $results;
                    })
                    ->getOptionLabelUsing(function (?string $value): string {
                        // Esto es importante para que el valor seleccionado en el filtro se muestre desencriptado.
                        // El $value aquí es el valor cifrado que se seleccionó del desplegable.
                        if ($value) {
                            return Crypt::decryptString($value);
                        }
                        return '';
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query; // Si no se selecciona nada, no se filtra
                        }

                        // El $data['value'] aquí es el valor CIFRADO del ID seleccionado.
                        $selectedEncryptedId = $data['value'];

                        // Filtra las solicitudes_medico donde el paciente tenga el ID cifrado seleccionado
                        return $query->whereHas('paciente', function (Builder $pacienteQuery) use ($selectedEncryptedId) {
                            $pacienteQuery->where('numero_identificacion', $selectedEncryptedId);
                        });
                    }),
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                     Tables\Actions\ViewAction::make()
            ->label('Ver Detalles')
            ->icon('heroicon-o-eye')
            ->modalHeading('Detalles de la Solicitud')
            ->infolist(fn ($record): array => [
                // dd($record->paciente), // Esto es para depuración, quítalo cuando ya funcione
                
          
                Fieldset::make('informacion_paciente') // Ya estaba corregido
                    ->label('Información de la Solicitud')
                    ->schema([

                        TextEntry::make('id_solicitud_admision')->label('Id Solicitud')
                            ->formatStateUsing(fn ($state) => $state),
                        TextEntry::make('paciente.nombre')->label('Nombre')->formatStateUsing(fn ($state) => Crypt::decryptString($state)),
                        
                        TextEntry::make('paciente.apellido')->label('Apellido')->formatStateUsing(fn ($state) => Crypt::decryptString($state)),
                        TextEntry::make('paciente.tipo_identificacion')->label('Tipo de Identificación')->formatStateUsing(fn ($state) => Crypt::decryptString($state)),
                        TextEntry::make('paciente.numero_identificacion')->label('Número de Identificación')->formatStateUsing(fn ($state) => Crypt::decryptString($state)),
                        TextEntry::make('paciente.celular')->label('Teléfono')->formatStateUsing(fn ($state) => Crypt::decryptString($state)),
                        TextEntry::make('paciente.procedimiento.nombre')->label('Procedimiento'),
                        TextEntry::make('estado')->label('Estado')->badge(),
                        TextEntry::make('comentario')->label('Comentario')
                            ->formatStateUsing(function ($state) {
                                if (empty($state)) {
                                    return '<span class="text-gray-500 italic">No disponible</span>';
                                }
                                return $state;
                            })
                            ->html(),
                   
                        TextEntry::make('paciente.correo')
                            ->label('Correo Electrónico')
                            ->formatStateUsing(fn ($state) => Crypt::decryptString($state)),
                        TextEntry::make('paciente.ciudad.nombre')->label('Ciudad'),
                        TextEntry::make('paciente.eps.nombre')->label('EPS'),

                            TextEntry::make('paciente.historia_clinica')     
                            ->label('Historia Clínica')
                            ->formatStateUsing(function ($state) {
                                if (!$state) {
                                    return '<span class="text-gray-500 italic">No disponible</span>';
                                }
                                $url = asset('storage/' . $state);
                                return '<a href="' . $url . '" target="_blank" class="text-blue-600 hover:underline">Visualizar</a>';
                            })
                            ->html(),
                           TextEntry::make('paciente.autorizacion')     
                            ->label('Autorización')
                            ->formatStateUsing(function ($state) {
                                if (!$state) {
                                    return '<span class="text-gray-500 italic">No disponible</span>';
                                }
                                $url = asset('storage/' . $state);
                                return '<a href="' . $url . '" target="_blank" class="text-blue-600 hover:underline">Visualizar</a>';
                            })
                            ->html(),
                        TextEntry::make('paciente.orden_medica')
                            ->label('Orden Médica')
                            ->formatStateUsing(function ($state) {
                                if (!$state) {
                                    return '<span class="text-gray-500 italic">No disponible</span>';
                                }
                                $url = asset('storage/' . $state);
                                return '<a href="' . $url . '" target="_blank" class="text-blue-600 hover:underline">Visualizar</a>';
                            })
                            ->html(),
                        TextEntry::make('created_at')->label('Fecha de Creación')
                            ->dateTime('d/m/Y H:i:s'),
                        TextEntry::make('updated_at')->label('Fecha de Actualización')
                            ->dateTime('d/m/Y H:i:s'),
                    


                    
                    ])->columns(2),
            ])
            ->slideOver()
            ->closeModalByClickingAway(true),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSolicitudAdmisions::route('/'),
            'create' => Pages\CreateSolicitudAdmision::route('/create'),
            //'edit' => Pages\EditSolicitudAdmision::route('/{record}/edit'),
        ];
    }
}
