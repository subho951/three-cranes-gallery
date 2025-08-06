<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Faq;
use App\Helpers\Helper;
?>
<style type="text/css">
    .order-list .nav-tabs .nav-link.active {
        color: #fff !important;
    }
</style>
@if(session('success_message'))
<h6 class="alert alert-success autohide">{{ session('success_message') }}</h6>
@endif
@if(session('error_message'))
<h6 class="alert alert-danger autohide">{{ session('error_message') }}</h6>
@endif
<!-- Nav tabs -->
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
            type="button" role="tab" aria-controls="home" aria-selected="true">New (<?= count($getCustOrders1) ?>)</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
            type="button" role="tab" aria-controls="profile" aria-selected="false">Processing (<?= count($getCustOrders2) ?>)</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#nav-contact"
            type="button" role="tab" aria-controls="profile" aria-selected="false">Incomplete (<?= count($getCustOrders3) ?>)</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#nav-contact2"
            type="button" role="tab" aria-controls="profile" aria-selected="false">Shipped (<?= count($getCustOrders4) ?>)</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#nav-contact3"
            type="button" role="tab" aria-controls="profile" aria-selected="false">Complete (<?= count($getCustOrders5) ?>)</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#nav-contact4"
            type="button" role="tab" aria-controls="profile" aria-selected="false">Rejected (<?= count($getCustOrders6) ?>)</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#nav-contact5"
            type="button" role="tab" aria-controls="profile" aria-selected="false">Cancelled (<?= count($getCustOrders7) ?>)</button>
    </li>
</ul>

