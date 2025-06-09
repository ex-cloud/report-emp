<?php
declare(strict_types=1);

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\ActionSize;
use Filament\Infolists\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Services\FormsComponentsEmployees;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use App\Filament\Tables\Columns\NumberColumn;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationGroup = 'HRM';

    public static function form(Form $form): Form
    {
        // $view = 'filament::components.employee-profile-header';
        return $form
            ->schema([
                    Forms\Components\Section::make('Employee Information')
                        ->description(fn(?Model $record) => $record
                        ? 'Informasi Pekerja - ID: ' . $record->employee_id
                        : 'Informasi Pekerja (ID akan dibuat otomatis)')
                        ->icon('heroicon-m-chevron-double-right')
                        ->iconSize(IconSize::Small)
                        ->iconColor('primary')
                        ->compact()
                        ->schema([
                            Forms\Components\Tabs::make('Tabs')
                                ->tabs([
                                    Forms\Components\Tabs\Tab::make('Basic Information')
                                        ->schema([
                                            FormsComponentsEmployees::getName(),
                                            FormsComponentsEmployees::getEmail(),
                                            FormsComponentsEmployees::getWhatsapp(),
                                            FormsComponentsEmployees::getDateHired(),
                                            FormsComponentsEmployees::getType(),
                                            FormsComponentsEmployees::getPosition(),
                                            FormsComponentsEmployees::getStatus(),
                                            FormsComponentsEmployees::getAddress(),
                                        ])->columns(3),
                                    Forms\Components\Tabs\Tab::make('Document')
                                        ->schema([
                                            FormsComponentsEmployees::getGender(),
                                            FormsComponentsEmployees::getBirthPlace(),
                                            FormsComponentsEmployees::getDateOfBirth(),
                                        ]),
                                    Forms\Components\Tabs\Tab::make('Note')
                                        ->schema([
                                            FormsComponentsEmployees::getCountry(),
                                            FormsComponentsEmployees::getPostalCode(),
                                        ]),
                                ]),
                            ])->columnSpan(3),
                    Forms\Components\Section::make('Photo')
                        ->schema([
                            FormsComponentsEmployees::getPhoto(),
                        ])->columnSpan(1),
                    Forms\Components\Section::make()
                        ->schema([
                            FormsComponentsEmployees::getWebsite()->columnSpan(1),
                            FormsComponentsEmployees::getInstagram()->columnSpan(1),
                            FormsComponentsEmployees::getFacebook()->columnSpan(1),
                            FormsComponentsEmployees::getYoutube()->columnSpan(1),
                            FormsComponentsEmployees::getCustomLink()->columnSpan(1),
                        ])->columns(2),
                    ])->columns(4)
            ->columns(4)
            ->columns([
                'default' => 1,
                'sm' => 2,
                'md' => 3,
                'lg' => 4,
            ]);
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                    'type',
                    'position',
                ])
            ->defaultGroup('type')
            ->columns([
                NumberColumn::make('No')
                    ->label('No'),
                Tables\Columns\ImageColumn::make('photo')
                    ->label('photo')
                    ->circular()
                    ->defaultImageUrl(url('/assets/placeholder.png')),
                Tables\Columns\TextColumn::make('name')
                    ->description(fn (Employee $record): string => $record->employee_id)
                    ->label('Nama Pekerja')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('whatsapp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_hired')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_by')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
            ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
                ->label('Lihat Aksi')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Employee Information')
                    ->description(fn(?Model $record) => $record
                        ? 'Informasi Pekerja - ID: ' . $record->employee_id
                        : 'Informasi Pekerja (ID akan dibuat otomatis)')
                    ->icon('heroicon-m-chevron-double-right')
                    ->iconSize(IconSize::Small)
                    ->iconColor('primary')
                    ->compact()
                    ->schema([
                // FormsComponentsEmployees::getEmployeeProfileHeader(),
                // FormsComponentsEmployees::getName(),
                // FormsComponentsEmployees::getEmail(),
                // FormsComponentsEmployees::getWhatsapp(),
                // FormsComponentsEmployees::getDateHired(),
                // FormsComponentsEmployees::getType(),
                // FormsComponentsEmployees::getPosition(),
                // FormsComponentsEmployees::getStatus(),
                // FormsComponentsEmployees::getAddress(),
                    Grid::make()
                        ->schema([
                            ViewEntry::make('components.employee-profile-header')
                                ->view('components.employee-profile-header')
                                ->visible(fn(?Model $record) => $record !== null),
                            TextEntry::make('address')
                                ->icon('heroicon-o-map-pin')
                                ->hiddenLabel(),
                            TextEntry::make('date_hired')
                                ->icon('heroicon-o-calendar')
                                ->hiddenLabel()
                                ->date(),
                            TextEntry::make('position')
                                ->icon('heroicon-o-briefcase')
                                ->hiddenLabel(),
                            TextEntry::make('website')
                                ->icon('lucide-globe')
                                ->formatStateUsing(
                                    fn(?string $state) => filled($state)
                                        ? ltrim($state, '/')
                                        : 'example.com' // Tampilkan default jika kosong
                                )
                                ->default('example.com')
                                ->url(
                                    fn(?Model $record) => filled($record?->website)
                                        ? 'https://' . ltrim($record->website, '/')
                                        : 'https://example.com'
                                )
                                ->openUrlInNewTab()
                                ->hiddenLabel(),
                            TextEntry::make('facebook')
                                ->hiddenLabel()
                                ->icon('lucide-facebook')
                                ->formatStateUsing(
                                    fn(?string $state) => filled($state)
                                        ? ltrim($state, '/')
                                        : 'facebook.com' // Tampilkan default jika kosong
                                )
                                ->default('facebook')
                                ->url(
                                    fn(?Model $record) => filled($record?->facebook)
                                        ? 'https://facebook.com/' . ltrim($record->facebook, '/')
                                        : 'https://facebook.com'
                                )
                                ->openUrlInNewTab(),
                            TextEntry::make('instagram')
                                ->icon('lucide-instagram')
                                ->formatStateUsing(
                                    fn(?string $state) => filled($state)
                                        ? ltrim($state, '/')
                                        : 'instagram.com' // Tampilkan default jika kosong
                                )
                                ->default('instagram')
                                ->url(
                                    fn(?Model $record) => filled($record?->instagram)
                                        ? 'https://instagram.com/' . ltrim($record->instagram, '/')
                                        : 'https://instagram.com'
                                )
                                ->openUrlInNewTab()
                                ->copyable() // opsional: bisa klik untuk copy username
                                ->hiddenLabel(),
                            TextEntry::make('youtube')
                                ->icon('lucide-youtube')
                                ->formatStateUsing(
                                    fn(?string $state) => filled($state)
                                        ? ltrim($state, '/')
                                        : 'youtube.com' // Tampilkan default jika kosong
                                )
                                ->default('youtube')
                                ->url(
                                    fn(?Model $record) => filled($record?->youtube)
                                        ? 'https://youtube.com/' . ltrim($record->youtube, '/')
                                        : 'https://youtube.com'
                                )
                                ->openUrlInNewTab()
                                ->hiddenLabel(),
                        ])->columns(1),
                    ])->columnSpan(1),
            ])->columns(4);
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
