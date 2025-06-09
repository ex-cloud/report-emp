@if (filament()->getCurrentPanel()->getId() === 'admin')
<nav class="flex items-center space-x-2 transition-all duration-300 ease-in-out">
    <x-breadcrumb />
</nav>
@endif