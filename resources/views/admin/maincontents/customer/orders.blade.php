<?php
use App\Helpers\Helper;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\OrderDetail;
$controllerRoute = $module['controller_route'];
?>
<style type="text/css">
 .order-list .nav-tabs .nav-link.active {
    color: #fff !important;
 }
</style>
<div class="pagetitle">
  <h1><?=$page_header?></h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=url('admin/dashboard')?>">Home</a></li>
      <li class="breadcrumb-item active"><?=$page_header?></li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<section class="section">
  <div class="row">
    <div class="col-xl-12">
      @if(session('success_message'))
        <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show autohide" role="alert">
          {{ session('success_message') }}
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if(session('error_message'))
        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show autohide" role="alert">
          {{ session('error_message') }}
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
    </div>
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
               <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">New (<?=count($getCustOrders1)?>)</button>
               <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Processing (<?=count($getCustOrders2)?>)</button>
               <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Incomplete (<?=count($getCustOrders3)?>)</button>
               <button class="nav-link" id="nav-contact-tab2" data-bs-toggle="tab" data-bs-target="#nav-contact2" type="button" role="tab" aria-controls="nav-contact2" aria-selected="false">Shipped  (<?=count($getCustOrders4)?>)</button>
               <button class="nav-link" id="nav-contact-tab3" data-bs-toggle="tab" data-bs-target="#nav-contact3" type="button" role="tab" aria-controls="nav-contact3" aria-selected="false">Complete (<?=count($getCustOrders5)?>)</button>
               <button class="nav-link" id="nav-contact-tab4" data-bs-toggle="tab" data-bs-target="#nav-contact4" type="button" role="tab" aria-controls="nav-contact4" aria-selected="false">Rejected (<?=count($getCustOrders6)?>)</button>
               <button class="nav-link" id="nav-contact-tab5" data-bs-toggle="tab" data-bs-target="#nav-contact5" type="button" role="tab" aria-controls="nav-contact5" aria-selected="false">Cancelled (<?=count($getCustOrders7)?>)</button>
            </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
               <table cellspacing="0" cellpadding="6" width="100%" class="global_table">
                  <thead>
                     <tr style="background:#f9b922">
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Order no</th>
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Order Date</th>
                        <th scope="col" style="text-align:center;border:1px solid #eee">Order Amount</th>
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Payment Mode</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if(count($getCustOrders1) > 0){ foreach($getCustOrders1 as $getCustOrder){?>
                        <tr width="100%">
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0;word-wrap:break-word">
                              <?=$getCustOrder->order_no?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                              <?=date_format(date_create($getCustOrder->order_date), "M d, Y")?> <?=date_format(date_create($getCustOrder->order_time), "h:i A")?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;border-top:0">
                              <span>$<?=number_format($getCustOrder->net_amt,2)?></span>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                              <?=$getCustOrder->payment_mode?>
                           </td>
                        </tr>
                     <?php } } else {?>
                        <tr>
                           <td colspan="5" style="text-align:center; color: red;">No Orders Found !!!</td>
                        </tr>
                     <?php }?>
                  </tbody>
               </table>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
               <table cellspacing="0" cellpadding="6" width="100%" class="global_table">
                  <thead>
                     <tr style="background:#f9b922">
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Order no</th>
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Order Date</th>
                        <th scope="col" style="text-align:center;border:1px solid #eee">Order Amount</th>
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Payment Mode</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if(count($getCustOrders2) > 0){ foreach($getCustOrders2 as $getCustOrder){?>
                        <tr width="100%">
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0;word-wrap:break-word">
                              <?=$getCustOrder->order_no?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                              <?=date_format(date_create($getCustOrder->order_date), "M d, Y")?> <?=date_format(date_create($getCustOrder->order_time), "h:i A")?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;border-top:0">
                              <span>$<?=number_format($getCustOrder->net_amt,2)?></span>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                              <?=$getCustOrder->payment_mode?>
                           </td>
                        </tr>
                     <?php } } else {?>
                        <tr>
                           <td colspan="5" style="text-align:center; color: red;">No Orders Found !!!</td>
                        </tr>
                     <?php }?>
                  </tbody>
               </table>
            </div>
            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
               <table cellspacing="0" cellpadding="6" width="100%" class="global_table">
                  <thead>
                     <tr style="background:#f9b922">
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Order no</th>
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Order Date</th>
                        <th scope="col" style="text-align:center;border:1px solid #eee">Order Amount</th>
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Payment Mode</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if(count($getCustOrders3) > 0){ foreach($getCustOrders3 as $getCustOrder){?>
                        <tr width="100%">
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0;word-wrap:break-word">
                              <?=$getCustOrder->order_no?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                              <?=date_format(date_create($getCustOrder->order_date), "M d, Y")?> <?=date_format(date_create($getCustOrder->order_time), "h:i A")?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;border-top:0">
                              <span>$<?=number_format($getCustOrder->net_amt,2)?></span>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                              <?=$getCustOrder->payment_mode?>
                           </td>
                        </tr>
                     <?php } } else {?>
                        <tr>
                           <td colspan="5" style="text-align:center; color: red;">No Orders Found !!!</td>
                        </tr>
                     <?php }?>
                  </tbody>
               </table>
            </div>
            <div class="tab-pane fade" id="nav-contact2" role="tabpanel" aria-labelledby="nav-contact-tab2">
               <table cellspacing="0" cellpadding="6" width="100%" class="global_table">
                  <thead>
                     <tr style="background:#f9b922">
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Order no</th>
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Order Date</th>
                        <th scope="col" style="text-align:center;border:1px solid #eee">Order Amount</th>
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Payment Mode</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if(count($getCustOrders4) > 0){ foreach($getCustOrders4 as $getCustOrder){?>
                        <tr width="100%">
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0;word-wrap:break-word">
                              <?=$getCustOrder->order_no?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                              <?=date_format(date_create($getCustOrder->order_date), "M d, Y")?> <?=date_format(date_create($getCustOrder->order_time), "h:i A")?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;border-top:0">
                              <span>$<?=number_format($getCustOrder->net_amt,2)?></span>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                              <?=$getCustOrder->payment_mode?>
                           </td>
                        </tr>
                     <?php } } else {?>
                        <tr>
                           <td colspan="5" style="text-align:center; color: red;">No Orders Found !!!</td>
                        </tr>
                     <?php }?>
                  </tbody>
               </table>
            </div>
            <div class="tab-pane fade" id="nav-contact3" role="tabpanel" aria-labelledby="nav-contact-tab3">
               <table cellspacing="0" cellpadding="6" width="100%" class="global_table">
                  <thead>
                     <tr style="background:#f9b922">
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Order no</th>
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Order Date</th>
                        <th scope="col" style="text-align:center;border:1px solid #eee">Order Amount</th>
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Payment Mode</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if(count($getCustOrders5) > 0){ foreach($getCustOrders5 as $getCustOrder){?>
                        <tr width="100%">
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0;word-wrap:break-word">
                              <?=$getCustOrder->order_no?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                              <?=date_format(date_create($getCustOrder->order_date), "M d, Y")?> <?=date_format(date_create($getCustOrder->order_time), "h:i A")?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;border-top:0">
                              <span>$<?=number_format($getCustOrder->net_amt,2)?></span>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                              <?=$getCustOrder->payment_mode?>
                           </td>
                        </tr>
                     <?php } } else {?>
                        <tr>
                           <td colspan="5" style="text-align:center; color: red;">No Orders Found !!!</td>
                        </tr>
                     <?php }?>
                  </tbody>
               </table>
            </div>
            <div class="tab-pane fade" id="nav-contact4" role="tabpanel" aria-labelledby="nav-contact-tab4">
               <table cellspacing="0" cellpadding="6" width="100%" class="global_table">
                  <thead>
                     <tr style="background:#f9b922">
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Order no</th>
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Order Date</th>
                        <th scope="col" style="text-align:center;border:1px solid #eee">Order Amount</th>
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Payment Mode</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if(count($getCustOrders6) > 0){ foreach($getCustOrders6 as $getCustOrder){?>
                        <tr width="100%">
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0;word-wrap:break-word">
                              <?=$getCustOrder->order_no?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                              <?=date_format(date_create($getCustOrder->order_date), "M d, Y")?> <?=date_format(date_create($getCustOrder->order_time), "h:i A")?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;border-top:0">
                              <span>$<?=number_format($getCustOrder->net_amt,2)?></span>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                              <?=$getCustOrder->payment_mode?>
                           </td>
                        </tr>
                     <?php } } else {?>
                        <tr>
                           <td colspan="5" style="text-align:center; color: red;">No Orders Found !!!</td>
                        </tr>
                     <?php }?>
                  </tbody>
               </table>
            </div>
            <div class="tab-pane fade" id="nav-contact5" role="tabpanel" aria-labelledby="nav-contact-tab5">
               <table cellspacing="0" cellpadding="6" width="100%" class="global_table">
                  <thead>
                     <tr style="background:#f9b922">
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Order no</th>
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Order Date</th>
                        <th scope="col" style="text-align:center;border:1px solid #eee">Order Amount</th>
                        <th scope="col"  style="text-align:center;border:1px solid #eee">Payment Mode</th>
                        <th scope="col" style="text-align:center;border:1px solid #eee">Status</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if(count($getCustOrders7) > 0){ foreach($getCustOrders7 as $getCustOrder){?>
                        <tr width="100%">
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0;word-wrap:break-word">
                              <?=$getCustOrder->order_no?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                              <?=date_format(date_create($getCustOrder->order_date), "M d, Y")?> <?=date_format(date_create($getCustOrder->order_time), "h:i A")?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;border-top:0">
                              <span>$<?=number_format($getCustOrder->net_amt,2)?></span>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                              <?=$getCustOrder->payment_mode?>
                           </td>
                           <td style="text-align:center;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;border-top:0">
                              <p>
                                 <?php if($getCustOrder->status == 7){?>
                                    <h6><?=$getCustOrder->cancel_order_reason?></h6>
                                    <h6><?=$getCustOrder->cancel_order_description?></h6>
                                    <span class="badge bg-success">Approved Cancel Request</span>
                                    <h6><small>Approved : <?=date_format(date_create($getCustOrder->cancel_request_timestamp), "M d, Y h:i A")?></small></h6>
                                 <?php } elseif(($getCustOrder->status == 1) && ($getCustOrder->cancel_approve_reject_timestamp == '')){?>
                                    <h6><?=$getCustOrder->cancel_order_reason?></h6>
                                    <h6><?=$getCustOrder->cancel_order_description?></h6>
                                    <span class="badge bg-warning">Pending Cancel Request</span>
                                    <h6><small>Requested : <?=date_format(date_create($getCustOrder->cancel_request_timestamp), "M d, Y h:i A")?></small></h6>
                                 <?php } elseif(($getCustOrder->status == 1) && ($getCustOrder->cancel_approve_reject_timestamp != '')){?>
                                    <h6><?=$getCustOrder->cancel_order_reason?></h6>
                                    <h6><?=$getCustOrder->cancel_order_description?></h6>
                                    <span class="badge bg-danger">Rejected Cancel Request</span>
                                    <h6><small>Rejected : <?=date_format(date_create($getCustOrder->cancel_approve_reject_timestamp), "M d, Y h:i A")?></small></h6>
                                 <?php }?>
                              </p>
                           </td>
                        </tr>
                     <?php } } else {?>
                        <tr>
                           <td colspan="5" style="text-align:center; color: red;">No Orders Found !!!</td>
                        </tr>
                     <?php }?>
                  </tbody>
               </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>