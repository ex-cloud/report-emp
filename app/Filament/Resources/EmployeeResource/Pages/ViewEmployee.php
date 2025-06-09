<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;
    protected static string $view = 'filament.resources.employees.view-employee';

    protected function getViewData(): array
    {
        return [
            'form' => $this->form,
            'infolist' => $this->infolist,
            'hasInfolist' => $this->hasInfolist(),
            'relationManagers' => $this->getRelationManagers(),
            'activeRelationManager' => $this->activeRelationManager,
            'record' => $this->record,
            'pageClass' => static::class,
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
