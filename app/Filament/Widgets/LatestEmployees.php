<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestEmployees extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Latest Employees';

    public static function canView(): bool
    {
        return auth()->user()->hasPermissionTo('View Dashboard Employee Table');
    }

    public function table(Table $table): Table
    {
        $is_admin = auth()->user()->is_admin;
        return $table
            ->query(Employee::query())
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name'),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name'),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Creator')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible($is_admin),
                Tables\Columns\TextColumn::make('address')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('zip_code')
                    ->label('Zipcode')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_hired')
                    ->label('Joined At')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
