@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-12">
                    <div id="signup-wrapper">
                        <div class="row align-items-center">
                            <div class="col-md-12 col-sm-12">
                                <h2 class="lgin-title">Request A Title</h2>
                            </div>
                        </div>
                        <div class="gap-20"></div>

                        @if($message = Session::get('success'))
                        <div class="alert alert-success" role="alert">
                            <span class="fa fa-check"></span> {{ $message }}
                        </div>
                        @endif

                        <div id="signup-form">
                            <form method="post" action="{{ route('front.store-request-title') }}">
                                @csrf
                                <div class="row form-style-login">
                                    <div class="col-md-12">
                                        <div class="gap-10"></div>
                                        <p class="text-center text-dark"><strong>Can't find the title you're looking for? We probably have it somewhere. Fill in the details and we'll see if we can get you a copy.</strong></p>
                                        <div class="gap-20"></div>
                                    </div>
                                    <div class="col-md-2 col-sm-12"></div>
                                    <div class="form-group col-md-12">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <p>Email Address *</p>
                                                <input id="emailInput" type="text" name="email" class="form-control form-input  @error('email') is-invalid @enderror" value="{{ old('email') }}" oninput="checkEmail();" onkeypress="checkEmail();">
                                                <span class="invalid-feedback" role="alert" style="display: hidden;">
                                                    <strong>Invalid Email.</strong>
                                                </span>
                                                @hasError(['inputName' => 'email'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-6">
                                                <p>First Name*</p>
                                                <input type="text" name="firstname" class="form-control form-input @error('firstname') is-invalid @enderror" value="{{ old('firstname') }}">
                                                @hasError(['inputName' => 'firstname'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-6">
                                                <p>Last Name*</p>
                                                <input type="text" name="lastname" class="form-control form-input @error('lastname') is-invalid @enderror" value="{{ old('lastname') }}">
                                                @hasError(['inputName' => 'lastname'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Mobile Number</p>
                                                <input type="number" name="mobileno" id="mobileno" class="form-control form-input">
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Book Title *</p>
                                                <input type="text" name="title" class="form-control form-input @error('title') is-invalid @enderror" value="{{ old('title') }}">
                                                @hasError(['inputName' => 'title'])
                                                @endhasError
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Book Author</p>
                                                <input type="text" name="author" class="form-control form-input">
                                                <div class="gap-10"></div>
                                            </div>
                                            <div class="col-md-12">
                                                <p>ISBN</p>
                                                <input type="text" name="isbn" class="form-control form-input" placeholder="XXX-XXXXXXXXX">
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <p>Message</p>
                                                <textarea name="message" id="message" class="form-control form-input textarea">{{ old('message') }}</textarea>
                                                <div class="gap-10"></div>    
                                            </div>
                                            <div class="col-md-12">
                                                <script src="https://www.google.com/recaptcha/api.js?hl=en" async="" defer="" ></script>
                                                <div class="g-recaptcha" data-sitekey="{{ \Setting::info()->google_recaptcha_sitekey }}"></div>
                                                <label class="control-label text-danger" for="g-recaptcha-response" id="catpchaError" style="display:none;font-size: 14px;"><i class="fa fa-times-circle-o"></i>The Captcha field is required.</label></br>
                                                @if($errors->has('g-recaptcha-response'))
                                                    @foreach($errors->get('g-recaptcha-response') as $message)
                                                        <label class="control-label text-danger" for="g-recaptcha-response"><i class="fa fa-times-circle-o"></i>{{ $message }}</label></br>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="col-lg-6 col-md-7">
                                                <div class="gap-10"></div>
                                                <button type="submit" class="btn btn-md primary-btn btn-block mt-1">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('pagejs')
    <script>
        /** form validations **/
        $(document).ready(function () {
            //called when key is pressed in textbox
            $("#mobileno").keypress(function (e) {
                //if the letter is not digit then display error and don't type anything
                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
        });  
    </script>
    <script>
        function checkEmail() {
            var email_input = document.querySelector("#emailInput");
            var email_regex = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;
            if (!email_regex.test(email_input.value)) {
                email_input.setCustomValidity("Invalid Email.");
                $("#emailInput").next().removeAttr("style");
                $("#emailInput").next().css("display", "block");
            } else {
                email_input.setCustomValidity("");
                $("#emailInput").next().removeAttr("style");
                $("#emailInput").next().css("display", "hidden");
            }
        }
    </script>
@endsection

@section('customjs')
    <script>
        $('#signUpForm').submit(function (evt) {
            let recaptcha = $("#g-recaptcha-response").val();
            if (recaptcha === "") {
                evt.preventDefault();
                $('#catpchaError').show();
                return false;
            }
        });
    </script>
@endsection
