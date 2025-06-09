<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Helpers\Helper;
use App\Models\Company;
use Filament\Forms\Get;
use Illuminate\Validation\Rule;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Tables\Columns\NumberColumn;

final class FormsComponentsContacts
{

    public static function getNameInput(): TextInput
    {
        return TextInput::make('name')
            ->required()
            ->maxLength(255);
    }

    public static function getEmailInput(): TextInput
    {
        return TextInput::make('email')
            ->email()
            ->required()
            ->maxLength(255);
    }

    public static function getPhoneInput(): TextInput
    {
        return TextInput::make('phone')
            ->tel()
            ->maxLength(255);
    }

    public static function getPositionInput(): TextInput
    {
        return TextInput::make('position')
            ->label('Jabatan')
            ->required()
            ->maxLength(255);
    }
    
    public static function getUserSelectInput(): \Filament\Forms\Components\Select
    {
        return \Filament\Forms\Components\Select::make('user_id')
            ->label('User')
            ->default(auth()->id())
            ->options(
                fn($livewire) =>
                Helper::getAvailableUsersByRole(['user', 'pic'], $livewire->record?->user_id ?? null)
            )
            ->getOptionLabelUsing(fn($value) => User::find($value)?->name ?? 'User tidak ditemukan')
            ->preload()
            ->placeholder('Select User')
            ->searchable()
            ->rules(function (Get $get, ?Model $record) {
                return [
                    Rule::unique('contacts', 'user_id')->ignore($record?->id),
                ];
            })
            ->disabled(fn() => auth()->user()->hasRole('pic'))
            ->helperText('User hanya boleh menjadi PIC untuk satu company saja.');
    }

    public static function getCompanySelectInput(): \Filament\Forms\Components\Select
    {
        return \Filament\Forms\Components\Select::make('company_id')
            ->label('Company')
            ->relationship('company', 'name')
            ->preload()
            ->options(
                fn($livewire) =>
                Helper::getAvailableCompanyByRole(['user', 'pic'], isset($livewire->record?->company_id) ? (int) $livewire->record->company_id : null)
            )
            ->placeholder('Select Company')
            ->searchable()
            ->getOptionLabelFromRecordUsing(fn($record) => $record->name)
            ->disabled(fn() => auth()->user()->hasRole('pic'))
            ->helperText('Secara otomatis terisi jika Anda login sebagai PIC.');
    }

    /**
     * @return array<string, string>
     * Table Columns
     */
    public static function getTableColumns(): array
    {
        $user = auth()->user();

        return [
            NumberColumn::make('No'),
            TextColumn::make('name')
                ->searchable(),
            TextColumn::make('email')
                ->searchable(),
            TextColumn::make('phone')
                ->searchable(),
            TextColumn::make('position')
                ->searchable(),
            TextColumn::make('company.name')
                ->label('Company')
                ->searchable(),
            TextColumn::make('user.name')
                ->label('User')
                ->searchable()
                // visible when role super admin and administrator
                ->visible(fn($record) => $user->hasAnyRole(['super_admin', 'administrator'])),
            TextColumn::make('created_by')
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable(),
            TextColumn::make('updated_by')
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable(),
            TextColumn::make('deleted_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
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

            // Filter berdasarkan company_id untuk menampilkan PIC dari company yang sama
            SelectFilter::make('company_id')
                ->label('Company')
                ->options(fn() => Company::pluck('name', 'id'))
                ->visible(fn() => auth()->user()->hasAnyRole(['super_admin', 'administrator']))
                ->preload()
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