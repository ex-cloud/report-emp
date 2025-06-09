<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Models\Company;
use Filament\PanelProvider;
use App\Enums\User\StatusUserEnum;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckIfUserIsActive;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class ClientPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->tenant(Company::class, 'slug', ownershipRelationship: 'companies')
            ->id('client')
            ->path('client')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Client/Resources'), for: 'App\\Filament\\Client\\Resources')
            ->discoverPages(in: app_path('Filament/Client/Pages'), for: 'App\\Filament\\Client\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->topNavigation()
            ->discoverWidgets(in: app_path('Filament/Client/Widgets'), for: 'App\\Filament\\Client\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                CheckIfUserIsActive::class,
        ]);
    }

    public function auth(): \Closure
    {
        return function (Authenticatable $user): bool {
            // Cek berdasarkan status user
            // Misalnya kamu punya kolom: is_active (bool), status (enum/string: 'active', 'pending', 'banned', dll)

            // Kasus: jika user dibanned
            if (($user instanceof \App\Models\User) && $user->status === StatusUserEnum::BLOCKED->value) {
                session()->flash('filament.auth.error', 'Akun Anda diblokir. Hubungi administrator.');
                return false;
            }

            // Kasus: user masih pending aktivasi
            if ($user->status === StatusUserEnum::PENDING->value) {
                session()->flash('filament.auth.error', 'Akun Anda belum diaktifkan.');
                return false;
            }

            // Kasus: nonaktif (via boolean)
            if (property_exists($user, 'is_active') && !$user->is_active) {
                session()->flash('filament.auth.error', 'Akun Anda dinonaktifkan.');
                return false;
            }

            return true; // hanya user aktif & valid yang bisa login
        };
    }
}
