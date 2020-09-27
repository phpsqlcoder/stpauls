<div class="modal effect-scale" id="prompt-remove-logo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">{{__('standard.settings.website.remove_logo_confirmation_title')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{__('standard.settings.website.remove_logo_confirmation')}}</p>
            </div>
            <form action="{{route('website-settings.remove-logo')}}" method="POST">
            @csrf
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger">Yes, remove logo</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="prompt-remove-icon" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">{{__('standard.settings.website.remove_icon_confirmation_title')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{__('standard.settings.website.remove_icon_confirmation')}}</p>
            </div>
            <form action="{{route('website-settings.remove-icon')}}" method="POST">
            @csrf
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger">Yes, remove icon</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="prompt-delete-social" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">{{__('standard.settings.website.remove_social_account_confirmation_title')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{__('standard.settings.website.remove_social_account_confirmation')}}</p>
            </div>
            <form action="{{route('website-settings.remove-media')}}" method="POST">
            @csrf
            <input type="hidden" id="mid" name="id">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger">Yes, remove account</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Ecommerce -->
<div class="modal effect-scale" id="prompt-add-bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Bank</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('ecommerce-setting.add-bank') }}" method="post">
                @method('POST')
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="d-block">Name *</label>
                        <input required type="text" name="name" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label class="d-block">Account Number *</label>
                        <input required type="text" name="account_no" class="form-control"> 
                    </div>
                    
                    <div class="form-group">
                        <label class="d-block">Branch *</label>
                        <input required type="text" name="branch" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="prompt-edit-bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Bank</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('ecommerce-setting.update-bank') }}" method="post">
                @method('POST')
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="d-block">Name *</label>
                        <input type="hidden" name="id" id="bank_id">
                        <input required type="text" name="name" id="bankname" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label class="d-block">Account Number *</label>
                        <input required type="text" name="account_no" id="bankaccountno" class="form-control"> 
                    </div>
                    
                    <div class="form-group">
                        <label class="d-block">Branch *</label>
                        <input required type="text" name="branch" id="bankbranch" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="prompt-delete-bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Remove Bank</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item?</p>
            </div>
            <form action="{{route('ecommerce-setting.delete-bank')}}" method="POST">
            @csrf
                <input type="hidden" name="id" id="dbank_id">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger">Yes, remove bank</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="prompt-add-remittance" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Remittance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('ecommerce-setting.add-remittance')}}" method="post" enctype="multipart/form-data">
                @method('POST')
                @csrf
                <div class="modal-body">
                    <label class="d-block">Name *</label>
                    <input required type="text" name="name" class="form-control" maxlength="150">

                    <label class="d-block">Recepient Name *</label>
                    <input required type="text" name="recipient" class="form-control" maxlength="150">

                    <label class="d-block">Number *</label>
                    <input required type="text" name="account_no" class="form-control" maxlength="50">

                    <label class="d-block">Upload QR</label>
                    <input type="file" name="qrcode" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="prompt-edit-remittance" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Remittance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('ecommerce-setting.edit-remittance')}}" method="post" enctype="multipart/form-data">
                @method('POST')
                @csrf
                <div class="modal-body">
                    <label class="d-block">Name *</label>
                    <input type="hidden" name="id" id="remittance_id">
                    <input required type="text" name="name" id="remittance_name" class="form-control">

                    <label class="d-block">Recepient Name *</label>
                    <input required type="text" name="recipient" id="recipient" class="form-control" maxlength="150">

                    <label class="d-block">Number *</label>
                    <input required type="text" name="account_no" id="remittance_account_no" class="form-control" maxlength="50">

                    <label class="d-block">Upload QR</label>
                    <input type="file" name="qrcode" id="qrcode" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="prompt-delete-remittance" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Remove Remittance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item?</p>
            </div>
            <form action="{{route('ecommerce-setting.delete-remittance')}}" method="POST">
            @csrf
                <input type="hidden" name="id" id="dremittance_id">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger">Yes, remove remittance</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal effect-scale" id="prompt-opt-payment-deactivate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Deactivate Option</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to deactivate this payment option?</p>
            </div>
            <form action="{{route('ecommerce-setting.deactivate-payment-opt')}}" method="POST">
            @csrf
                <input type="hidden" name="id" id="dpayment_id">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger">Yes, deactivate</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="prompt-opt-payment-activate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Activate Option</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to activate this payment option?</p>
            </div>
            <form action="{{route('ecommerce-setting.activate-payment-opt')}}" method="POST">
            @csrf
                <input type="hidden" name="id" id="apayment_id">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Yes, activate</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
