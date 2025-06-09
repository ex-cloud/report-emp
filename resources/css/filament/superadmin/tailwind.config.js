import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Superadmin/**/*.php',
        './resources/views/filament/superadmin/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
