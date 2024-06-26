<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestDepartments extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Latest Departments';

    public static function canView(): bool
    {
        return auth()->user()->hasPermissionTo('View Dashboard Department Table');
    }

    public function table(Table $table): Table
    {
        $is_admin = auth()->user()->is_admin;
        return $table
            ->query(Department::query())
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('employees_count')
                    ->label('Total Employee')
                    ->counts('employees'),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Creator')
                    ->badge()
                    ->visible($is_admin),
            ]);
    }
}
