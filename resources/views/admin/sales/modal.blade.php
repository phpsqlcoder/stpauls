
<!-- Add Payment modal -->
<div class="modal effect-scale" id="prompt-change-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">{{__('')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="id" id="id">
                    <input type="hidden" class="form-control" name="status" id="editStatus">
                    <div class="form-group">
                        <label class="d-block">Payment source *</label>
                        <select id="payment_type" class="selectpicker mg-b-5" name="payment_type" data-style="btn btn-outline-light btn-md btn-block tx-left" title="- None -" data-width="100%">
                            <option value="Gift Certificate">Gift Certificate</option>
                            <option value="Credit Card">Credit Card</option>
                            <optgroup label="Money Transfer">
                                <option value="gcash">GCash</option>
                                <option value="paymaya">PayMaya</option>
                            </optgroup>
                            <option value="Cash">Cash</option>
                        </select>
                        <p class="tx-10 text-danger" id="error">
                            @hasError(['inputName' => 'payment_type'])
                            @endhasError
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Amount *</label>
                        <input type="text" class="form-control" name="amount" id="amount">
                        <p class="tx-10 text-danger" id="error">
                            @hasError(['inputName' => 'amount'])
                            @endhasError
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Payment date *</label>
                        <input type="date" class="form-control" name="payment_date" id="payment_date">
                        <p class="tx-10 text-danger" id="error">
                            @hasError(['inputName' => 'payment_date'])
                            @endhasError
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Receipt number *</label>
                        <input type="text" class="form-control" name="receipt_number" id="receipt_number">
                        <p class="tx-10 text-danger" id="error">
                            @hasError(['inputName' => 'receipt_number'])
                            @endhasError
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary">Update</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end add payment -->

<!-- delete transaction -->
<div class="modal effect-scale" id="prompt-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="" id="frm_delete" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.delete_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                        @csrf
                        @method('DELETE ')
                    <input type="hidden" name="id_delete" id="id_delete">
                    <p>Are you sure you want to delete this transaction no: <span id="delete_order_div"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger" id="btnDelete">Yes, Cancel</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- end delete transaction -->

<!-- delivery status -->
<div class="modal effect-scale" id="prompt-change-delivery-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">{{__('Delivery Status')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="dd_form" method="POST" action="{{route('sales-transaction.delivery_status')}}">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="delivery_status">Status</label>
                        <select id="delivery_status" class="selectpicker mg-b-5" name="delivery_status" data-style="btn btn-outline-light btn-md btn-block tx-left" title="- None -" data-width="100%" required="required">
                            <option value="Scheduled for Processing">Scheduled for Processing</option>
                            <option value="Processing">Processing</option>
                            <option value="Ready For delivery">Ready For delivery</option>
                            <option value="In Transit">In Transit</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Returned">Returned</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                        <p class="tx-10 text-danger" id="error">
                            @hasError(['inputName' => 'delivery_status'])
                            @endhasError
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="delivery_status">Remarks</label>
                        <textarea name="del_remarks" class="form-control" id="del_remarks" cols="30" rows="4"></textarea>
                    </div>
                </div>
                <input type="hidden" id="del_id" name="del_id" value="">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- end delivery status -->

<!-- update delivery fee for door-2-door delivery -->
<div class="modal effect-scale" id="prompt-update-deliveryfee" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Update Delivery Fee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('sales-transaction.update_delivery_fee')}}">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="delivery_fee">Delivery Fee*</label>
                        <input type="hidden" name="salesid" id="salesid">
                        <input required type="number" class="form-control" name="delivery_fee" min="1">
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
<!-- end -->
