<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsuarioResource\Pages;
use App\Filament\Resources\UsuarioResource\RelationManagers;
use App\Models\User;
use App\Models\Usuario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Facades\Crypt;

class UsuarioResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
      protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $modelLabel = 'Gestión de Usuarios';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                 Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nombre'),
          
                   Forms\Components\TextInput::make('email')
                    ->required()
                    ->label('Correo'),
                   Forms\Components\TextInput::make('password')
                    ->required()
                    ->label('Contraseña')
                  
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('password')
                    ->label('Contraseña')
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
            'index' => Pages\ListUsuarios::route('/'),
            'create' => Pages\CreateUsuario::route('/create'),
            'edit' => Pages\EditUsuario::route('/{record}/edit'),
        ];
    }
}
