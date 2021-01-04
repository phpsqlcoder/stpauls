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

        <div class="row row-sm">
            <div class="col-lg-12">
                <form autocomplete="off" action="{{ route('branch.update', $branch->id) }}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row row-sm">
                        <div class="col-sm-6">
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Name*</label>
                                <input required type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name',$branch->name) }}" maxlength="250">
                                @hasError(['inputName' => 'name'])
                                @endhasError
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Email*</label>
                                <input required type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email',$branch->email) }}" maxlength="250">
                                @hasError(['inputName' => 'email'])
                                @endhasError
                            </div>

                            <div class="form-group">
                                @if(empty($branch->img))
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
                                @else
                                    <div>
                                        <img src="{{ asset('storage/branches/'.$branch->id.'/'.$branch->img) }}" height="330" width="100%" alt="Branch Image">  <br /><br />
                                        <button type="button" class="btn btn-danger btn-xs btn-uppercase remove-img" type="button" data-id=""><i data-feather="x"></i> Remove Image</button>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Social Media Link</label>
                                <input type="text" class="form-control" name="url" value="{{ old('url',$branch->url) }}" maxlength="250">
                            </div>

                            <label class="mg-b-5 tx-color-03">Contact Number*</label>
                            @foreach($branch->contacts as $contact)
                                <div class="form-group input-group input-icon">
                                    <input type="hidden" class="form-control" name="contactid[]" value="{{ $contact->id }}">
                                    <input required type="text" class="form-control" name="contactname[]" value="{{ $contact->contact_name }}">
                                    &nbsp;
                                    <input required type="text" class="form-control" name="contactnumber[]" value="{{ $contact->contact_no }}">
                                    <span class="input-group-btn">&nbsp;<button type="button" data-cid="{{$contact->id}}" class="btn btn-danger remove-contact">x</button></span>
                                </div>
                            @endforeach

                            <div class="form-group input-group input-icon">
                                <input type="hidden" class="form-control" name="contactid[]" value="0">
                                <input type="text" class="form-control" name="contactname[]" placeholder="Contact name">
                                &nbsp;
                                <input type="text" class="form-control" name="contactnumber[]" placeholder="Contact number">
                                <span class="input-group-btn">&nbsp;
                                    <button type="button" class="btn btn-sm btn-primary btn-add"><i>+</i></button>
                                </span>
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Area*</label>
                                <select required class="form-control mg-b-5 @error('area') is-invalid @enderror" name="area">
                                    @foreach($areas as $area)
                                        <option @if($branch->area == $area->id) selected @endif value="{{ $area->id }}">{{ $area->name }}</option>
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
                                    <option @if($branch->province_id == $province->id) selected @endif value="{{ $province->id }}">{{ $province->province }}</option>
                                    @endforeach
                                </select>
                                @hasError(['inputName' => 'province'])
                                @endhasError
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">City*</label>
                                <select required class="form-control mg-b-5 @error('city') is-invalid @enderror" name="city">
                                    @foreach($cities as $city)
                                    <option @if($branch->city_id == $city->id) selected @endif value="{{ $city->id }}">{{ $city->city }}</option>
                                    @endforeach
                                </select>
                                @hasError(['inputName' => 'city'])
                                @endhasError
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Address*</label>
                                <input required type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address" value="{{ old('address',$branch->address) }}" maxlength="250">
                                @hasError(['inputName' => 'address'])
                                @endhasError
                            </div>

                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Other Details</label>
                                <textarea type="text" class="form-control" name="other_details">{{ old('other_details',$branch->other_details) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="d-block">Status</label>
                                <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                                    <input type="checkbox" class="custom-control-input" name="status" {{ (old("status") == "ON" || $branch->status == "ACTIVE" ? "checked":"") }} id="customSwitch1">
                                    <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ucfirst(strtolower($branch->status))}}</label>
                                </div>
                                @hasError(['inputName' => 'status'])
                                @endhasError
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch @error('store_pickup') is-invalid @enderror">
                                    <input type="checkbox" class="custom-control-input" name="store_pickup" {{ (old("store_pickup") == "ON" || $branch->store_pickup == 1 ? "checked":"") }} id="customSwitch2">
                                    <label class="custom-control-label" for="customSwitch2">Store Pick-up</label>
                                </div>
                                @hasError(['inputName' => 'store_pickup'])
                                @endhasError
                            </div>

                            @if($branch->isfeatured == 1)
                            <div class="form-group">
                                <div class="custom-control custom-switch @error('is_featured') is-invalid @enderror">
                                    <input type="checkbox" class="custom-control-input" name="is_featured" {{ (old("is_featured") == "ON" || $branch->isfeatured == 1 ? "checked":"") }} id="customSwitch3">
                                    <label class="custom-control-label" for="customSwitch3">Superstore</label>
                                </div>
                                @hasError(['inputName' => 'is_featured'])
                                @endhasError
                            </div>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary btn-uppercase">Update Branch</button>
                    <a href="{{ route('branch.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-delete-contact" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Remove Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove this contact?</p>
                </div>
                <form action="{{route('branch-remove-contact')}}" method="POST">
                @csrf
                <input type="hidden" id="cid" name="id">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-danger">Yes, remove contact</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-remove-img" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Remove Branch Logo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove this branch image?</p>
                </div>
                <form action="{{route('branch.remove-image')}}" method="POST">
                @csrf
                    <div class="modal-footer">
                        <input type="hidden" name="branchid" value="{{$branch->id}}">
                        <button type="submit" class="btn btn-sm btn-danger">Yes, remove image</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
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
    {{--    Image validation    --}}
    <script>
        let BRANCH_WIDTH = "{{ env('BRANCH_WIDTH') }}";
        let BRANCH_HEIGHT =  "{{ env('BRANCH_HEIGHT') }}";
    </script>
    {{--    Image validation    --}}

    <script>
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

@section('customjs')
    <script>
        $(document).on('click', '.remove-contact    ', function() {
            $('#prompt-delete-contact').modal('show');
            $('#cid').val($(this).data('cid'));
        });

        $(document).on('click', '.remove-img', function() {
            $('#prompt-remove-img').modal('show');
        });

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



