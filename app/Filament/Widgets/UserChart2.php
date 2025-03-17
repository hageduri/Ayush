<?php

namespace App\Filament\Widgets;

use App\Models\District;
use Filament\Widgets\ChartWidget;

class UserChart2 extends ChartWidget
{
    protected static ?string $heading = 'Chart';



    protected function getData(): array
    {
        // Fetch all districts with the count of associated facilities
        $districts = District::withCount('facility')->get();

        // Initialize labels (district names) and dataset (facility counts)
        $labels = [];
        $dataset = [];

        foreach ($districts as $district) {
            $labels[] = $district->district_name;  // Use district name as label
            $dataset[] = $district->facility_count;  // Use facility count as data
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Facilities',
                    'data' => $dataset,
                    'fill' => true,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)', // Optional: Customize the color
                    'borderColor' => [

                        'rgb(75, 192, 192)',

                      ],
                      'borderWidth'=> 1
                ],
            ],
            'labels' => $labels,




        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    public function getColumnSpan(): int|string|array
    {
        return 2; // Adjust as needed
    }
}
