<?php
declare(strict_types=1);

namespace App\Enums\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TypeEmpEnum: string implements HasLabel, HasColor, HasIcon
{
    case EMP = 'emp';
    case FREE = 'free';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::EMP => 'EMP',
            self::FREE => 'FREE',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::EMP => 'success',
            self::FREE => 'danger',
        };
    }

    public function getIcon(): string|null
    {
        return match ($this) {
            self::EMP => 'heroicon-o-user',
            self::FREE => 'heroicon-o-user-plus',
        };
    }
}