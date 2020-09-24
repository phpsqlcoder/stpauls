@extends('admin.layouts.app')

@section('pagetitle')
    Sales Transaction Manager
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <style>
        .row-selected {
            background-color: #92b7da !important;
        }
    </style>
@endsection

@section('content')

    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('sales-transaction.index')}}">Sales Transaction</a></li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Sales Transaction : Cash on Delivery</h4>
            </div>
        </div>

        <div class="row row-sm">

            <!-- Start Filters -->
            <div class="col-md-12">
                <div class="filter-buttons">
                    <div class="d-md-flex bd-highlight">
                        <div class="ml-auto bd-highlight mg-t-5">
                            <form class="form-inline" id="searchForm">
                                <div class="mg-b-10 mg-r-5">
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Receipt #" value="{{ $filter->search }}">
                                </div>
                                <div class="mg-b-8">
                                    <button class="btn btn-sm btn-info" type="button" id="btnSearch">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Filters -->


            <!-- Start Pages -->
            <div class="col-md-12">
                <div class="table-list mg-b-10">
                    <div class="table-responsive-lg">
                        <table class="table mg-b-0 table-light table-hover">
                            <thead>
                                <th>Order#</th>
                                <th>Customer</th>
                                <th>Payment Date</th>
                                <th>Amount</th>
                                <th>Paid Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($sales as $sale)
                                <tr>
                                    <td><strong>{{ $sale->order_number }}</strong></td>
                                    <td>{{ $sale->customer_name }}</td>
                                    <td>{{ $sale->payment_date }}</td>
                                    <td>
                                        @if(\App\EcommerceModel\SalesPayment::check_if_has_added_payments($sale->id) == 1)
                                            <a href="javascript:;" onclick="show_added_payments('{{$sale->id}}');">{{ number_format($sale->gross_amount,2) }}</a>
                                        @else
                                            {{ number_format($sale->gross_amount,2) }}
                                        @endif
                                    </td>
                                    <td>{{number_format(\App\EcommerceModel\SalesHeader::paid($sale->id),2)}}</td>
                                    <td>
                                        @if($sale->status == 'CANCELLED')
                                            <span class="badge badge-danger">CANCELLED</span>
                                        @else
                                            @if($sale->payment_status == 'UNPAID')
                                                <span class="badge badge-secondary">UNPAID</span>
                                            @else
                                                <span class="badge badge-success">PAID</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <nav class="nav table-options">
                                            <a class="nav-link" target="_blank" href="{{ route('sales-transaction.view',$sale->id) }}" title="View Sales Transaction"><i data-feather="eye"></i></a>
                                            
                                            @if($sale->status<>'CANCELLED')
                                                @if($sale->payment_status == 'UNPAID')
                                                    <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i data-feather="settings"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if (auth()->user()->has_access_to_route('payment.add.store'))
                                                            <a class="dropdown-item" href="javascript:;" onclick="addPayment('{{$sale->id}}','{{\App\EcommerceModel\SalesPayment::remaining_balance($sale->gross_amount,$sale->id)}}');">Add Payment</a>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endif
                                        </nav>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="8" style="text-align: center;"> <p class="text-danger">No payments found.</p></th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Pages -->
            <div class="col-md-6">
                <div class="mg-t-5">
                    @if ($sales->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $sales->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Payment modal -->
    <div class="modal effect-scale" id="prompt-add-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form autocomplete="off" action="{{ route('payment.add.store') }}" method="post">
                @csrf
                    <div class="modal-body">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="d-block">Mode of Payment *</label>
                                <input required readonly type="text" class="form-control" name="payment_type" value="Cash">
                                @hasError(['inputName' => 'payment_type'])@endhasError
                            </div>
                            <div class="form-group">
                                <label class="d-block">Payment Date *</label>
                                <input type="hidden" name="sales_header_id" id="sales_header_id">
                                <input required type="date" name="payment_dt" class="form-control" id="payment_dt" placeholder="Choose date" value="{{ old('date') }}">
                                @hasError(['inputName' => 'payment_dt'])@endhasError
                            </div>
                            <div class="form-group">
                                <label class="d-block">Amount *</label>
                                <input required type="number" step="0.01" value="0.00" class="form-control text-right" name="amount" id="payment_amount">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Remarks</label>
                                <textarea name="payment_remarks" class="form-control" id="payment_remarks" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal effect-scale" id="prompt-validate-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Payment Validation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{route('sales.validate-payment')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="cs-search">
                            <label class="d-block tx-semibold">Payment Details</label>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <input type="hidden" name="payment_id" id="pay_id">
                                            <th scope="row">Order #</th>
                                            <td id="orderno"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Reference #</th>
                                            <td id="refno"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Type</th>
                                            <td id="pay_type"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Date</th>
                                            <td id="pay_date"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Attachment</th>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Validate</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script>
        //var dateToday = new Date();
        $(function(){
            'use strict'

            $('#payment_dt').datepicker({
                //minDate: dateToday,
                dateFormat: 'yy-mm-dd',
            });
        });
    </script>

    <script>
        let listingUrl = "{{ route('sales-transaction-cash-on-delivery') }}";
        let searchType = "{{ $searchType }}";
    </script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>

        function validate_payment(id,orderno,refno,type,date){
            $('#prompt-validate-payment').modal('show');

            $('#pay_id').val(id);
            $('#orderno').html(orderno);
            $('#refno').html(refno);
            $('#pay_type').html(type);
            $('#pay_date').html(date);

        }

        function delete_sales(x,order_number){
            $('#frm_delete').attr('action',"{{route('sales-transaction.destroy',"x")}}");
            $('#id_delete').val(x);
            $('#delete_order_div').html(order_number);
            $('#prompt-delete').modal('show');
        }

        function addPayment(id,balance){
            $('#prompt-add-payment').modal('show');
            $('#sales_header_id').val(id);
            $("#payment_amount").attr({
                "max" : balance,
                "min" : balance
            });
        }

        function show_added_payments(id){
            $.ajax({
                type: "GET",
                url: "{{ route('display.added-payments') }}",
                data: { id : id },
                success: function( response ) {
                    $('#added_payments_tbl').html(response);
                    $('#prompt-show-added-payments').modal('show');
                }
            });
        }

        $(".js-range-slider").ionRangeSlider({
            grid: true,
            from: selected,
            values: perPage
        });

        



    </script>
@endsection
