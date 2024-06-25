<?php

namespace App\Filament\Pages\Tenancy;

use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\{Section, TextInput};
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;
use Illuminate\Support\Str;

class EditTeamProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Edit Project';
    }

    public static function canView(Model $tenant): bool
    {
        return auth()->user()->is_admin;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Team Information')
                    ->description('Put the team information.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->readOnly(),
                    ])->columns(2)
            ]);
    }
}
