<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\City;
use App\Models\State;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Tasks';

    protected static ?string $navigationGroup = 'Task Management';

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
                Forms\Components\Section::make('Basic Information')
                    ->description('Put all the task information correctly.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('details')
                            ->label('Details')
                            ->required(),
                    ]),
                Forms\Components\Section::make('Other Information')
                    ->description('Put all the other information correctly.')
                    ->schema([
                        Forms\Components\DatePicker::make('due_date')
                            ->label('Due Date')
                            ->minDate(now()->subYears(150))
                            ->required(),
                        Forms\Components\Select::make('user_id')
                            ->label('Assigned To')
                            ->relationship(
                                name: 'assignedTo',
                                titleAttribute: 'name'
                            )
                            ->options(User::associatedUsers()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ])->columns(2),
                Hidden::make('created_by')
                    ->default($logged_user->id),
                Hidden::make('team_id')
                    ->default($current_tenant->id),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label('Assigned To')
                    ->badge()
                    ->searchable(),
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

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->visible(fn ($record) => $record->created_by === Auth::id()),
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