<!-- Tab content -->
<div class="tab-content p-3 border border-top-0" id="myTabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="home-tab">
        <div class="table-responsive custom-table">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">Order no</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Order Amount</th>
                        <th scope="col">Payment Mode</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($getCustOrders1) > 0) {
                        foreach ($getCustOrders1 as $getCustOrder) { ?>
                            <tr width="100%">
                                <td><?= $getCustOrder->order_no ?></td>
                                <td><?= date_format(date_create($getCustOrder->order_date), "M d, Y") ?> <?= date_format(date_create($getCustOrder->order_time), "h:i A") ?></td>
                                <td><span>$<?= number_format($getCustOrder->net_amt, 2) ?></span></td>
                                <td><?= $getCustOrder->payment_mode ?></td>
                                <td>
                                    <a href="<?= url('user/order-details/' . Helper::encoded($getCustOrder->id)) ?>" target="_blank">Details</a>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="5" style="text-align:center; color: red;">Nothing here… yet!</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="profile-tab">
        <div class="table-responsive custom-table">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">Order no</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Order Amount</th>
                        <th scope="col">Payment Mode</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($getCustOrders2) > 0) {
                        foreach ($getCustOrders2 as $getCustOrder) { ?>
                            <tr width="100%">
                                <td><?= $getCustOrder->order_no ?></td>
                                <td><?= date_format(date_create($getCustOrder->order_date), "M d, Y") ?> <?= date_format(date_create($getCustOrder->order_time), "h:i A") ?></td>
                                <td><span>$<?= number_format($getCustOrder->net_amt, 2) ?></span></td>
                                <td><?= $getCustOrder->payment_mode ?></td>
                                <td>
                                    <a href="<?= url('user/order-details/' . Helper::encoded($getCustOrder->id)) ?>" target="_blank">Details</a>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="5" style="text-align:center; color: red;">Nothing here… yet!</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="profile-tab">
        <div class="table-responsive custom-table">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">Order no</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Order Amount</th>
                        <th scope="col">Payment Mode</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($getCustOrders3) > 0) {
                        foreach ($getCustOrders3 as $getCustOrder) { ?>
                            <tr width="100%">
                                <td><?= $getCustOrder->order_no ?></td>
                                <td><?= date_format(date_create($getCustOrder->order_date), "M d, Y") ?> <?= date_format(date_create($getCustOrder->order_time), "h:i A") ?></td>
                                <td><span>$<?= number_format($getCustOrder->net_amt, 2) ?></span></td>
                                <td><?= $getCustOrder->payment_mode ?></td>
                                <td>
                                    <a href="<?= url('user/order-details/' . Helper::encoded($getCustOrder->id)) ?>" target="_blank">Details</a>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="5" style="text-align:center; color: red;">Nothing here… yet!</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-contact2" role="tabpanel" aria-labelledby="profile-tab">
        <div class="table-responsive custom-table">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">Order no</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Order Amount</th>
                        <th scope="col">Payment Mode</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($getCustOrders4) > 0) {
                        foreach ($getCustOrders4 as $getCustOrder) { ?>
                            <tr width="100%">
                                <td><?= $getCustOrder->order_no ?></td>
                                <td><?= date_format(date_create($getCustOrder->order_date), "M d, Y") ?> <?= date_format(date_create($getCustOrder->order_time), "h:i A") ?></td>
                                <td><span>$<?= number_format($getCustOrder->net_amt, 2) ?></span></td>
                                <td><?= $getCustOrder->payment_mode ?></td>
                                <td>
                                    <a href="<?= url('user/order-details/' . Helper::encoded($getCustOrder->id)) ?>" target="_blank">Details</a>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="5" style="text-align:center; color: red;">Nothing here… yet!</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-contact3" role="tabpanel" aria-labelledby="profile-tab">
        <div class="table-responsive custom-table">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">Order no</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Order Amount</th>
                        <th scope="col">Payment Mode</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($getCustOrders5) > 0) {
                        foreach ($getCustOrders5 as $getCustOrder) { ?>
                            <tr width="100%">
                                <td><?= $getCustOrder->order_no ?></td>
                                <td><?= date_format(date_create($getCustOrder->order_date), "M d, Y") ?> <?= date_format(date_create($getCustOrder->order_time), "h:i A") ?></td>
                                <td><span>$<?= number_format($getCustOrder->net_amt, 2) ?></span></td>
                                <td><?= $getCustOrder->payment_mode ?></td>
                                <td>
                                    <a href="<?= url('user/order-details/' . Helper::encoded($getCustOrder->id)) ?>" target="_blank">Details</a>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="5" style="text-align:center; color: red;">Nothing here… yet!</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-contact4" role="tabpanel" aria-labelledby="profile-tab">
        <div class="table-responsive custom-table">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">Order no</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Order Amount</th>
                        <th scope="col">Payment Mode</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($getCustOrders6) > 0) {
                        foreach ($getCustOrders6 as $getCustOrder) { ?>
                            <tr width="100%">
                                <td><?= $getCustOrder->order_no ?></td>
                                <td><?= date_format(date_create($getCustOrder->order_date), "M d, Y") ?> <?= date_format(date_create($getCustOrder->order_time), "h:i A") ?></td>
                                <td><span>$<?= number_format($getCustOrder->net_amt, 2) ?></span></td>
                                <td><?= $getCustOrder->payment_mode ?></td>
                                <td>
                                    <a href="<?= url('user/order-details/' . Helper::encoded($getCustOrder->id)) ?>" target="_blank">Details</a>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="5" style="text-align:center; color: red;">Nothing here… yet!</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-contact5" role="tabpanel" aria-labelledby="profile-tab">
        <div class="table-responsive custom-table">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">Order no</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Order Amount</th>
                        <th scope="col">Payment Mode</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($getCustOrders7) > 0) {
                        foreach ($getCustOrders7 as $getCustOrder) { ?>
                            <tr width="100%">
                                <td><?= $getCustOrder->order_no ?></td>
                                <td><?= date_format(date_create($getCustOrder->order_date), "M d, Y") ?> <?= date_format(date_create($getCustOrder->order_time), "h:i A") ?></td>
                                <td><span>$<?= number_format($getCustOrder->net_amt, 2) ?></span></td>
                                <td><?= $getCustOrder->payment_mode ?></td>
                                <td>
                                    <a href="<?= url('user/order-details/' . Helper::encoded($getCustOrder->id)) ?>" target="_blank">Details</a>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="5" style="text-align:center; color: red;">Nothing here… yet!</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>