<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $navigationGroup = "User Management";

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'email'
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Creator' => $record->createdBy->name
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return User::associatedUsers();
    }

    public static function getCount(): int
    {
        return self::getEloquentQuery()->count();
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
        $is_admin = $logged_user->is_admin;
        $current_tenant = Filament::getTenant();
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Verification Date')
                            ->default(now()->toDateTimeString())
                            ->timezone('Asia/Karachi')
                            ->visible($is_admin),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn(string $context): bool => $context === 'create')
                            ->dehydrated(fn($state) => filled($state))
                            ->maxLength(255)
                            ->revealable(),
                        ToggleButtons::make('is_admin')
                            ->label('Is Admin?')
                            ->boolean()
                            ->default(0)
                            ->inline()
                            ->visible($is_admin),
                        Forms\Components\TextInput::make('team_id')
                            ->label('Project')
                            ->hint('Active Project')
                            ->hintIcon('heroicon-m-exclamation-circle')
                            ->default($current_tenant->name)
                            ->hiddenOn('edit')
                            ->readOnly(),
                        Forms\Components\Hidden::make('team_id')
                            ->default($current_tenant->id),
                        Forms\Components\Hidden::make('created_by')
                            ->default($logged_user->id)
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
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Creator')
                    ->badge()
                    ->searchable()
                    ->visible($is_admin)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email_verified_at')
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->warning()
                            ->title('User Successfully Updated')
                            ->body('The user has been successfully updated.')
                    ),
                Tables\Actions\DeleteAction::make()
                    ->visible($is_admin)
                    ->successNotification(
                        Notification::make()
                            ->danger()
                            ->title('User Successfully Deleted')
                            ->body('The user has been successfully deleted.')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible($is_admin)
                        ->successNotification(
                            Notification::make()
                                ->danger()
                                ->title('Bulk Deletion Successful')
                                ->body('The bulk deletion has been successfully completed.')
                        ),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
