<?php

namespace App\Filament\Resources\AgendamientoResource\Pages;

use App\Filament\Resources\AgendamientoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgendamientos extends ListRecords
{
    protected static string $resource = AgendamientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
