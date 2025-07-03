<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcedimientoResource\Pages;
use App\Filament\Resources\ProcedimientoResource\RelationManagers;
use App\Models\Procedimiento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProcedimientoResource extends Resource
{
    protected static ?string $model = Procedimiento::class;

protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
      protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Procedimientos';
    protected static ?string $modelLabel = 'Gestión de Procedimientos ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                  Forms\Components\Select::make('fk_especialidad')
                ->label('Especialidad')
                ->relationship('especialidad', 'nombre')
                ->nullable(),
             
                 Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->label('Nombre del Procedimiento'),
            
            ])->columns(2);
              
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 
           
                Tables\Columns\TextColumn::make('especialidad.nombre')
                    ->label('Especialidad')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre del Procedimiento')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Fecha de Creación'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Fecha de Actualización'),
            ])
            ->defaultPaginationPageOption(20)
            ->paginationPageOptions([10, 50, 100, 200])
            ->filters([
                  Tables\Filters\SelectFilter::make('fk_especialidad')
                ->label('Especialidad')
                ->relationship('especialidad', 'nombre')
                ->placeholder('Todas las especialidades'),
              
                             
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
            'index' => Pages\ListProcedimientos::route('/'),
            'create' => Pages\CreateProcedimiento::route('/create'),
            'edit' => Pages\EditProcedimiento::route('/{record}/edit'),
        ];
    }
}
