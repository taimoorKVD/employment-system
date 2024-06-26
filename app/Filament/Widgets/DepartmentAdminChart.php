<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DepartmentAdminChart extends ChartWidget
{
    protected static ?string $heading = 'Department Registration Stats';

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return auth()->user()->hasPermissionTo('View Dashboard Department Chart');
    }

    protected function getData(): array
    {
        $data = Trend::model(Department::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Department Registered',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
