<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Enums\User\StatusUserEnum;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Components\ToggleButtons;
use App\Filament\Tables\Columns\NumberColumn;

final class FormsComponentsUsers
{
    use \App\Traits\RoleFiltering;
    public function __construct(
        private readonly string $getNameInput = 'name',
        private readonly string $getUserNameInput = 'username',
        private readonly string $getEmailInput = 'email',
        private readonly string $getPasswordInput = 'password',
        private readonly string $getPasswordConfirmationInput = 'password_confirmation',
        private readonly string $getAvatarUpload = 'avatar_url',
        private readonly string $getPhoneInput = 'phone',
        private readonly string $getUserRoles = 'roles.name',
        private readonly string $getStatusAccount = 'status',
        private readonly string $getIsActive = 'is_active',
    ) {}

    /**
     * @return TextInput
     */
    public static function getNameInput(): TextInput
    {
        return TextInput::make('name')
            ->label(__('Fullname'))
            ->placeholder(__('Masukan Nama Lengkap'))
            ->required()
            ->maxLength(255)
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                $set('name', $state);
            });
    }


    /**
     * @return TextInput
     */
    public static function getUserNameInput(): TextInput
    {
        return TextInput::make('username')
            ->label(__('Username'))
            ->placeholder(__('Masukan Username'))
            ->required()
            ->maxLength(255)
            ->reactive()
            ->unique('users', 'username', ignoreRecord: true)
            ->afterStateUpdated(function ($state, callable $set) {
                $set('username', $state);
            });
    }

    /**
     * @return TextInput
     */
    public static function getEmailInput(): TextInput
    {
        return TextInput::make('email')
            ->label(__('Email'))
            ->placeholder(__('Masukan Email'))
            ->required()
            ->email()
            ->maxLength(255)
            ->reactive()
            ->unique('users', 'email', ignoreRecord: true);
    }

    /**
     * @return TextInput
     */
    public static function getPasswordInput(): TextInput
    {
        return TextInput::make('password')
            ->label(__('Password'))
            ->placeholder(__('Password'))
            ->required()
            ->password()
            ->revealable()
            ->minLength(8)
            ->maxLength(255)
            ->same('password_confirmation')
            ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
            ->dehydrated(fn(?string $state): bool => filled($state))
            ->required(fn($livewire) => $livewire instanceof \App\Filament\Resources\UserResource\Pages\CreateUser);
    }

    public static function getPasswordConfirmationInput(): TextInput
    {
        return TextInput::make('password_confirmation')
            ->label(__('Password Confirmation'))
            ->placeholder(__('Masukan Password Confirmation'))
            ->password()
            ->revealable()
            ->dehydrated(false)
            ->required(fn($livewire) => $livewire instanceof \App\Filament\Resources\UserResource\Pages\CreateUser);
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
     * @return FileUpload
     */
    public static function getAvatarUpload(): FileUpload
    {
        return FileUpload::make('avatar_url')
            ->hiddenLabel()
            ->image()
            ->imageEditor()
            ->maxSize(2048)
            ->directory('users')
            ->optimize('webp')
            ->resize(50)
            ;
    }

    /**
     * @return Select
     */
    public static function getStatusAccount(): Select
    {
        return Select::make('status')
            ->label(__('Status Akun'))
            ->options([
                StatusUserEnum::PENDING->value => 'Menunggu',
                StatusUserEnum::ACTIVE->value => 'Aktif',
                StatusUserEnum::BLOCKED->value => 'Diblokir',
            ])
            ->afterStateUpdated(function ($state, callable $set) {
                if ($state === StatusUserEnum::ACTIVE->value) {
                    $set('is_active', true);
                } elseif ($state === StatusUserEnum::PENDING->value) {
                    $set('is_active', false);
                } elseif ($state === StatusUserEnum::BLOCKED->value) {
                    $set('is_active', false);
                }
            })
            ->reactive()
            ->required()
            ->preload()
            ->reactive()
            ->searchable()
            ->placeholder(__('Pilih Status Akun'));
    }

    /**
     * @return ToggleButtons
     */
    public static function getIsActive(): ToggleButtons
    {
        return ToggleButtons::make('is_active')
            ->label(__('Active'))
            ->inline()
            ->options([
                '1' => 'Aktif',
                '0' => 'Non-Aktif',
            ])
            ->colors([
                '1' => 'info',
                '0' => 'danger',
            ])
            ->default(false)
            ->disabled(fn(callable $get) => $get('status') === StatusUserEnum::BLOCKED->value)
            ->visible(fn(callable $get) => $get('status') !== null);
    }

    // roles
    /**
     * @return Select
     */
    public static function getUserRoles(): Select
    {
        return Select::make('roles.name')
            ->relationship('roles', 'name')
            ->saveRelationshipsUsing(function (Model $record, $state) {
                $record->roles()
                ->sync($state);
            })
            // ->options(
            //     self::getFilteredRoles([
            //         'super_admin' => ['super_admin', 'administrator', 'pic', 'user', 'guest'],
            //         'administrator' => ['administrator', 'pic', 'user', 'guest'],
            //         'default' => ['user', 'guest'],
            //     ])
            // )
            ->multiple()
            ->preload()
            ->required()
            ->reactive();
    }


    /**
     * @return array<string, string>
     * Table Columns
     */
    public static function getTableColumns(): array
    {
        return [
            NumberColumn::make('no')
                ->label('No'),
            ImageColumn::make('avatar_url')
                ->label('Avatar')
                ->circular()
                ->defaultImageUrl(url('/assets/placeholder.png')),
            TextColumn::make('name')
                ->description(fn(User $record): string => 'Created: ' . $record->created_at?->format('d M Y') ?? 'Not available')
                ->searchable()
                ->sortable(),
            TextColumn::make('phone')
                ->copyable()
                ->copyMessage('Phone Number copied')
                ->copyMessageDuration(1500)
                ->searchable()
                ->sortable(),
            TextColumn::make('email')
                ->copyable()
                ->copyMessage('Email Address copied')
                ->badge()
                ->color('success')
                ->copyMessageDuration(1500)
                ->searchable()
                ->sortable(),
            TextColumn::make('roles.name')
                ->label('Role')
                ->formatStateUsing(fn(string $state): string => \Illuminate\Support\Str::headline($state))
                ->sortable()
                ->searchable()
                ->badge(),
            TextColumn::make('status')
                ->label('Status Akun')
                ->badge()
                ->searchable(),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
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
            TrashedFilter::make(),
            Filter::make('created_at')
                ->form([
                    DatePicker::make('start_date')
                        ->label('Start Date')
                        ->native(false)
                        ->placeholder('Start Date')
                        ->required(),
                    DatePicker::make('end_date')
                        ->label('End Date')
                        ->native(false)
                        ->placeholder('End Date')
                        ->required(),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->whereBetween('created_at', [
                        $data['start_date'] ?? now()->subMonth(),
                        $data['end_date'] ?? now(),
                    ]);
                }),
            SelectFilter::make('status')
                ->options([
                    StatusUserEnum::ACTIVE->value => 'Active',
                    StatusUserEnum::BLOCKED->value => 'Blocked',
                    StatusUserEnum::PENDING->value => 'Pending',
                ])
                ->placeholder('Semua Status'),
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
                DeleteAction::make(),
                Action::make('confirm')
                    ->label('Confirm')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->visible(
                        fn($record) => $record->status === StatusUserEnum::PENDING &&
                            Auth::user()?->hasAnyRole(['super_admin', 'administrator', 'noc'])
                    )
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => StatusUserEnum::ACTIVE,
                            'is_active' => true,
                        ]);
                    }),
                Action::make('block')
                    ->label('Block')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->visible(fn($record) => $record->status === StatusUserEnum::ACTIVE &&
                        Auth::user()?->hasAnyRole(['super_admin', 'administrator', 'noc']))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => StatusUserEnum::BLOCKED,
                            'is_active' => false,
                        ]);
                    }),
                Action::make('unblock')
                    ->label('Unblock')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->status === StatusUserEnum::BLOCKED &&
                        Auth::user()?->hasAnyRole(['super_admin', 'administrator', 'noc']))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => StatusUserEnum::PENDING,
                            'is_active' => false,
                        ]);
                    }),
            ])
                ->label('Lihat Aksi')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button(),
        ];
    }
}
