<?php
declare(strict_types=1);

namespace App\Services;

use App\Enums\Employee\TypeEmpEnum;
use App\Enums\Employee\StatusEmpEnum;
use Filament\Forms\Components\Select;
use App\Enums\Employee\PositionEmpEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\ViewEntry;
use Filament\Forms\Components\Actions\Action;

final class FormsComponentsEmployees {
    public function __construct(
        private readonly string $getName = 'name',
        private readonly ?string $getEmployeeId = 'employee_id',
        private readonly ?string $getGender = 'gander',
        private readonly ?string $getBirthPlace = 'birth_place',
        private readonly string $getDateOfBirth = 'date_of_birth',
        private readonly string $getEmail = 'email',
        private readonly string $getAddress = 'address',
        private readonly string $getCity = 'city',
        private readonly string $getCountry = 'country',
        private readonly ?string $getPostalCode = 'postal_code',
        private readonly string $getDateHired = 'date_hired',
        private readonly string $getPosition = 'position',
        private readonly string $getType = 'type',
        private readonly ?string $getStatus = 'status',
        private readonly ?string $getCv = 'cv',
        private readonly ?string $getPhoto = 'photo',
        private readonly ?string $getHourlyRate = 'hourly_rate',
        private readonly ?string $getContract = 'contract',
        private readonly ?string $getWhatsapp = 'whatsapp',
        private readonly ?string $getFacebook = 'facebook',
        private readonly ?string $getInstagram = 'instagram',
        private readonly ?string $getYoutube = 'youtube',
        private readonly ?string $getWebsite = 'website',
        private readonly ?string $getCustomLink = 'custom_link',
        private readonly ?string $getDescription = 'description',
        private readonly ?string $getCompanyId = 'company_id',
        private readonly ?string $getUserId = 'user_id',
        private readonly ?string $getCreatedBy = 'created_by',
        private readonly ?string $getUpdatedBy = 'updated_by',
        private readonly ?string $getDeletedBy = 'deleted_by',
    ) {}

    /** @return TextInput */
    public static function getName(): TextInput
    {
        return TextInput::make('name')
            ->label(__('Full Name'))
            ->required()
            ->maxLength(255);
    }

    /** @return TextInput */
    public static function getEmployeeId(): TextInput
    {
        return TextInput::make('employee_id')
            ->label(__('Employee ID'))
            ->maxLength(50)
            ->dehydrated(false)
            ->default(fn ($record) => $record?->employee_id) // agar tampil saat edit
            ->disabled();
    }

    /** @return Select */
    public static function getGender(): Select
    {
        return Select::make('gender')
            ->label(__('Gender'))
            ->native(false)
            ->options([
                'laki-laki' => 'Laki-laki',
                'perempuan' => 'Perempuan',
            ])
            ->default('laki-laki')
            ->required();
    }

    /** @return TextInput */
    public static function getBirthPlace(): TextInput
    {
        return TextInput::make('birth_place')
            ->label(__('Place of Birth'))
            ->required()
            ->maxLength(50);
    }

    /** @return DatePicker */
    public static function getDateOfBirth(): DatePicker
    {
        return DatePicker::make('date_of_birth')
            ->label(__('Date of Birth'))
            ->displayFormat('d F Y')
            // ->maxDate(now()->subYears(10))
            ->placeholder('Select date of birth')
            ->native(false);
    }

    /** @return TextInput */
    public static function getEmail(): TextInput
    {
        return TextInput::make('email')
            ->label(__('Email'))
            ->email()
            ->required()
            ->maxLength(25);
    }

    /** @return Textarea */
    public static function getAddress(): Textarea
    {
        return Textarea::make('address')
            ->label(__('Full Address'))
            ->required()
            ->maxLength(255);
    }

    /** @return TextInput */
    public static function getCountry(): TextInput
    {
        return TextInput::make('country')
            ->label(__('Country'))
            ->required()
            ->maxLength(25);
    }

    /** @return TextInput */
    public static function getPostalCode(): TextInput
    {
        return TextInput::make('postal_code')
            ->label(__('Postal Code'))
            ->maxLength(6);
    }
    
    /** @return DatePicker */
    public static function getDateHired(): DatePicker
    {
        return DatePicker::make('date_hired')
            ->label(__('Date Hired'))
            ->displayFormat('d F Y')
            ->placeholder('Select date hired')
            ->native(false)
            ->required();
    }

    /** @return Select */
    public static function getPosition(): Select
    {
        return Select::make('position')
            ->label(__('Position'))
            ->options(PositionEmpEnum::class)
            ->default('technician');
    }

    /** @return Select */
    public static function getType(): Select
    {
        return Select::make('type')
            ->label(__('Type'))
            ->options(TypeEmpEnum::class)
            ->default('emp');
    }

    /** @return Select */
    public static function getStatus(): Select
    {
        return Select::make('status')
            ->label(__('Status'))
            ->options(StatusEmpEnum::class)
            ->default('active');
    }

