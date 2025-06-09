<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Company\TypeCompany;
use App\Enums\Company\StatusCompany;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Tables\Columns\NumberColumn;
use Filament\Forms\Components\MarkdownEditor;

final class FormsComponentsCompanies
{
    public function __construct(
        private readonly string $getNameInput = 'name',
        private readonly string $getEmailInput = 'email',
        private readonly string $getPhoneInput = 'phone',
        private readonly string $getAddressInput = 'address',
        private readonly string $getCityInput = 'city',
        private readonly string $getRegionInput = 'region',
        private readonly string $getCountryInput = 'country',
        private readonly string $getPostalCodeInput = 'postal_code',
        private readonly string $getWebsiteInput = 'website',
        private readonly string $getInstagramInput = 'instagram',
        private readonly string $getFacebookInput = 'facebook',
        private readonly string $getYoutubeInput = 'youtube',
        private readonly string $getCustomLinkInput = 'custom_link',
        private readonly string $getLogoUpload = 'logo',
        private readonly string $getNotesInput = 'notes',
        private readonly string $getStatusInput = 'status',
        private readonly string $getStartDateInput = 'start_date',
        private readonly string $getEndDateInput = 'end_date',
        private readonly string $getTypeInput = 'type',
        private readonly string $getCreatedByInput = 'created_by',
        private readonly string $getUpdatedByInput = 'updated_by',
    ) {}

    /**
     * @return TextInput
     */
    public static function getNameInput(): TextInput
    {
        return TextInput::make('name')
            ->label(__('Company Name'))
            ->placeholder(__('Enter Company Name'))
            ->required()
            ->maxLength(255)
            ->reactive()
            ->disabled(fn() => auth()->user()->hasRole('pic'))
            ->afterStateUpdated(function ($state, callable $set) {
                $set('name', $state);
            });
    }

    /**
     * @return TextInput
     */
    public static function getEmailInput(): TextInput
    {
        return TextInput::make('email')
            ->label(__('Email'))
            ->placeholder(__('Enter Email'))
            ->required()
            ->email()
            ->maxLength(255)
            ->reactive()
            ->unique('companies', 'email', ignoreRecord: true);
    }

    /**
     * @return TextInput
     */
    public static function getPhoneInput(): TextInput
    {
        return TextInput::make('phone')
            ->label(__('Nomor Telepon'))
            ->mask('99 9999 999 999')
            ->prefix('+')
            ->tel()
            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
            ->required()
            ->maxLength(15);
    }

    /**
     * @return MarkdownEditor
     */
    public static function getAddressInput(): MarkdownEditor
    {
        return MarkdownEditor::make('address')
            ->label(__('Address'))
            ->placeholder(__('Enter Address'))
            ->required()
            ->maxLength(255)
            ->reactive()
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }

    /**
     * @return TextInput
     */
    public static function getCityInput(): TextInput
    {
        return TextInput::make('city')
            ->label(__('City'))
            ->placeholder(__('Enter City'))
            ->required()
            ->maxLength(255)
            ->reactive()
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }

    /**
     * @return TextInput
     */
    public static function getRegionInput(): TextInput
    {
        return TextInput::make('region')
            ->label(__('Region'))
            ->placeholder(__('Enter Region'))
            ->required()
            ->maxLength(255)
            ->reactive()
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }

    /**
     * @return TextInput
     */
    public static function getCountryInput(): TextInput
    {
        return TextInput::make('country')
            ->label(__('Country'))
            ->placeholder(__('Enter Country'))
            ->required()
            ->maxLength(255)
            ->reactive()
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }

    /**
     * @return TextInput
     */
    public static function getPostalCodeInput(): TextInput
    {
        return TextInput::make('postal_code')
            ->label(__('Postal Code'))
            ->placeholder(__('Enter Postal Code'))
            ->required()
            ->maxLength(255)
            ->reactive()
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }

    /**
     * @return TextInput
     */
    public static function getWebsiteInput(): TextInput
    {
        return TextInput::make('website')
            ->label(__('Website'))
            ->placeholder(__('Enter Website'))
            ->url()
            ->maxLength(255)
            ->reactive()
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }

    /**
     * @return TextInput
     */
    public static function getInstagramInput(): TextInput
    {
        return TextInput::make('instagram')
            ->label(__('Instagram'))
            ->placeholder(__('Enter Instagram'))
            ->url()
            ->maxLength(255)
            ->reactive()
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }

    /**
     * @return TextInput
     */
    public static function getFacebookInput(): TextInput
    {
        return TextInput::make('facebook')
            ->label(__('Facebook'))
            ->placeholder(__('Enter Facebook'))
            ->url()
            ->maxLength(255)
            ->reactive()
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }

    /**
     * @return TextInput
     */
    public static function getYoutubeInput(): TextInput
    {
        return TextInput::make('youtube')
            ->label(__('Youtube'))
            ->placeholder(__('Enter Youtube'))
            ->url()
            ->maxLength(255)
            ->reactive()
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }

    /**
     * @return TextInput
     */
    public static function getCustomLinkInput(): TextInput
    {
        return TextInput::make('custom_link')
            ->label(__('Custom Link'))
            ->placeholder(__('Enter Custom Link'))
            ->url()
            ->maxLength(255)
            ->reactive()
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }

    /**
     * @return FileUpload
     */
    public static function getLogoUpload(): FileUpload
    {
        return FileUpload::make('logo')
            ->hiddenLabel()
            ->image()
            ->imageEditor()
            ->maxSize(1024)
            ->directory('companies')
            ->optimize('webp')
            ->resize(50);
    }

