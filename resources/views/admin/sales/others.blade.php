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
                <h4 class="mg-b-0 tx-spacing--1">Sales Transaction : Others</h4>
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
                                <th>Customer Name</th>
                                <th>Payment Type</th>
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
                                    <td>{{ $sale->customer_name }}</td>
                                    <td>{{ \App\EcommerceModel\SalesHeader::payment_type($sale->id) }}</td>
                                    <td>{{ number_format($sale->net_amount,2) }}</td>
                                    <td>{{ $sale->delivery_status }}</td>
                                    <td>{{ $sale->delivery_type }}</td>
                                    <td>
                                        <nav class="nav table-options">
                                            <a class="nav-link" target="_blank" href="{{ route('sales-transaction.view',$sale->id) }}" title="View Page"><i data-feather="eye"></i></a>
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
        let listingUrl = "{{ route('sales-transaction.money-transfer') }}";
        let searchType = "{{ $searchType }}";
    </script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection
