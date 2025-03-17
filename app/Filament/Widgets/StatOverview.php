<?php

namespace App\Filament\Widgets;

use App\Models\AhwcMember;
use App\Models\Facility;
use App\Models\Indicator;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class StatOverview extends BaseWidget
{

    protected function getStats(): array
    {
        $previousMonth = Carbon::now()->subMonth()->format('F-Y');

        // Get the count of approved indicators for the previous month
        $approvedCount = Indicator::where('status', 'approved')
            ->where('month', $previousMonth)
            ->count();

        $descriptionText = "Approved for $previousMonth";
        //making custom view for super admin and admin only. later can add more user
        if (Auth::user()->role==='SUPER'||Auth::user()->role==='ADMIN') {
            return [
            Stat::make('', new HtmlString('<span style = color:#4571BF; >'.Facility::count().'</span>'))
                ->description('Total Facilities')

                ->color('primary')
                ->descriptionIcon('heroicon-o-building-storefront', IconPosition::Before)
                // ->color(color:'success')
                // ->extraAttributes(['style' => 'background-color: #D8EFD3;',]),
                ->extraAttributes(['style' => 'background-color: #CAD9F9;',]),

            Stat::make('',new HtmlString('<span style = color:#C96B12; >'.$approvedCount.'/'.Facility::count().'</span>') )
                ->description($descriptionText)
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('warning')
                // ->extraAttributes(['style' => 'background-color: #555555;',]),
                ->extraAttributes(['style' => 'background-color: #F7E3CC;',]),



            Stat::make('', new HtmlString('<span style = color:#2D874C; >'.AhwcMember::count().'</span>'))
            ->description('AHWC Members')
            ->descriptionIcon('heroicon-s-user-group')
            ->color('success')
            ->extraAttributes(['style' => 'background-color: #BFEBDE;',]),


                ];
        }

        else{
            return[];
        }
    }
}
