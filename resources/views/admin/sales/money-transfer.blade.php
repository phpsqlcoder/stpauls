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
                <h4 class="mg-b-0 tx-spacing--1">Sales Transaction : Money Transfer</h4>
            </div>
        </div>

        <div class="row row-sm">

            <!-- Start Filters -->
            <div class="col-md-12">
                <div class="filter-buttons">
                    <div class="d-md-flex bd-highlight">
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
                                <th>Receipt</th>
                                <th>Payment Type</th>
                                <th>Payment Date</th>
                                <th>Order Number</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Delivery Status</th>
                                <th>Status</th>
                                <th>Attachment</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($sales as $sale)
                                <tr>
                                    <td><strong>{{ $sale->receipt_number }}</strong></td>
                                    <td>{{ $sale->payment_type }}</td>
                                    <td>{{ $sale->payment_date }}</td>
                                    <td>{{ $sale->order_number }}</td>
                                    <td>{{ number_format($sale->amount,2) }}</td>
                                    <td>{{ $sale->payment_status }}</td>
                                    <td>{{ $sale->delivery_status }}</td>
                                    <td>
                                        @if($sale->is_verify == 0)
                                            <span class="badge badge-secondary">PENDING</span>
                                        @else
                                            <span class="badge badge-success">VALIDATED</span>
                                        @endif
                                    </td>
                                    <td><a target="_blank" href="{{ asset('storage/payments/'.$sale->id.'/'.$sale->attachment) }}">{{ $sale->attachment }}</a></td>
                                    <td>
                                        <nav class="nav table-options">
                                            @if($sale->is_verify == 0)
                                            <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="settings"></i>
                                            </a>
                                            @endif
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if($sale->is_verify == 0)
                                                    <a class="dropdown-item" href="javascript:void(0)" title="Confirm Payment" onclick="validate_payment('{{$sale->id}}')">Confirm Payment</a>
                                                @endif
                                                <a class="dropdown-item" href="javascript:void(0);" onclick="change_delivery_status('{{$sale->id}}')" title="Update Delivery Status" data-id="{{$sale->id}}">Update Delivery Status</a>

                                                <a class="dropdown-item" href="javascript:void(0);" onclick="show_delivery_history('{{$sale->id}}')" title="Update Delivery Status" data-id="{{$sale->id}}">Show Delivery History</a>
                                            </div>
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
                        <input type="hidden" name="payment_id" id="pay_id">
                        You are about to validate this payment. Do you want to continue?
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Yes, Validate</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.sales.modal')
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
        let listingUrl = "{{ route('sales-transaction-money-transfer') }}";
        let searchType = "{{ $searchType }}";
    </script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>

        function validate_payment(id){
            $('#prompt-validate-payment').modal('show');

            $('#pay_id').val(id);

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

        



    </script>
@endsection
