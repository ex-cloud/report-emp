<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentView;

class FilamentCustomProvider extends ServiceProvider
{
    public function boot(): void
    {

        Filament::serving(function () {
            if (session()->has('user_inactive')) {
                Notification::make()
                    ->title('Akun Dinonaktifkan')
                    ->body('Akun Anda sedang nonaktif. Hubungi administrator untuk mengaktifkannya kembali.')
                    ->danger()
                    ->persistent()
                    ->send();
            }
        });
        
        Filament::serving(function () {
            FilamentView::registerRenderHook(
                PanelsRenderHook::CONTENT_START,
                fn() => view('components.custom-topbar')
            );
        });

        Filament::serving(function () {
            FilamentView::registerRenderHook(
                'panels::global-search.after',
                function () {
                    return View::make('components.shortcut-menu', [
                        'shortcuts' => [
                            [
                                'label' => 'Roles',
                                'url' => '/superadmin/shield/roles',
                                'icon' => 'heroicon-o-shield-check'
                            ],
                            [
                                'label' => 'Users',
                                'url' => '/superadmin/users',
                                'icon' => 'heroicon-o-user-group'
                            ],
                            [
                                'label' => 'Contact',
                                'url' => '/superadmin/contacts',
                                'icon' => 'heroicon-o-phone'
                            ],
                            [
                                'label' => 'Company',
                                'url' => '/superadmin/companies',
                                'icon' => 'heroicon-o-building-office-2'
                            ]

                        ],
                    ]);
                }
            );
        });
    }
}
