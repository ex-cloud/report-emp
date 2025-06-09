<?php

namespace App\Filament\Client\Resources\EmployeeResource\Pages;

use App\Filament\Client\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}