    /**
     * @return MarkdownEditor
     */
    public static function getNotesInput(): MarkdownEditor
    {
        return MarkdownEditor::make('notes')
            ->label(__('Notes'))
            ->placeholder(__('Enter Notes'))
            ->maxLength(255)
            ->reactive()
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }

    /**
     * @return Select
     */
    public static function getStatusInput(): Select
    {
        return Select::make('status')
            ->options(StatusCompany::class)
            ->required()
            ->native(false)
            ->default(StatusCompany::Pending->value)
            ->label('Status')
            ->reactive()
            ->preload()
            ->searchable()
            ->disabled(fn() => !auth()->user()->hasRole('super_admin') && !auth()->user()->hasPermissionTo('edit_status_company'))
            ->helperText('Hanya Admin yang dapat mengubah status company.')
            ->getOptionLabelUsing(fn($value) => StatusCompany::from($value)->name)
            ->placeholder(__('Select Status'))
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }


    /**
     * @return DatePicker
     */
    public static function getStartDateInput(): DatePicker
    {
        return DatePicker::make('start_date')
            ->label(__('Start Date'))
            ->placeholder(__('Select Start Date'))
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                $set('start_date', $state);
            })
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }

    /**
     * @return DatePicker
     */
    public static function getEndDateInput(): DatePicker
    {
        return DatePicker::make('end_date')
            ->label(__('End Date'))
            ->placeholder(__('Select End Date'))
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                $set('end_date', $state);
            })
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }

    /**
     * @return Select
     */
    public static function getTypeInput(): Select
    {
        return Select::make('type')
            ->options(TypeCompany::class)
            ->required()
            ->native(false)
            ->default(TypeCompany::Mitra->value)
            ->label('Type')
            ->reactive()
            ->preload()
            ->searchable()
            ->placeholder(__('Select Type'))
            ->disabled(fn() => !auth()->user()->hasRole('super_admin') && !auth()->user()->hasPermissionTo('edit_type_company'))
            ->helperText('Hanya Admin yang dapat mengubah type company.')
            ->getOptionLabelUsing(fn($value) => StatusCompany::from($value)->name)
            ->columnSpan(['default' => 2, 'lg' => 1]);
    }

    /**
     * @return array<string, string>
     * Table Columns
     */
    public static function getTableColumns(): array
    {
        return [
            NumberColumn::make('No'),
            ImageColumn::make('logo')
                ->label('Logo')
                ->circular()
                ->defaultImageUrl(url('/assets/placeholder.png')),
            TextColumn::make('name')
                ->searchable(auth()->user()->hasAnyRole(['super_admin', 'administrator']) == true)
                ->sortable(auth()->user()->hasAnyRole(['super_admin', 'administrator']) == true),
            TextColumn::make('email')
                ->searchable(auth()->user()->hasAnyRole(['super_admin', 'administrator']) == true)
                ->sortable(auth()->user()->hasAnyRole(['super_admin', 'administrator']) == true),
            TextColumn::make('phone')
                ->searchable(auth()->user()->hasAnyRole(['super_admin', 'administrator']) == true)
                ->sortable(auth()->user()->hasAnyRole(['super_admin', 'administrator']) == true),
            TextColumn::make('address')
                ->searchable(auth()->user()->hasAnyRole(['super_admin', 'administrator']) == true)
                ->sortable(auth()->user()->hasAnyRole(['super_admin', 'administrator']) == true)
                ->wrap(),
            TextColumn::make('status')
                ->searchable(auth()->user()->hasAnyRole(['super_admin', 'administrator']) == true)
                ->sortable(auth()->user()->hasAnyRole(['super_admin', 'administrator']) == true),
            TextColumn::make('type')
                ->searchable(auth()->user()->hasAnyRole(['super_admin', 'administrator']) == true)
                ->sortable(auth()->user()->hasAnyRole(['super_admin', 'administrator']) == true),

            TextColumn::make('start_date')
                ->date()
                ->sortable(auth()->user()->hasAnyRole(['super_admin', 'administrator']) == true),
            TextColumn::make('end_date')
                ->date()
                ->sortable(auth()->user()->hasAnyRole(['super_admin', 'administrator']) == true),

            TextColumn::make('creator.name')->label('Created By')
                ->visible(fn($record) => auth()->user()->hasAnyRole(['super_admin', 'administrator', 'noc']))
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updater.name')->label('Updated By')
                ->visible(fn($record) => auth()->user()->hasAnyRole(['super_admin', 'administrator', 'noc']))
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('deleted_at')
                ->dateTime()
                ->sortable()
                ->visible(fn($record) => auth()->user()->hasAnyRole(['super_admin', 'administrator', 'noc']))
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->visible(fn($record) => auth()->user()->hasAnyRole(['super_admin', 'administrator', 'noc']))
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->visible(fn($record) => auth()->user()->hasAnyRole(['super_admin', 'administrator', 'noc']))
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<string, string>
     * Table Filters
     */
    public static function getTableFilters(): array
    {
        return [
            TrashedFilter::make()
                ->visible(fn($livewire) => auth()->user()->hasAnyRole(['super_admin', 'administrator', 'noc'])),
        ];
    }

    /**
     * @return array<string, string>
     * Table Actions
     */
    public static function getTableActions(): array
    {
        return [
            ActionGroup::make([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
            ])
                ->label('Lihat Aksi')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button()
        ];
    }
}
