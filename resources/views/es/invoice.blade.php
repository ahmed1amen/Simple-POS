@extends(backpack_view('blank'))
@push('after_styles')
<style>

    .badge {
        font-size: 1.5rem;
    }
</style>

@endpush
@section('content')
    <div id="root">
                <div class="row justify-content-center animated bounceInLeft">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">الاصناف</h3>
                    </div>
                    <div class="card-body overflow-auto" style="max-height: 200px;">
                        @foreach ($products as $product)
                            <button id="{{$product['id']}}"
                                    style="direction: rtl; margin: 3px; font-family: cairo,serif; font-weight: 700;"
                                    class="btn animated  {{ (strpos($product['name'], 'كمبيوتر'))? 'btn-success' :'btn-danger'   }} btn-lg"
                                    @click="addNewRow">
                                {{$product['name']}}
                                <i class="fas fa-fw fa-plus-circle"></i>
                            </button>
                        @endforeach
                    </div>


                </div>
            </div>
        </div>


        <div class="row animated bounceInUp">
            <div class="col-12">
                <div class="card" style="font-family: 'Cairo', sans-serif;font-weight: 900;">
                    <div class="card-header">
                        <h3 class="card-title">تفاصيل الفاتورة</h3>
                    </div>
                    <div class="card-body table-responsive p-0 ">
                        <div class="row">
                            <div class="row col-sm-12 justify-content-center">

                                <div class="form-group " style="width: 609px;">

                                    <label class="form-check-label" for="cusname"> اسم العميل</label>

                                    <input name="cusname" id="cusname" type="text" placeholder="اسم العميل"
                                           v-model="customrename" class=" text-center form-control input-lg mw-50"
                                           style="    font-weight: 800;">

                                </div>



                            </div>

                            <div class="row col-sm-12 justify-content-center mb-3">


                                <div class="input-group col-md-3">


                                    <div class="input-group-append">
                                        <span class="input-group-text text-bold text-dark">موعد التسليم بعد </span>

                                    </div>

                                    <input min="1"  v-on:keyup="datacalc" v-model="deliverday" step="1" type="number" class="form-control text-bold text-center" >
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-bold text-dark">ايام</span>

                                    </div>

                                </div>
                                <span class="   badge badge-dark " v-text="deliverdate"> </span>



                            </div>

                            <div class="col-sm-12">

                                <table style="direction: rtl;" id="product_table"
                                       class="table table-bordered table-hover dataTable dtr-inline calculateclass"
                                       role="grid">
                                    <thead>
                                    <tr class="text-center" role="row">
                                        <th class="text-center" tabindex="0" rowspan="1"> الكود
                                        </th>
                                        <th class="text-center" tabindex="0">
                                            اسم الصنف
                                        </th>
                                        <th class="text-center" tabindex="0" rowspan="1">
                                            الكميه
                                        </th>
                                        <th class="text-center" tabindex="0" rowspan="1">
                                            السعر
                                        </th>
                                        <th class="text-center" tabindex="0" rowspan="1">
                                            الاجمالي
                                        </th>
                                        <th class="text-center" tabindex="0" rowspan="1">
                                            اجراء
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="product_Body">

                                    <tr class='text-center' role='row'
                                        v-for="(invoice_product, k) in invoice_products" :key="k">
                                        <td><span   v-text="invoice_product.product_no" id='td_barcode' class="text-lg-center badge badge-danger"> </span> </td>
                                        <td >
                                            <span  v-text="invoice_product.product_name" id='td_name' class="text-lg-center badge badge-primary"> </span>

                                        </td>
                                        <td><input  v-on:keyup="calculateLineTotal(invoice_product)"
                                                   v-model="invoice_product.product_qty" type='number'
                                                   class='text-center text-bold' id='td_quantity' value='1' min='0'
                                                   step='1'></td>
                                        <td>
                                            <span  v-text="invoice_product.product_price" id='td_price'  class="text-lg-center badge badge-info" ></span>
                                        </td>
                                        <td>
                                            <span  v-text="invoice_product.line_total" id='td_total'  class="text-lg-center badge badge-info" ></span>
                                        </td>
                                        <td><span class='text-center text-red'></span>
                                            <i style="cursor: pointer;" class="fas fa-plus fa-trash text-red"
                                               @click="deleteRow(k, invoice_product)"></i>

                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row float-right  overflow-hidden">
                            <div class="table-responsive">
                                <table class="table calculateclass overflow-hidden w-50">
                                    <tbody>

                                    <tr>
                                        <th>الاجمالي:</th>
                                        <td
                                            class="text-bold text-center">
                                            <span  style="font-size: 1.2rem;" v-text="invoice_total" id="invoce_totxal"  class="text-lg-center badge badge-primary">0</span>

                                        </td>
                                    </tr>

                                    <tr>
                                        <th>المدفوع:</th>
                                        <td class="">
                                            <input  v-on:keyup="calculateTotal" @change="calculateTotal"
                                                   v-model="invoice_paid" class="text-center text-bold"
                                                   id="invoce_paid" type="number" value="0"
                                                   min="0" step="1">

                                            <button @click="completedinvoice" id="complete"
                                                    class="btn btn-success btn-sm">

                                                <i class="fas fa-fw fa-check-circle"></i>

                                            </button>


                                        </td>
                                    </tr>


                                    <tr>
                                        <th>المتبقي:</th>
                                        <td class="text-bold text-center">

                                            <span  style="    font-size: 1.2rem;"  v-text="invoice_rest" id="invoce_Rest"   class="text-lg-center badge badge-danger">0</span>

                                        </td>

                                    </tr>


                                    <tr style="border: 3px solid black">
                                        <th>مدفوع من الزبون:</th>
                                        <td>
                                            <input
                                                   v-model="cutomer_paid" @keyup="calchelper" @change="calchelper"
                                                   class="text-center text-bold"
                                                   type="number" value="0"
                                                   min="0" step="1">
                                            <div class="row text-center justify-content-center">
                                                <label v-text="cutomer_rest" class="text-bold text-center">0</label>
                                            </div>

                                        </td>

                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>


        <div class="row text-center animated bounceInDown ">
            <div class="col-md-12" style="font-family: 'Cairo SemiBold',serif;">

                <button @click="resetdata" id="cleardata" class="btn btn-pill btn-danger btn-lg  float-left"
                        style="font-family: cairo, serif; font-weight: 700;">
                    <i class="fas fa-fw fa-trash"></i>
                    تفريغ

                </button>

                <button @click="storebill" id="btnFetch" class="btn btn-pill  btn-warning btn-lg"
                        style="font-family: cairo, serif; font-weight: 700;">

                    <i class="fas fa-fw fa-print"></i>
                    حفظ وطباعة
                </button>


            </div>
        </div>


        <iframe id="printf" class="mt-4" name="printf" style="display: block;width: 278px;height: 300px;"></iframe>
    </div>



