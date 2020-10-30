@extends('admin.layouts.app')

@section('pagetitle')
    Website Settings
@endsection

@section('pagecss')
    <script src="{{ asset('lib/ckeditor/ckeditor.js') }}"></script>

    <style>
        .weekDays-selector input {
            display: none!important;
        }

        .weekDays-selector input[type=checkbox] + label {
            display: inline-block;
            border-radius: 6px;
            background: #dddddd;
            height: 40px;
            width: 30px;
            margin-right: 3px;
            line-height: 40px;
            text-align: center;
            cursor: pointer;
        }

        .weekDays-selector input[type=checkbox]:checked + label {
            background: #b82e24;
            color: #ffffff;
        }

        .file-upload{display:block;text-align:center;font-family: Helvetica, Arial, sans-serif;font-size: 12px;}
        .file-upload .file-select{display:block;border: 2px solid #dce4ec;color: #34495e;cursor:pointer;height:40px;line-height:40px;text-align:left;background:#FFFFFF;overflow:hidden;position:relative;}
        .file-upload .file-select .file-select-button{background:#dce4ec;padding:0 10px;display:inline-block;height:40px;line-height:40px;}
        .file-upload .file-select .file-select-name{line-height:40px;display:inline-block;padding:0 10px;}
        .file-upload.active .file-select{border-color:#b82e24;transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;}
        .file-upload.active .file-select .file-select-button{background:#b82e24;color:#FFFFFF;transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;}
        .file-upload .file-select input[type=file]{z-index:100;cursor:pointer;position:absolute;height:100%;width:100%;top:0;left:0;opacity:0;filter:alpha(opacity=0);}
        .file-upload .file-select.file-select-disabled{opacity:0.65;}

    </style>
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page">Settings</li>
                        <li class="breadcrumb-item active" aria-current="page">Website Settings</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Website Settings</h4>
            </div>
        </div>
        <div class="row row-sm">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link @if(session()->has('tabname')) @else active @endif" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Website</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#social" role="tab" aria-controls="social" aria-selected="false">Social Media</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="privacy-tab" data-toggle="tab" href="#privacy" role="tab" aria-controls="privacy" aria-selected="false">Data Privacy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(session()->has('tabname') && session('tabname') == 'ecommerce') active @endif" id="ecommerce-tab" data-toggle="tab" href="#ecommerce" role="tab" aria-controls="ecommerce" aria-selected="false">Ecommerce</a>
                    </li>
                </ul>
                <div class="tab-content rounded bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
                    <!-- Website Settings Tab -->
                    <div class="tab-pane fade @if(session()->has('tabname')) @else show active @endif" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="col-md-6 mg-t-15">
                            <form method="POST" action="{{ route('website-settings.update') }}" enctype="multipart/form-data" id="selectForm1" class="parsley-style-1" data-parsley-validate novalidate>
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <div id="company" class="parsley-input">
                                        <label>Company Name <span class="tx-danger">*</span></label>
                                        <input type="text" name="company_name" data-toggle="tooltip" data-placement="right" data-title="The company name will appear at the footer of your website" class="form-control" value="{{ old('company_name',$web->company_name) }}" data-parsley-class-handler="#company" required @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div id="website" class="parsley-input">
                                        <label>Website Name <span class="tx-danger">*</span></label>
                                        <input type="text" name="website_name" data-toggle="tooltip" data-placement="right" data-title="The website name will appear at the login page of your CMS" class="form-control" value="{{ old('website_name',$web->website_name) }}" data-parsley-class-handler="#website" required @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div id="copyright" class="parsley-input">
                                        <label>Copyright year <span class="tx-danger">*</span></label>
                                        <input required type="text" name="copyright" class="form-control" data-parsley-class-handler="#copyright" value="{{ old('copyright',$web->copyright) }}" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                    </div>
                                </div>


                                <div class="form-group {{ $errors->has('company_logo') ? 'has-error' : '' }}">
                                    <label class="d-block">Logo</label>
                                    <div class="custom-file">
                                        <input type="file" class="form-control" id="company_logo" name="company_logo">
                                        <span class="text-danger tx-12">{{ $errors->first('company_logo') }}</span>
                                    </div>
                                    <p class="tx-10">
                                        Maximum file size: 1MB <br /> File extension: PNG, JPG, SVG
                                    </p>
                                    @if(empty($web->company_logo))
                                        <div id="image_div" style="display:none;">
                                            <img src="" height="100" width="300" id="img_temp" alt="Company Logo">  <br /><br />
                                        </div>
                                    @else
                                        <div>
                                            <img src="{{ asset('storage/logos/'.$web->company_logo) }}" id="img_temp" height="100" width="300" alt="Company Logo">  <br /><br />
                                            <button type="button" class="btn btn-danger btn-xs btn-uppercase remove-logo" type="button" data-id=""><i data-feather="x"></i> Remove Logo</button>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('web_favicon') ? 'has-error' : '' }}">
                                    <label class="d-block">Favicon</label>
                                    <div class="custom-file">
                                        <input type="file" class="form-control" id="web_favicon" name="web_favicon" >
                                        <span class="text-danger tx-12">{{ $errors->first('web_favicon') }}</span>
                                    </div>
                                    <p class="tx-10">
                                        Required image dimension: 128px by 128px <br /> Maximum file size: 100KB <br/> File extension: ICO
                                    </p>
                                    @if(empty($web->website_favicon))
                                        <div id="icon_div" style="display:none;">
                                            <img src="" height="100" width="300" id="icon_temp" alt="Website Favicon">  <br /><br />
                                        </div>
                                    @else
                                        <div>
                                            <img src="{{ asset('storage/icons/'.$web->website_favicon) }}" height="100" width="300" id="icon_temp" alt="Website Favicon">  <br /><br />
                                            <button type="button" class="btn btn-danger btn-xs btn-uppercase remove-icon" type="button"><i data-feather="x"></i> Remove Icon</button>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="d-block">Google Analytics Tracking Code</label>
                                    <textarea rows="3" name="g_analytics_code" class="form-control">{{ old('g_analytics_code',$web->google_analytics) }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label class="d-block">Google Map</label>
                                    <textarea rows="6" name="g_map" class="form-control">{{ old('g_map',$web->google_map) }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label class="d-block">Google reCaptcha Code <span class="tx-danger">*</span></label>
                                    <textarea required rows="3" name="g_recaptcha_sitekey" class="form-control" @htmlValidationMessage({{__('standard.empty_all_field')}})>{{ old('g_recaptcha_sitekey',$web->google_recaptcha_sitekey) }}</textarea>
                                </div>

                                <div class="col-lg-12 mg-t-30 ">
                                    <button class="btn btn-primary btn-sm btn-uppercase " type="submit ">Save Settings</button>
                                    <a href="{{ route('website-settings.edit') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Discard Changes</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Tab -->
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="col-md-6 mg-t-15">
                            <p class="tx-13 mg-b-40"><i data-feather="zap" class="wd-12"></i><strong> Tip</strong> <br />{{__('standard.settings.website.tip_helper')}}</p>
                            <form  method="POST" action="{{route('website-settings.update-contacts')}}" id="selectForm2" class="parsley-style-1" data-parsley-validate novalidate>
                                @csrf
                                <div class="form-group">
                                    <label>Company Address<span class="tx-danger">*</span></label>
                                    <textarea id="company_address" name="company_address" class="form-control" required @htmlValidationMessage({{__('standard.empty_all_field')}})>{{ $web->company_address }}</textarea>
                                </div>
                                <div class="form-group">
                                    <div id="mob_no" class="parsley-input">
                                        <label>Mobile Number/s <span class="tx-danger">*</span></label>
                                        <input type="text" id="mobile_no" name="mobile_no" class="form-control" value="{{ $web->mobile_no }}" data-parsley-class-handler="#mob_no" required @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Fax Number</label>
                                    <input type="text" id="fax_no" name="fax_no" class="form-control" value="{{ $web->fax_no }}">
                                </div>
                                <div class="form-group">
                                    <div id="tel_no" class="parsley-input">
                                        <label>Telephone Number/s <span class="tx-danger">*</span></label>
                                        <input type="text" id="telephone_no" name="tel_no" class="form-control" value="{{ $web->tel_no }}" data-parsley-class-handler="#tel_no" placeholder="000 000-0000" required @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div id="email" class="parsley-input">
                                        <label>Email Address/es <span class="tx-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ $web->email }}" data-parsley-class-handler="#email" required  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                    </div>
                                </div>
                                <div class="col-lg-12 mg-t-30 ">
                                    <button class="btn btn-primary btn-sm btn-uppercase " type="submit ">Save Settings</button>
                                    <a href="{{ route('website-settings.edit') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Discard Changes</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Social Tab -->
                    <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="col-lg-12 mg-t-15">
                            <div class="col-md-6">
                                <div class="form-group multiple-form-group">
                                    <label>Social Media Accounts</label>
                                    <form method="post" action="{{route('website-settings.update-media-accounts')}}">
                                        @csrf
                                        @forelse($medias as $media)
                                            <div class="form-group input-group input-icon">
                                                <input type="hidden" value="{{$media->id}}" name="mid[]">
                                                <select name="social_media[]"  class="form-control">
                                                    <option value="">Choose One</option>
                                                    <option @if($media->name == 'facebook') selected @endif value="facebook">Facebook</option>
                                                    <option @if($media->name == 'messenger') selected @endif value="messenger">Messenger</option>
                                                    <option @if($media->name == 'twitter') selected @endif value="twitter">Twitter</option>
                                                    <option @if($media->name == 'youtube') selected @endif value="youtube">Youtube</option>
                                                    <option @if($media->name == 'viber') selected @endif value="viber">Viber</option>
                                                    <option @if($media->name == 'whatsapp') selected @endif value="whatsapp">Whatsapp</option>
                                                    <option @if($media->name == 'instagram') selected @endif value="instagram">Instagram</option>
                                                </select>
                                                &nbsp;
                                                <input type="text" class="form-control" name="url[]" value="{{ $media->media_account }}">
                                                <span class="input-group-btn">&nbsp;<button type="button" data-mid="{{$media->id}}" class="btn btn-danger remove-media">x</button></span>
                                            </div>
                                        @empty

                                        @endforelse
                                        <div class="form-group input-group input-icon">
                                            <input type="hidden" name="mid[]">
                                            <select name="social_media[]"  class="form-control">
                                                <option value="">Choose One</option>
                                                <option value="facebook">Facebook</option>
                                                <option value="messenger">Messenger</option>
                                                <option value="twitter">Twitter</option>
                                                <option value="youtube">Youtube</option>
                                                <option value="viber">Viber</option>
                                                <option value="whatsapp">Whatsapp</option>
                                                <option value="instagram">Instagram</option>
                                            </select>
                                            &nbsp;
                                            <input type="text" class="form-control" name="url[]" placeholder="URL">
                                            <span class="input-group-btn">&nbsp;<button type="button" class="btn btn-sm btn-primary btn-add"><i>+</i>
                                        </button></span>
                                        </div>
                                        <div class="col-lg-12 mg-t-30 ">
                                            <button class="btn btn-primary btn-sm btn-uppercase " type="submit ">Save Settings</button>
                                            <a href="{{ route('website-settings.edit') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Discard Changes</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Privacy Tab -->
                    <div class="tab-pane fade" id="privacy" role="tabpanel" aria-labelledby="privacy-tab">
                        <div class="col-lg-12 mg-t-15">
                            <form action="{{route('website-settings.update-data-privacy')}}" method="post" class="parsley-style-1" data-parsley-validate novalidate>
                                @csrf
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div id="title" class="parsley-input">
                                            <label>Page Title <span class="tx-danger">*</span></label>
                                            <input type="text" name="privacy_title" class="form-control" data-parsley-class-handler="#title" value="{{ old('privacy_title',$web->data_privacy_title) }}" required @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div id="pop_up" class="parsley-input">
                                            <label>Pop-up Content <span class="tx-danger">*</span></label>
                                            <textarea rows="3" name="pop_up_content" class="form-control" data-parsley-class-handler="#pop_up" required @htmlValidationMessage({{__('standard.empty_all_field')}})>{{ old('pop_up_content',$web->data_privacy_popup_content) }}</textarea>
                                            <small><i data-feather="alert-circle" width="13"></i> {{__('standard.settings.website.pop-up_helper')}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="d-block">Content <span class="tx-danger">*</span></label>
                                        <textarea required name="content" id="editor1" rows="10" cols="80">
                                        {!! old('content',$web->data_privacy_content) !!}
                                    </textarea>
                                        <span class="invalid-feedback" role="alert" id="contentRequired" style="...">
                                        <strong>The content field is required</strong>
                                    </span>
                                        <script>
                                            // Replace the <textarea id="editor1"> with a CKEditor
                                            // instance, using default configuration.
                                            var options = {
                                                filebrowserImageBrowseUrl: '{{ env('APP_URL') }}/laravel-filemanager?type=Images',
                                                filebrowserImageUpload: '{{ env('APP_URL') }}/laravel-filemanager/upload?type=Images&_token={{ csrf_token() }}',
                                                filebrowserBrowseUrl: '{{ env('APP_URL') }}/laravel-filemanager?type=Files',
                                                filebrowserUploadUrl: '{{ env('APP_URL') }}/laravel-filemanager/upload?type=Files&_token={{ csrf_token() }}',
                                                allowedContent: true,
                                            };
                                            let editor = CKEDITOR.replace('content', options);
                                            editor.on('required', function (evt) {
                                                if($('.invalid-feedback').length == 1){
                                                    $('#contentRequired').show();
                                                }
                                                $('#cke_editor1').addClass('is-invalid');
                                                evt.cancel();
                                            });
                                        </script>
                                        @error('content')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                        <div class="alert alert-danger" id="contentRequired" style="display: none;">The content field is required</div>
                                    </div>
                                </div>

                                <div class="col-lg-12 mg-t-30">
                                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Save Settings</button>
                                    <a href="{{ route('website-settings.edit') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Discard Changes</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Ecommerce Tab -->
                    <div class="tab-pane fade @if(session()->has('tabname') && session('tabname') == 'ecommerce') show active @endif" id="ecommerce" role="tabpanel" aria-labelledby="ecommerce-tab">
                        <div class="col-lg-12 mg-t-15">
                            <div class="col-md-9">
                                <h4>Payment Options</h4>
                                <div class="form-group">
                                    <div class="parsley-input">                                            
                                        <input type="checkbox" @if(\App\EcommerceModel\PaymentList::paymentOptionstatus(1) == 1) checked @endif onclick="optionCC(1)" id="cb-credit-card">
                                        Option 1: Credit Card Payment
                                    </div>
                                </div>
                                <hr>

                                <form method="post" action="{{ route('ecom-setting-bank-update') }}">
                                @csrf
                                    <div class="form-group">
                                        <div class="parsley-input">                                            
                                            <input type="checkbox" @if(\App\EcommerceModel\PaymentList::paymentOptionstatus(2) == 1) checked @endif onclick="optionFT(2)" id="cb-fund-transfer">
                                            Option 2: Online Fund Transfer

                                            <table class="table table-borderless">
                                                <thead>
                                                    <th></th>
                                                    <th>Bank Name</th>
                                                    <th>Account Name</th>
                                                    <th>Account #</th>
                                                    <th>Branch</th>
                                                    <th></th>
                                                </thead>
                                                <tbody>
                                                    @foreach($banks as $bank)
                                                    <tr>
                                                        <td class="text-right" width="10%"><input type="checkbox" name="bank[]" value="{{$bank->id}}" @if($bank->is_active == 1) checked @endif id="{{ $bank->id }}"></td>
                                                        <td>{{ $bank->name }}</td>
                                                        <td>{{ $bank->account_name }}</td>
                                                        <td>{{ $bank->account_no }}</td>
                                                        <td>{{ $bank->branch }}</td>
                                                        <td class="text-right">
                                                            <a href="javascript:void(0)" onclick="edit_bank('{{$bank->id}}','{{$bank->name}}','{{$bank->account_no}}','{{$bank->branch}}','{{$bank->account_name}}')"><i class="fa fa-edit"></i></a>
                                                            <a href="javascript:void(0)" onclick="delete_bank('{{$bank->id}}')"><i class="fa fa-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-group mg-l-30">
                                        <button type="submit" class="btn btn-xs btn-primary">Save Changes</button>
                                        <button type="button" onclick="add_bank()" class="btn btn-xs btn-secondary">Add Bank</button>
                                    </div>
                                </form>
                                <hr>

                                <form method="post" action="{{ route('ecom-setting-remittance-update') }}">
                                @csrf
                                    <div class="form-group">
                                        <div class="parsley-input">                                            
                                            <input type="checkbox" @if(\App\EcommerceModel\PaymentList::paymentOptionstatus(3) == 1) checked @endif onclick="optionMT(3)" id="cb-money-transfer">
                                            Option 3: Money Transfer

                                            <table class="table table-borderless">
                                                <thead>
                                                    <th></th>
                                                    <th>Name</th>
                                                    <th>Account #</th>
                                                    <th>Recipient</th>
                                                    <th>QR Code</th>
                                                    <th></th>
                                                </thead>
                                                <tbody>
                                                    @foreach($remittances as $remittance)
                                                    <tr>
                                                        <td class="text-right" width="10%"><input type="checkbox" name="remittance[]" value="{{$remittance->id}}" @if($remittance->is_active == 1) checked @endif id="{{ $remittance->id }}"></td>
                                                        <td>{{ $remittance->name }}</td>
                                                        <td>{{ $remittance->account_no }}</td>
                                                        <td>{{ $remittance->recipient }}</td>
                                                        <td><a href="{{ asset('storage/qrcodes/'.$remittance->id.'/'.$remittance->qrcode) }}" target="_blank">{{ $remittance->qrcode }}</a></td>
                                                        <td class="text-right">
                                                            <a href="javascript:void(0)" onclick="edit_remittance('{{$remittance->id}}','{{$remittance->name}}','{{$remittance->qrcode}}','{{$remittance->account_no}}','{{$remittance->recipient}}')"><i class="fa fa-edit"></i></a>
                                                            <a href="javascript:void(0)" onclick="delete_remittance('{{$remittance->id}}')"><i class="fa fa-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-group mg-l-30">
                                        <button type="submit" class="btn btn-xs btn-primary">Save Changes</button>
                                        <button type="button" onclick="add_remittance()" class="btn btn-xs btn-secondary">Add Remittance</button>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-6">
                                <h4>Checkout Options</h4>
                                <div class="form-group">
                                    <div class="parsley-input">
                                        Option 4: Cash on Delivery
                                    </div>
                                </div>

                                <div class="mg-l-30">
                                    <form method="post" action="{{ route('ecom-setting-cash-on-delivery-update') }}">
                                        @csrf
                                        <div class="form-group">
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="checkbox" {{ ($cod->within_metro_manila == 1 ? "checked":"") }} name="within_metro_manila" id="withinMetroManila">
                                              <label class="form-check-label" for="withinMetroManila">Metro Manila</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input" type="checkbox" {{ ($cod->outside_metro_manila == 1 ? "checked":"") }} name="outside_metro_manila" id="outsideMetroManila">
                                              <label class="form-check-label" for="outsideMetroManila">Outside Metro Manila</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div id="title" class="parsley-input">
                                                <label>Service Fee</label>
                                                <input type="hidden" name="id" value="{{$cod->id}}">
                                                <input type="number" name="service_fee" id="cod_service_fee" class="form-control" data-parsley-class-handler="#service_fee" value="{{ $cod->service_fee }}" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div id="title" class="parsley-input">
                                                <label>Maximum Purchase</label>
                                                <input type="number" name="max_purchase" id="cod_max_purchase" class="form-control" data-parsley-class-handler="#cod_max_purchase" value="{{ $cod->maximum_purchase }}" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div id="title" class="parsley-input">
                                                <label>Note/Reminder</label>
                                                <textarea name="reminder" class="form-control" cols="5">{{ $cod->reminder }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Allowed Days</label>
                                            @php
                                                $cod_days = [];

                                                foreach(explode('|',$cod->allowed_days) as $day){
                                                    array_push($cod_days,$day);
                                                }
                                            @endphp

                                            <div class="weekDays-selector">
                                                <input type="checkbox" value="Mon" name="cod_days[]" id="cod-mon" class="weekday" @if(in_array('Mon',$cod_days)) checked @endif/>
                                                <label for="cod-mon">M</label>
                                                <input type="checkbox" value="Tue" name="cod_days[]" id="cod-tue" class="weekday" @if(in_array('Tue',$cod_days)) checked @endif/>
                                                <label for="cod-tue">T</label>
                                                <input type="checkbox" value="Wed" name="cod_days[]" id="cod-wed" class="weekday" @if(in_array('Wed',$cod_days)) checked @endif/>
                                                <label for="cod-wed">W</label>
                                                <input type="checkbox" value="Thu" name="cod_days[]" id="cod-thu" class="weekday" @if(in_array('Thu',$cod_days)) checked @endif/>
                                                <label for="cod-thu">T</label>
                                                <input type="checkbox" value="Fri" name="cod_days[]" id="cod-fri" class="weekday" @if(in_array('Fri',$cod_days)) checked @endif/>
                                                <label for="cod-fri">F</label>
                                                <input type="checkbox" value="Sat" name="cod_days[]" id="cod-sat" class="weekday" @if(in_array('Sat',$cod_days)) checked @endif/>
                                                <label for="cod-sat">S</label>
                                                <input type="checkbox" value="Sun" name="cod_days[]" id="cod-sun" class="weekday" @if(in_array('Sun',$cod_days)) checked @endif/>
                                                <label for="cod-sun">S</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-xs btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                                <hr>

                                <div class="form-group">
                                    <div class="parsley-input">
                                        Option 5: Store Pick up
                                    </div>
                                </div>

                                <div class="mg-l-30">
                                    <form method="post" action="{{ route('ecom-setting-store-pickup-update') }}">
                                    @csrf
                                        <div class="form-group">
                                            <div id="title" class="parsley-input">
                                                <label>Note/Reminder</label>
                                                <input type="hidden" name="id" value="{{$stp->id}}">
                                                <textarea name="reminder" class="form-control" cols="5">{{ $stp->reminder }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Allowed Days</label>
                                            @php
                                                $stp_days = [];

                                                foreach(explode('|',$stp->allowed_days) as $day){
                                                    array_push($stp_days,$day);
                                                }
                                            @endphp

                                            <div class="weekDays-selector">
                                                <input type="checkbox" value="Mon" name="stp_days[]" id="stp-mon" class="weekday" @if(in_array('Mon',$stp_days)) checked @endif/>
                                                <label for="stp-mon">M</label>
                                                <input type="checkbox" value="Tue" name="stp_days[]" id="stp-tue" class="weekday" @if(in_array('Tue',$stp_days)) checked @endif/>
                                                <label for="stp-tue">T</label>
                                                <input type="checkbox" value="Wed" name="stp_days[]" id="stp-wed" class="weekday" @if(in_array('Wed',$stp_days)) checked @endif/>
                                                <label for="stp-wed">W</label>
                                                <input type="checkbox" value="Thu" name="stp_days[]" id="stp-thu" class="weekday" @if(in_array('Thu',$stp_days)) checked @endif/>
                                                <label for="stp-thu">T</label>
                                                <input type="checkbox" value="Fri" name="stp_days[]" id="stp-fri" class="weekday" @if(in_array('Fri',$stp_days)) checked @endif/>
                                                <label for="stp-fri">F</label>
                                                <input type="checkbox" value="Sat" name="stp_days[]" id="stp-sat" class="weekday" @if(in_array('Sat',$stp_days)) checked @endif/>
                                                <label for="stp-sat">S</label>
                                                <input type="checkbox" value="Sun" name="stp_days[]" id="stp-sun" class="weekday" @if(in_array('Sun',$stp_days)) checked @endif/>
                                                <label for="stp-sun">S</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="form-row">
                                                <div class="col">
                                                    <label>Time From*</label>
                                                    <input type="time" name="time_from" value="{{ $stp->allowed_time_from }}" class="form-control">
                                                </div>
                                                <div class="col">
                                                    <label>Time To*</label>
                                                    <input type="time" name="time_to" value="{{ $stp->allowed_time_to }}" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-xs btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                                <hr>

                                <div class="form-group">
                                    <div class="parsley-input">
                                        Option 6: Door to Door Delivery
                                    </div>
                                </div>
                                <hr>
                                
                                <div class="form-group">
                                    <div class="parsley-input">
                                        Option 7: Same Day Delivery
                                    </div>
                                </div>

                                <div class="mg-l-30">
                                    <form method="post" action="{{ route('ecom-setting-same-day-delivery-update') }}">
                                    @csrf
                                        <div class="form-group">
                                            <div id="title" class="parsley-input">
                                                <label>Service Fee</label>
                                                <input type="hidden" name="id" value="{{$sdd->id}}">
                                                <input type="number" name="service_fee" id="sdd_service_fee" class="form-control" data-parsley-class-handler="#title" value="{{ $sdd->service_fee }}" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div id="title" class="parsley-input">
                                                <label>Maximum Purchase</label>
                                                <input type="number" name="max_purchase" id="sdd_max_pruchase" class="form-control" data-parsley-class-handler="#sdd_max_pruchase" value="{{ $sdd->maximum_purchase }}" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div id="title" class="parsley-input">
                                                <label>Note/Reminder</label>
                                                <textarea name="reminder" class="form-control" cols="5">{{ $sdd->reminder }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Allowed Days</label>
                                            @php
                                                $sdd_days = [];

                                                foreach(explode('|',$sdd->allowed_days) as $day){
                                                    array_push($sdd_days,$day);
                                                }
                                            @endphp

                                            <div class="weekDays-selector">
                                                <input type="checkbox" value="Mon" name="sdd_days[]" id="sdd-mon" class="weekday" @if(in_array('Mon',$sdd_days)) checked @endif/>
                                                <label for="sdd-mon">M</label>
                                                <input type="checkbox" value="Tue" name="sdd_days[]" id="sdd-tue" class="weekday" @if(in_array('Tue',$sdd_days)) checked @endif/>
                                                <label for="sdd-tue">T</label>
                                                <input type="checkbox" value="Wed" name="sdd_days[]" id="sdd-wed" class="weekday" @if(in_array('Wed',$sdd_days)) checked @endif/>
                                                <label for="sdd-wed">W</label>
                                                <input type="checkbox" value="Thu" name="sdd_days[]" id="sdd-thu" class="weekday" @if(in_array('Thu',$sdd_days)) checked @endif/>
                                                <label for="sdd-thu">T</label>
                                                <input type="checkbox" value="Fri" name="sdd_days[]" id="sdd-fri" class="weekday" @if(in_array('Fri',$sdd_days)) checked @endif/>
                                                <label for="sdd-fri">F</label>
                                                <input type="checkbox" value="Sat" name="sdd_days[]" id="sdd-sat" class="weekday" @if(in_array('Sat',$sdd_days)) checked @endif/>
                                                <label for="sdd-sat">S</label>
                                                <input type="checkbox" value="Sun" name="sdd_days[]" id="sdd-sun" class="weekday" @if(in_array('Sun',$sdd_days)) checked @endif/>
                                                <label for="sdd-sun">S</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="form-row">
                                                <div class="col">
                                                    <label>Time From*</label>
                                                    <input type="time" name="time_from" value="{{ $sdd->allowed_time_from }}" class="form-control">
                                                </div>
                                                <div class="col">
                                                    <label>Time To*</label>
                                                    <input type="time" name="time_to" value="{{ $sdd->allowed_time_to }}" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-xs btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.settings.website.modal')
@endsection

@section('pagejs')
    <script src="{{ asset('lib/cleave.js/cleave.min.js')}}"></script>
    <script src="{{ asset('lib/cleave.js/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('lib/parsleyjs/parsley.min.js') }}"></script>

    {{--    Image validation    --}}
    <script>
        let errorMessages;
        let QR_WIDTH = "{{ env('QR_WIDTH') }}";
        let QR_HEIGHT =  "{{ env('QR_HEIGHT') }}";
    </script>
    {{--    Image validation    --}}
@endsection

@section('customjs')

    <script>
        $('#min_order_is_allowed').change(function(){
            if (this.checked) {
                // the checkbox is now checked 
                $('#div1').show();
                $('#min_order').attr('min', '1');
            } else {
                // the checkbox is now no longer checked
                $('#min_order').removeAttr('min');
                $('#div1').hide();
            }
        });

        var _cURL = window.URL || window.webkitURL;
        $('#qrfile_create').change(function () {
            var file = $(this)[0].files[0];
            var ext  = $(this).val().split('.').pop().toLowerCase();

            img = new Image();
            var imgwidth = 0;
            var imgheight = 0;
            var file_size = (file.size / 1048576).toFixed(3);

            qr_validation(ext,file_size,file.name,'create')

            img.src = _cURL.createObjectURL(file);
            img.onload = function() {
                imgwidth = this.width;
                imgheight = this.height;

                if (imgwidth != QR_WIDTH || imgheight != QR_HEIGHT) {
                    qr_dimension(1,file.name,'create');
                } else {
                    qr_dimension(0,file.name,'create');
                }

            }
        });

        var _eURL = window.URL || window.webkitURL;
        $('#qrfile_update').change(function () {
            var file = $(this)[0].files[0];
            var ext  = $(this).val().split('.').pop().toLowerCase();

            img = new Image();
            var imgwidth = 0;
            var imgheight = 0;
            var file_size = (file.size / 1048576).toFixed(3);

            qr_validation(ext,file_size,file.name,'update')

            img.src = _eURL.createObjectURL(file);
            img.onload = function() {
                imgwidth = this.width;
                imgheight = this.height;

                if (imgwidth != QR_WIDTH || imgheight != QR_HEIGHT) {
                    qr_dimension(1,file.name,'update');
                } else {
                    qr_dimension(0,file.name,'update');
                }

            }
        });

        function qr_dimension(result,filename,action){
            if(result == 1){
                $('#qrfile_'+action).val('');
                $('#span_file_dimension_'+action).css('display','block');
                $('#span_file_size_'+action).css('display','none');
                $('#span_file_type_'+action).css('display','none');

                if(action == 'update'){
                    $('#btnEditRemittance').attr('disabled',true); 
                } else {
                    $('#btnAddRemittance').attr('disabled',true); 
                }
                

                $('#span_file_dimension_'+action).html(filename + ' has invalid dimensions.');
            } else {
                if(action == 'update'){
                    $('#btnEditRemittance').attr('disabled',false); 
                } else {
                    $('#btnAddRemittance').attr('disabled',false); 
                }
                $('#span_file_dimension_'+action).css('display','none');
            }

        }

        function qr_validation(ext,size,filename,action){
            if ($.inArray(ext, ['jpeg', 'jpg', 'png']) == -1) {
                $('#qrfile_'+action).val('');
                $('#span_file_type_'+action).css('display','block');
                $('#span_file_dimension_'+action).css('display','none');
                $('#span_file_size_'+action).css('display','none');

                if(action == 'update'){
                    $('#btnEditRemittance').attr('disabled',true); 
                } else {
                    $('#btnAddRemittance').attr('disabled',true); 
                }

                $('#span_file_type_'+action).html(filename+ ' has invalid extension');         
            } else {
                if(action == 'update'){
                    $('#btnEditRemittance').attr('disabled',false); 
                } else {
                    $('#btnAddRemittance').attr('disabled',false); 
                }
                $('#span_file_type_'+action).css('display','none');
            }

            if (size > 1) {
                $('#qrfile_'+action).val('');
                $('#span_file_size_'+action).css('display','block');
                $('#span_file_dimension_'+action).css('display','none');
                $('#span_file_type_'+action).css('display','none');

                if(action == 'update'){
                    $('#btnEditRemittance').attr('disabled',true); 
                } else {
                    $('#btnAddRemittance').attr('disabled',true); 
                }

                $('#span_file_size_'+action).html(filename+ ' exceeded the maximum file size');        
            } else {
                if(action == 'update'){
                    $('#btnEditRemittance').attr('disabled',false); 
                } else {
                    $('#btnAddRemittance').attr('disabled',false); 
                }

                $('#span_file_size_'+action).css('display','none');
            }
        }


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


        $(document).ready(function(){
            var i = 1;
            $('#add').on('click', function(){
                i++;
                var input = $(
                    '<tr id="row'+i+'">'
                    + '<td><input type="text" class="form-control" name="name[]" placeholder="Enter here..."/></td>'
                    + '<td><button type="button" id="'+i+'"class="btn btn-danger remove"><span class="glyphicon glyphicon-trash"></span></button></td>'
                    + '</tr>'
                );
                $('#dynamic_input').append(input);
            });

            $(document).on('click', '.remove', function(){
                var btn_id = $(this).attr("id");
                $('#row'+btn_id+'').remove();
            })
        });
    </script>

    <script>
        $(document).on('click', '.remove-logo', function() {
            $('#prompt-remove-logo').modal('show');
        });

        $(document).on('click', '.remove-icon', function() {
            $('#prompt-remove-icon').modal('show');
        });

        $(document).on('click', '.remove-media', function() {
            $('#prompt-delete-social').modal('show');
            $('#mid').val($(this).data('mid'));
        });

        // Company Logo
        $("#company_logo").change(function() {
            readLogo(this);
        });

        function readLogo(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#img_temp').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
                $('#image_div').show();
                $('.remove-logo').hide();
            }
        }

        // Web Favicon
        $("#web_favicon").change(function() {
            readIcon(this);
        });

        $("#min_order").change(function() {
            if($(this).val() > 0){
                $('#promo_header').show();
            }
            else{
                $('#promo_header').hide();
            }
        });

        function readIcon(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#icon_temp').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
                $('#icon_div').show();
                $('.remove-icon').hide();
            }
        }
    </script>

    <script>
        function add_bank(){
            $('#prompt-add-bank').modal('show');
        }

        function edit_bank(id,name,accountno,branch,account_name){
            $('#prompt-edit-bank').modal('show');
            $('#bank_id').val(id);
            $('#bankname').val(name);
            $('#bankaccountno').val(accountno);
            $('#bankaccountname').val(account_name);
            $('#bankbranch').val(branch);
        }

        function delete_bank(id){
            $('#prompt-delete-bank').modal('show');
            $('#dbank_id').val(id);
        }

        function add_remittance()
        {
            $('#prompt-add-remittance').modal('show');
        }

        $('#qrfile_update').bind('change', function () {
          var filename = $("#qrfile_update").val();
          if (/^\s*$/.test(filename)) {
            $(".file-upload").removeClass('active');
            $("#noFile").text("No file chosen..."); 
          }
          else {
            $(".file-upload").addClass('active');
            $("#noFile").text(filename.replace("C:\\fakepath\\", "")); 
          }
        });

        $('#qrfile_create').bind('change', function () {
          var filename = $("#qrfile_create").val();
          if (/^\s*$/.test(filename)) {
            $(".file-upload").removeClass('active');
            $("#noFileCreate").text("No file chosen..."); 
          }
          else {
            $(".file-upload").addClass('active');
            $("#noFileCreate").text(filename.replace("C:\\fakepath\\", "")); 
          }
        });

        function edit_remittance(id,name,qrcode,accountno,recipient)
        {
            $('#prompt-edit-remittance').modal('show');
            $('#recipient').val(recipient);
            $('#remittance_account_no').val(accountno);
            $('#remittance_id').val(id);
            $('#remittance_name').val(name);
            $('#noFile').html(qrcode);
        }

        function delete_remittance(id){
            $('#prompt-delete-remittance').modal('show');
            $('#dremittance_id').val(id);
        }


        var ckbox_cc = $('#cb-credit-card');
        function optionCC(id){
            if (ckbox_cc.is(':checked')) {
                $('#prompt-opt-payment-activate').modal('show');
                $('#apayment_id').val(id);
            } else {
                $('#prompt-opt-payment-deactivate').modal('show');
                $('#dpayment_id').val(id);
            }
        }

        var ckbox_ft = $('#cb-fund-transfer');
        function optionFT(id){
            if (ckbox_ft.is(':checked')) {
                $('#prompt-opt-payment-activate').modal('show');
                $('#apayment_id').val(id);
            } else {
                $('#prompt-opt-payment-deactivate').modal('show');
                $('#dpayment_id').val(id);
            }
        }

        var ckbox_mt = $('#cb-money-transfer');
        function optionMT(id){
            if (ckbox_mt.is(':checked')) {
                $('#prompt-opt-payment-activate').modal('show');
                $('#apayment_id').val(id);
            } else {
                $('#prompt-opt-payment-deactivate').modal('show');
                $('#dpayment_id').val(id);
            }
        }
    </script>
@endsection
