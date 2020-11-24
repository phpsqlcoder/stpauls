@extends('admin.layouts.app')

@section('pagetitle')
    Branch Manager
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
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
                <form autocomplete="off" action="{{ route('branch.store') }}" method="post" enctype="multipart/form-data">
                    @method('POST')
                    @csrf
                    <div class="row row-sm">
                        <div class="col-sm-6">
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Name*</label>
                                <input required type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" maxlength="250">
                                @hasError(['inputName' => 'name'])
                                @endhasError
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Email*</label>
                                <input required type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" maxlength="250">
                                @hasError(['inputName' => 'email'])
                                @endhasError
                            </div>

                            <div class="form-group">
                                <label class="d-block">Image*</label>
                                <div class="custom-file">
                                    <input required type="file" class="form-control" id="branch_img" name="branch_img">
                                </div>
                                <p class="tx-10">
                                    Required image dimension: {{ env('BRANCH_WIDTH') }}px by {{ env('BRANCH_HEIGHT') }}px <br /> Maximum file size: 1MB <br /> File extension: PNG, JPG, JPEG
                                </p>
                                <span id="span_file_type" style="display: none;" class="text-danger"></span>
                                <span id="span_file_size" style="display: none;" class="text-danger"></span>
                                <span id="span_file_dimension" style="display: none;" class="text-danger"></span>
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Social Media Link</label>
                                <input type="text" class="form-control" name="url" value="{{ old('url') }}" maxlength="250">
                            </div>

                            <label class="mg-b-5 tx-color-03">Contact Number*</label>
                            <div class="form-group input-group input-icon">

                                <input required type="text" class="form-control" name="contactname[]" placeholder="Contact name">
                                &nbsp;
                                <input required type="text" class="form-control" name="contactnumber[]" placeholder="Contact number">
                                <span class="input-group-btn">&nbsp;
                                    <button type="button" class="btn btn-sm btn-primary btn-add"><i>+</i></button>
                                </span>
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Area*</label>
                                <select required class="form-control mg-b-5 @error('area') is-invalid @enderror" name="area">
                                    <option value="">- Select Area -</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @hasError(['inputName' => 'area'])
                                @endhasError
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Province*</label>
                                <select required class="form-control mg-b-5 @error('province') is-invalid @enderror" name="province">
                                    <option value="">- Select Province -</option>
                                    @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->province }}</option>
                                    @endforeach
                                </select>
                                @hasError(['inputName' => 'province'])
                                @endhasError
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">City*</label>
                                <select required class="form-control mg-b-5 @error('city') is-invalid @enderror" name="city">
                                </select>
                                @hasError(['inputName' => 'city'])
                                @endhasError
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Address*</label>
                                <input required type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address" value="{{ old('address') }}" maxlength="250">
                                @hasError(['inputName' => 'address'])
                                @endhasError
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Other Details</label>
                                <textarea type="text" class="form-control" name="other_details">{{ old('other_details') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="d-block">Status</label>
                                <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                                    <input type="checkbox" class="custom-control-input" name="status" {{ (old("status") ? "checked":"") }} id="customSwitch1">
                                    <label class="custom-control-label" id="label_visibility" for="customSwitch1">Inactive</label>
                                    @hasError(['inputName' => 'status'])
                                    @endhasError
                                </div>
                            </div>

                            @if($featured == 0)
                            <div class="form-group">
                                <div class="custom-control custom-switch @error('is_featured') is-invalid @enderror">
                                    <input type="checkbox" class="custom-control-input" name="is_featured" {{ (old("is_featured") ? "checked":"") }} id="customSwitch3">
                                    <label class="custom-control-label" for="customSwitch3">Superstore</label>
                                </div>
                                @hasError(['inputName' => 'is_featured'])
                                @endhasError
                            </div>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary btn-uppercase">Save Branch</button>
                    <a href="{{ route('branch.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script src="{{ asset('js/listing.js') }}"></script>
    {{--    Image validation    --}}
    <script>
        let BRANCH_WIDTH = "{{ env('BRANCH_WIDTH') }}";
        let BRANCH_HEIGHT =  "{{ env('BRANCH_HEIGHT') }}";
    </script>
    {{--    Image validation    --}}
@endsection

@section('customjs')
    <script>
        var _URL = window.URL || window.webkitURL;
        $('#branch_img').change(function () {
            var file = $(this)[0].files[0];
            var ext  = $(this).val().split('.').pop().toLowerCase();

            img = new Image();
            var imgwidth = 0;
            var imgheight = 0;
            var file_size = (file.size / 1048576).toFixed(3);

            if ($.inArray(ext, ['jpeg', 'jpg', 'png']) == -1) {

                $('#branch_img').val('');
                $('#span_file_type').css('display','block');
                $('#span_file_type').html(file.name+ ' has invalid extension');

                $('#span_file_dimension').css('display','none');
                $('#span_file_size').css('display','none');
      
            } else {
                $('#span_file_type').css('display','none');
            }

            img.src = _URL.createObjectURL(file);
            img.onload = function() {
                imgwidth = this.width;
                imgheight = this.height;

                if (imgwidth != BRANCH_WIDTH || imgheight != BRANCH_HEIGHT) {

                    $('#branch_img').val('');
                    $('#span_file_dimension').css('display','block');
                    $('#span_file_dimension').html(file.name + ' has invalid dimensions.');

                    $('#span_file_size').css('display','none');
                    $('#span_file_type').css('display','none');

                } else {
                    $('#span_file_dimension').css('display','none');
                }

            }
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
                        $('select[name="city"]').append('<option selected disabled value="">-- Select City --</option>');
                        $.each(data, function(key, value) {
                            $('select[name="city"]').append('<option value="'+value.id+'">'+value.city+'</option>');
                        });
                    }
                });
            } else {
                $('select[name="city"]').empty();
            }
        });

        (function ($) {
            $(function () {

                var addFormGroup = function (event) {
                    event.preventDefault();

                    var $formGroup = $(this).closest('.form-group');
                    var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
                    var $formGroupClone = $formGroup.clone();
                    $(this)
                        .toggleClass('btn-add btn-sm btn-danger btn-remove')
                        .html('x');
                    $formGroupClone.find('input').val('');
                    $formGroupClone.insertAfter($formGroup);
                };

                var removeFormGroup = function (event) {
                    event.preventDefault();

                    var $formGroup = $(this).closest('.form-group');
                    var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
                    $formGroup.remove();
                };

                $(document).on('click', '.btn-add', addFormGroup);
                $(document).on('click', '.btn-remove', removeFormGroup);
            });
        })
        (jQuery);

    </script>
@endsection
