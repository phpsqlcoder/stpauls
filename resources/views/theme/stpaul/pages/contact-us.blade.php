@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row contact-details">
                <div class="col-lg-8">
                    {!! $page->contents !!}
                </div>
                <div class="col-lg-4">
                    <h3 class="subpage-heading">Inquiry Form</h3>
                    <p><small><strong>Note:</strong> Please do not leave required fields * empty.</small></p>
                    <div class="form-style fs-sm">
                        @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                        @endif
                        @if(session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session()->get('error') }}
                            </div>
                        @endif
                        <form autocomplete="off" id="contactUsForm" action="{{ route('contact-us') }}" method="POST">
                            @method('POST')
                            @csrf
                            <div class="form-group">
                                <label for="fullName">Full Name *</label>
                                <input type="text" id="fullName" class="form-control form-input" name="name" placeholder="First and Last Name" />
                            </div>
                            
                            <div class="form-group">
                                <label for="emailAddress">E-mail Address *</label>
                                <input type="email" id="emailAddress" class="form-control form-input" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="hello@email.com" />
                            </div>
                            <div class="form-group">
                                <label for="contactNumber">Contact Number *</label>
                                <input type="number" id="contactNumber" class="form-control form-input" name="contact" placeholder="Landline or Mobile" />
                            </div>
                            <div class="form-group">
                                <label for="message">Message *</label>
                                <textarea name="message" id="message" class="form-control form-input textarea" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <script src="https://www.google.com/recaptcha/api.js?hl=en" async="" defer="" ></script>
                                <div class="g-recaptcha" data-sitekey="{{ \Setting::info()->google_recaptcha_sitekey }}"></div>
                                <label class="control-label text-danger" for="g-recaptcha-response" id="catpchaError" style="display:none;font-size: 14px;"><i class="fa fa-times-circle-o"></i>The Captcha field is required.</label></br>
                                @if($errors->has('g-recaptcha-response'))
                                    @foreach($errors->get('g-recaptcha-response') as $message)
                                        <label class="control-label text-danger" for="g-recaptcha-response"><i class="fa fa-times-circle-o"></i>{{ $message }}</label></br>
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg primary-btn">Submit</button>&nbsp;
                                <button type="reset" class="btn btn-lg default-btn">Reset</button>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('customjs')
    <script>
        $('#contactUsForm').submit(function (evt) {
            let recaptcha = $("#g-recaptcha-response").val();
            if (recaptcha === "") {
                evt.preventDefault();
                $('#catpchaError').show();
                return false;
            }
        });
    </script>
@endsection