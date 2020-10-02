<div class="modal effect-scale" id="prompt-approve-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('cod-approve-order') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Approve Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">  
                    <input type="hidden" name="orderid" id="id_approve">
                    <input type="hidden" name="status" value="APPROVE">
                    <p>Are you sure you want to approve this order #: <strong><span id="span_approve_order"></span></strong>?</p>

                    <div id="divshippingfee" style="display: none;">
                        <label>Shipping Fee*</label>
                        <input type="number" name="shippingfee" id="shippingfee" class="form-control" min="1">
                    </div>
                    
                    <br>
                    <label>Remarks*</label>
                    <textarea name="remarks" requried class="form-control" rows="5" placeholder="Please enter a remarks"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary" id="btnDelete">Yes, Approve</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal effect-scale" id="prompt-reject-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('cod-approve-order') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Reject Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">  
                    <input type="hidden" name="orderid" id="id_reject">
                    <input type="hidden" name="status" value="REJECT">
                    <p>Are you sure you want to reject this order #: <strong><span id="span_reject_order"></span></strong>?</p>
                    <textarea name="remarks" requried class="form-control" rows="5" placeholder="Please enter a remarks"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger" id="btnDelete">Yes, Reject</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>