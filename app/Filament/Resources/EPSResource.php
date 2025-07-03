<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EPSResource\Pages;
use App\Filament\Resources\EPSResource\RelationManagers;
use App\Models\EPS;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EPSResource extends Resource
{
    protected static ?string $model = EPS::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
      protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'EPS';
    protected static ?string $modelLabel = 'Gestión de EPS ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
         
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->label('Nombre de la EPS'),
            ])->columns(2);
         
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
             
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre de la EPS')
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
            'index' => Pages\ListEPS::route('/'),
            'create' => Pages\CreateEPS::route('/create'),
            'edit' => Pages\EditEPS::route('/{record}/edit'),
        ];
    }
}
