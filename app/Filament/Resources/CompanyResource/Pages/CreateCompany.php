<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id'] = \Illuminate\Support\Str::uuid()->toString();
        $data['created_by'] = auth()->user()->id ?? null;
        $data['updated_by'] = auth()->user()->id ?? null;
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function getTitle(): string
    {
        return __('Create Company');
    }
    

}
