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
                <h4 class="mg-b-0 tx-spacing--1">Sales Transaction Manager</h4>
            </div>
        </div>

        <div class="row row-sm">

            <!-- Start Filters -->
            <div class="col-md-12">
                <div class="filter-buttons">
                    <div class="d-md-flex bd-highlight">
                        <div class="bd-highlight mg-r-10 mg-t-10">
                            <div class="dropdown d-inline mg-r-5">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{__('common.filters')}}
                                </button>
                                <div class="dropdown-menu">
                                    <form id="filterForm" class="pd-20">
                                        <div class="form-group">
                                            <label for="exampleDropdownFormEmail1">{{__('common.sort_by')}}</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy1" name="orderBy" class="custom-control-input" value="order_number" @if ($filter->orderBy == 'order_number') checked @endif>
                                                <label class="custom-control-label" for="orderBy1">Order Number</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="customer_name" @if ($filter->orderBy == 'customer_name') checked @endif>
                                                <label class="custom-control-label" for="orderBy2">Customer Name</label>
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
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="showDeleted" name="showDeleted" class="custom-control-input" @if ($filter->showDeleted) checked @endif>
                                                <label class="custom-control-label" for="showDeleted">{{__('common.show_deleted')}}</label>
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

                        <div class="ml-auto bd-highlight mg-t-10">
                            <form class="form-inline" id="searchForm">

                                <div class="mg-b-10 mg-r-5">Start:
                                    <input name="startdate" type="date" id="startdate" class="form-control"
                                    value="@if(isset($_GET['startdate'])) {{ date('m-d-Y',strtotime($_GET['startdate'])) }} @endif">
                                </div>
                                <div class="mg-b-10 mg-r-5">End:
                                    <input name="enddate" type="date" id="enddate" class="form-control"
                                    value="@if(isset($_GET['enddate'])) {{ date('m-d-Y',strtotime($_GET['enddate'])) }} @endif">
                                </div>

                                <div class="mg-b-10 mg-r-5">
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Order #" value="{{ $filter->search }}">
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
                            <tr>
                                <th style="width: 10%;display:none;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                        <label class="custom-control-label" for="checkbox_all"></label>
                                    </div>
                                </th>
                                <th>Order Number</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Payment Status</th>
                                <th>Delivery Status</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($sales as $sale)

                                <tr>
                                    <td style="display:none;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input cb" id="cb{{ $sale->id }}">
                                            <label class="custom-control-label" for="cb{{ $sale->id }}"></label>
                                        </div>
                                    </td>
                                    <td> <strong @if($sale->trashed()) style="text-decoration:line-through;" @endif> {{$sale->order_number }}<br></strong></td>
                                    <td>{{ $sale->customer_name }}</td>
                                    <td>{{ $sale->created_at }}</td>
                                    <td>{{ $sale->Paymentstatus }}</td>
                                    <td><a href="{{route('admin.report.delivery_report',$sale->id)}}" target="_blank">{{$sale->delivery_status}}</a></td>
                                    <td>
                                        @if(\App\EcommerceModel\SalesPayment::check_if_has_added_payments($sale->id) == 1)
                                            <a href="javascript:;" onclick="show_added_payments('{{$sale->id}}');">{{ number_format($sale->net_amount,2) }}</a>
                                        @else
                                            {{ number_format($sale->net_amount,2) }}
                                        @endif
                                    </td>
                                    <td>
                                        <nav class="nav table-options">
                                            @if($sale->trashed())
                                                <nav class="nav table-options">
                                                    <a class="nav-link" href="{{route('sales-transaction.restore',$sale->id)}}" title="Restore this Sales Transaction"><i data-feather="rotate-ccw"></i></a>
                                                </nav>
                                            @else

                                                <a class="nav-link" target="_blank" href="{{ route('sales-transaction.view',$sale->id) }}" title="View Page"><i data-feather="eye"></i></a>
                                                <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i data-feather="settings"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if($sale->status == 'UNPAID')
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#prompt-change-status" title="Update Sales Transaction" data-id="{{$sale->id}}" data-status="PAID">Paid</a>
                                                    @else
                                                    @endif

                                                    @if($sale->status<>'CANCELLED')
                                                        @if (auth()->user()->has_access_to_route('sales-transaction.delivery_status'))
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="change_delivery_status({{$sale->id}})" title="Update Delivery Status" data-id="{{$sale->id}}">Update Delivery Status</a>
                                                            @if($sale->delivery_type == 'Door 2 Door Delivery' && $sale->delivery_fee_amount == 0)
                                                            <a class="dropdown-item" href="javascript:;" onclick="updateDelveryFee('{{$sale->id}}');">Update Delivery Fee</a>
                                                            @endif
                                                        @endif
                                                    @endif

                                                    <a class="dropdown-item" href="javascript:void(0);" onclick="show_delivery_history({{$sale->id}})" title="Update Delivery Status" data-id="{{$sale->id}}">Show Delivery History</a>
                                                    <a class="dropdown-item" target="_blank" href="{{ route('sales-transaction.view_payment',$sale->id) }}" title="Show payment" data-id="{{$sale->id}}">Sales Payment</a>

                                                    {{--@if(\App\EcommerceModel\SalesPayment::check_if_has_remaining_balance($sale->gross_amount,$sale->id) == 1)--}}
                                                        @if($sale->status<>'CANCELLED')
                                                            @if (auth()->user()->has_access_to_route('payment.add.store'))
                                                                <a class="dropdown-item" href="javascript:;" onclick="addPayment('{{$sale->id}}','{{\App\EcommerceModel\SalesPayment::remaining_balance($sale->gross_amount,$sale->id)}}');">Add Payment</a>
                                                            @endif
                                                        @endif
                                                    {{--@endif--}}

                                                    @if($sale->status<>'CANCELLED')
                                                        @if (auth()->user()->has_access_to_route('sales-transaction.destroy'))
                                                            <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="delete_sales({{$sale->id}},'{{$sale->order_number}}')" title="Cancel Transaction">Cancel</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endif
                                        </nav>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="7" style="text-align: center;"> <p class="text-danger">No Sales Transaction found.</p></th>
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

    <form action="" id="posting_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="pages" name="pages">
        <input type="text" id="status" name="status">
    </form>

    @include('admin.sales.modal')

    <div class="modal effect-scale" id="prompt-multiple-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.delete_mutiple_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{__('common.delete_mutiple_confirmation')}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnDeleteMultiple">Yes, Delete</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-no-selected" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.no_selected_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{__('common.no_selected')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
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
        let listingUrl = "{{ route('sales-transaction.index') }}";
        let searchType = "{{ $searchType }}";
    </script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>

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
                "max" : balance
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

        function updateDelveryFee(id)
        {
            $('#prompt-update-deliveryfee').modal('show');
            $('#salesid').val(id);
        }

        function post_form(id,status,pages){

            $('#posting_form').attr('action',id);
            $('#pages').val(pages);
            $('#status').val(status);
            $('#posting_form').submit();
        }

        $(".js-range-slider").ionRangeSlider({
            grid: true,
            from: selected,
            values: perPage
        });

        /*** Handles the Select All Checkbox ***/
        $("#checkbox_all").click(function(){
            $('.cb').not(this).prop('checked', this.checked);
        });

        $('#prompt-change-status').on('show.bs.modal', function (e) {
            //get data-id attribute of the clicked element
            let sales = e.relatedTarget;
            let salesId = $(sales).data('id');
            let salesStatus = $(sales).data('status');
            let formAction = "{{ route('sales-transaction.quick_update', 0) }}".split('/');
            formAction.pop();
            let editFormAction = formAction.join('/') + "/" + salesId;
            $('#editForm').attr('action', editFormAction);
            $('#id').val(salesId);
            $('#editStatus').val(salesStatus);

        });

        function change_delivery_status(id){
            $('#prompt-change-delivery-status').modal('show');
            $('#del_id').val(id);
            // $('#btnChangeDeliveryStatus').on('click', function() {
            //     let sales = $('#delivery_status').val();
            //     post_form("{{route('sales-transaction.delivery_status')}}",sales,id)
            // });
        }



    </script>
@endsection
