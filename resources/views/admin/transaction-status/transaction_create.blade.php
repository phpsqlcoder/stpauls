@extends('admin.layouts.app')

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('transaction-status.index')}}">Transaction Status</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create a Transaction</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Create a Transaction</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form autocomplete="off" action="{{ route('transactions.store') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label class="d-block">Name*</label>
                        <input type="text" name="name" id="name" value="{{ old('name')}}" class="form-control @error('name') is-invalid @enderror" maxlength="150">
                        @hasError(['inputName' => 'name'])
                        @endhasError
                        <small id="category_slug"></small>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Transaction type</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">-- None --</option>
                            <option value="Payment">Payment</option>
                            <option value="Order">Order</option>
                            <option value="Shipping Fee">Shipping Fee</option>
                            <option value="Delivery Status">Delivery Status</option>
                            <option value="Reactivation Request">Reactivation Request</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Status</label>
                        <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="status" {{ (old("status") ? "checked":"") }} id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">
                                {{ old('status') ? (old('status') == 'on') ? 'Active' : 'Inactive' : 'Inactive' }}
                            </label>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Save Transaction</button>
                    <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('transactions.index') }}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customjs')
    <script>
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
