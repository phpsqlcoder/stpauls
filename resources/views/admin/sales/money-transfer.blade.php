@extends('admin.layouts.app')

@section('pagetitle')
    Sales Transaction Manager
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
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
                                <th>Payment Type</th>
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
                                    <td><strong>{{ $sale->order_number }}</strong></td>
                                    <td>{{ date('Y-m-d',strtotime($sale->created_at)) }}</td>
                                    <td>{{ $sale->payment->payment_type }}</td>
                                    <td>{{ $sale->customer_name }}</td>
                                    <td>{{ number_format($sale->net_amount,2) }}</td>
                                    <td><a href="javascript:;" onclick="show_payment_details('{{$sale->id}}')"><strong>{{ $sale->delivery_status }} [{{$count}}]</strong></a>

                                    <td>{{ $sale->delivery_type }}</td>
                                    <td>
                                        <nav class="nav table-options">
                                            <a class="nav-link" target="_blank" href="{{ route('sales-transaction.view',$sale->id) }}" title="View Page"><i data-feather="eye"></i></a>

                                            @if($sale->payment_status == 'PAID')
                                            <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="settings"></i>
                                            </a>
                                            @endif
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="javascript:void(0);" onclick="change_delivery_status('{{$sale->id}}')" title="Update Delivery Status" data-id="{{$sale->id}}">Update Delivery Status</a>

                                                <a class="dropdown-item" href="javascript:void(0);" onclick="show_delivery_history('{{$sale->id}}')" title="Show Delivery History" data-id="{{$sale->id}}">Show Delivery History</a>
                                            </div>
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
                    <div class="table-responsive">
                        <table class="table table-bordered payment_details">
                            <thead>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Attachment</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody id="payment_details_tbl">

                            </tbody>
                        </table>
                    </div>
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
        let listingUrl = "{{ route('sales-transaction.money-transfer') }}";
        let searchType = "{{ $searchType }}";
    </script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
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
