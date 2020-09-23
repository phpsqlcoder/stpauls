@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/legande/plugins/responsive-tabs/css/responsive-tabs.css') }}" />
@endsection

@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.account-page-options')
                </div>
                <div class="col-lg-9">
                    <div class="article-content">
                        <h3 class="subpage-heading">{{ $page->name }}</h3>
                        @if(Session::has('success-change-password'))
                            <div class="alert alert-success" role="alert"><span class="fa fa-info-circle"></span>{{ Session::get('success-change-password') }}</div>
                        @endif

                        <form class="form message-form" role="form" autocomplete="off" action="{{ route('my-account.update-password') }}" method="post">
                            @csrf
                            @if (Session::has('success'))
                                <div class="alert alert-success" role="alert"><span class="fa fa-info-circle"></span>{{ Session::get('success') }}</div>
                            @endif
                            <div class="form-group">
                                <label for="inputPasswordOld">Current Password</label>
                                <input type="password" class="form-control col-md-6" name="current_password" required id="inputPasswordOld">
                                @if(Session::has('error-change-password'))
                                <p class="text-danger"><small><strong>The password is incorrect.</strong></small></p>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="inputPasswordNew">New Password</label>
                                <input type="password" class="form-control col-md-6 @error('password') is-invalid @enderror" name="password" required id="inputPasswordNew">
                                <span class="form-text small text-muted">
                                    Minimum of 8 characters
                                </span>
                                @hasError(['inputName' => 'password'])
                                @endhasError

                            </div>
                            <div class="form-group">
                                <label for="inputPasswordNewVerify">Verify Password</label>
                                <input type="password" class="form-control col-md-6 @error('confirm_password') is-invalid @enderror" name="confirm_password" required id="inputPasswordNewVerify">
                                <span class="form-text small text-muted">
                                    To confirm, type the new password again.
                                </span>
                                @hasError(['inputName' => 'confirm_password'])
                                @endhasError

                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn primary-btn more2">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

