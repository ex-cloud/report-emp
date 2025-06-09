<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Services\FormsComponentsUsers as FormsComponents;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $tenantRelationshipName = 'companies';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Filament Shield';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 2 ? 'warning' : 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make('Profile Information')
                            ->schema([
                                FormsComponents::getNameInput()->columnSpan(1),
                                FormsComponents::getUserNameInput()->columnSpan(1),
                                FormsComponents::getEmailInput()->columnSpan(1),
                                FormsComponents::getPhoneInput()->columnSpan(1),
                            ])->columns(2),
                        Forms\Components\Section::make()
                            ->schema([
                                FormsComponents::getPasswordInput(),
                                FormsComponents::getPasswordConfirmationInput(),
                            ])->columns(2),
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\MarkdownEditor::make('description')
                                    ->label('Description')
                                    ->placeholder('Masukan Deskripsi Pengguna')
                                    ->columnSpan(2)
                                    ->disableToolbarButtons([
                                        'attachFiles',
                                        'codeBlock',
                                        'link',
                                        'image',
                                        'table',
                                        'horizontalRule',
                                        'codeBlock',
                                    ]),
                            ])->columns(1),
                    ])->columnSpan(3),
                Forms\Components\Grid::make()
                    ->schema([
                            Forms\Components\Section::make('Photo Profile')
                                ->schema([
                                    FormsComponents::getAvatarUpload(),
                                ]),
                            Forms\Components\Section::make()
                                ->schema([
                                    FormsComponents::getUserRoles(),
                                    FormsComponents::getStatusAccount(),
                                    FormsComponents::getIsActive(),
                                ])
                    ])->columnSpan(1),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Daftar Users')
            ->description('Kelola daftar Users disini.')
            ->deferLoading()
            ->defaultSort('name', direction: 'asc')
            ->emptyStateHeading('Tidak ada User')
            ->emptyStateDescription('Tidak ada User yang terdaftar, Buat User baru untuk menambahkan ke daftar.')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label('Create User')
                    ->url(fn() => filament()->getTenant()
                        ? static::getUrl(name: 'create', tenant: filament()->getTenant())
                        : static::getUrl(name: 'create'))
                    ->icon('heroicon-m-user-plus')
                    ->button()
            ])
            ->columns(FormsComponents::getTableColumns())
            ->filters(FormsComponents::getTableFilters())
            ->actions(FormsComponents::getTableActions())
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
            //
        ];
    }
    // public static function shouldApplyTenantScope(): bool
    // {
    //     return !auth()->user()?->hasRole('super_admin');
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->hideSuperAdminUnlessCurrent();
    }
}
