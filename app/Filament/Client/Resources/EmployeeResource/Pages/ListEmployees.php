<?php

namespace App\Filament\Client\Resources\EmployeeResource\Pages;

use App\Filament\Client\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;
    public function getHeading(): string
    {
        return 'List Teams';
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
