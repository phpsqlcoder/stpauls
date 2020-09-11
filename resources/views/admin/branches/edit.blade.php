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
                            <li class="breadcrumb-item active" aria-current="page">Edit Branch</li>
                        </ol>
                    </nav>
                    <h4 class="mg-b-0 tx-spacing--1">Edit a Branch</h4>
                </div>
            </div>


            <form autocomplete="off" action="{{ route('branch.update', $branches->id) }}" method="post">
                <div class="row row-sm">
                    @method('PUT')
                    @csrf
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="d-block">Branch Name *</label>
                            <input name="name" id="name" value="{{ old('name', $branches->name) }}" required type="text" class="form-control @error('name') is-invalid @enderror" maxlength="250">
                            @hasError(['inputName' => 'name'])
                            @endhasError
                        </div>
                        <div class="form-group">
                            <label class="d-block">Address *</label>
                            <input name="address" id="address" value="{{ old('address',$branches->address) }}" required type="text" class="form-control @error('address') is-invalid @enderror" maxlength="250">
                            @hasError(['inputName' => 'address'])
                            @endhasError
                        </div>
                        <div class="form-group">
                            <label class="d-block">Contact Number *</label>
                            <input name="contact_no" id="contact_no" value="{{ old('contact_no',$branches->contact_no) }}" required type="text" class="form-control @error('contact_no') is-invalid @enderror" maxlength="11">
                            @hasError(['inputName' => 'contact_no'])
                            @endhasError
                        </div>
                        <div class="form-group">
                            <label class="d-block">Contact Person *</label>
                            <input name="contact_person" id="contact_person" value="{{ old('name',$branches->contact_person) }}" type="text" class="form-control @error('contact_person') is-invalid @enderror" maxlength="250">
                            @hasError(['inputName' => 'contact_person'])
                            @endhasError
                        </div>
                        <div class="form-group">
                            <label class="d-block">Email Address *</label>
                            <input name="email_address" id="email_address" value="{{ old('name',$branches->email) }}" type="text" class="form-control @error('email_address') is-invalid @enderror" maxlength="250">
                            @hasError(['inputName' => 'email_address'])
                            @endhasError
                        </div>
                    </div>
                    <div class="col-lg-12 mg-t-30">
                        <input class="btn btn-primary btn-sm btn-uppercase" type="submit" value="Update Branch">
                        <a href="{{ route('branch.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                    </div>
                </div>
            </form>
        </div>



@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script src="{{ asset('js/listing.js') }}"></script>
    <script>
        $(document).ready(function () {
            //called when key is pressed in textbox
            $("#contact_nos").keypress(function (e) {

                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
        });  
    </script>
@endsection


