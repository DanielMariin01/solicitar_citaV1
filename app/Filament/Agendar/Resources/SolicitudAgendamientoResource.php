<?php

namespace App\Filament\Agendar\Resources;

use App\Filament\Agendar\Resources\SolicitudAgendamientoResource\Pages;
use App\Filament\Agendar\Resources\SolicitudAgendamientoResource\RelationManagers;
use App\Models\EPS;
use App\Models\SolicitudAgendamiento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Solicitud_agendamiento;
use App\Enums\SolicitudEstado;
use App\Enums\SolicitudEstadoAgendamiento;
use App\Models\Paciente;
use App\Models\Solicitud_Admision;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Fieldset;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Crypt;


class SolicitudAgendamientoResource extends Resource
{
    protected static ?string $model = Solicitud_Admision::class;
protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
      protected static ?string $navigationGroup = 'Agendamiento';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Solicitudes Agendamiento';
    protected static ?string $modelLabel = 'Agendar Cita' ;


     public static function getNavigationBadge(): ?string
    {
        // Esto reflejará el conteo de registros visibles bajo el scope getEloquentQuery()
        return static::getEloquentQuery()->count();
    }

    // Color del contador: NARANJA
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning'; // Cambiado a 'warning' para el color naranja
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                     Forms\Components\Select::make('estado')
                    ->label('Estado de Solicitud')
                    ->options(SolicitudEstadoAgendamiento::class)
                    ->required()
                    ->native(false),
                Forms\Components\TextInput::make('comentario')
                    ->label('Observación')
                    ->maxLength(1000)
                    ->required()
                //campo para fecha y hora
             

                
                    // Elimina ->dehydrateStateUsing(fn (string $state) => null)
                    // Elimina ->default(null)
                    // Elimina ->fillFromModel(false)

                    // Esta es la forma correcta de hacer que el campo esté vacío al cargar
                    ->afterStateHydrated(function (Forms\Components\TextInput $component, ?string $state) {
                        // Siempre establece el estado del componente a null (vacío)
                        // al hidratarse, ignorando el valor que venga del modelo.
                        $component->state(null);
                    })
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

                    Tables\Columns\TextColumn::make('paciente.eps.nombre')
                    ->label('EPS')
                    ->sortable()
                    ->searchable(),
                    
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

               
                 Tables\Columns\TextColumn::make('paciente.historia_clinica')
    ->label('Historia Clínica')
    ->formatStateUsing(function ($state) {
        if (!$state) {
            return '<span class="text-gray-500 italic">No disponible</span>';
        }

        $archivo = basename($state);
        $url = url('/solicitarcita/ver-archivo/historia_clinica/' . $archivo);

        return '<a href="' . $url . '" target="_blank" style="color: #1d4ed8; font-weight: 600;">Visualizar</a>';
    })
    ->html(),


              Tables\Columns\TextColumn::make('paciente.autorizacion')
    ->label('Autorización')
    ->formatStateUsing(function ($state) {
        if (!$state) {
            return '<span class="text-gray-500 italic">No disponible</span>';
        }

        $archivo = basename($state);
        $url = url('/solicitarcita/ver-archivo/autorizacion/' . $archivo);

        return '<a href="' . $url . '" target="_blank" style="color: #1d4ed8; font-weight: 600;">Visualizar</a>';
    })
    ->html(),



            Tables\Columns\TextColumn::make('paciente.orden_medica')
    ->label('Orden Médica')
    ->formatStateUsing(function ($state) {
        if (!$state) {
            return '<span class="text-gray-500 italic">No disponible</span>';
        }

        $archivo = basename($state);
        $url = url('/solicitarcita/ver-archivo/orden_medica/' . $archivo);

        return '<a href="' . $url . '" target="_blank" style="color: #1d4ed8; font-weight: 600;">Visualizar</a>';
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
                



SelectFilter::make('fk_eps')
    ->label('EPS')
    ->options(function () {
        return EPS::all()->pluck('nombre', 'id_eps')->toArray();
    })
    ->searchable()
    ->query(function (Builder $query, array $data): Builder {
        return $query->whereHas('paciente', function (Builder $q) use ($data) {
            if (isset($data['value'])) {
                $q->where('fk_eps', $data['value']);
            }
        });
    }),
    
    
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
                 Tables\Actions\Action::make('Responder')
            ->url(fn (Solicitud_Admision $record): string => AgendamientoResource::getUrl('create', [
                // Asegúrate de que el nombre del parámetro sea 'fk_paciente'
                // Y que el valor sea el ID correcto del paciente de la tabla 'pacientes'
                'fk_solicitud_admision' => $record->id_solicitud_admision, // O $record->id si el ID de tu tabla Paciente es 'id'
            ]))
            ->icon('heroicon-o-chat-bubble-left-right')
            ->color('primary'),

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

        $archivo = basename($state);
        $url = url('/solicitarcita/ver-archivo/historia_clinica/' . $archivo);

        return '<a href="' . $url . '" target="_blank" class="text-blue-600 hover:underline font-semibold">Visualizar</a>';
    })
    ->html(),

                         TextEntry::make('paciente.autorizacion')
    ->label('Autorización')
    ->formatStateUsing(function ($state) {
        if (!$state) {
            return '<span class="text-gray-500 italic">No disponible</span>';
        }

        $archivo = basename($state);
        $url = url('/solicitarcita/ver-archivo/autorizacion/' . $archivo);

        return '<a href="' . $url . '" target="_blank" class="text-blue-600 hover:underline font-semibold">Visualizar</a>';
    })
    ->html(),
TextEntry::make('paciente.orden_medica')
    ->label('Orden Médica')
    ->formatStateUsing(function ($state) {
        if (!$state) {
            return '<span class="text-gray-500 italic">No disponible</span>';
        }

        $archivo = basename($state);
        $url = url('/solicitarcita/ver-archivo/orden_medica/' . $archivo);

        return '<a href="' . $url . '" target="_blank" class="text-blue-600 hover:underline font-semibold">Visualizar</a>';
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
            ])
                ->defaultSort('created_at', 'asc');
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
            'index' => Pages\ListSolicitudAgendamientos::route('/'),
            'create' => Pages\CreateSolicitudAgendamiento::route('/create'),
            'edit' => Pages\EditSolicitudAgendamiento::route('/{record}/edit'),
        ];
    }

          public static function getEloquentQuery(): Builder
    {
        // Esto filtrará la tabla para que solo muestre registros donde 'estado' sea 'aprobada'.
        // Los usuarios no podrán cambiar este filtro desde la UI.
        return parent::getEloquentQuery()->where('estado', 'agendar');
    }
}
