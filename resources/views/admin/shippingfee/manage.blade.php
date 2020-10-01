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
            <h4 class="mg-b-0 tx-spacing--1">Manage {{$sp->name}}</h4>
        </div>
    </div>

{{--    @if($message = Session::get('duplicate'))--}}
{{--        <div class="alert alert-warning d-flex align-items-center mg-t-15" role="alert">--}}
{{--            <p class="mg-b-0"><i data-feather="alert-circle" class="mg-r-10"></i>{{ $message }}--}}
{{--        </div>--}}
{{--    @endif--}}

    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('shippingfee_location.store') }}" method="post">
                @csrf
                @method('POST')
                <input type="hidden" name="shippingfee_id" value="{{$sp->id}}">
                <div class="mg-b-20">
                  
                    <select id='custom-headers' multiple='multiple' name="selected_countries[]">
                        @foreach(Setting::countries() as $country)                            
                            <option value="{{$country}}" @if($sp->locations->contains('name',$country)) selected="selected" @endif>{{$country}}</option>
                        @endforeach

                    </select>
                </div>                
                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Save</button>
                <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('shippingfee.index') }}">Cancel</a>
                   
            </form>
            
        </div>
        <div class="col-md-6">
            <div class="table-list mg-b-10">
                <div class="table-responsive-lg">
                    <table width="100%">
                        <tr>
                            <td align="right"><a class="btn btn-xs btn-info" href="#" onclick="$('#modal-new-weight').modal('show');">Add New</a>
                           <a class="btn btn-xs btn-success" href="#" onclick="$('#modal-upload-csv-weight').modal('show');">Upload CSV</a>
                            <a class="btn btn-xs btn-danger" href="#" onclick="$('#modal-delete_all-weight').modal('show')">Delete All</a></td>
                        </tr>
                    </table>
                    <table class="table mg-b-0 table-light table-hover">
                        <thead>
                        <tr>
                            <th>Weight</th>
                            <th>Rate</th>
                            <th>Actions</th>                            
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($sp->weights as $weight)
                            <tr> 
                                <td>{{ $weight->weight }}</td>
                                <td>{{ number_format($weight->rate,2) }}</td>                                
                                <td style="text-align:center">                                    
                                    <nav class="nav table-options">
                                        <a class="nav-link" href="#" title="Edit Rate" onclick="edit_weight({{$weight->id}},{{$weight->weight}},{{$weight->rate}})"><i data-feather="edit"></i></a>
                                    </nav>
                                   
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center;"> <p class="text-danger">No record found.</p></td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
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
                        <label class="d-block">Weight *</label>
                        <input type="number" name="weight" id="weight" class="form-control" step="0.01">
                       
                    </div>
                    <div class="form-group">
                        <label class="d-block">Rate *</label>
                        <input type="number" name="rate" id="rate" class="form-control" step="0.01">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-info">Save</button>                    
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
                        <label class="d-block">Weight *</label>
                        <input type="number" name="weight" id="weight_edit" class="form-control" step="0.01">                       
                    </div>
                    <div class="form-group">
                        <label class="d-block">Rate *</label>
                        <input type="number" name="rate" id="rate_edit" class="form-control" step="0.01">                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-info">Save</button>                    
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal effect-scale" id="modal-delete_all-weight" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Delete All Rates</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <p>Are you sure you want to delete all records for {{$sp->name}}?</p>
            <form action="{{route('shippingfee_weight.delete_all')}}" method="post">
                @csrf      
                <input type="hidden" name="shipping_id_delete" value="{{$sp->id}}">  
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger">Delete All</button>                    
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
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
@endsection

@section('pagejs')

    <script src="{{ asset('js/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('js/multiselect/quicksearch-master/jquery.quicksearch.js') }}"></script>
@endsection

@section('customjs')
    <script>
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
    <script>
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
