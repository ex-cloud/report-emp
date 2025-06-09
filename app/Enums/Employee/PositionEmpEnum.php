<?php
declare(strict_types=1);

namespace App\Enums\Employee;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PositionEmpEnum: string implements HasLabel, HasColor, HasIcon
{
    case Manager = 'manager';
    case Developer = 'developer';
    case Designer = 'designer';
    case Marketing = 'marketing';
    case Sales = 'sales';
    case HR = 'hr';
    case Finance = 'finance';
    case Support = 'support';
    case Noc = 'noc';
    case Tecknician = 'technician';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Manager => 'Manager',
            self::Developer => 'Developer',
            self::Designer => 'Designer',
            self::Marketing => 'Marketing',
            self::Sales => 'Sales',
            self::HR => 'HR',
            self::Finance => 'Finance',
            self::Support => 'Support',
            self::Noc => 'NOC',
            self::Tecknician => 'Technician',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Manager => 'success',
            self::Developer => 'info',
            self::Designer => 'warning',
            self::Marketing => 'primary',
            self::Sales => 'secondary',
            self::HR => 'danger',
            self::Finance => 'success',
            self::Support => 'info',
            self::Noc => 'warning',
            self::Tecknician => 'primary',
        };
    }

    public function getIcon(): string|null
    {
        return match ($this) {
            self::Manager => 'heroicon-o-user',
            self::Developer => 'heroicon-o-code-bracket',
            self::Designer => 'heroicon-o-paint-brush',
            self::Marketing => 'heroicon-o-chart-bar',
            self::Sales => 'heroicon-o-shopping-cart',
            self::HR => 'heroicon-o-users',
            self::Finance => 'heroicon-o-banknotes',
            self::Support => 'heroicon-o-wrench-screwdriver',
            self::Noc => 'heroicon-o-shield-check',
            self::Tecknician => 'heroicon-o-wrench',
        };
    }
}