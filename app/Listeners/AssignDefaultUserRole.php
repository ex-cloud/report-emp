<?php
declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;


class AssignDefaultUserRole
{
    public function handle(Registered $event): void
    {
        $user = $event->user;

        // Asign role default 'user'
        $user->assignRole('user');

        // Optional: pastikan status & is_active sesuai
        $user->status = 'pending';
        $user->is_active = false;
        $user->save();
    }
}
