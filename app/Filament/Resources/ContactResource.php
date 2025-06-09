<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Contact;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use App\Services\FormsComponentsContacts;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ContactResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ContactResource\RelationManagers;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class ContactResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Contact::class;
    protected static ?string $navigationGroup = 'CRM';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make('Detail Contact')
                            ->schema([
                                FormsComponentsContacts::getNameInput(),
                                FormsComponentsContacts::getEmailInput(),
                                FormsComponentsContacts::getPhoneInput(),
                                FormsComponentsContacts::getPositionInput(),
                            ])->columns(2)
                    ]),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make('Relations')
                            ->schema([
                                FormsComponentsContacts::getUserSelectInput(),
                                FormsComponentsContacts::getCompanySelectInput(),
                            ])->columns(2)
                    
                    ])->visible(fn($record) => auth()->user()->hasAnyRole(['super_admin', 'administrator'])),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('company.name')
            // ->groupingSettingsHidden()
            ->columns(FormsComponentsContacts::getTableColumns())
            ->filters(FormsComponentsContacts::getTableFilters())
            ->actions(FormsComponentsContacts::getTableActions())
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'view' => Pages\ViewContact::route('/{record}'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery(); // lihat semua contact di company
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
        ];
    }
}
