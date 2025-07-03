<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EspecialidadResource\Pages;
use App\Filament\Resources\EspecialidadResource\RelationManagers;
use App\Models\Especialidad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EspecialidadResource extends Resource
{
    protected static ?string $model = Especialidad::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
        protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Especialidades';
    protected static ?string $modelLabel = 'Especialidad ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                     Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->label('Nombre del Procedimiento'),
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
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
            'index' => Pages\ListEspecialidads::route('/'),
            'create' => Pages\CreateEspecialidad::route('/create'),
            'edit' => Pages\EditEspecialidad::route('/{record}/edit'),
        ];
    }
}
