<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Company;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Services\FormsComponentsCompanies;
use App\Filament\Resources\CompanyResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CompanyResource\RelationManagers;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class CompanyResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Company::class;
    protected static ?string $navigationGroup = 'CRM';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make('Profile Information')
                            ->schema([
                                FormsComponentsCompanies::getNameInput()->columnSpan(1),
                                FormsComponentsCompanies::getEmailInput()->columnSpan(1),
                                FormsComponentsCompanies::getPhoneInput()->columnSpan(1),
                                FormsComponentsCompanies::getAddressInput()->columnSpan(1),
                                FormsComponentsCompanies::getCityInput()->columnSpan(1),
                                FormsComponentsCompanies::getRegionInput()->columnSpan(1),
                                FormsComponentsCompanies::getCountryInput()->columnSpan(1),
                                FormsComponentsCompanies::getPostalCodeInput()->columnSpan(1),
                            ])->columns(2),
                        Forms\Components\Section::make()
                            ->schema([
                                FormsComponentsCompanies::getWebsiteInput()->columnSpan(1),
                                FormsComponentsCompanies::getInstagramInput()->columnSpan(1),
                                FormsComponentsCompanies::getFacebookInput()->columnSpan(1),
                                FormsComponentsCompanies::getYoutubeInput()->columnSpan(1),
                                FormsComponentsCompanies::getCustomLinkInput()->columnSpan(1),
                            ])->columns(2),
                    ])->columnSpan(3),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make('Logo')
                            ->schema([
                                FormsComponentsCompanies::getLogoUpload(),
                            ]),
                        Forms\Components\Section::make()
                            ->schema([
                                FormsComponentsCompanies::getNotesInput()->columnSpan(1),
                                Forms\Components\Grid::make()
                                    ->schema([
                                        FormsComponentsCompanies::getStartDateInput()->columnSpan(1),
                                        FormsComponentsCompanies::getEndDateInput()->columnSpan(1),
                                    ])->columns(2),
                                FormsComponentsCompanies::getStatusInput()->columnSpan(1),
                                FormsComponentsCompanies::getTypeInput()->columnSpan(1),
                            ]),
                    ])->columnSpan(1),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        
        return $table
            ->heading(fn($livewire) => auth()->user()->hasAnyRole(['super_admin', 'administrator']) ? 'Daftar Company' : null)
            ->description(fn($livewire) => auth()->user()->hasAnyRole(['super_admin', 'administrator']) ? 'Kelola daftar Company disini.' : null)
            ->deferLoading()
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Tidak ada Company')
            ->emptyStateDescription('Tidak ada Company yang terdaftar, Buat Company baru untuk menambahkan ke daftar.')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label('Create Company')
                    ->url(route('filament.admin.resources.companies.create'))
                    ->icon('heroicon-m-user-plus')
                    ->button()
                    ->visible(fn($record) => auth()->user()->hasAnyRole(['super_admin', 'administrator', 'noc']))
            ])
            ->columns(FormsComponentsCompanies::getTableColumns())
            ->filters(FormsComponentsCompanies::getTableFilters())
            ->actions(FormsComponentsCompanies::getTableActions())
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\UserRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'view' => Pages\ViewCompany::route('/{record}'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Kalau super_admin => langsung lihat semua (tanpa owned/selfEditable)
        if (auth()->user()->hasRole('super_admin')) {
            return $query
                ->with(['creator', 'updater'])
                ->withoutGlobalScopes([SoftDeletingScope::class]);
        }

        // Kalau bukan super_admin => baru dibatasi
        try {
            $query = $query
                ->owned()  // Batasi data berdasarkan perusahaan atau unit user
                ->selfEditable()  // Hanya bisa edit diri sendiri jika user biasa
                ->with(['creator', 'updater']);
        } catch (\Throwable $e) {
            \Log::error('Owned query error', ['error' => $e->getMessage()]);
        }

        return $query->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function beforeSave($record)
    {
        if ($record->exists) {
            $record->updated_by = auth()->user()->name;
            $record->updated_at = now();
        }
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'attach_pic',
            'detach_pic',
            'force_delete',
            'force_delete_any',
            'edit_status',
            'edit_type',
        ];
    }
}
