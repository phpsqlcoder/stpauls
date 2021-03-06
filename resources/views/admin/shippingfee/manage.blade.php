@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('js/multiselect/css/multi-select.css') }}" rel="stylesheet">
    <style type="text/css">
        .ms-container { width: auto; }
    </style>
@endsection

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('shippingfee.index')}}">Shipping Rates</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Shipping Rate</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit Shipping Rate</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7 mg-t-45">
            <form id="manageRateForm" action="{{ route('shippingfee_location.store') }}" method="post">
                @csrf
                @method('POST')
                <input type="hidden" name="shippingfee_id" value="{{$sp->id}}">
                <div class="form-group">
                    <label>Name*</label>
                    <input type="text" class="form-control" name="name" value="{{$sp->name}}">
                </div>

                <div class="form-group">
                    @if($sp->is_international == 0)
                    <label>Flat Rate*</label>
                    @endif
                    <input required @if($sp->is_international == 0) type="text" @else type="hidden" @endif class="form-control" name="rate" value="{{$sp->rate}}">
                </div>

                @if($sp->is_international == 0)
                <div class="form-group">
                    <div class="custom-control custom-switch @error('is_nearby') is-invalid @enderror">
                        <input type="checkbox" class="custom-control-input" name="is_nearby" {{ ($sp->is_nearby_city == 1 ? "checked":"") }}  id="customSwitch1">
                        <label class="custom-control-label" id="label_visibility" for="customSwitch1">
                            Nearby City/Metro Manila
                        </label>
                    </div>
                    <small>Check to allow Same Day Delivery shipping option for these zone/area.</small>
                </div>
                @endif

                <div class="form-group">
                    <label>@if($sp->is_international == 0) Provinces* @else Countries* @endif</label>
                    <select class="form-control selected_locations" id='custom-headers' multiple='multiple' name="selected_locations[]">
                        @if($sp->is_international == 0)
                            @if(in_array($sp->area,['luzon','visayas','mindanao']))

                                @php
                                    $provinces = \App\ShippingfeeLocations::provinces($sp->id);
                                @endphp
                                @foreach($provinces as $province)
                                    <option {{ \App\ShippingfeeLocations::checkIfSelected($sp->id,$province->id) }} value="{{ $province->id }}">{{ $province->province }}</option>
                                @endforeach

                            @else

                                @php
                                    $cities = \App\ShippingfeeLocations::cities($sp->id);
                                @endphp
                                @foreach($cities as $city)
                                    <option {{ \App\ShippingfeeLocations::checkIfCitySelected($sp->id,$city->city,$sp->province) }} value="{{ $city->city }}">{{ $city->city }}</option>
                                @endforeach

                            @endif
                        @else

                            @foreach(Setting::countries() as $country)                            
                                <option value="{{$country->name}}" @if($sp->locations->contains('name',$country->name)) selected="selected" @endif>{{$country->name}}</option>
                            @endforeach

                        @endif
                    </select>
                </div>                
                <button class="btn btn-primary btn-sm btn-uppercase" type="button" id="btnUpdateRate">Update Shipping Rate</button>
                <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('shippingfee.index') }}">Cancel</a>
            </form> 
        </div>
        <div class="col-md-5">
            <table width="100%" class="table table-borderless">
                <tr>
                    <td align="right">
                        @if (auth()->user()->has_access_to_route('shippingfee_weight.store'))
                        <a class="btn btn-xs btn-primary" href="#" onclick="$('#modal-new-weight').modal('show');">Add New</a>
                        @endif

                        @if (auth()->user()->has_access_to_route('shippingfee_weight.upload_csv'))
                        <a class="btn btn-xs btn-success" href="#" onclick="$('#modal-upload-csv-weight').modal('show');">Upload CSV</a>
                        @endif

                        @if (auth()->user()->has_access_to_route('shippingfee-weight.multiple-delete'))
                        <a class="btn btn-xs btn-danger" href="#" onclick="delete_rates();">Delete Selected</a>
                        @endif
                    </td>
                </tr>
            </table>

            <table class="table mg-b-0 table-light table-hover">
                <thead>
                <tr>
                    <th width="5%">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="checkbox_all">
                            <label class="custom-control-label" for="checkbox_all"></label>
                        </div>
                    </th>
                    <th scope="col" width="40%">Weight (Kg)</th>
                    <th scope="col" width="40%">Rate / Weight</th>
                    <th scope="col" width="15%">Actions</th>                            
                </tr>
                </thead>
                <tbody>
                @php $weight_counter = 0; @endphp
                @forelse($weights as $weight)
                    @php $weight_counter++; @endphp
                    <tr id="row{{$weight->id}}"> 
                        <th>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input cb" id="cb{{ $weight->id }}">
                                <label class="custom-control-label" for="cb{{ $weight->id }}"></label>
                            </div>
                        </th>
                        <td>{{ $weight->weight }}</td>
                        <td>{{ number_format($weight->rate,2) }}</td>                                
                        <td style="text-align:center">                                    
                            <nav class="nav table-options">
                                @if (auth()->user()->has_access_to_route('shippingfee_weight.update'))
                                <a class="nav-link" href="#" title="Edit Rate" onclick="edit_weight('{{$weight->id}}','{{$weight->weight}}','{{$weight->rate}}')"><i data-feather="edit"></i></a>
                                @endif
                                @if (auth()->user()->has_access_to_route('shippingfee-weight.single-delete'))
                                <a class="nav-link" href="#" title="Delete Rate" onclick="single_delete_weight('{{$weight->id}}')"><i data-feather="trash"></i></a>
                                @endif
                            </nav>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;"> <p class="text-danger">No rates found.</p></td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <input type="hidden" id="weight_counter" value="{{$weight_counter}}">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-7"></div>
        <div class="col-sm-2">
            <div>
                @if ($weights->firstItem() == null)
                    <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                @else
                    <p class="tx-gray-400 tx-12 d-inline">Showing {{$weights->firstItem()}} to {{$weights->lastItem()}} of {{$weights->total()}} items</p>
                @endif
            </div>
        </div>
        <div class="col-sm-3">
            <div class="text-md-right float-md-right">
                <div>
                    {!! $weights->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<form action="" id="posting_form" method="post" style="display: none;">
    @csrf
    <input type="text" id="rates" name="rates">
</form>

<div class="modal effect-scale" id="modal-new-weight" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Rate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('shippingfee_weight.store')}}" method="post">
                @csrf      
                <input type="hidden" name="shippingfee_id" value="{{$sp->id}}">  
                <div class="modal-body">
                    <div class="form-group">
                        <label class="d-block">Weight (Kg) *</label>
                        <input type="number" name="weight" id="weight" class="form-control" step="0.01">
                       
                    </div>
                    <div class="form-group">
                        <label class="d-block">Rate *</label>
                        <input type="number" name="rate" id="rate" class="form-control" step="0.01">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Save Rate</button>                    
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="modal-edit-weight" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Update Rate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('shippingfee_weight.update')}}" method="post">
                @csrf      
                <input type="hidden" name="weight_id" id="weight_id_edit" value="0" min="0">  
                <div class="modal-body">
                    <div class="form-group">
                        <label class="d-block">Weight (Kg) *</label>
                        <input readonly type="number" name="weight" id="weight_edit" class="form-control" step="0.01">                       
                    </div>
                    <div class="form-group">
                        <label class="d-block">Rate *</label>
                        <input type="number" name="rate" id="rate_edit" class="form-control" step="0.01">                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Update Rate</button>                    
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="modal-single-delete-weight" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <p>Are you sure you want to delete this rate ?</p>  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" id="btnSingleDelete">Yes, Delete</button>                    
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="modal-multiple-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete all rates for {{$sp->name}}?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" id="btnDeleteMultiple">Yes, Delete</button> 
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="modal-upload-csv-weight" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Upload Rates</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('shippingfee_weight.upload_csv')}}" method="post" enctype="multipart/form-data" id="formUploadCsv">
            @csrf
                <div class="modal-body">
                <p><strong>Note: Previous data will be overwritten once new csv file is uploaded.</strong></p>       
                    <input type="hidden" name="shipping_id_csv" value="{{$sp->id}}">  
                    <input type="file" name="csv" id="csvfile">  
                    <br>
                    <small>File extension: .csv</small>
                    <span id="filext" style="display: none;" class="text-danger"></span>
                    <span id="nofile" style="display: none;" class="text-danger"></span>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-info">Upload</button>                    
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
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

