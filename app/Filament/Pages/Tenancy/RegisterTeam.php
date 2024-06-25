<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\{Components\Section, Components\TextInput, Form};
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Str;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Add Project';
    }

    public static function getSlug(): string
    {
        return 'create-new-project';
    }

    public static function canView(): bool
    {
        return auth()->user()->is_admin;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Project Information')
                    ->description('Put the project information.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('slug', Str::slug($state));
                            })
                            ->unique(),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->readOnly(),
                    ])
            ]);
    }

    protected function handleRegistration(array $data): Team
    {
        $team = Team::create($data);

        $team->members()->attach(auth()->user());

        return $team;
    }
}
