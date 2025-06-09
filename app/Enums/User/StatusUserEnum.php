<?php
declare(strict_types=1);

namespace App\Enums\User;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatusUserEnum: string implements HasLabel, HasColor, HasIcon
{
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case BLOCKED = 'blocked';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Aktif',
            self::PENDING => 'Menunggu',
            self::BLOCKED => 'Diblokir',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::PENDING => 'warning',
            self::BLOCKED => 'danger',
        };
    }

    public function getIcon(): string|null
    {
        return match ($this) {
            self::ACTIVE => 'heroicon-o-check-circle',
            self::PENDING => 'heroicon-o-clock',
            self::BLOCKED => 'heroicon-o-x-circle',
        };
    }

    public function getIconColor(): string|null
    {
        return match ($this) {
            self::ACTIVE => 'text-success-500',
            self::PENDING => 'text-warning-500',
            self::BLOCKED => 'text-secondary-500',
        };
    }
}