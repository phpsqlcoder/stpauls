@extends('admin.layouts.app')

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('locations.index')}}">Serviceable Areas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create new rate</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Create new rate</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form id="delivery_form" autocomplete="off" action="{{ route('locations.store') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label class="d-block">Province *</label>
                        <select name="province" id="province" class="form-control mg-b-5">
                            <option value="0">-- Select Province --</option>
                            @foreach($provinces as $province)
                                <option value="{{$province->id}}">{{ $province->province }}</option>
                            @endforeach
                        </select>                                      
                    </div>  

                    <div class="form-group">
                        <label class="d-block">City *</label>
                        <select name="city" id="city" class="form-control mg-b-5" >
                        </select>                                      
                    </div>  

                    <div class="form-group">
                        <label class="d-block">Rate *</label>
                        <input type="number" class="form-control" name="rate" min="1" step="0.01" value="0.00">
                    </div>

                    <div class="form-group">
                        <label class="d-block">Visibility</label>
                        <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="status" {{ (old("status") ? "checked":"") }} id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">Private</label>
                            @hasError(['inputName' => 'status'])
                            @endhasError
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Outside</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="is_outside" id="customSwitch2">
                            <label class="custom-control-label" id="label_visibility2" for="customSwitch2">No</label>
                        </div>
                    </div>                
                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Submit</button>
                    <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('locations.index') }}">Cancel</a>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script>
        $(document).ready(function() {
            $('select[name="province"]').on('change', function() {
                var provinceID = $(this).val();
                if(provinceID) {

                    var url = "{{ route('ajax.get-cities', ':provinceID') }}";
                    url = url.replace(':provinceID',provinceID);

                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                            $('select[name="city"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="city"]').append('<option value="'+value.id+'">'+value.city+'</option>');
                            });
                        }
                    });
                } else {
                    $('select[name="city"]').empty();
                }
            });
        });

        $("#customSwitch1").change(function() {
            if(this.checked) {
                $('#label_visibility').html('Published');
            }
            else{
                $('#label_visibility').html('Private');
            }
        });

        $("#customSwitch2").change(function() {
            if(this.checked) {
                $('#label_visibility2').html('Yes');
            }
            else{
                $('#label_visibility2').html('No');
            }
        });
    </script>
@endsection

