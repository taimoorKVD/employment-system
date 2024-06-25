<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\RelationManagers\EmployeesRelationManager;
use App\Filament\Resources\DepartmentResource\Pages;
use App\Models\Department;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Department';

    protected static ?string $modelLabel = 'Department';

    protected static ?string $navigationGroup = "Department Management";

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Total Employees' => $record->employees->count()
        ];
    }

    public static function getCount()
    {
        return self::getModel()::Count();
    }

    public static function getNavigationBadge(): ?string
    {
        return self::getCount();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return self::getCount() > 10 ? 'success' : 'info';
    }

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $logged_user = auth()->user();
        $current_tenant = Filament::getTenant();
        return $form
            ->schema([
                Section::make('Department Management')
                    ->description('Put the department information.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('team_id')
                            ->label('Project')
                            ->hint('Active Project')
                            ->hintIcon('heroicon-m-exclamation-circle')
                            ->default($current_tenant->name)
                            ->hiddenOn('edit')
                            ->readOnly(),
                        Forms\Components\Hidden::make('team_id')
                            ->default($current_tenant->id),
                        Forms\Components\Hidden::make('user_id')
                            ->default($logged_user->id),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        $is_admin = auth()->user()->is_admin;
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Creator')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible($is_admin),
                Tables\Columns\TextColumn::make('employees_count')
                    ->label('Total Employee')
                    ->counts('employees'),
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->warning()
                            ->title('Department Successfully Updated')
                            ->body('The department has been successfully updated.')
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->danger()
                            ->title('Department Successfully Deleted')
                            ->body('The department has been successfully deleted.')
                    )
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->danger()
                                ->title('Bulk Deletion Successful')
                                ->body('The bulk deletion has been successfully completed.')
                        ),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('employees_count')
                            ->label('Total Employee')
                            ->state(function (Model $model): int {
                                return $model->employees()->count();
                            }),
                    ])->columns(2)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            EmployeesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            //'view' => Pages\ViewDepartment::route('/{record}'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
