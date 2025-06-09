<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;

class CheckIfUserIsActive
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->hasRole('super_admin')) {
                return $next($request);
            }

            // User tidak aktif
            if (! $user->is_active) {
                auth()->logout();
                session()->flash('user_inactive', true);

                // Ambil panel aktif
                $panel = Filament::getCurrentPanel();

                // Redirect ke URL login panel, atau fallback ke /
                $loginUrl = $panel?->getLoginUrl() ?? '/';

                return redirect($loginUrl);
            }
        }



        return $next($request);
    }
}