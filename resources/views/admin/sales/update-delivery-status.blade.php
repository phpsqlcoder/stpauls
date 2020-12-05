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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('transaction-status.index')}}">Email Notifications</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Update Delivery Status</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Update Delivery Status</h4>
            </div>
        </div>
        <form autocomplete="off" method="POST" action="{{route('sales-transaction.delivery_status')}}" enctype="multipart/form-data">
            <div class="row row-sm">
                @method('POST')
                @csrf   
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">Order Number</label>
                        <input type="hidden" class="form-control" name="del_id" value="{{$sales->id}}">
                        <input type="text" readonly class="form-control" value="{{$sales->order_number}}">
                    </div>

                    <div class="form-group">
                        <label class="d-block">Status*</label>
                        <select required id="delivery_status" class="selectpicker mg-b-5" name="delivery_status" data-style="btn btn-outline-light btn-md btn-block tx-left" title="- None -" data-width="100%" required="required">
                            <option value="Scheduled for Processing">Scheduled for Processing</option>
                            <option value="Processing">Processing</option>
                            <option value="Ready For delivery">Ready For delivery</option>
                            <option value="Ready for Pickup">Ready for Pickup</option>
                            <option value="Shipped">Shipped</option>
                            <option value="In Transit">In Transit</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Returned">Returned</option>
                            <option value="CANCELLED">Cancelled</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="d-block" id="long_descriptionLabel">Remarks</label>
                        <textarea name="del_remarks" id="editor1" rows="10" cols="80" required>
                            {{ old('content') }}
                        </textarea>
                    </div>
                </div>

                <div class="col-lg-12 mg-t-30">
                    <button type="submit" class="btn btn-primary btn-sm btn-uppercase" id="btnSave">Update</button>
                    @if($sales->payment_method == 0)
                    <a href="{{ route('sales-transaction.cash-on-delivery') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                    @endif

                    @if($sales->payment_method == 1)
                    <a href="{{ route('sales-transaction.card-payment') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                    @endif

                    @if($sales->payment_method == 2 || $sales->payment_method == 3)
                    <a href="{{ route('sales-transaction.money-transfer') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                    @endif

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

        let editor = CKEDITOR.replace('del_remarks', options);

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
