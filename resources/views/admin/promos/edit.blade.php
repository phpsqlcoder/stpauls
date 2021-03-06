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
                    <li class="breadcrumb-item" aria-current="page">Promos</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit a Promo</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit a Promo</h4>
        </div>
    </div>
    <form autocomplete="off" action="{{ route('promos.update',$promo->id) }}" method="post" id="promo_form">
        @csrf
        @method('PUT')
        <div class="row row-sm">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="d-block">Name*</label>
                    <input required type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name',$promo->name) }}" maxlength="150">
                    @hasError(['inputName' => 'name'])
                    @endhasError
                </div>
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="d-block">Promotion Date & Time*</label>
                            <input required type="text" name="promotion_dt" class="form-control wd-100p @error('promotion_dt') is-invalid @enderror" placeholder="Choose date range" id="date1" value="{{ date('Y-m-d H:i',strtotime($promo->promo_start)) }} - {{ date('Y-m-d H:i',strtotime($promo->promo_end)) }}">
                            @hasError(['inputName' => 'promotion_dt'])
                            @endhasError
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Discount (%)*</label>
                    <input required name="discount" id="discount" value="{{ old('discount',$promo->discount) }}" type="number" class="form-control @error('discount') is-invalid @enderror" max="100" min="1">
                    @hasError(['inputName' => 'discount'])
                    @endhasError
                </div>

                <div class="form-group">
                    <label class="d-block">Status</label>
                    <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                        <input type="checkbox" class="custom-control-input" name="status" {{ (old("status") == "ON" || $promo->status == "ACTIVE" ? "checked":"") }} id="customSwitch1">
                        <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ucfirst(strtolower($promo->status))}}</label>
                    </div>
                    @hasError(['inputName' => 'status'])
                    @endhasError
                </div>


                <div class="access-table-head">
                    <div class="table-responsive-lg text-nowrap">
                        <table class="table table-borderless" style="width:100%;">
                            <thead>
                            <tr>
                                <td width="50%" class="text-success"><b>On-sale Products</b></td>
                                <td class="text-right">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox_onsale">
                                        <label class="custom-control-label" for="checkbox_onsale"></label>
                                    </div>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <table class="table table-hover" style="width:100%;">
                    <thead>
                        
                    </thead>
                    <tbody>
                    @foreach(\App\EcommerceModel\Product::promo_product_categories($promo->id) as $category)
                        <tr>
                            <td width="50%"><p class="mg-0 pd-t-5 pd-b-5 tx-uppercase tx-semibold tx-primary">{{ \App\EcommerceModel\ProductCategory::categoryName($category->category_id) }}</p></td>
                            <td class="text-right">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input category_onsale onsale_{{$category->category_id}}" data-category_onsale="{{$category->category_id}}" id="onsale_cat{{$category->category_id}}">
                                    <label class="custom-control-label" for="onsale_cat{{$category->category_id}}"></label>
                                </div>
                            </td>
                        </tr>
                        @forelse($onsale_products as $product)
                            @if($product->category_id == $category->category_id)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td class="text-right">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="productid[]" value="{{$product->id}}" checked class="custom-control-input cb_onsale onsale_{{$product->category_id}}" id="pcategory{{$product->id}}">
                                        <label class="custom-control-label" for="pcategory{{$product->id}}"></label>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr><td>No Products</td></tr>
                        @endforelse
                    @endforeach
                    </tbody>
                </table>

                
                <div class="access-table-head">
                    <div class="table-responsive-lg text-nowrap">
                        <table class="table table-borderless" style="width:100%;">
                            <thead>
                            <tr>
                                <td width="50%" class="text-danger"><b>Products</b></td>
                                <td class="text-right">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                        <label class="custom-control-label" for="checkbox_all"></label>
                                    </div>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <table class="table table-hover" style="width:100%;">
                    <thead>
                        
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        @if(\App\EcommerceModel\ProductCategory::count_unsale_products($category->id) > 0)
                            <tr>
                                <td width="50%"><p class="mg-0 pd-t-5 pd-b-5 tx-uppercase tx-semibold tx-primary">{{ $category->name }}</p></td>
                                <td class="text-right">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input category category_{{$category->id}}" data-category="{{$category->id}}" id="cat{{$category->id}}">
                                        <label class="custom-control-label" for="cat{{$category->id}}"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                @forelse($unsale_products as $product)
                                    @if($product->category_id == $category->id)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td class="text-right">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="productid[]" value="{{$product->id}}" class="custom-control-input cb category_{{$product->category_id}}" id="pcategory{{$product->id}}">
                                                <label class="custom-control-label" for="pcategory{{$product->id}}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @empty
                                    <tr><td>No Products</td></tr>
                                @endforelse
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-lg-12 mg-t-20 mg-b-30">
                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Promo</button>
                <a href="{{ route('promos.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
            </div>
        </div>
    </form>
</div>

<div class="modal effect-scale" id="prompt-no-selected" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.no_selected_title')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{__('common.no_product_selected')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>

    <script src="{{ asset('lib/datextime/moment.min.js') }}"></script>
    <script src="{{ asset('lib/datextime/daterangepicker.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>

    <script>
        var dateToday = new Date(); 

        $(function(){
            'use strict'

            $('#date1').daterangepicker({
                autoUpdateInput: false,
                timePicker: true,
                locale: {
                    format: 'MM/DD/YYYY h:mm A',
                    cancelLabel: 'Clear'
                },
                minDate: dateToday,
            });

            $('input[name="promotion_dt"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD H:mm') + ' - ' + picker.endDate.format('YYYY-MM-DD H:mm'));
            });

            $('input[name="promotion_dt"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });

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

@section('customjs')
    <script>
        /*** Handles the Select All Checkbox ***/
        $("#checkbox_all").click(function(){
            $('.cb').not(this).prop('checked', this.checked);
            $('.category').not(this).prop('checked', this.checked);
        });

        $('.category').on('click', function() {
            let category = $(this).data('category');
            let checked = $(this).is(':checked');
            let objectName = '.category_'+category;
            $(objectName).each(function() {
                this.checked = checked;
            });
        });

        $("#checkbox_onsale").click(function(){
            $('.cb_onsale').not(this).prop('checked', this.checked);
            $('.category_onsale').not(this).prop('checked', this.checked);
        });

        $('.category_onsale').on('click', function() {
            let category = $(this).data('category_onsale');
            let checked = $(this).is(':checked');
            let objectName = '.onsale_'+category;
            $(objectName).each(function() {
                this.checked = checked;
            });
        });

        $('#promo_form').submit(function(){
            if(!$("input[name='productid[]']:checked").val()) {        
                $('#prompt-no-selected').modal('show');
                return false;
            } else {
                return true;
            }
        });

        /** form validations **/
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