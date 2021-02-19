<?php

namespace App\Http\Controllers\Admin\Charts;

use App\Models\Bill;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\ChartController;
use Carbon\Carbon;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Doctrine\DBAL\Tools\Dumper;

/**
 * Class WeeklyUsersChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WeeklyUsersChartController extends ChartController
{

    public function setup()
    {
        $this->chart = new Chart();

        $sales_created_today = Bill::whereDate('created_at' ,today())->select(['created_at'])->get();
//        // MANDATORY. Set the labels for the dataset points
        $labels = [];


        foreach ($sales_created_today as $sale) {
            $labels[] = $sale->created_at->format('H:i:s A');

        }

        $this->chart->labels( $labels );
        // RECOMMENDED.
        // Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/weekly-users'));

        // OPTIONAL.
        $this->chart->minimalist(false);
        $this->chart->displayLegend(true);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    public function data()
    {
        $sales_created_today = Bill::whereDate('created_at', today())->select(['paid'])->get()->pluck('paid');
        $this->chart->dataset('مبيعات اليوم', 'line',
            $sales_created_today)
            ->color('rgba(0,0,0,1)')
            ->backgroundColor('rgba(0,0,0,0)')
                ->options(['borderWidth'=>4,'lineTension'=>0.2]);



    }
}
