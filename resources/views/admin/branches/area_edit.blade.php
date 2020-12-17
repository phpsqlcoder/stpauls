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
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('branch.index')}}">Branches</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Update Area</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Update Area</h4>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-lg-12">
                <form autocomplete="off" action="{{ route('branch.area-update') }}" method="post">
                    @csrf
                    <div class="row row-sm">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="mg-b-5 tx-color-03">Area Name*</label>
                                <input type="hidden" name="areaid" value="{{ $area->id }}">
                                <input required type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $area->name }}" maxlength="250">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary btn-uppercase">Update Area</button>
                    <a href="{{ route('branch.areas') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