    /** @return FileUpload */
    public static function getCv(): FileUpload
    {
        return FileUpload::make('cv')
            ->label(__('CV'))
            ->disk('public')
            ->directory('employees/cv')
            ->acceptedFileTypes(['application/pdf'])
            ->maxSize(1024) // 1 MB
            ->visibility('public')
            ->previewable(false)
            ->loadingIndicatorPosition('left')
            ->removeUploadedFileButtonPosition('right')
            ->uploadButtonPosition('left')
            ->uploadProgressIndicatorPosition('left');
    }

    /** @return FileUpload */
    public static function getPhoto(): FileUpload
    {
        return FileUpload::make('photo')
            // ->label(__('Photo'))
            ->hiddenLabel()
            ->imageEditor()
            ->downloadable()
            ->openable()
            ->optimize('webp')
            ->image()
            ->columnSpan('full')
            ->panelAspectRatio('3:2')
            ->disk('public')
            ->directory('employees/photos')
            ->acceptedFileTypes(['image/*'])
            ->maxSize(2048) // 2 MB
            ->visibility('public');
    }

    /** @return TextInput */
    public static function getHourlyRate(): TextInput
    {
        return TextInput::make('hourly_rate')
            ->label(__('Hourly Rate'))
            ->numeric()
            ->prefix('Rp.')
            ->maxLength(10);
    }

    /** @return FileUpload */
    public static function getContract(): FileUpload
    {
        return FileUpload::make('contract')
            ->label(__('Contract'))
            ->disk('public')
            ->directory('employees/contracts')
            ->acceptedFileTypes(['application/pdf'])
            ->maxSize(2048) // 2 MB
            ->visibility('public')
            ->loadingIndicatorPosition('left')
            ->removeUploadedFileButtonPosition('right')
            ->uploadButtonPosition('left')
            ->uploadProgressIndicatorPosition('left');
    }

    /** @return TextInput */
    public static function getWhatsapp(): TextInput
    {
        return TextInput::make('whatsapp')
            ->label(__('No. HP/WA'))
            // ->mask('99 9999 999 999')
            ->prefix('(+62)')
            ->tel()
            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
            ->required();
    }

    /** @return TextInput */
    public static function getFacebook(): TextInput
    {
        return TextInput::make('facebook')
            ->label(__('Facebook'))
            // ->url()
            ->hint('https://www.facebook.com/')
            ->prefixIcon('lucide-facebook')
            ->placeholder('your.username')
            ->suffixAction(
                fn(?string $state): Action => Action::make('visit')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(
                        fn() => filled($state) ? "https://facebook.com/{$state}" : null,
                        shouldOpenInNewTab: true,
                    )
            );
    }
    
    /** @return TextInput */
    public static function getInstagram(): TextInput
    {
        return TextInput::make('instagram')
            ->label(__('Instagram'))
            ->hint('https://www.instagram.com/')
            ->prefixIcon('lucide-instagram')
            ->placeholder('your.username')
            ->suffixAction(
                fn(?string $state): Action => Action::make('visit')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(
                        filled($state) ? "{$state}" : null,
                        shouldOpenInNewTab: true,
                    ),
            );
    }

    /** @return TextInput */
    public static function getYoutube(): TextInput
    {
        return TextInput::make('youtube')
            ->label(__('YouTube'))
            ->hint('https://www.youtube.com/')
            ->prefixIcon('lucide-youtube')
            ->placeholder('your.username')
            ->suffixAction(
                fn(?string $state): Action => Action::make('visit')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(
                        filled($state) ? "{$state}" : null,
                        shouldOpenInNewTab: true,
                    ),
            );
    }

    /** @return TextInput */
    public static function getWebsite(): TextInput
    {
        return TextInput::make('website')
            ->label(__('Website'))
            ->hint('https://www.domain.com/')
            ->url()
            ->placeholder('www.domain.com')
            ->prefixIcon('lucide-globe')
            ->suffixAction(
                fn(?string $state): Action => Action::make('visit')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(
                        filled($state) ? "{$state}" : null,
                        shouldOpenInNewTab: true,
                    ),
            );
    }

    /** @return TextInput */
    public static function getCustomLink(): TextInput
    {
        return TextInput::make('custom_link')
            ->label(__('Custom Link'))
            ->url()
            ->suffixAction(
                fn(?string $state): Action => Action::make('visit')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(
                        filled($state) ? "{$state}" : null,
                        shouldOpenInNewTab: true,
                    ),
            );
    }

    /** @return Textarea */
    public static function getDescription(): Textarea
    {
        return Textarea::make('description')
            ->label(__('Description'))
            ->maxLength(500)
            ->columnSpanFull();
    }

    /** @return Select */
    public static function getCompanyId(): Select
    {
        return Select::make('company_id')
            ->label(__('Company'))
            ->relationship('companies', 'name')
            ->searchable()
            ->required();
    }

    /** @return Select */
    public static function getUserId(): Select
    {
        return Select::make('user_id')
            ->label(__('User'))
            ->relationship('user', 'name')
            ->searchable()
            ->required();
    }


}