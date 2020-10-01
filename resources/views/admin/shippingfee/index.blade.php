@extends('admin.layouts.app')

@section('pagetitle')
    Shipping Fee Management
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
                        <li class="breadcrumb-item active" aria-current="page">Shipping Rates</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Int'l Rates</h4>
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
                                                <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="name" @if ($filter->orderBy == 'name') checked @endif>
                                                <label class="custom-control-label" for="orderBy2">{{__('common.name')}}</label>
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
                                                <input type="checkbox" id="showInactive" name="showDeleted" class="custom-control-input" @if ($filter->showDeleted) checked @endif>
                                                <label class="custom-control-label" for="showInactive">Show Inactive shippingfee</label>
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
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Name" value="{{ $filter->search }}">
                                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="mg-t-10">
                         
                                <a class="btn btn-primary btn-sm" href="#" onclick="$('#new-zone-modal').modal('show');">Create New Int'l Rate</a>
                           
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
                                <th width="5%">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                        <label class="custom-control-label" for="checkbox_all"></label>
                                    </div>
                                </th>
                                <th scope="col" width="50%">Zone</th>
                                <th scope="col" width="35%">Total Locations</th>                                
                                <th width="10%">Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($shippingfees as $shippingfee)
                                <tr id="row{{$shippingfee->id}}">
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input cb" id="cb{{ $shippingfee->id }}">
                                            <label class="custom-control-label" for="cb{{ $shippingfee->id }}"></label>
                                        </div>
                                    </th>
                                    <td>
                                        <strong> {{$shippingfee->name}}</strong>
                                    </td>
                                    <td><span class="badge badge-primary">{{$shippingfee->locations->count()}}</span></td>
                                    <td style="text-align: center">
                                        <nav class="nav table-options">
                                            <a class="nav-link" href="{{ route('shippingfee.manage', $shippingfee->id) }}" title="Manage Rate"><i data-feather="edit"></i></a>

                                            <a class="nav-link" href="{{ route('shippingfee.manage', $shippingfee->id) }}" title="Delete Rate"><i data-feather="trash"></i></a>

                                            <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="settings"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="" > Private</a>
                                                <a class="dropdown-item" href=""> Publish</a>
                                            </div>
                                        </nav>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center;"> <p class="text-danger">No record found.</p></td>
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
                    @if ($shippingfees->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{$shippingfees->firstItem()}} to {{$shippingfees->lastItem()}} of {{$shippingfees->total()}} shippingfees</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $shippingfees->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
            <!-- End Navigation -->

        </div>
    </div>
    <div class="modal effect-scale" id="new-zone-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Create New Zone</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('shippingfee.store')}}" method="post">
                    @csrf                    
                    <div class="modal-body">
                        <p>Enter zone name</p>
                        <table width="80%">
                            <tr>
                                <td><input type="text" class="form-control" name="zone"></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="is_international"> Zone is International</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="is_outside_manila"> Zone is Outside Manila</td>
                            </tr>
                        </table>
                        
                    <input name="location_after_submit" id="location_after_submit" type="hidden" value="shippingfee.store">
                                                
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-info">Save</button>
                        <a href="#" onclick="$('#location_after_submit').val('shippingfee.manage')" class="btn btn-sm btn-success">Save & Manage</a>
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
    <!-- <script src="{{ asset('scripts/shippingfee/scripts.js') }}"></script> -->

    <script>
        let listingUrl = "{{ route('shippingfee.index') }}";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>

    <script>
        $(".js-range-slider").ionRangeSlider({
            grid: true,
            from: selected,
            values: perPage
        });
    </script>
@endsection

@section('customjs')
    <script>
        // $(document).on('click','.delete_shippingfee', function(){
        //     $('#modalshippingfeeDelete').show();

        //     $('#shippingfee_id').val($(this).data('shippingfee_id'));
        // });

        $(document).on('click','.deactivate_shippingfee', function(){
            $('#modalshippingfeeDeactivate').show();

            $('#deactivate_shippingfee_id').val($(this).data('shippingfee_id'));
        });

        $(document).on('click','.activate_shippingfee', function(){
            $('#modalshippingfeeAactivate').show();

            $('#activate_shippingfee_id').val($(this).data('shippingfee_id'));
        });
    </script>
@endsection
