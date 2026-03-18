<?php

use App\Models\Category;
use App\Models\Faq;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductVariation;
use App\Helpers\Helper;

function formatCartItems($items)
{
    $output = "";

    foreach ($items as $item) {
        if (is_array($item)) {
            // Handle nested array items
            $output .= "<ul>";
            foreach ($item as $subItem) {
                $output .= "<li><small>" . htmlspecialchars($subItem) . "</small></li>";
            }
            $output .= "</ul>";
        } else {
            // Handle single item strings
            $output .= "<p><small>" . htmlspecialchars($item) . "</small></p>";
        }
    }

    return $output;
}
?>
<div class="order-details-main">
    <div class="panel-body no-padding-bottom">
        @if(session('success_message'))
        <h6 class="alert alert-success autohide">{{ session('success_message') }}</h6>
        @endif
        @if(session('error_message'))
        <h6 class="alert alert-danger autohide">{{ session('error_message') }}</h6>
        @endif
        <div class="invoice-section">
            <div class="row">
                <div class="col-sm-6 content-group">
                    <h2><?= $getOrderDetail->s_fname . ' ' . $getOrderDetail->s_lname ?></h2>
                    <ul class="list-condensed list-unstyled">
                        <li><?= $getOrderDetail->s_street ?>, <?= $getOrderDetail->s_suburb ?></li>
                        <li><?= $getOrderDetail->s_state ?> <?= $getOrderDetail->s_postcode ?></li>
                        <li><?= $getOrderDetail->s_country ?></li>
                        <li><a href="tel:<?= $getOrderDetail->s_phone ?>"><?= $getOrderDetail->s_phone ?></a></li>
                        <li><a href="mailto:<?= $getOrderDetail->s_email ?>"><?= $getOrderDetail->s_email ?></a></li>
                    </ul>
                </div>
                <div class="col-sm-6 content-group">
                    <div class="invoice-details">
                        <h5 class="text-uppercase text-semibold">Invoice #<?= $getOrderDetail->order_no ?></h5>
                        <ul class="list-condensed list-unstyled">
                            <li>Order Date: <span class="text-semibold"><?= date_format(date_create($getOrderDetail->order_date), "M d, Y") ?> <?= date_format(date_create($getOrderDetail->order_time), "h:i A") ?></span></li>
                        </ul>
                    </div>
                    <div class="order_btn-group">
                        <?php if ($getOrderDetail->status == 1) {
                            if ($getOrderDetail->is_cancel_request == 0) { ?>
                            <a href="<?=env('UPLOADS_URL').'/orders/'.$getOrderDetail->invoice_pdf?>" target="_blank" type="button" class="btn btn-primary" style="color:#FFF"><i class="fa fa-print"></i> Print Invoice</a>
                            <!-- <a href="<?= url('user/cancel-order/' . Helper::encoded($getOrderDetail->id) . '/' . Helper::encoded(url()->current())) ?>" class="btn btn-danger" onclick="return confirm('Do you want to cancel this order ?');" style="color:#FFF"><i class="fa fa-times"></i> Cancel Order</a> -->
                            <a href="javascript:void(0);" class="btn btn-danger" style="color:#FFF" data-bs-toggle="modal" data-bs-target="#cancelModal<?= $getOrderDetail->id ?>"><i class="fa fa-times"></i> Cancel Order</a>
                        <?php }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="invoice-payment-section">
            <div class="row">
                <div class="col-md-6 col-lg-9 content-group">
                    <span class="text-muted">Invoice To:</span>
                    <ul class="list-condensed list-unstyled">
                        <li>
                            <h5><?= $getOrderDetail->b_fname . ' ' . $getOrderDetail->b_lname ?></h5>
                        </li>
                        <li><span class="text-semibold"><?= $getOrderDetail->b_company ?></span></li>
                        <li><?= $getOrderDetail->b_street ?> <?= $getOrderDetail->b_suburb ?></li>
                        <li><?= $getOrderDetail->b_state ?> <?= $getOrderDetail->b_postcode ?></li>
                        <li><?= $getOrderDetail->b_country ?></li>
                        <li><a href="tel:<?= $getOrderDetail->b_phone ?>"><?= $getOrderDetail->b_phone ?></a></li>
                        <li><a href="mailto:<?= $getOrderDetail->b_email ?>"><?= $getOrderDetail->b_email ?></a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-3 content-group">
                    <span class="text-muted">Payment Details:</span>
                    <ul class="list-condensed list-unstyled invoice-payment-details">
                        <li>
                            <h5>Amount: <span class="text-right text-semibold">$<?= number_format($getOrderDetail->net_amt, 2) ?></span></h5>
                        </li>
                        <li>Status: <span class="text-success fw-bold"><?= (($getOrderDetail->payment_status) ? 'SUCCESS' : 'FAILED') ?></span></li>
                        <li>Mode: <span class="text-semibold"><?= $getOrderDetail->payment_mode ?></span></li>
                        <li>Txn: <span><?= $getOrderDetail->payment_txn_no ?></span></li>
                        <li>Date/Time: <span><?= date_format(date_create($getOrderDetail->payment_date_time), "M d, Y h:i A") ?></span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="order-summary">
            <div>
                <h5 class="order-title">Order Summary</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 70px;border: 1px solid #eee;">No.</th>
                                <th style="border: 1px solid #eee;">Item</th>
                                <th style="border: 1px solid #eee; min-width: 180px;">Size / Color</th>
                                <th style="border: 1px solid #eee; min-width: 120px;">SKU</th>
                                <th style="border: 1px solid #eee;">Price</th>
                                <th style="border: 1px solid #eee;">Quantity</th>
                                <th class="text-end border: 1px solid #eee;" style="width: 120px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $orderDetails = OrderDetail::where('order_id', '=', $getOrderDetail->id)->get();
                            $sl = 1;
                            $subtotal = 0;
                            if ($orderDetails) {
                                foreach ($orderDetails as $orderDetail) {
                                    $getProduct     = Product::where('id', '=', $orderDetail->product_id)->first();
                                    $subtotal         += $orderDetail->total;
                                    $parent_id_val     = json_decode($orderDetail->parent_id_val);
                                    $child_id_val     = json_decode($orderDetail->child_id_val);
                                    $variationInfo = null;
                                    if ((int)$orderDetail->variation_id > 0) {
                                        $variationInfo = ProductVariation::select('sku')->where('id', '=', $orderDetail->variation_id)->first();
                                    }
                                    $sku = (($variationInfo && $variationInfo->sku != '') ? $variationInfo->sku : (($getProduct && $getProduct->product_sku != '') ? $getProduct->product_sku : '-'));
                                    $sizeColor = '-';
                                    if (is_array($parent_id_val) && is_array($child_id_val) && count($parent_id_val) > 0) {
                                        $sizeColorList = [];
                                        for ($a = 0; $a < count($parent_id_val); $a++) {
                                            $attrName = trim((string)$parent_id_val[$a]);
                                            $attrVal = trim((string)($child_id_val[$a] ?? ''));
                                            if ($attrName != '' && $attrVal != '') {
                                                $sizeColorList[] = $attrName . ' : ' . $attrVal;
                                            } elseif ($attrVal != '') {
                                                $sizeColorList[] = $attrVal;
                                            }
                                        }
                                        if (!empty($sizeColorList)) {
                                            $sizeColor = implode(', ', $sizeColorList);
                                        }
                                    }
                                    if ($sizeColor == '-' && $orderDetail->variation_name != '') {
                                        $sizeColor = $orderDetail->variation_name;
                                    }
                            ?>
                                    <tr>
                                        <th style="border: 1px solid #eee;" scope="row"><?= $sl++ ?></th>
                                        <td style="border: 1px solid #eee;">
                                            <div class="order-thumble-img">
                                                <a target="_blank" href="<?= url('product/' . (($getProduct) ? $getProduct->slug : '') . '/' .Helper::encoded((($getProduct)?$getProduct->id:''))) ?>"><img width="100" height="100" src="<?= env('UPLOADS_URL') . 'product/' . $getProduct->cover_image ?>" alt="<?= $getProduct->name ?>"></a>
                                            </div>
                                            <div>
                                                <h5 class="text-truncate font-size-14 mb-0"><a target="_blank" href="<?= url('product/' . (($getProduct) ? $getProduct->slug : '') . '/' .Helper::encoded((($getProduct)?$getProduct->id:''))) ?>"><?= wordwrap($getProduct->name,35,"<br>\n") ?></a></h5>
                                            </div>
                                        </td>
                                        <td style="border: 1px solid #eee;"><?= $sizeColor ?></td>
                                        <td style="border: 1px solid #eee;"><?= $sku ?></td>
                                        <td style="border: 1px solid #eee;">$<?= number_format($orderDetail->rate, 2) ?></td>
                                        <td style="border: 1px solid #eee;"><?= $orderDetail->qty ?></td>
                                        <td style="border: 1px solid #eee;" class="text-end">$<?= number_format($orderDetail->total, 2) ?></td>
                                    </tr>
                            <?php }
                            } ?>
                            <tr>
                                <th scope="row" colspan="6" class="text-end">Sub Total</th>
                                <td class="text-end">$<?= number_format($subtotal, 2) ?></td>
                            </tr>
                            <tr>
                                <th scope="row" colspan="6" class="border-0 text-end"> Discount :</th>
                                <td class="border-0 text-end">- $<?= number_format($getOrderDetail->disc_amount, 2) ?></td>
                            </tr>
                            <tr>
                                <th scope="row" colspan="6" class="border-0 text-end"> Shipping Charge :</th>
                                <td class="border-0 text-end">$<?= number_format($getOrderDetail->shipping_amt, 2) ?></td>
                            </tr>
                            <tr>
                                <th scope="row" colspan="6" class="border-0 text-end"> Tax</th>
                                <td class="border-0 text-end">$<?= number_format($getOrderDetail->tax_amt, 2) ?></td>
                            </tr>
                            <tr>
                                <th style="border: 1px solid #eee;background: #ecebeb5c;" scope="row" colspan="6"
                                    class="text-end"> Net Total
                                </th>
                                <td style="border: 1px solid #eee;background: #ecebeb5c;" class="text-end">
                                    <h4 class="m-0 fw-semibold" style="font-size: 20px;">$<?= number_format($getOrderDetail->net_amt, 2) ?></h4>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal<?= $getOrderDetail->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="">
            @csrf
            <input type="hidden" name="order_id" value="<?= $getOrderDetail->id ?>">
            <input type="hidden" name="page_name" value="<?= Helper::encoded(url()->current()) ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cancel Order : #<?= $getOrderDetail->order_no ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cancel_order_reason">Cancel Order Reason</label>
                        <select class="form-control" name="cancel_order_reason" id="cancel_order_reason">
                            <option value="" selected>Select</option>
                            <?php if ($cancelOrderReasons) {
                                foreach ($cancelOrderReasons as $cancelOrderReason) { ?>
                                    <option value="<?= $cancelOrderReason->name ?>"><?= $cancelOrderReason->name ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cancel_order_description">Cancel Order Reason</label>
                        <textarea name="cancel_order_description" class="form-control" id="cancel_order_description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Cancel Order</button>
                </div>
            </div>
        </form>
    </div>
</div>
