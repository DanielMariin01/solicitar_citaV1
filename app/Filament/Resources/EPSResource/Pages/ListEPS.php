<?php

namespace App\Filament\Resources\EPSResource\Pages;

use App\Filament\Resources\EPSResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEPS extends ListRecords
{
    protected static string $resource = EPSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
