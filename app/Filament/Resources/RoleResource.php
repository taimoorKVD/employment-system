<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static bool $isScopedToTenant = false;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';

    protected static ?string $navigationLabel = 'Roles';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = "User Management";

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('Admin');
    }

    public static function getCount(): int
    {
        return parent::getEloquentQuery()->count();
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) self::getCount();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return self::getCount() > 10 ? 'success' : 'info';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Role Information')
                    ->description('Put the role information.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->minLength(2)
                            ->maxLength(255),
                        Forms\Components\Select::make('permissions')
                            ->label('Permissions')
                            ->multiple()
                            ->relationship('permissions', 'name')
                            ->preload()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('permissions.name')
                    ->label('Permissions')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d-m-Y')
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
