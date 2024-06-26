<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsAdminOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user()->hasPermissionTo('View Dashboard Modules Counter');
    }

    protected function getStats(): array
    {
        $employeeCount = Employee::Count();
        $departmentCount = Department::Count();
        return [
            Stat::make('Users', User::associatedUsers()->count())
                ->description('All users from the database')
                ->descriptionIcon('heroicon-m-users')
                //->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),
            Stat::make('Employees', $employeeCount)
                ->description('All employees from the database')
                ->descriptionIcon($employeeCount > 3 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($employeeCount > 3 ? 'success' : 'danger'),
            Stat::make('Departments', $departmentCount)
                ->description('All departments from the database')
                ->descriptionIcon($departmentCount > 3 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($departmentCount > 3 ? 'success' : 'warning'),
        ];
    }
}
