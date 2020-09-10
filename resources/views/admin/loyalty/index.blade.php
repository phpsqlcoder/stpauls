@extends('admin.layouts.app')

@section('pagetitle')
    Customer Management
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                        <li class="breadcrumb-item" aria-current="page">CMS</li>
                        <li class="breadcrumb-item active" aria-current="page">Loyalty</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Loyalty</h4>
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
                                                <input type="radio" id="orderBy1" name="orderBy" class="custom-control-input" value="updated_at" @if ($filter->orderBy == 'updated_at') checked @endif>
                                                <label class="custom-control-label" for="orderBy1">{{__('common.date_modified')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="total_purchase" @if ($filter->orderBy == 'total_purchase') checked @endif>
                                                <label class="custom-control-label" for="orderBy2">Total Purchase</label>
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
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Lastname" value="{{ $filter->search }}">
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
                        <table class="table mg-b-0 table-light table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th scope="col" width="30%">Name</th>
                                    <th scope="col">Total Purchase</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Discount Name</th>
                                    <th scope="col">Discounted Amount</th>
                                    <th scope="col">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr>
                                        <th>
                                            {{ $customer->details->fullName }}
                                        </th>
                                        <td>{{ $customer->total_purchase }}</td>
                                        <td>
                                            @if($customer->status == 'PENDING')
                                                <span class="badge badge-info">PENDING</span>
                                            @else
                                                @if($customer->status == 'APPROVED')
                                                    <span class="badge badge-success">APPROVED</span>
                                                @else
                                                    <span class="badge badge-danger">DISAPPROVED</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>@if($customer->discount_id <> 0)
                                            {{ $customer->discount_details->name }}
                                            @endif
                                        </td>
                                        <td>@if($customer->discount_id <> 0)
                                            {{ number_format($customer->discount_details->discount,2) }}
                                            @endif
                                        </td>
                                        <td>
                                            <nav class="nav table-options justify-content-begin">
                                                @if($customer->status == 'APPROVED')
                                                    <a class="nav-link" href="javascript:;" onclick="updateDiscount('{{$customer->id}}','{{$customer->discount_id}}')" title="Edit Discount"><i data-feather="edit"></i></a>
                                                @endif

                                                <a href="javascript:;" class="nav-link" data-toggle="collapse" data-target="#customer_{{$customer->customer_id}}" class="accordion-toggle" title="View Purchases"><i data-feather="eye"></i></a>

                                                @if($customer->status == 'PENDING')
                                                <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i data-feather="settings"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="javascript:;" onclick="approved('{{$customer->id}}')"> Approved</a>
                                                    <a class="dropdown-item" href="javascript:;" onclick="disapproved('{{$customer->id}}')"> Disapproved</a>
                                                </div>
                                                @endif
                                            </nav>
                                        </td>
                                    </tr>
                                    <tr>
                                    <td colspan="8" class="hiddenRow">
                                        <div class="accordian-body collapse" id="customer_{{$customer->customer_id}}">
                                            <div class="autoship-table">
                                                <div class="table-responsive mg-b-20">
                                                    <table class="table table-sm table-hover mg-0">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="30%">Order#</th>
                                                                <th scope="col">Amount</th>
                                                                <th scope="col">Payment Status</th>
                                                                <th scope="col">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $purchases = \App\EcommerceModel\SalesHeader::where('customer_id',$customer->customer_id)->get();
                                                            @endphp

                                                            @foreach($purchases as $purchase)
                                                                <tr>
                                                                    <th scope="row">{{ $purchase->order_number }}</th>
                                                                    <td>{{ number_format($purchase->gross_amount,2) }}</td>
                                                                    <td>{{ $purchase->payment_status }}</td>
                                                                    <td class="text-uppercase">{{ $purchase->status }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center;"> <p class="text-danger">No customers found.</p></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Pages -->

            <!-- Start Navigation -->
            <div class="col-md-6">
                <div class="mg-t-5">
                    @if ($customers->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{$customers->firstItem()}} to {{$customers->lastItem()}} of {{$customers->total()}} customers</p>
                    @endif

                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $customers->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
            <!-- End Navigation -->

        </div>
    </div>

    <div class="modal effect-scale" id="modalLoyaltyDispproved" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Disapprove Loyalty</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('loyalty.disapproved')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" id="did">
                    <div class="modal-body">
                        <p>You're about to disapproved this customer loyalty. Do you want to continue?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-danger">Yes, Deactivate</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="modalLoyaltyApproved" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Approve Loyalty</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('loyalty.approved')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Select Discount*</label>
                            <input type="hidden" name="id" id="aid">
                            <select required class="form-control" name="discount">
                                <option value="" selected disabled>-- Select Discount --</option>
                                @foreach($discounts as $discount)
                                <option value="{{$discount->id}}">{{$discount->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <p>You're about to approved this customer loyalty. Do you want to continue?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Yes, Approved</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="modalDiscount" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Update Discount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('loyalty.update-discount')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Select Discount*</label>
                            <input type="hidden" name="id" id="uid">
                            <select required class="form-control" name="discount" id="discount">
                                <option value="" selected disabled>-- Select Discount --</option>
                                @foreach($discounts as $discount)
                                <option value="{{$discount->id}}">{{$discount->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
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
    <script src="{{ asset('scripts/user/scripts.js') }}"></script>

    <script>
        let listingUrl = "{{ route('loyalty.index') }}";
        let advanceListingUrl = "";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
        function approved(id){
            $('#modalLoyaltyApproved').modal('show');
            $('#aid').val(id);
        }

        function disapproved(id){
            $('#modalLoyaltyDispproved').modal('show');
            $('#did').val(id);
        }

        function updateDiscount(id,discountId)
        {
            $('#modalDiscount').modal('show');
            $('#uid').val(id);
            $('#discount').val(discountId);
        }
    </script>
@endsection
