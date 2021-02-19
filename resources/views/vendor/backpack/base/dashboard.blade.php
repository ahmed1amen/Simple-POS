@extends(backpack_view('blank'))

@php


    $widgets['before_content'][] =[
        'type'       => 'chart',
        'controller' => \App\Http\Controllers\Admin\Charts\WeeklyUsersChartController::class,

        // OPTIONALS

         'class'   => 'card mb-2',
         'wrapper' => ['class'=> 'col-md-12 col-12'] ,
         'content' => [
              'header' => 'التقارير',
              'body'   => 'احصائيات',
         ]
    ];


$today = \App\Models\Bill::query()->whereDate('created_at',today());
$total =$today->sum('total');
$total_paid =$today->sum('paid');
$total_rest = $total-$total_paid ;

$percentage_paid = 0;
$percentage_total =  0;

if (!($total== 0 || $total_paid == 0))
    {
$percentage_paid =  ceil($total_paid/$total *100)  ;
$percentage_total =  ceil($total_rest/$total_paid *100)  ;

    }


$widgets['before_content'][]=[
    'type'    => 'div',
    'class'   => 'row',
    'content' => [ // widgets
      [
    'type'        => 'progress',
    'class'       => 'card text-white bg-dark mb-2',
    'description' => 'إجمالي الفواتير',
    'value'       => $today->sum('total') . ' جنية',
    'progress'    => $percentage_paid   // integer
    ],
         [
    'type'        => 'progress',
    'class'       => 'card text-white bg-success mb-2',
    'description' => 'إجمالي المدفوع',
    'value'       => $today->sum('paid') . ' جنية',
    'progress'    => $percentage_paid == 0 ? 100 :$percentage_paid, // integer
    ],      [
    'type'        => 'progress',
    'class'       => 'card text-white bg-danger mb-2',
    'description' => 'إجمالي المتبقي',
    'value'       => $total_rest . ' جنية',
    'progress'    => $percentage_total   , // integer
    ],







    ]
];
@endphp

@section('content')
@endsection
