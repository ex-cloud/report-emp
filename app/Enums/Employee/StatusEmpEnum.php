<?php
declare(strict_types=1);

namespace App\Enums\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatusEmpEnum: string implements HasLabel, HasColor, HasIcon
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
    case BLOCKED = 'blocked';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::PENDING => 'Pending',
            self::BLOCKED => 'Blocked',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',
            self::PENDING => 'warning',
            self::BLOCKED => 'secondary',
        };
    }

    public function getIcon(): string|null
    {
        return match ($this) {
            self::ACTIVE => 'heroicon-o-check-circle',
            self::INACTIVE => 'heroicon-o-x-circle',
            self::PENDING => 'heroicon-o-exclamation-triangle',
            self::BLOCKED => 'heroicon-o-no-symbol',
        };
    }
    public function getIconColor(): string|null
    {
        return match ($this) {
            self::ACTIVE => 'text-success-500',
            self::INACTIVE => 'text-danger-500',
            self::PENDING => 'text-warning-500',
            self::BLOCKED => 'text-secondary-500',
        };
    }

}