@stop

@push('after_scripts')
    @include('sweetalert::alert')

@endpush

@push('after_scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

    <script>
        var _token = "{{ csrf_token() }}";

        var app = new Vue({
            el: '#root',
            data: {
                customrename: '',
                cutomer_paid: 0,
                cutomer_rest: 0,
                invoice_total: 0,
                invoice_paid: 0,
                invoice_rest: 0,
                deliverday: 1,
                deliverdate: '',
                invoice_products: [],
                products:@json($products)
            },
            methods: {
                datacalc(){
                    var date = new Date();

                    date.setDate(date.getDate() + parseInt( this.deliverday));

                    this.deliverdate=date.toLocaleDateString();
                },
                completedinvoice(){
                    this.invoice_paid = this.invoice_total
                    this.calculateTotal()
                },
                resetdata() {
                    this.invoice_products = [];
                    this.invoice_paid = 0;
                    this.calculateTotal();
                    this.customrename = "";
                    this.deliverday = 1;
                },
                storebill() {
                        if (this.customrename == '' || this.invoice_products.length == 0) {
                        swal.fire("خطأ!",

                            "<b>تأكد من ادخال</b>" +
                            "<ul style='direction: rtl; font-weight: 800; '>" +
                            "<li>اسم العميل</li>" +

                            "<li>اصناف الفاتورة</li>" +

                            "</ul>"

                            ,
                            "error");
                    } else {


                        let $this = $("#btnFetch");
                        $this.button('loading');
                        $this.prop("disabled", true);
                        $this.data('original-text', $this.html());
                        $this.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`);


                        $.ajax({
                            type: "post",
                            url: "{{route('bill.store')}}",
                            dataType: 'json',
                            'contentType': 'application/json',

                            data:
                                JSON.stringify({
                                    'customername': this.customrename,
                                    'total': this.invoice_total,
                                    'paid': this.invoice_paid,
                                    'reset': this.invoice_rest,
                                    'deliverday':this.deliverday,
                                    'billitems': this.invoice_products,
                                    '_token': _token
                                }),

                        })
                            .done(data => {
                                swal.fire("Invoice", "Invoice successfully Created!", "success");
                                let $this = $("#btnFetch");
                                $this.prop("disabled", false);
                                $this.html($this.data('original-text'));
                                this.resetdata();
                                $("#printf").attr("src", data.invoiceUrl);

                            })
                            .fail(function (data) {
                                swal.fire("Invoice !", "Make Sure From Your Data", "error");
                                let $this = $("#btnFetch");
                                $this.prop("disabled", false);
                                $this.html($this.data('original-text'));
                            });


                    }


                },
                calchelper() {


                    this.cutomer_rest = this.cutomer_paid - this.invoice_total
                },


                addNewRow(ele) {
                    var product = this.products.filter((item) => {
                        return item.id === parseInt(ele.currentTarget.getAttribute('id'))
                    });
                    if ((this.invoice_products.filter(d => d.product_no === product[0].id).length) != 0) {
                        var ar2 = this.invoice_products.filter(d => d.product_no === product[0].id).slice();
                        ar2[0].product_qty += 1;
                        ar2[0].line_total = (ar2[0].product_qty * ar2[0].product_price);
                    } else {

                        this.invoice_products.push({
                            product_no: product[0]['id'],
                            product_name: product[0]['name'],
                            product_price: product[0]['price'],
                            product_qty: 1,
                            line_total: product[0]['price'],
                        });
                    }
                    this.calculateTotal();

                },
                deleteRow(index, invoice_product) {
                    var idx = this.invoice_products.indexOf(invoice_product);
                    console.log(idx, index);
                    if (idx > -1) {
                        this.invoice_products.splice(idx, 1);
                    }
                    this.calculateTotal();
                },
                calculateLineTotal(invoice_product) {
                    var total = parseFloat(invoice_product.product_price) * parseFloat(invoice_product.product_qty);
                    if (!isNaN(total)) {
                        invoice_product.line_total = total;
                    }
                    this.calculateTotal();

                },
                calculateTotal() {
                    var total;
                    total = this.invoice_products.reduce(function (sum, product) {
                        var lineTotal = parseFloat(product.line_total);
                        if (!isNaN(lineTotal)) {
                            return sum + lineTotal;
                        }
                    }, 0);

                    total = parseFloat(total);
                    if (!isNaN(total)) {


                        this.invoice_total = total;
                        this.invoice_rest = (this.invoice_total - this.invoice_paid);

                    } else {
                        this.invoice_total = '0.00'
                    }
                },


            },
            mounted: function () {
                var date = new Date();

                date.setDate(date.getDate() + parseInt( this.deliverday));

                this.deliverdate=date.toLocaleDateString();
            }
                })
    </script>







@endpush
