<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Client;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Utilisateurs', User::count())
                ->description('Total des agents')
                ->descriptionIcon('heroicon-s-users')
                ->color('success'),

            Stat::make('Clients', Client::count())
                ->description('Total des membres enregistrés')
                ->descriptionIcon('heroicon-s-user-group')
                ->color('primary'),
        ];
    }
}
