@php
    use Filament\Facades\Filament;
    use App\Filament\Pages\Dashboard;
@endphp

@if (filament()->getCurrentPanel()->getId() === 'admin')
    @php
        $segments = request()->segments();
        $panel = Filament::getCurrentPanel();
        $panelId = $panel?->getId();

        // Cek jika halaman dashboard
        $isDashboard = request()->routeIs("filament.{$panelId}.pages.dashboard");

        // URL dashboard (jika support tenant, sertakan tenant)
        $dashboardUrl = method_exists(Dashboard::class, 'getUrl')
            ? Dashboard::getUrl(tenant: Filament::hasTenancy() ? Filament::getTenant() : null)
            : route("filament.{$panelId}.pages.dashboard");
    @endphp

    <nav class="items-center hidden text-xs text-gray-600 md:flex dark:text-gray-300">
        <div class="flex items-center mt-6">
            <div class="flex items-center">
                @if (!$isDashboard)
                    <a href="{{ $dashboardUrl }}" class="hover:text-gray-900 dark:hover:text-white dark:text-gray-200">
                        <span class="flex px-2 text-gray-400 dark:text-gray-300"> 
                            <x-heroicon-o-sparkles class="w-4 h-4 text-[#65a30d]" />
                        </span>
                    </a>
                @endif

                @foreach ($segments as $index => $segment)
                    @if ($index === 0)
                        @continue {{-- Lewati 'superadmin' --}}
                    @endif
                    @if ($isDashboard && $index === 1)
                        @continue
                    @endif

                    @php
                        $isActive = $index === count($segments) - 1;
                        $url = url(implode('/', array_slice($segments, 0, $index + 1)));
                    @endphp

                    <span class="px-3 text-gray-400 dark:text-gray-300">/</span>

                    <a href="{{ $url }}" wire:navigate
                        class="
                        items-center 
                        text-gray-500
                        dark:text-gray-300
                        hover:text-gray-900 
                        dark:hover:text-white 
                        hover:bg-gray-600/10 
                        hover:py-1 
                        hover:px-4 
                        rounded-md
                        animate-fade-in
                        transition-all duration-200 ease-in-out
                        {{ $isActive ? 'relative isactive font-semibold' : '' }}
                       "
                       >
                        {{ str_replace('-', ' ', ucfirst($segment)) }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>
@endif
