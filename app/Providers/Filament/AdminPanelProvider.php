<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use App\Enums\User\StatusUserEnum;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckIfUserIsActive;
use Filament\FontProviders\GoogleFontProvider;
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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchDebounce(150)
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo(asset('assets/213213.png'))
            ->brandLogoHeight('2rem')
            ->brandName(config('app.name'))
            ->favicon(asset('/assets/213213.png'))
            ->colors([
                'danger' => '#e11d48',
                'gray' => Color::Gray,
                'info' => Color::Sky,
                'primary' => '#65a30d',
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->collapsedSidebarWidth('8rem')
            ->sidebarWidth('228px')
            ->maxContentWidth('full')
            ->breadcrumbs(false)
            ->sidebarFullyCollapsibleOnDesktop()
            ->font('Nunito', provider: GoogleFontProvider::class)
            ->spa()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('List Users')
                    ->icon('heroicon-o-user-group')
                    ->url(fn() => UserResource::getUrl(tenant: filament()->getTenant()))
                    ->visible(fn() => auth()->user()?->hasRole('super_admin'))
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
                CheckIfUserIsActive::class,
        ])
        
        ->navigationGroups([
            NavigationGroup::make()
                ->icon('heroicon-o-building-office-2')
                ->label('CRM')
                ->collapsed()
                ->items([
                    NavigationItem::make()
                        ->label('Contacts')
                        ->url('/admin/contacts'),
                    NavigationItem::make()
                        ->label('Companies')
                        ->url('/admin/companies'),
                ]),
            NavigationGroup::make()
                ->icon('heroicon-o-users')
                ->label('HRM')
                ->collapsed()
                ->items([
                    NavigationItem::make()
                        ->label('Employees')
                        ->url('/admin/employees'),
                ]),
        ])
        ;


        return $panel;
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
