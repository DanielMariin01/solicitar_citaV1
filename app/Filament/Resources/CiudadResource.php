<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CiudadResource\Pages;
use App\Filament\Resources\CiudadResource\RelationManagers;
use App\Models\Ciudad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CiudadResource extends Resource
{
    protected static ?string $model = Ciudad::class;

protected static ?string $navigationIcon = 'heroicon-o-map-pin';
      protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Ciudades';
    protected static ?string $modelLabel = 'Gestión de Ciudades';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->label('Nombre de la Ciudad'),
                Forms\Components\Select::make('fk_departamento')
                    ->relationship('departamento', 'nombre')
                    ->required()
                    ->label('Departamento')
                    ->searchable()
                    ->preload(),
            ])->columns(2);
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre de la Ciudad')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('departamento.nombre')
                    ->label('Departamento')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Fecha de Creación'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Fecha de Actualización'),
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
            'index' => Pages\ListCiudads::route('/'),
            'create' => Pages\CreateCiudad::route('/create'),
            'edit' => Pages\EditCiudad::route('/{record}/edit'),
        ];
    }
}
