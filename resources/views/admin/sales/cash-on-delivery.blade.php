@extends('admin.layouts.app')

@section('pagetitle')
    Sales Transaction Manager
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
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
                <div class="filter-buttons mg-b-10">
                    <div class="d-md-flex bd-highlight">
                        <div class="bd-highlight mg-r-10 mg-t-10">
                            <div class="dropdown d-inline mg-r-5">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Filters
                                </button>
                                <div class="dropdown-menu">
                                    <form id="filterForm" class="pd-20">
                                        <div class="form-group">
                                            <label for="exampleDropdownFormEmail1">{{__('common.sort_by')}}</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy1" name="orderBy" class="custom-control-input" value="created_at" @if ($filter->orderBy == 'created_at') checked @endif>
                                                <label class="custom-control-label" for="orderBy1">Order Date</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="order_number" @if ($filter->orderBy == 'order_number') checked @endif>
                                                <label class="custom-control-label" for="orderBy2">Order Number</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleDropdownFormEmail1">{{__('common.sort_order')}}</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="sortByAsc" name="sortBy" class="custom-control-input" value="asc" @if ($filter->sortBy == 'asc') checked @endif>
                                                <label class="custom-control-label" for="sortByAsc">{{__('common.ascending')}}</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="sortByDesc" name="sortBy" class="custom-control-input" value="desc"  @if ($filter->sortBy == 'desc') checked @endif>
                                                <label class="custom-control-label" for="sortByDesc">{{__('common.descending')}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group mg-b-40">
                                            <label class="d-block">{{__('common.item_displayed')}}</label>
                                            <input id="displaySize" type="text" class="js-range-slider" name="perPage" value="{{ $filter->perPage }}"/>
                                        </div>
                                        <button id="filter" type="button" class="btn btn-sm btn-primary">{{__('common.apply_filters')}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="ml-auto bd-highlight mg-t-10 mg-r-10">
                            <form class="form-inline" id="searchForm">
                                <div class="search-form mg-r-10">
                                    <input style="width: 280px;" name="search" type="search" id="search" class="form-control"  placeholder="Search by Order # and Customer Name" value="{{ $filter->search }}">
                                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
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
                                <th>Payment Date</th>
                                <th>Customer Name</th>
                                <th>Total Amount</th>
                                <th>Order Status</th>
                                <th>Delivery Type</th>
                                <th width="8%">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($sales as $sale)
                                @php
                                    $payment = \App\EcommerceModel\SalesPayment::where('sales_header_id',$sale->id)->first();
                                @endphp
                                <tr>
                                    <td><strong>{{ $sale->order_number }}</strong></td>
                                    <td>{{ date('Y-m-d',strtotime($sale->created_at)) }}</td>
                                    <td>@if($sale->payment_status == 'PAID') {{$payment->payment_date}} @endif</td>
                                    <td>{{ $sale->customer_name }}</td>
                                    <td>{{ number_format($sale->net_amount,2) }}</td>
                                    <td>
                                        @if($sale->delivery_status == 'Waiting for Approval')
                                            <a href="{{ route('sales-transaction.view',$sale->id) }}" class="tx-semibold tx-danger">{{$sale->delivery_status}}</a>
                                        @else
                                            <span class="@if($sale->delivery_status == 'Shipping Fee Validation') tx-semibold tx-primary @endif">{{ $sale->delivery_status }}</span>
                                        @endif
                                        
                                    </td>
                                    <td>{{ $sale->delivery_type }}</td>
                                    <td>
                                        <nav class="nav table-options">
                                            
                                            <a class="nav-link" target="_blank" href="{{ route('sales-transaction.view',$sale->id) }}" title="View Sales Details"><i data-feather="eye"></i></a>

                                            @if($sale->status != 'CANCELLED')
                                                @if($sale->is_approve == 1)
                                                <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i data-feather="settings"></i>
                                                </a>
                                                @endif

                                                @if (auth()->user()->has_access_to_route('payment.add.store') || auth()->user()->has_access_to_route('sales-transaction.delivery_status') || auth()->user()->has_access_to_route('display.delivery-history'))
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if($sale->delivery_status != 'Waiting for Approval')
                                                        @if($sale->payment_status == 'UNPAID')
                                                            @if (auth()->user()->has_access_to_route('payment.add.store'))
                                                                <a class="dropdown-item" href="javascript:;" onclick="addPayment('{{$sale->id}}','{{$sale->net_amount}}');">Add Payment</a>
                                                            @endif
                                                        @endif

                                                        @if (auth()->user()->has_access_to_route('sales-transaction.delivery_status'))
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="change_delivery_status('{{$sale->id}}')" title="Update Delivery Status" data-id="{{$sale->id}}">Update Delivery Status</a>
                                                        @endif

                                                        @if (auth()->user()->has_access_to_route('display.delivery-history'))
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="show_delivery_history('{{$sale->id}}')" title="Show Delivery History" data-id="{{$sale->id}}">Show Delivery History</a>
                                                        @endif
                                                    @endif
                                                </div>
                                                @endif
                                            @endif
                                        </nav>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="9" style="text-align: center;"> <p class="text-danger">No Sales Transaction Found.</p></th>
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
            var topay = parseFloat(amount);
            $('#prompt-add-payment').modal('show');
            $('#sales_header_id').val(id);
            $("#payment_amount").val(topay.toFixed(2));
            $("#payment_amount").attr({
                "max" : amount,
                "min" : amount
            });
        }
    </script>
@endsection
