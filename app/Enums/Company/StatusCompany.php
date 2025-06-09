<?php 
declare(strict_types=1);

namespace App\Enums\Company;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatusCompany: string implements HasLabel, HasColor, HasIcon
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
    case Dismantle = 'dismantle';
    case Pending = 'pending';

    public function getLabel(): ?string
    {

        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Suspended => 'Suspend',
            self::Dismantle => 'Dismantle',
            self::Pending => 'Pending',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Active => 'primary',
            self::Inactive => 'warning',
            self::Suspended => 'danger',
            self::Dismantle => 'gray',
            self::Pending => 'secondary',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Active => 'heroicon-m-face-smile',
            self::Inactive => 'heroicon-m-face-frown',
            self::Suspended => 'heroicon-m-no-symbol',
            self::Dismantle => 'heroicon-m-x-circle',
            self::Pending => 'heroicon-m-clock',
        };
    }
    
    public function getValue(): string
    {
        return $this->value;
    }
}