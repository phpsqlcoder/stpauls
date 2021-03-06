@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/prismjs/themes/prism-vs.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datextime/daterangepicker.css') }}" rel="stylesheet">

    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
    	.table td {
		    padding: 0 0px;
		}
    </style>

@endsection

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page">CMS</li>
                    <li class="breadcrumb-item" aria-current="page">Loyalty</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit a Discount</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit a Discount</h4>
        </div>
    </div>
    <form autocomplete="off" action="{{ route('discounts.update',$discount->id) }}" method="post" id="promo_form">
        @csrf
        @method('PUT')
        <div class="row row-sm">
            <div class="col-lg-6">
            	<div class="form-group">
            		<label class="d-block">Discount title*</label>
            		<input required type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name',$discount->name) }}" maxlength="150">
            		@hasError(['inputName' => 'name'])
                    @endhasError
            	</div>

                <div class="form-group">
                    <label>Discount (%)*</label>
                    <input required name="discount" id="discount" value="{{ old('discount',$discount->discount) }}" type="number" class="form-control @error('discount') is-invalid @enderror" min="1" max="100">
                    @hasError(['inputName' => 'discount'])
                    @endhasError
                </div>

                <div class="form-group">
                    <label class="d-block">Status</label>
                    <div class="custom-control custom-switch @error('visibility') is-invalid @enderror">
                        <input type="checkbox" class="custom-control-input" name="status" {{ ($discount->status == 'ACTIVE' ? "checked":"") }} id="customSwitch1">
                        <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ucwords(strtolower($discount->status))}}</label>
                    </div>
                    @error('visibility')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-lg-12 mg-t-20 mg-b-30">
                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Discount</button>
                <a href="{{ route('discounts.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('pagejs')
    <script>
        /** form validations **/
        $("#customSwitch1").change(function() {
            if(this.checked) {
                $('#label_visibility').html('Active');
            }
            else{
                $('#label_visibility').html('Inactive');
            }
        });

        $(document).ready(function () {
            //called when key is pressed in textbox
            $("#discount").keypress(function (e) {
                //if the letter is not digit then display error and don't type anything
                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
        });
    </script>
@endsection