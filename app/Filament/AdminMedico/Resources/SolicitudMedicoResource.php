<?php

namespace App\Filament\AdminMedico\Resources;

use App\Filament\AdminMedico\Resources\SolicitudMedicoResource\Pages;
use App\Filament\AdminMedico\Resources\SolicitudMedicoResource\RelationManagers;
use App\Models\Solicitud_Admision;
use App\Models\SolicitudMedico;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Solicitud_Medico;
use App\Enums\SolicitudEstado;
use App\Enums\SolicitudEstadoMedico;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\Crypt;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;

class SolicitudMedicoResource extends Resource
{
    protected static ?string $model = Solicitud_Admision::class;

   
      protected static ?string $navigationIcon = 'heroicon-o-user-circle';
      protected static ?string $navigationGroup = 'Solicitudes';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Solicitudes Médico';
    protected static ?string $modelLabel = 'Gestión de Solicitudes';

    
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
                    ->options(SolicitudEstadoMedico::class)
                    ->required()
                    ->native(false),
                    

                     Forms\Components\TextInput::make('comentario')
                    ->label('Observación')
                    ->maxLength(1000)
                    ->required()
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
                  Tables\Filters\SelectFilter::make('id_eps')
                    ->label('EPS')
                  ->relationship('eps', 'nombre'),
            ])
            ->actions([
             
                  Tables\Actions\EditAction::make()
                ->label('Gestionar Solicitud') // Cambia el texto del botón
                ->icon('heroicon-o-pencil-square'),
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
            'index' => Pages\ListSolicitudMedicos::route('/'),
            'create' => Pages\CreateSolicitudMedico::route('/create'),
            'edit' => Pages\EditSolicitudMedico::route('/{record}/edit'),
        ];
    }
              public static function getEloquentQuery(): Builder
    {
        // Esto filtrará la tabla para que solo muestre registros donde 'estado' sea 'aprobada'.
        // Los usuarios no podrán cambiar este filtro desde la UI.
        return parent::getEloquentQuery()->where('estado', 'pertinencia_medica');
    }
}
