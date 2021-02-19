<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BillRequest;
use App\Models\Bill;
use App\Models\Billitem;
use App\Models\Product;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BillCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BillCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Bill::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/bill');
        CRUD::setEntityNameStrings(__('sidebar.singular.bill'), __('sidebar.plural.bill'));
        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'date',
            'label' => 'تاريخ'
        ], false, function ($value) {
            $this->crud->addClause('whereDate', 'created_at', $value);
        });
        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'id',
            'label' =>  'كود'
        ], false, function ($value) {
            $this->crud->addClause('where', 'id', $value);
        });



    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        $this->crud->addColumn(['name' => 'id', 'type' => 'number', 'label' => 'كود']);
        $this->crud->addColumn(['name' => 'customername', 'type' => 'text', 'label' => 'أسم العميل']);
        $this->crud->addColumn(['name' => 'total', 'type' => 'number', 'label' => 'الإجمالي', 'wrapper' => ['class' => 'badge badge-primary text-white']]);
        $this->crud->addColumn(['name' => 'paid', 'type' => 'number', 'label' => 'المدفوع', 'wrapper' => ['class' => 'badge badge-success text-white']]);
        $this->crud->addColumn(['name' => 'rest', 'type' => 'number', 'label' => 'المتبقي', 'wrapper' => ['class' => 'badge badge-danger text-white']]);
        $this->crud->addColumn(['name' => 'deliverday', 'type' => 'number', 'label' => 'ايام التسيلم', 'wrapper' => ['class' => 'badge badge-dark text-white']]);
        $this->crud->addColumn(['name' => 'created_at', 'type' => 'datetime', 'label' => 'تاريخ الفاتورة']);
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(BillRequest::class);
        $this->crud->addField(['name' => 'customername', 'type' => 'text', 'label' => 'أسم العميل']);
        $this->crud->addField(['name' => 'total', 'type' => 'number', 'label' => 'الإجمالي']);
        $this->crud->addField(['name' => 'paid', 'type' => 'number', 'label' => 'المدفوع']);
        $this->crud->addField(['name' => 'rest', 'type' => 'number', 'label' => 'المتبقي']);
        $this->crud->addField(['name' => 'deliverday', 'type' => 'number', 'label' => 'ايام التسيلم']);
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }


    public function create()
    {

        $product = \App\Models\Product::all();
        return view('es.invoice', ['products' => $product->jsonSerialize()]);

    }

    public function store()
    {

        $invoice = new Bill();
        $invoice->customername = request()->customername;
        $invoice->total = request()->total;
        $invoice->paid = request()->paid;
        $invoice->rest = (request()->total - request()->paid);
        $invoice->rest = (request()->total - request()->paid);
        $invoice->deliverday = request()->deliverday;

        $invoice->save();

        $items = [];
        foreach (request()->billitems as $billitem) {
            $barcode = $billitem['product_no'];
            $productinfo = Product::query()->find($barcode);
            array_push($items, new Billitem([
                'productname' => $productinfo->name,
                'quantity' => $billitem['product_qty'],
                'price' => $productinfo->price,
                'total' => ($billitem['product_qty'] * $productinfo->price)
            ]));
        }
        $invoice->items()->saveMany($items);
        return response()->json(
            [
                'invoice_id' => $invoice->id,
                'invoiceUrl' => route('invoice.print', $invoice->id)

            ]);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {

        $invoice = \App\Models\Bill::query()->with('items')->findOrFail($id);

        return view('es.InvoicePrint',['invoice'=>$invoice , 'print'=>false]);
    }

}
