<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.


Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('bill', 'BillCrudController');
    Route::crud('billitem', 'BillitemCrudController');
    Route::crud('product', 'ProductCrudController');
    Route::view('/test', 'welcome');



    Route::get('/inv',
        function (){
            $products = \App\Models\Product::all()->take(50);
            return view('es.invoice',['products'=>$products->jsonSerialize()]);});


    Route::get('/invoice/all',
        function (){
            $invoices = \App\Models\Bill::all()->take(50);
            return view('es.invoiceall',['invoices'=>$invoices->jsonSerialize()])
                ;});

    Route::get('/product/all',
        function (){
            $products = \App\Models\Product::all()->take(50);
            return view('es.productall',['products'=>$products->jsonSerialize()])

                ;});




    Route::get('/invoice/print/{id}',
        function ($id){

            $invoice = \App\Models\Bill::query()->with('items')->findOrFail($id);

            return view('es.InvoicePrint',['invoice'=>$invoice , 'print'=>true]);}

            )->name('invoice.print');

    Route::get('/invoice/{id}',
        function ($id){

            $invoice = \App\Models\Bill::query()->with('items')->findOrFail($id);

            return view('es.InvoicePrint',['invoice'=>$invoice , 'print'=>false]);});

    Route::get('/invoice',
        function (){
            $product = \App\Models\Product::all();
            return view('es.invoice',['products'=>$product->jsonSerialize()]);});



    Route::get('charts/weekly-users', 'Charts\WeeklyUsersChartController@response')->name('charts.weekly-users.index');
}); // this should be the absolute last line of this file
