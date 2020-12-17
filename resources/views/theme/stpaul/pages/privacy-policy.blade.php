@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-12">
                    <div id="order-detail">
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <h2><strong>{{$page->name}}</strong></h2>
                            </div>
                        </div>
                        <div class="gap-20"></div>
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <p>{!! \Setting::info()->data_privacy_content !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
