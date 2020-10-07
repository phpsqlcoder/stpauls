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
                        <li class="breadcrumb-item active" aria-current="page">Customers</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Account Reactivation Request</h4>
            </div>
        </div>

        <div class="row row-sm">

            <!-- Start Filters -->
            <div class="col-md-12">
                <div class="filter-buttons mg-b-10">
                    <div class="d-md-flex bd-highlight">
                        <div class="ml-auto bd-highlight mg-t-10 mg-r-10">
                            <form class="form-inline" id="searchForm">
                                <div class="search-form mg-r-10">
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Name" value="{{ $filter->search }}">
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
                                    <th scope="col">Email</th>
                                    <th scope="col" width="10%">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr>
                                        <th>
                                            <strong> {{ $customer->fullName }}</strong>
                                        </th>
                                        <td>{{ $customer->email }}</td>
                                        <td>
                                            <nav class="nav table-options">
                                                <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i data-feather="settings"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="javascript:;" onclick="approve('{{$customer->id}}')">Approve</a>
                                                    <a class="dropdown-item" href="javascript:;" onclick="disapprove('{{$customer->id}}')">Disapprove</a>
                                                </div>
                                            </nav>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" style="text-align: center;"> <p class="text-danger">No account reactivation request found.</p></td>
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
@include('admin.customers.modals')
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('scripts/user/scripts.js') }}"></script>

    <script>
        let listingUrl = "{{ route('customers.reactivate-request') }}";
        let advanceListingUrl = "";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
        function approve(id){
            $('#modalReactivate').modal('show');
            $('#approve_id').val(id);
        }

        function disapprove(id){
            $('#modalDisapprove').modal('show');
            $('#disapprove_id').val(id);
        }

        
    </script>
@endsection
