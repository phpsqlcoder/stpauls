@extends('admin.layouts.app')

@section('pagetitle')
    Create Product
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-product.css') }}" rel="stylesheet">
    <script src="{{ asset('lib/ckeditor/ckeditor.js') }}"></script>
    <style>
        #errorMessage {
            list-style-type: none;
            padding: 0;
            margin-bottom: 0px;
        }
        .image_path {
            opacity: 0;
            width: 0;
            display: none;
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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('products.index')}}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Product</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Create a Product</h4>
            </div>
        </div>
        <form autocomplete="off" id="albumForm" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            <div class="row row-sm">
                @method('POST')
                @csrf
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">Code *</label>
                        <input required name="code" id="code" value="{{ old('code') }}" type="text" class="form-control @error('code') is-invalid @enderror" maxlength="150">
                        @hasError(['inputName' => 'code'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <label class="d-block">Name *</label>
                        <input required name="name" id="name" value="{{ old('name') }}" type="text" class="form-control @error('name') is-invalid @enderror" maxlength="150">
                        <small id="product_slug"></small>
                        @hasError(['inputName' => 'name'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <label class="d-block">Category *</label>
                        <select required name="category_id" id="category_id" class="selectpicker mg-b-5 @error('category_id') is-invalid @enderror" value="{{ old('category_id') }}" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select category" data-width="100%">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{strtoupper($category->name)}}</option>
                            @endforeach
                        </select>
                        @hasError(['inputName' => 'category_id'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <label>Price (in Php) *</label>
                        <input required class="form-control @error('price') is-invalid @enderror" type="number" step="0.01" min="0.00" value="0.00" name="price" id="price">
                        @hasError(['inputName' => 'price'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <label>Discount (in Php) </label>
                        <input class="form-control @error('discount') is-invalid @enderror" type="number" step="0.01" min="0.00" value="0.00" name="discount" id="discount">
                        @hasError(['inputName' => 'discount'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <label lass="d-block" id="short_descriptionLabel">Short Description</label>
                        <textarea rows="3" name="short_description" class="form-control">{{ old('short_description') }}</textarea>
                        @hasError(['inputName' => 'short_description'])@endhasError
                        <span class="invalid-feedback" role="alert" id="short_descriptionRequired" style="display: none;">
                            <strong>The short description field is required</strong>
                        </span>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="d-block" id="long_descriptionLabel">Description</label>
                        <textarea name="long_description" id="editor1" rows="10" cols="80">
                             {{ old('long_description') }}
                        </textarea>
                    </div>
                </div>

                <div class="col-lg-6 mg-t-20">
                    <div class="form-group">
                        <label class="d-block">Inventory *</label>
                        <input required name="qty" id="qty" value="{{ old('qty') }}" type="number" min="1" class="form-control @error('qty') is-invalid @enderror">
                        @hasError(['inputName' => 'qty'])
                        @endhasError
                        <small class="text-danger">Note: Please make sure that inventory value is final. For it will be posted automatically.</small>
                    </div>
 
                    <div class="form-group">
                        <label class="d-block">Reorder Point</label>
                        <input name="reorder_point" id="reorder_point" value="{{ old('reorder_point',0) }}" type="number" min="0" class="form-control @error('reorder_point') is-invalid @enderror" maxlength="250">
                        @hasError(['inputName' => 'reorder_point'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <label>Weight (grams) *</label>
                        <input required class="form-control @error('weight') is-invalid @enderror" type="number" name="weight" id="weight">
                        @hasError(['inputName' => 'weight'])
                        @endhasError
                    </div>
           
                    <div class="form-group">
                        <label class="d-block">Size</label>
                        <input name="size" value="{{ old('size') }}" type="text" class="form-control" maxlength="30">
                    </div>

                    <div class="form-group">
                        <label>Unit of Measurement *</label>
                        <input required class="form-control @error('uom') is-invalid @enderror" type="text" name="uom" id="uom">
                        @hasError(['inputName' => 'uom'])
                        @endhasError
                    </div>

                    <!-- product additional info -->
                    <div class="form-group">
                        <label class="d-block">Author/s</label>
                        <input type="text" class="form-control" data-role="tagsinput" name="authors" value="{{ old('authors') }}">
                    </div>

                    <div class="form-group">
                        <label>Materials</label>
                        <input name="materials" value="{{ old('materials') }}" type="text" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>No of Pages</label>
                        <input name="no_of_pages" value="{{ old('no_of_pages') }}" type="number" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>ISBN</label>
                        <input class="form-control" type="text" name="isbn" value="{{ old('isbn') }}">
                    </div>

                    <div class="form-group">
                        <label>Editorial Reviews</label>
                        <textarea rows="3" name="editorial_review" class="form-control">{{ old('editorial_review') }}</textarea>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="d-block" id="synopsisLabel">Synopsis</label>
                        <textarea name="synopsis" rows="10" cols="80">
                             {{ old('synopsis') }}
                        </textarea>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="d-block" id="about_authorLabel">About Author</label>
                        <textarea name="about_author" rows="10" cols="80">
                             {{ old('about_author') }}
                        </textarea>
                    </div>
                </div>
               

                <div class="col-lg-6 mg-t-20">
                    <div class="form-group">
                        <label>Additional Information</label>
                        <textarea rows="3" name="add_info" class="form-control">{{ old('add_info') }}</textarea>
                    </div>
                     <!-- product additional info -->
                    <div class="form-group">
                        <label class="d-block">Upload images</label>
                        <input type="file" id="upload_image" class="image_path" accept="image/*" multiple>
                        <button type="button" class="btn btn-secondary btn-sm upload" id="selectImages">Select images</button>
                        <p class="tx-10">
                            Required image dimension: {{ env('PRODUCT_WIDTH') }}px by {{ env('PRODUCT_HEIGHT') }}px <br /> Maximum file size: 1MB <br /> Required file type: .jpeg .png <br /> Maximum images: 5
                        </p>
                        <div class="prodimg-thumb" id="bannersDiv" style="display: none;">
                            <ul id="banners">
                                <li id="addMoreBanner">
                                    <div class="add-more txt-center upload">
                                        <i data-feather="plus-circle"></i>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="d-block">Tags</label>
                        <input type="text" class="form-control @error('tags') is-invalid @enderror" data-role="tagsinput" name="tags" id="tags" value="{{ old('tags') }}">
                        @hasError(['inputName' => 'tags'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <label class="d-block">Visibility</label>
                        <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="status" {{ (old("status") ? "checked":"") }} id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">Private</label>
                            @hasError(['inputName' => 'status'])
                            @endhasError
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Display</label>
                        <div class="custom-control custom-switch @error('isfront') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="isfront" {{ (old("isfront") ? "checked":"") }} id="customSwitch2">
                            <label class="custom-control-label" for="customSwitch2">Front Page</label>
                        </div>
                        @hasError(['inputName' => 'isfront'])
                        @endhasError
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch @error('is_featured') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="is_featured" {{ (old("is_featured") ? "checked":"") }} id="customSwitch3">
                            <label class="custom-control-label" for="customSwitch3">Featured</label>
                        </div>
                        @hasError(['inputName' => 'is_featured'])
                        @endhasError
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-switch @error('is_recommended') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="is_recommended" {{ (old("is_recommended") ? "checked":"") }} id="customSwitch4">
                            <label class="custom-control-label" for="customSwitch4">Recommended</label>
                        </div>
                        @hasError(['inputName' => 'is_recommended'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch @error('for_pickup') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="for_pickup" {{ (old("for_pickup") ? "checked":"") }} id="customSwitch5">
                            <label class="custom-control-label" for="customSwitch5">Store Pick-up</label>
                        </div>
                        @hasError(['inputName' => 'for_pickup'])
                        @endhasError
                    </div>
                </div>

                <div class="col-lg-12 mg-t-30">
                    <h4 class="mg-b-0 tx-spacing--1">Manage SEO</h4>
                    <hr>
                </div>

                <div class="col-lg-6 mg-t-30">
                    <div class="form-group">
                        <label class="d-block">Title <code>(meta title)</code></label>
                        <input type="text" class="form-control @error('seo_title') is-invalid @enderror" name="seo_title" value="{{ old('seo_title') }}">
                        @hasError(['inputName' => 'seo_title'])
                        @endhasError
                        <p class="tx-11 mg-t-4">{{ __('standard.seo.title') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Description <code>(meta description)</code></label>
                        <textarea rows="3" class="form-control @error('seo_description') is-invalid @enderror" name="seo_description">{{ old('seo_description') }}</textarea>
                        @hasError(['inputName' => 'seo_description'])
                        @endhasError
                        <p class="tx-11 mg-t-4">{{ __('standard.seo.description') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Keywords <code>(meta keywords)</code></label>
                        <textarea rows="3" class="form-control @error('seo_keywords') is-invalid @enderror" name="seo_keywords">{{ old('seo_keywords') }}</textarea>
                        @hasError(['inputName' => 'seo_keywords'])
                        @endhasError
                        <p class="tx-11 mg-t-4">{{ __('standard.seo.keywords') }}</p>
                    </div>
                </div>

                <div class="col-lg-12 mg-t-30">
                    <input class="btn btn-primary btn-sm btn-uppercase" type="submit" value="Save Product">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </div>

            </div>
        </form>
        <!-- row -->
    </div>

    <div class="modal fade" id="remove-image" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Remove image?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this image? You cannot undo this action.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="removeImage">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="image-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Image details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>File Name</label>
                        <input type="text" class="form-control" value="" disabled id="fileName">
                    </div>
                    <div class="form-group mg-b-0">
                        <label>Alt</label>
                        <input type="text" class="form-control" placeholder="Input alt text" id="changeAlt">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveChangeAlt">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>

    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>

    {{--    Image validation    --}}
    <script>
        let BANNER_WIDTH = "{{ env('PRODUCT_WIDTH') }}";
        let BANNER_HEIGHT =  "{{ env('PRODUCT_HEIGHT') }}";
        let MAX_IMAGE =  5;
    </script>
    <script src="{{ asset('js/image-upload-validation.js') }}"></script>
    {{--    End Image validation    --}}
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

        CKEDITOR.replace('about_author', options);
        CKEDITOR.replace('synopsis', options);
        CKEDITOR.replace('long_description', options);

        $(function() {
            let image_count = 1;
            let objUpload;
            let objRemove;
            $('.selectpicker').selectpicker();

            $("#customSwitch1").change(function() {
                if(this.checked) {
                    $('#label_visibility').html('Published');
                }
                else{
                    $('#label_visibility').html('Private');
                }
            });

            

            $(document).on('click', '.upload', function() {
                objUpload = $(this);
                $('#upload_image').click();
            });

            $('#upload_image').on('change', function (evt) {
                let files = evt.target.files;
                let uploadedImagesLength = $('.productImage').length;
                let totalImages = uploadedImagesLength + files.length;

                if (totalImages > 5) {
                    $('#bannerErrorMessage').html("You can upload up to 5 images only.");
                    $('#prompt-banner-error').modal('show');
                    return false;
                }

                if (totalImages == 5) {
                    $('#addMoreBanner').hide();
                }

                validate_images(evt, upload_image);
            });

            function upload_image(file)
            {
                let data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("banner", file);
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ route('products.upload') }}",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(returnData) {
                        $('#bannersDiv').show();
                        $('#selectImages').hide();
                        console.log(returnData);
                        if (returnData.status == "success") {
                            while ($('input[name="photos['+image_count+'][image_path]"]').length) {
                                image_count += 1;
                            }

                            let radioCheck = (image_count == 1) ? 'checked' : '';

                            $(`<li class="productImage" id="`+image_count+`li">
                                <div class="prodmenu-left" data-toggle="modal" data-target="#image-details" data-id="`+image_count+`" data-name="`+returnData.image_name+`">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="19" cy="12" r="1"></circle>
                                        <circle cx="5" cy="12" r="1"></circle>
                                    </svg>
                                </div>
                                <div class="prodmenu-right" data-toggle="modal" data-target="#remove-image"  data-id="`+image_count+`">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </div>
                                <label class="form-check-label radio" for="exampleRadios`+image_count+`" data-toggle="tooltip" data-placement="bottom" title="Set as primary image">
                                    <input class="form-check-input imageRadio" type="radio" name="is_primary" id="exampleRadios`+image_count+`" value="`+image_count+`" `+radioCheck+`>
                                    <input name="photos[`+image_count+`][image_path]" type="hidden" value="`+returnData.image_url+`">
                                    <input name="photos[`+image_count+`][name]" type="hidden" id="`+image_count+`name" value="">
                                    <img src="`+returnData.image_url+`" />
                                    <div class="radio-button"></div>
                                </label>
                            </li>`).insertBefore('#addMoreBanner');
                        }
                    },
                    failed: function() {
                        alert('FAILED NGA!');
                    }
                });
            }

            let imageId;
            $('#image-details').on('show.bs.modal', function(e) {
                let selectedImage = e.relatedTarget;
                imageId = $(selectedImage).data('id');
                let imageName = $(selectedImage).data('name');
                $('#fileName').val(imageName);
                $('#changeAlt').val($('#'+imageId+"name").val());
            });

            $('#saveChangeAlt').on('click', function() {
                $('#'+imageId+"name").val($('#changeAlt').val());
                $('#image-details').modal('hide');
            });

            $('#image-details').on('hide.bs.modal', function() {
                $('#fileName').val('');
                $('#changeName').val('');
                imageId = 0;
            });

            $('#remove-image').on('show.bs.modal', function(e) {
                let selectedImage = e.relatedTarget;
                imageId = $(selectedImage).data('id');
            });

            $('#removeImage').on('click', function() {
                let isChecked = $('#exampleRadios'+imageId).is(':checked');
                $('#'+imageId+"li").remove();
                $('#addMoreBanner').show();
                if (isChecked) {
                    $.each($('.imageRadio'), function () {
                        $(this).prop('checked', true);
                        return false;
                    });
                }
                $('#remove-image').modal('hide');
            });

            $('#image-details').on('hide.bs.modal', function() {
                imageId = 0;
            });
        });

        /** Generation of the page slug **/
        $('#name').change(function(){

            var url = $('#name').val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "/admin/product-get-slug",
                data: { url: url }
            })

            .done(function(response){
                slug_url = '{{env('APP_URL')}}/products/'+response;
                $('#product_slug').html("<a target='_blank' href='"+slug_url+"'>"+slug_url+"</a>");
            });
        });

       
    </script>
@endsection
