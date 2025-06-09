<x-filament-panels::page>
    @if (isset($hasInfolist) && $hasInfolist)
        {{ $infolist }}
    @elseif (isset($form))
    {{ $form }}
    @endif

        @if (isset($relationManagers) && count($relationManagers))
            <x-filament-panels::resources.relation-managers
                :active-manager="$activeRelationManager"
                :managers="$relationManagers"
                :owner-record="$record"
                :page-class="$pageClass"
            />
        @endif
</x-filament-panels::page>
