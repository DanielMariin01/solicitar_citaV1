<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SedeResource\Pages;
use App\Filament\Resources\SedeResource\RelationManagers;
use App\Models\Sede;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SedeResource extends Resource
{
    protected static ?string $model = Sede::class;

protected static ?string $navigationIcon = 'heroicon-o-building-office';
      protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Sedes';
    protected static ?string $modelLabel = 'Gestión de Sedes';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->label('Nombre de la Sede'),
                Forms\Components\TextInput::make('direccion')
                    ->required()
                    ->maxLength(255)
                    ->label('Dirección'),
                Forms\Components\Select::make('fk_ciudad')
                    ->label('Ciudad')
                    ->relationship('ciudad', 'nombre')
                    ->required()
                    //->searchable()
                    ->placeholder('Seleccione una ciudad')

                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
       
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre de la Sede')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->label('Dirección')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('ciudad.nombre')
                    ->label('Ciudad')       
                    ->sortable()
                    ->searchable(),
                 Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Fecha de Actualización')
                    ->dateTime()
                    ->sortable(),
                    
                //
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
            'index' => Pages\ListSedes::route('/'),
            'create' => Pages\CreateSede::route('/create'),
            'edit' => Pages\EditSede::route('/{record}/edit'),
        ];
    }
}
