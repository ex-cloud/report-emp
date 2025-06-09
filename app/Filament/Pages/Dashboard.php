<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as FilamentDashboard;

class Dashboard extends FilamentDashboard
{

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?int $navigationSort = -2;
    protected static string $view = 'filament.pages.dashboard';
    protected static ?string $navigationGroup = 'Dashboard';
    protected static ?string $slug = 'dashboard';
    protected static ?string $navigationLabel = 'Main Dashboard';
    public static function getRoutePath(): string
    {
        return '/dashboard';
    }
    public function getHeading(): string
    {
        return '';
    }
    public function getTitle(): string
    {
        return 'Dashboard';
    }
}
