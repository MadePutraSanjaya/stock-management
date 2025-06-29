<?php

namespace App\Filament\PegawaiKeuangan\Widgets;

use Filament\Widgets\Widget;

class WelcomeBanner extends Widget
{
    protected static string $view = 'filament.widgets.welcome-banner';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 1;
}
