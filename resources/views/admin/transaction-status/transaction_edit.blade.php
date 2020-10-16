@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('transactions.index')}}">Transaction Status</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit a Transaction</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit a Transaction</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('transactions.update',$transaction->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="d-block">Name *</label>
                        <input required type="text" name="name" id="name" value="{{ old('name',$transaction->name)}}" class="form-control @error('name') is-invalid @enderror" maxlength="150">
                        @hasError(['inputName' => 'name'])
                        @endhasError
                    </div>

                    <div class="form-group">
                        <label class="d-block">Transaction type</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">-- None --</option>
                            <option @if($transaction->type == 'Payment') selected @endif value="Payment">Payment</option>
                            <option @if($transaction->type == 'Order') selected @endif value="Order">Order</option>
                            <option @if($transaction->type == 'Delivery Status') selected @endif value="Delivery Status">Delivery Status</option>
                            <option @if($transaction->type == 'Reactivation Request') selected @endif value="Reactivation Request">Reactivation Request</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Status</label>
                        <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="status" {{ ($transaction->status == 'ACTIVE' ? "checked":"") }} id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ucwords(strtolower($transaction->status))}}</label>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Transaction</button>
                    <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('transactions.index') }}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $(function() {
            $('.selectpicker').selectpicker();
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
