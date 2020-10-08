@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <script src="{{ asset('lib/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('transaction-status.index')}}">Transaction Status</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit a Transaction Status</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Edit a Transaction Status</h4>
            </div>
        </div>
        <form autocomplete="off" id="albumForm" method="POST" action="{{ route('transaction-status.update',$transaction->id) }}" enctype="multipart/form-data">
            <div class="row row-sm">
                @method('PUT')
                @csrf
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">Name *</label>
                        <input required name="name" id="name" value="{{ old('name',$transaction->name) }}" type="text" class="form-control @error('name') is-invalid @enderror" maxlength="150">
                        @hasError(['inputName' => 'name'])
                        @endhasError
                    </div>

                    <div class="form-group">
                        <label class="d-block">Email Subject *</label>
                        <input required name="subject" id="subject" value="{{ old('subject',$transaction->subject) }}" type="text" class="form-control @error('subject') is-invalid @enderror" maxlength="150">
                        @hasError(['inputName' => 'subject'])
                        @endhasError
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="d-block" id="long_descriptionLabel">Content *</label>
                        <span>To display order details, you need to add the following keywords.</span>
                        <ul>
                            <li>{shippingfee}   = Displays the shipping fee</li>
                            <li>{customer_name} = Displays the customer name</li>
                            <li>{order_number}  = Displays the order number</li>
                            <li>{company_name}  = Displays the company name</li>
                            <li>{paid_amount}   = Displays the amount paid of the customer</li>
                            <li>{remarks}       = Displays the sales remarks</li>
                        </ul>

                        <textarea name="content" id="editor1" rows="10" cols="80" required>
                            {{ old('content',$transaction->content) }}
                        </textarea>
                        @hasError(['inputName' => 'content'])
                        @endhasError
                        <span class="invalid-feedback" role="alert" id="contentRequired" style="display: none;">
                            <strong>The content field is required</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="d-block">Status</label>
                    <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                        <input type="checkbox" class="custom-control-input" name="status" {{ (old("status") == "ON" || $transaction->status == "ACTIVE" ? "checked":"") }} id="customSwitch1">
                        <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ucfirst(strtolower($transaction->status))}}</label>
                    </div>
                    @hasError(['inputName' => 'status'])
                    @endhasError
                </div>

                <div class="col-lg-12 mg-t-30">
                    <button type="submit" class="btn btn-primary btn-sm btn-uppercase">Update Transaction Status</button>
                    <a href="{{ route('transaction-status.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </div>

            </div>
        </form>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
@endsection

@section('customjs')
    <script>
        var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUpload: '/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token',
            allowedContent: true,

        };

        let editor1 = CKEDITOR.replace('content', options);
        editor1.on('required', function (evt) {
            if ($('.invalid-feedback').length == 1) {
                $('#contentRequired').show();
            }
            $('#cke_editor1').addClass('is-invalid');
            evt.cancel();
        });

        /** form validations **/
        $("#customSwitch1").change(function() {
            if(this.checked) {
                $('#label_visibility').html('Active');
            }
            else{
                $('#label_visibility').html('Inactive');
            }
        });
    </script>
@endsection
