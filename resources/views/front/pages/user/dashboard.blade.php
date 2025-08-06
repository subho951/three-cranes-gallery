<?php
use App\Helpers\Helper;
?>
<div class="card mb-5 p-3">
    <b>Welcome <?=session('name')?> !</b>
    <div class="row mt-3 mb-3">
        <div class="col-lg-6">
            <a href="/account/myorder">
                <div class="card">
                    <div class="card-header">
                        <p>Total Orders</p>
                    </div>
                    <div class="card-body">
                        <p><?=$order_count?></p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6">
            <a href="/account/wishlist">
                <div class="card">
                    <div class="card-header">
                        <p>Total Wishlist</p>
                    </div>
                    <div class="card-body">
                        <p><?=$wishlist_count?></p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <?php if(count($orderList) > 0){?>
        <h5 class="recent">Recent Order</h5>
        <div class="table-responsive custom-table">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Price</th>
                        <th>Order Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orderList as $orderRow){?>
                        <tr>
                            <td><?=$orderRow->order_no?></td>
                            <td>$ <?=number_format($orderRow->net_amt,2)?></td>
                            <td><?=date_format(date_create($orderRow->created_at), "M d, Y h:i A")?></td>
                            <td><a href="<?=url('user/order-details/' . Helper::encoded($orderRow->id))?>"><u>Details</u></a></td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    <?php }?>
</div>