@extends('admin.layouts.app')

@section('pagetitle')
    Sales Transaction Manager
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <style>
        .payment_details td {
            padding: 10px;
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
                <h4 class="mg-b-0 tx-spacing--1">Sales Transaction : Credit/Debit</h4>
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
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($sales as $sale)
                                @php
                                    $qry = \App\EcommerceModel\SalesPayment::where('sales_header_id',$sale->id);
                                    $count = $qry->count();
                                    $payment = $qry->first();
                                @endphp
                                <tr>
                                    <td><strong>{{ $sale->order_number }} </strong></td>
                                    <td>{{ date('Y-m-d h:i A',strtotime($sale->created_at)) }}</td>
                                    <td>
                                        @if($sale->payment_status == 'PAID') 
                                            @if($count > 0)
                                                {{ $payment->payment_date }}
                                            @endif 
                                        @endif</td>
                                    <td>{{ $sale->customer_name }}</td>
                                    <td>{{ number_format($sale->net_amount,2) }}</td>
                                    <td>
                                        @if($count > 0 && $sale->status != 'CANCELLED')
                                            @if($payment->is_verify == 0)
                                                @if (auth()->user()->has_access_to_route('display.payment-details'))
                                                    <a href="javascript:;" onclick="show_payment_details('{{$sale->id}}')"><strong>{{ $sale->delivery_status }} [{{$count}}]</strong></a>
                                                @endif
                                            @else
                                                <span class="@if($sale->delivery_status == 'Waiting for Payment') tx-semibold tx-primary @endif">{{ $sale->delivery_status }}</span>
                                            @endif
                                        @else
                                            <span class="@if($sale->delivery_status == 'Shipping Fee Validation' || $sale->delivery_status == 'Waiting for Payment') tx-semibold tx-primary @endif">{{ $sale->delivery_status }}</span>
                                        @endif   
                                    </td>
                                    <td>{{ $sale->delivery_type }}</td>
                                    <td>
                                        <nav class="nav table-options">
                                            <a class="nav-link" target="_blank" href="{{ route('sales-transaction.view',$sale->id) }}" title="View Sales Details"><i data-feather="eye"></i></a>

                                            @if (auth()->user()->has_access_to_route('sales-transaction.delivery_status') || auth()->user()->has_access_to_route('display.delivery-history'))
                                                @if($sale->status != 'CANCELLED')
                                                    <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i data-feather="settings"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if($sale->payment_status == 'PAID')
                                                            @if (auth()->user()->has_access_to_route('sales-transaction.delivery_status'))
                                                                <a class="dropdown-item" href="javascript:void(0);" onclick="change_delivery_status('{{$sale->id}}')" title="Update Delivery Status" data-id="{{$sale->id}}">Update Delivery Status</a>
                                                            @endif

                                                            @if (auth()->user()->has_access_to_route('display.delivery-history'))
                                                                <a class="dropdown-item" href="javascript:void(0);" onclick="show_delivery_history('{{$sale->id}}')" title="Show Delivery History" data-id="{{$sale->id}}">Show Delivery History</a>
                                                            @endif
                                                        @endif

                                                        @if($sale->delivery_status != 'Delivered')
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="cancel_order('{{$sale->id}}')" title="Cancel Order">Cancel Order</a>
                                                        @endif
                                                    </div>
                                                @endif  
                                            @endif
                                        </nav>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="8" style="text-align: center;"> <p class="text-danger">No Sales Transaction found.</p></th>
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

    <div>
        <form style="display: none;" id="payment_form" method="post" action="{{route('sales.validate-payment')}}">
            @csrf
            <input type="text" name="payment_id" id="payment_id" value="">
            <input type="text" name="status" id="status" value="">
        </form>
    </div>

    <div class="modal effect-scale" id="prompt-show-payment-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Payment Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered payment_details" style="word-break: break-all;">
                        <thead>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Attachment</th>
                            <th>Amount</th>
                            <th>Book a Rider</th>
                            <th>Action</th>
                        </thead>
                        <tbody id="payment_details_tbl">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @include('admin.sales.modals.common')
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script>
        let listingUrl = "{{ route('sales-transaction.card-payment') }}";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
        function cancel_order(id){
            $('#orderid').val(id);
            $('#prompt-delete').modal('show');
        }

        function show_payment_details(id){
            var url = "{{ route('display.payment-details', ':id') }}";
                url = url.replace(':id',id);

            $.ajax({
                type: "GET",
                url: url,
                data: '',
                success: function( response ) {
                    $('#payment_details_tbl').html(response);
                    $('#prompt-show-payment-details').modal('show');
                }
            });
        }

        function approve_payment(id,status){
            if(status == 'APPROVE'){
                var text = 'approve';
                var btnColor = '#8CD4F5';
            } else {
                var text = 'reject'
                var btnColor = '#d33';
            }

            swal({
                title: '',
                text: "You are about to "+text+" this payment. Do you want to continue?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: btnColor,
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, '+text+' it!'            
            },
            function(isConfirm) {
                if (isConfirm) {
                    $('#payment_id').val(id);
                    $('#status').val(status);
                    $('#payment_form').submit();
                } 
                else {                    
                    swal.close();                   
                }
            });
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

    </script>
@endsection
