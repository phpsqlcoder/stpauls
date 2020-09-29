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
                                <th>Order #</th>
                                <th>Order Date</th>
                                <th>Date & Time Needed</th>
                                <th>Payment Date</th>
                                <th>Customer Name</th>
                                <th>Total Amount</th>
                                <th>Order Status</th>
                                <th>Delivery Status</th>
                                <th>Delivery Type</th>
                                <th width="8%">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($sales as $sale)
                                @php
                                    $payment = \App\EcommerceModel\SalesPayment::where('sales_header_id',$sale->id)->first();
                                    $date_needed = $sale->pickup_date.' '.$sale->pickup_time;
                                @endphp
                                <tr>
                                    <td><strong>{{ $sale->order_number }}</strong></td>
                                    <td>{{ date('Y-m-d h:i A',strtotime($sale->created_at)) }}</td>
                                    <td>{{ date('Y-m-d h:i A',strtotime($date_needed)) }}</td>
                                    <td>@if($sale->payment_status == 'PAID') {{$payment->payment_date}} @endif</td>
                                    <td>{{ $sale->customer_name }}</td>
                                    <td>{{ number_format($sale->net_amount,2) }}</td>
                                    <td>
                                        @if($sale->status == 'CANCELLED')
                                            CANCELLED
                                        @else
                                            {{ $sale->status }}
                                        @endif
                                    </td>
                                    <td><a href="{{route('admin.report.delivery_report',$sale->id)}}" target="_blank">{{ $sale->delivery_status }}</a></td>
                                    <td>{{ $sale->delivery_type }}</td>
                                    <td>
                                        <nav class="nav table-options">
                                            @if($sale->status != 'CANCELLED')
                                            <a class="nav-link" target="_blank" href="{{ route('sales-transaction.view',$sale->id) }}" title="View Sales Transaction"><i data-feather="eye"></i></a>
                                            @endif
                                            
                                            @if($sale->status != 'CANCELLED')
                                                <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i data-feather="settings"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if($sale->is_approve == 0)

                                                    <a class="dropdown-item" href="javascript:;" onclick="order_response('{{$sale->id}}','{{$sale->order_number}}','APPROVE');">Approve Order</a>

                                                    <a class="dropdown-item" href="javascript:;" onclick="order_response('{{$sale->id}}','{{$sale->order_number}}','REJECT');">Reject Order</a>
                                                    @endif

                                                    @if($sale->delivery_status != 'Waiting for Approval')
                                                        @if($sale->payment_status == 'UNPAID')
                                                        <a class="dropdown-item" href="javascript:;" onclick="addPayment('{{$sale->id}}','{{$sale->net_amount}}');">Add Payment</a>
                                                        @endif
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="change_delivery_status({{$sale->id}})" title="Update Delivery Status" data-id="{{$sale->id}}">Update Delivery Status</a>

                                                    <a class="dropdown-item" href="javascript:void(0);" onclick="show_delivery_history({{$sale->id}})" title="Show Delivery History" data-id="{{$sale->id}}">Show Delivery History</a>
                                                    @endif
                                                    
                                                </div>
                                            @endif
                                        </nav>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="9" style="text-align: center;"> <p class="text-danger">No payments found.</p></th>
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

    @include('admin.sales.modals.cash-on-delivery')
    @include('admin.sales.modals.common')

    <div class="modal effect-scale" id="prompt-add-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal" role="document">
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
                            <input type="hidden" name="pamenty_mode" value="Cash">
                            <div class="form-group">
                                <input type="hidden" id="sales_header_id" name="sales_header_id">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Payment Date *</label>
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
        let listingUrl = "{{ route('sales-transaction.cash-on-delivery') }}";
        let searchType = "{{ $searchType }}";
    </script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>

        function order_response(id,order,status){
            if(status == 'APPROVE'){
                $('#id_approve').val(id);
                $('#span_approve_order').html(order);
                $('#prompt-approve-order').modal('show');
            } else {
                $('#id_reject').val(id);
                $('#span_reject_order').html(order);
                $('#prompt-reject-order').modal('show');
            }
        }

        function change_delivery_status(id){
            $('#prompt-change-delivery-status').modal('show');
            $('#del_id').val(id);
        }

        function show_delivery_history(id){
            $.ajax({
                type: "GET",
                url: "{{ route('display.delivery-history') }}",
                data: { id : id },
                success: function( response ) {
                    $('#delivery_history_tbl').html(response);
                    $('#prompt-show-delivery-history').modal('show');
                }
            });
        }

        function addPayment(id,amount){
            $('#prompt-add-payment').modal('show');
            $('#sales_header_id').val(id);
            $("#payment_amount").attr({
                "max" : amount,
                "min" : amount
            });
        }









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
