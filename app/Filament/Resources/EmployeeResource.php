<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\City;
use App\Models\Employee;
use App\Models\State;
use Carbon\Carbon;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Employees';

    protected static ?string $navigationGroup = 'Employee Management';

    protected static ?string $recordTitleAttribute = 'first_name';

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return "{$record->first_name} {$record->last_name}";
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'first_name',
            'middle_name',
            'last_name'
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Country' => $record->country->name
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with('country');
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

    public static function form(Form $form): Form
    {
        $logged_user = auth()->user();
        $current_tenant = Filament::getTenant();
        return $form
            ->schema([
                Forms\Components\Section::make('User Name')
                    ->description('Enter the user name details.')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('middle_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                    ])->columns(3),
                Forms\Components\Section::make('User Address')
                    ->description('Enter the user address details.')
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('zip_code')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),
                Forms\Components\Section::make('Dates')
                    ->description('Enter the dates details.')
                    ->schema([
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Birth Day')
                            ->minDate(now()->subYears(150))
                            ->maxDate(now())
                            ->required(),
                        Forms\Components\DatePicker::make('date_hired')
                            ->label('Joining Date')
                            ->required(),
                    ])->columns(2),
                Forms\Components\Section::make('Regional Information')
                    ->description('Enter the user regional details.')
                    ->schema([
                        Forms\Components\Select::make('country_id')
                            ->label('Country')
                            ->relationship('country', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('state_id', null);
                                $set('city_id', null);
                            })
                            ->required(),
                        Forms\Components\Select::make('state_id')
                            ->label('State')
                            ->options(fn(Get $get): Collection => State::query()
                                ->where('country_id', $get('country_id'))
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn(Set $set) => $set('city_id', null))
                            ->required(),
                        Forms\Components\Select::make('city_id')
                            ->label('City')
                            ->options(fn(Get $get): Collection => City::query()
                                ->where('state_id', $get('state_id'))
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('department_id')
                            ->label('Department')
                            ->relationship(
                                name: 'department',
                                titleAttribute: 'name',
                            )
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('team')
                                    ->label('Team')
                                    ->default($current_tenant->name)
                                    ->readonly(),
                                Hidden::make('team_id')
                                    ->default($current_tenant->id),
                                Hidden::make('user_id')
                                    ->default($logged_user->id)
                            ])
                            ->required(),
                        Hidden::make('user_id')
                            ->default($logged_user->id),
                    ])->columns(2),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        $is_admin = auth()->user()->is_admin;
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('middle_name')
                    ->label('Middle Name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('zip_code')
                    ->label('Zipcode')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Birth Day')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_hired')
                    ->label('Joined At')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Creator')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible($is_admin),
                Tables\Columns\TextColumn::make('country.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('department.name')
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
                SelectFilter::make('Department')
                    ->label('Filter by department')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->indicator('Department'),
                SelectFilter::make('Country')
                    ->label('Filter by country')
                    ->relationship('country', 'name')
                    ->searchable()
                    ->indicator('Country'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators[] = Indicator::make('Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString())
                                ->removeField('created_until');
                        }

                        return $indicators;
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
