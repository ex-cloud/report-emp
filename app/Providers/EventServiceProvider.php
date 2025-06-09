<?php
declare(strict_types=1);

namespace App\Providers;

class EventServiceProvider extends \Illuminate\Foundation\Support\Providers\EventServiceProvider
{
    protected $listen = [
        \Illuminate\Auth\Events\Registered::class => [
            \App\Listeners\AssignDefaultUserRole::class,
        ],
    ];
}