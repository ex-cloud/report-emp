<?php
declare(strict_types=1);

namespace App\Enums\Company;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TypeCompany: string implements HasLabel, HasColor, HasIcon
{
    case Vendor = 'vendor';
    case Partner = 'partner';
    case Mitra = 'mitra';
    case Supplier = 'supplier';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Vendor => 'Vendor',
            self::Partner => 'Partner',
            self::Mitra => 'Mitra',
            self::Supplier => 'Supplier',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Vendor => 'primary',
            self::Partner => 'warning',
            self::Mitra => 'success',
            self::Supplier => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Vendor => 'heroicon-m-user-group',
            self::Partner => 'heroicon-m-user-circle',
            self::Mitra => 'heroicon-m-user-plus',
            self::Supplier => 'heroicon-m-user-minus',
        };
    }
    public function getValue(): string
    {
        return $this->value;
    }
}