<div class="modal effect-scale" id="prompt-no-weight" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.no_selected_title')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Please add or upload new rates per weight.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="prompt-no-location" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.no_selected_title')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Please select at least one (1) country or city.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('js/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('js/multiselect/quicksearch-master/jquery.quicksearch.js') }}"></script>

    <script>
        /*** Handles the Select All Checkbox ***/
        $("#checkbox_all").click(function(){
            $('.cb').not(this).prop('checked', this.checked);
        });

        $('#custom-headers').multiSelect({
            selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' style='margin-bottom:5px;' placeholder='Search here..'>",
            selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' style='margin-bottom:5px;' placeholder='Search here..'>",
            afterInit: function(ms){
                var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                    if (e.which === 40){
                        that.$selectableUl.focus();
                        return false;
                    }
                });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                    if (e.which == 40){
                        that.$selectionUl.focus();
                        return false;
                    }
                });
            },
            afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function(){
                this.qs1.cache();
                this.qs2.cache();
            }
        });
    </script>
@endsection

@section('customjs')
    <script>
        $('#csvfile').change(function () {
            var file = $(this)[0].files[0];
            var ext  = $(this).val().split('.').pop().toLowerCase();

            if (ext != 'csv') {
                $('#csvfile').val('');
                $('#filext').css('display','block');
                $('#filext').html(file.name+ ' has invalid extension');         
            } else {
                $('#nofile').css('display','none');
                $('#filext').css('display','none');
            }
        });

        $('#formUploadCsv').submit(function(){
            if($('#csvfile').val() == ''){
                $('#nofile').css('display','block');
                $('#nofile').html('Please upload a csv file.');
                return false;
            } else {
                return true;
            }
        });


        $('#btnUpdateRate').click(function(){
            var count = $('#weight_counter').val();

            if (!$(".selected_locations option:selected").length) {
                $('#prompt-no-location').modal('show');
            } else {
                if(count >= 1){
                    $('#manageRateForm').submit();
                } else {
                    $('#prompt-no-weight').modal('show');
                }
            }
        });

        function post_form(url,rates){
            $('#posting_form').attr('action',url);
            $('#rates').val(rates);
            $('#posting_form').submit();
        }

        function single_delete_weight(id){
            $('#modal-single-delete-weight').modal('show');

            $('#btnSingleDelete').on('click', function() {
                post_form("{{route('shippingfee-weight.single-delete')}}",id);
            });
        }

        function delete_rates(){
            var counter = 0;
            var selected_rates = '';
            $(".cb:checked").each(function(){
                counter++;
                fid = $(this).attr('id');
                selected_rates += fid.substring(2, fid.length)+'|';
            });

            if(parseInt(counter) < 1){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else{
                $('#modal-multiple-delete').modal('show');
                $('#btnDeleteMultiple').on('click', function() {
                    post_form("{{route('shippingfee-weight.multiple-delete')}}",selected_rates);
                });
            }
        }

        function edit_weight(id,weight,rate){
            $('#weight_id_edit').val(id);
            $('#weight_edit').val(weight);
            $('#rate_edit').val(rate);
            $('#modal-edit-weight').modal('show');
        }

        function delete_add(){
            $('#weight_id_edit').val(id);
            $('#weight_edit').val(weight);
            $('#rate_edit').val(rate);
            $('#modal-edit-weight').modal('show');
        }

    </script>
@endsection
