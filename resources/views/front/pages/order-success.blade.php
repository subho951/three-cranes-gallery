<?php
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Helpers\Helper;
?>
<section class="order-success py-5">
    <div class="container">
        <div class="row justify-content-center">
            @if(session('success_message'))
                <h6 class="alert alert-success autohide">{{ session('success_message') }}</h6>
            @endif
            @if(session('error_message'))
                <h6 class="alert alert-danger autohide">{{ session('error_message') }}</h6>
            @endif
            <div class="col-md-8">
                <div class="card p-3 p-md-4">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-check-circle text-success fa-4x mb-2"></i>
                        <h3 class="my-2">Thank You For Your Purchase</h3>
                        <h4>You order number is <?=(($getOrder)?$getOrder->order_no:'')?></h4>
                        <h5>Txn No. is <?=(($getOrder)?$getOrder->payment_txn_no:'')?></h5>
                        <small>Payment Date/Time is <?=(($getOrder)?date_format(date_create($getOrder->payment_date_time), "M d, Y h:i A"):'')?></small>
                        <p><a href="<?=url('user/order-list')?>" class="btn btn-outline-success mt-3">Go to order detail</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>