<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Request;
use Filament\Facades\Filament;

class Breadcrumb extends Component
{
    public array $segments;
    public string $panelId;
    public bool $shouldRender;

    public function __construct()
    {
        $this->segments = Request::segments();
        $this->panelId = Filament::getCurrentPanel()->getId();

        // Hanya render jika panel adalah superadmin
        $this->shouldRender = $this->panelId === 'admin';
    }

    public function render()
    {
        return $this->shouldRender
            ? view('components.breadcrumb')
            : null;
    }
}
