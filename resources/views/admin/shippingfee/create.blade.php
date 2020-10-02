@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('shippingfee.index')}}">Shipping Rates</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Shipping Rate</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Create Shipping Rate</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form autocomplete="off" action="{{ route('shippingfee.store') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label class="d-block">Shipping type*</label>
                        <select required class="selectpicker mg-b-5 @error('type') is-invalid @enderror" name="type" id="type" data-style="btn btn-outline-light btn-md btn-block tx-left" title="-- Select Type --" data-width="100%">
                            <option value="0">Local</option>
                            <option value="1">International</option>
                        </select>
                        @hasError(['inputName' => 'type'])
                        @endhasError
                    </div>

                    <div class="form-group">
                        <label class="d-block">Name*</label>
                        <input required type="text" name="name" id="name" value="{{ old('name')}}" class="form-control @error('name') is-invalid @enderror" maxlength="150">
                        @hasError(['inputName' => 'name'])
                        @endhasError
                    </div>

                    <div class="form-group">
                        <label class="d-block">Province*</label>
                        <select class="form-control" name="province" id="province" required>
                            <option value="">-- Select Province --</option>
                            @foreach($provinces as $province)
                                <option value="{{$province->id}}">{{$province->province}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Rate*</label>
                        <input required type="number" name="rate" id="rate" value="{{ old('rate','0.00')}}" step="0.01" class="form-control @error('rate') is-invalid @enderror" min="1">
                        @hasError(['inputName' => 'rate'])
                        @endhasError
                    </div>

                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Save Rate</button>
                    <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('shippingfee.index') }}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $(function() {
            $('.selectpicker').selectpicker();
        });

        $('#type').change(function(){
            var type = $(this).val();

            if(type == 1){
                $("#rate").prop("readonly", true);
                $("#province").prop("disabled", true);
            } else {
                $("#rate").prop("readonly", false);
                $("#province").prop("disabled", false);
            }
        });
    </script>
@endsection