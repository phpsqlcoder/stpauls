@extends('admin.layouts.app')

@section('pagetitle')
    Branch Manager
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <style>
        .row-selected {
            background-color: #92b7da !important;
        }
    </style>
@endsection

@section('content')

    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('branch.index')}}">Branch</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Branch</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Create a Branch</h4>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-lg-12">
                <form autocomplete="off" action="{{ route('branch.store') }}" method="post">
                    @method('POST')
                    @csrf
                    <div class="row row-sm">
                        <div class="col-sm-6">
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Name*</label>
                                <input required type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" maxlength="150">
                                @hasError(['inputName' => 'name'])
                                @endhasError
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Address*</label>
                                <input required type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address" value="{{old('address')}}" maxlength="250">
                                @hasError(['inputName' => 'address'])
                                @endhasError
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Contact Number*</label>
                                <input required type="text" class="form-control @error('contact_no') is-invalid @enderror" name="contact_no" id="contact_no" maxlength="250" value="{{old('contact_no')}}">
                                @hasError(['inputName' => 'contact_no'])
                                @endhasError
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Contact Person</label>
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror" name="contact_person" id="contact_person" value="{{old('contact_person')}}">
                                @hasError(['inputName' => 'contact_person'])
                                @endhasError
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Email Address*</label>
                                <input required type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control @error('email_address') is-invalid @enderror" name="email_address" id="email_address" value="{{old('email_address')}}">
                                @hasError(['inputName' => 'email_address'])
                                @endhasError
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary btn-uppercase">Submit Branch</button>
                    <a href="{{ route('branch.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>

        $(".js-range-slider").ionRangeSlider({
            grid: true,
            from: selected,
            values: perPage
        });

        $(document).ready(function () {
            //called when key is pressed in textbox
            $("#contact_no").keypress(function (e) {

                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
        });  

    </script>
@endsection
