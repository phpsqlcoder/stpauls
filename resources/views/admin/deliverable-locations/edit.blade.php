@extends('admin.layouts.app')

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('locations.index')}}">Serviceable Areas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Delivery Rate</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit Delivery Rate</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form id="delivery_form" autocomplete="off" action="{{ route('locations.update',$rate->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="d-block">Province *</label>
                        <select name="province" id="province" class="form-control mg-b-5">
                            <option value="0">-- Select Province --</option>
                            @foreach($provinces as $province)
                                <option @if($province->id == $rate->province) selected @endif value="{{$province->id}}">{{ $province->province }}</option>
                            @endforeach
                        </select>                                      
                    </div>  

                    <div class="form-group">
                        <label class="d-block">City *</label>
                        <select name="city" id="city" class="form-control mg-b-5" >
                            @foreach($cities as $city)
                                <option @if($city->id == $rate->city) selected @endif value="{{$city->id}}">{!! $city->city !!}</option>
                            @endforeach
                        </select>                                      
                    </div>  

                    <div class="form-group">
                        <label class="d-block">Rate *</label>
                        <input type="number" class="form-control" name="rate" min="1" step="0.01" value="{{ $rate->rate }}">
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" @if($rate->is_outside == 1) checked @endif name="is_outside" id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">Outside</label>
                        </div>
                    </div> 

                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Delivery Rate</button>
                    <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('locations.index') }}">Cancel</a>
            </form>
            </div>
        </div>
    </div>
</div>=
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
    </script>
@endsection

