@extends('admin.layouts.report')

@section('pagecss')
    <!-- vendor css -->
    <link href="{{ asset('lib/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">

    <style>
        .row-selected {
            background-color: #92b7da !important;
        }
    </style>
@endsection

@section('content')       
<div style="margin:0px 40px 200px 40px;font-family:Arial;">
    <h4 class="mg-b-0 tx-spacing--1">Sales Report</h4>
    <form action="{{route('report.sales.list')}}" method="get">
        <input type="hidden" name="act" value="go">
        @csrf
        <table>
            <tr>
                <td>Start</td>
                <td>End</td>
                <td>Category</td>
                <td>Product</td>
                <td>Customer</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><input type="date" class="form-control input-sm" name="startdate" autocomplete="off"></td>
                <td><input type="date" class="form-control input-sm" name="enddate" autocomplete="off"></td>
                <td>
                    <select name="category" id="category" class="form-control input-sm">
                        <option value="">Select</option>
                        @php
                            $categories = \App\EcommerceModel\ProductCategory::orderBy('name')->get();
                        @endphp
                        @forelse($categories as $c)
                            <option value="{{$c->id}}">{{$c->name}}</option>
                        @empty
                        @endforelse
                    </select>
                </td>
                <td>
                    <select name="product" id="product" class="form-control input-sm">
                        <option value="">Select</option>
                        @php
                            $products = \App\EcommerceModel\Product::orderBy('name')->get();
                        @endphp
                        @forelse($products as $p)
                            <option value="{{$p->id}}">{{$p->name}}</option>
                        @empty
                        @endforelse
                    </select>
                </td>
                <td>
                    <select name="customer" id="customer" class="form-control input-sm">
                        <option value="">Select</option>
                        @php
                            $customers = \App\EcommerceModel\SalesHeader::distinct()->orderBy('customer_name')->get(['customer_name']);
                        @endphp
                        @forelse($customers as $cu)
                            <option value="{{$cu->id}}">{{$cu->customer_name}}</option>
                        @empty
                        @endforelse
                    </select>
                </td>
                <td><button type="submit" class="btn btn-primary" style="margin:0px 0px 0px 20px;">Generate</button></td>
            </tr>
        </table>
    </form>
        

    @if($rs <> '')
        <br><br>
        <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
            <thead>
            <tr>
                <th align="left">Order#</th>
                <th align="left">Date</th>
                <th align="left">Customer</th>
                <th align="left">Product Code</th>
                <th align="left">Product Details</th>
                <th align="left">Category</th>
                <th align="left">Delivery Status</th>
                <th align="left">Price</th>
                <th align="left">Qty</th>
                <th align="left">Total Amount</th>
            </tr>
            </thead>
            <tbody>
                
            @php $o = ''; @endphp
            @forelse($rs as $r)
                <tr>
                    <td>{{$r->order_number}}</td>
                    <td>{{$r->hcreated}}</td>
                    <td>{{$r->customer_name}}</td>
                    <td>{{$r->code}}</td>
                    <td>{{$r->product_name}}</td>
                    <td>{{$r->catname}}</td>
                    <td>{{$r->delivery_status}}</td>
                    <td>{{number_format($r->price,2)}}</td>
                    <td>{{number_format($r->qty,2)}}</td>
                    <td>{{number_format($r->price * $r->qty,2)}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No report result.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endif
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/prismjs/prism.js') }}"></script>
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
@endsection

@section('customjs')
<script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.colVis.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            pageLength: 20,
            buttons: [
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {   
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    },
                    orientation : 'landscape',
                    pageSize : 'LEGAL'
                },
                'colvis'
            ],
            columnDefs: [ {
                targets: [],
                visible: false
            } ]
        } );
    } );
</script>
@endsection



