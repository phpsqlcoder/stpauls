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
            <form autocomplete="off" action="{{ route('shippingfee.store') }}" method="post" novalidate>
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
                        <label class="d-block">Zone Name*</label>
                        <input required type="text" name="name" id="name" value="{{ old('name')}}" class="form-control @error('name') is-invalid @enderror" maxlength="150">
                        @hasError(['inputName' => 'name'])
                        @endhasError
                    </div>

                    <div id="local_input">
                        <div class="form-group">
                            <label class="d-block">Areas*</label>
                            <select class="form-control" name="areas" id="areas" required>
                                <option value="">-- Select Areas --</option>
                                <option value="metro manila">Metro Manila</option>
                                <option value="rizal">Rizal</option>
                                <option value="cavite">Cavite</option>
                                <option value="laguna">Laguna</option>
                                <option value="luzon">Luzon</option>
                                <option value="visayas">Visayas</option>
                                <option value="mindanao">Mindanao</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="d-block">Rate*</label>
                            <input type="number" name="rate" id="rate" value="{{ old('rate')}}" class="form-control @error('rate') is-invalid @enderror" min="1" required>
                            @hasError(['inputName' => 'rate'])
                            @endhasError
                        </div>
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

            if(type == 0){
                $('#local_input').css('display','block');
                $("#areas").prop("required", true);
                $("#rate").attr("required", true);
            } else {
                $('#local_input').css('display','none');
                $("#areas").prop("required", false);
                $("#rate").attr("required", false);
            }
        });
    </script>
@endsection
