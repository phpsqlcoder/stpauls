@extends('admin.layouts.app')

@section('pagetitle')
    Zone Management
@endsection

@section('pagecss')

  
    <link href="{{ asset('js/multiselect/css/multi-select.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('shippingfee.index')}}">Shipping Fee</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manage Zone</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Manage Rate</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mg-t-45">
            <form action="{{ route('shippingfee_location.store') }}" method="post">
                @csrf
                @method('POST')
                <input type="hidden" name="shippingfee_id" value="{{$sp->id}}">
                <div class="form-group">
                    <label>Name*</label>
                    <input type="text" class="form-control" name="name" value="{{$sp->name}}">
                </div>

                @if($sp->is_international == 0)
                <div class="form-group">
                    <label>Rate*</label>
                    <input required type="text" class="form-control" name="rate" value="{{$sp->rate}}">
                </div>
                @endif

                @php 
                    if($sp->province > 0){
                        $cities = \App\Cities::where('province',$sp->province)->orderBy('city','asc')->get();
                    }
                @endphp
                <div class="form-group">
                    <label>@if($sp->is_international == 0) Cities* @else Countries* @endif</label>
                    <select id='custom-headers' multiple='multiple' name="selected_locations[]">
                        @if($sp->is_international == 0)
                            @foreach($cities as $city)
                                <option @if($sp->locations->contains('name',$city->city)) selected="selected" @endif value="{{ $city->city }}">{{ $city->city }}</option>
                            @endforeach
                        @else
                            @foreach(Setting::countries() as $country)                            
                                <option value="{{$country}}" @if($sp->locations->contains('name',$country)) selected="selected" @endif>{{$country}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>                
                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Rate</button>
                <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('shippingfee.index') }}">Cancel</a>
            </form> 
        </div>
        <div class="col-md-8">
            <table width="100%" class="table table-borderless">
                <tr>
                    <td align="right"><a class="btn btn-xs btn-primary" href="#" onclick="$('#modal-new-weight').modal('show');">Add New</a>
                   <a class="btn btn-xs btn-success" href="#" onclick="$('#modal-upload-csv-weight').modal('show');">Upload CSV</a>
                    <a class="btn btn-xs btn-danger" href="#" onclick="delete_rates();">Delete Selected</a></td>
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
                    <th scope="col" width="40%">Rate</th>
                    <th scope="col" width="15%">Actions</th>                            
                </tr>
                </thead>
                <tbody>
                @forelse($weights as $weight)
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
                                <a class="nav-link" href="#" title="Edit Rate" onclick="edit_weight('{{$weight->id}}','{{$weight->weight}}','{{$weight->rate}}')"><i data-feather="edit"></i></a>
                                <a class="nav-link" href="#" title="Delete Rate" onclick="single_delete_weight('{{$weight->id}}')"><i data-feather="trash"></i></a>
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
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <div>
                @if ($weights->firstItem() == null)
                    <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                @else
                    <p class="tx-gray-400 tx-12 d-inline">Showing {{$weights->firstItem()}} to {{$weights->lastItem()}} of {{$weights->total()}} weights</p>
                @endif
            </div>
        </div>
        <div class="col-sm-4">
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
                        <input type="number" name="weight" id="weight_edit" class="form-control" step="0.01">                       
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
            <div class="modal-body">
            <p>Make sure to use standard csv template</p>
            <form action="{{route('shippingfee_weight.upload_csv')}}" method="post" enctype="multipart/form-data">
                @csrf      
                <input type="hidden" name="shipping_id_csv" value="{{$sp->id}}">  
                <input type="file" name="csv">  
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-info">Upload</button>                    
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
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
