<?php

namespace App\Filament\Widgets;

use App\Models\District;
use App\Models\Indicator;
use Filament\Widgets\ChartWidget;

class UserChart extends ChartWidget
{
    protected static ?string $heading = 'Total Patient Cover';
    protected static string $color = 'success';

//     protected function getData(): array
// {
//     // Fetch all districts with facilities and indicators
//     $districts = District::with(['facility.indicator' => function ($query) {
//         $query->where('status', 'approved');

//         // Apply month filter if provided
//         if (request()->has('month')) {
//             $query->where('month', request()->get('month'));
//         }
//     }])->get();

//     // Initialize labels and dataset
//     $labels = [];
//     $dataset = [];

//     foreach ($districts as $district) {
//         $labels[] = $district->district_name;

//         // Sum up indicators for all facilities in the district
//         $totalIndicators = 0;
//         foreach ($district->facility as $facility) {
//             foreach ($facility->indicator as $indicator) {
//                 $totalIndicators += array_sum([
//                     $indicator->indicator_1,
//                     $indicator->indicator_2,
//                     $indicator->indicator_3,
//                     $indicator->indicator_4,
//                     $indicator->indicator_5,
//                     $indicator->indicator_6,
//                     $indicator->indicator_7,
//                     $indicator->indicator_8,
//                     $indicator->indicator_9,
//                     $indicator->indicator_10,
//                 ]);
//             }
//         }

//         $dataset[] = $totalIndicators;
//     }

//     return [
//         'labels' => $labels,
//         'datasets' => [
//             [
//                 'label' => 'Approved Indicators',
//                 'data' => $dataset,
//                 'backgroundColor' => '#4CAF50', // Optional: Customize bar color
//             ],
//         ],
//     ];
// }


    protected function getData(): array
    {
         // Fetch all approved records grouped by month
         $indicators = Indicator::where('status', 'approved')
         ->selectRaw('month, SUM(indicator_1 + indicator_2 + indicator_3 + indicator_4 + indicator_5 + indicator_6 + indicator_7 + indicator_8 + indicator_9 + indicator_10) as total')
         ->groupBy('month')
         ->orderBy('month') // Sorting by month (ensure 'monthname-year' is sorted correctly)
         ->get();

     // Initialize labels and dataset
     $labels = [];
     $dataset = [];

     // Define all possible months to show on the x-axis
     $allMonths = [
         'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
     ];

     // Initialize dataset with 0 values for all months
     foreach ($allMonths as $month) {
         $labels[] = $month;
         $dataset[] = 0;  // Default value for the month
     }

     // Update dataset with actual data
     foreach ($indicators as $indicator) {
         // Extract the month and year (in 'monthname-year' format)
         $month = \Carbon\Carbon::createFromFormat('F-Y', $indicator->month)->format('M'); // Convert 'monthname-year' to 'Jan', 'Feb', etc.
         $monthIndex = array_search($month, $allMonths); // Find the index of the month

         if ($monthIndex !== false) {
             $dataset[$monthIndex] = $indicator->total; // Update the dataset for that month
         }
     }



        return [
            'datasets' => [
                [
                    'label' => 'Approved Indicators',
                    'data' => $dataset,
                    'fill' => true,
                ],
            ],
            'labels' => $labels, // Use the defined months as labels

        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    public function getColumnSpan(): int|string|array
    {
        return 2; // Spans 2 columns in the layout grid
    }
}
