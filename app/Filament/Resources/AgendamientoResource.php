<?php

namespace App\Filament\Resources;

use App\Enums\SolicitudEstadoAgendamiento;
use App\Filament\Resources\AgendamientoResource\Pages;
use App\Filament\Resources\AgendamientoResource\RelationManagers;
use App\Models\Agendamiento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgendamientoResource extends Resource
{
    protected static ?string $model = Agendamiento::class;
     protected static bool $shouldRegisterNavigation = false;
protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
      protected static ?string $navigationGroup = 'Agendamiento';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Agendar Cita';
    protected static ?string $modelLabel = 'Agendar Cita ';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\DatePicker::make('fecha')
                    ->label('Fecha')
                    ->required(),
                // Usa TimePicker para la entrada de hora
                Forms\Components\TimePicker::make('hora')
                    ->label('Hora')
                    ->required(),
                Forms\Components\Textarea::make('comentario')
                    ->label('Comentario'),
               
           
               Forms\Components\Select::make('estado')
                    ->label('Estado de Solicitud')
                    ->options(SolicitudEstadoAgendamiento::class)
                    ->required()
                    ->native(false),

                Forms\Components\Select::make('fk_sede')
                    ->label('Sede')
                    ->relationship('sede', 'nombre')
                    ->required()
                    
                    ->placeholder('Seleccione una sede'),



                Select::make('fk_solicitud_admision') // Usa Select si quieres que sea un desplegable de pacientes
                    ->label('ID Solicitud') // Etiqueta para el formulario
                    ->relationship('solicitudAdmision', 'id_solicitud_admision') // 'paciente' es el método de relación en tu modelo SolicitudAdmisiones
                                                                  // 'nombre_completo' es la columna que quieres mostrar en el desplegable
                                                                  // (si no tienes 'nombre_completo', usa 'nombre' o el campo que identifique al paciente)
                    ->required() // Si es un campo obligatorio
                    ->default(fn () => request()->query('fk_solicitud_admision')) // <-- ¡AQUÍ SE PRE-RELLENA DESDE LA URL!
                    ->disabled() // Hace que el campo sea visible pero no editable por el usuario
                    ->dehydrated(true) 
                    ]);

                 
                // Asegura que el valor se incluya cuando se guarden los datos
                // Puedes agregar un campo para el archivo si es necesario
                 // Si el archivo es obligat
                
           
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_agendamiento')
                    ->label('ID Agendamiento')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date(),
                Tables\Columns\TextColumn::make('hora')
                    ->label('Hora'),
                Tables\Columns\TextColumn::make('comentario')
                    ->label('Comentario'),
         

                Tables\Columns\TextColumn::make('solicitudAdmision.id_solicitud_admision')
                    ->label('ID Solicitud'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAgendamientos::route('/'),
            'create' => Pages\CreateAgendamiento::route('/create'),
            'edit' => Pages\EditAgendamiento::route('/{record}/edit'),
        ];
    }

}
