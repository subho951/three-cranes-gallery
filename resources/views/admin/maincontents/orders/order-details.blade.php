<?php
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\CancelOrderReason;
use App\Helpers\Helper;

$generalSetting = GeneralSetting::find(1);
?>
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
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
          <div class="container-fluid invoice-container" style="margin: 15px auto;padding: 40px;max-width: 100%;background-color: #fff;border: 1px solid #ccc;-moz-border-radius: 6px;-webkit-border-radius: 6px;-o-border-radius: 6px;border-radius: 6px;">
             <header class="text-center mt-4">
                <div class="btn-group btn-group-sm d-print-none">
                  <!-- <a href="javascript:window.print()" class="btn btn-light border text-black-50 shadow-none"><i class="fa fa-print"></i> Print & Download</a> -->
                  <a href="<?=env('UPLOADS_URL').'orders/'.$getOrderDetail->invoice_pdf?>" class="btn btn-light border text-black-50 shadow-none" target="_blank"><i class="fa fa-print"></i> Print Invoice</a>
               </div>
             </header>
             <table class="table table-bordered global_table mb-0">
                <tbody>
                   <tr>
                      <td colspan="2" class="bg-light text-center">
                         <h3 class="mb-0"><strong><?=$generalSetting->site_name?></strong></h3>
                      </td>
                   </tr>
                   <tr>
                      <td class="col-7">
                         <div class="row gx-2 gy-2">
                            <div class="col-auto"><strong>M/s. :</strong></div>
                            <div class="col">
                               <address>
                                  <strong><?=$getOrderDetail->b_fname.' '.$getOrderDetail->b_lname?></strong><br>
                                  <?=$getOrderDetail->b_street?>, <?=$getOrderDetail->b_suburb?><br>
                                  <?=$getOrderDetail->b_state?> <?=$getOrderDetail->b_postcode?><br>
                                  <?=$getOrderDetail->b_country?><br>
                                  <?=$getOrderDetail->b_phone?><br>
                                  <?=$getOrderDetail->b_email?><br>
                               </address>
                            </div>
                         </div>
                      </td>
                      <td class="col-5 bg-light">
                         <div class="row gx-2 gy-1 fw-600">
                            <div class="col-5">Invoice No <span class="float-end">:</span></div>
                            <div class="col-7">#<?=$getOrderDetail->order_no?></div>
                            <div class="col-5">Date <span class="float-end">:</span></div>
                            <div class="col-7"><?=date_format(date_create($getOrderDetail->order_date), "M d, Y")?> <?=date_format(date_create($getOrderDetail->order_time), "h:i A")?></div>

                            <div class="col-5">Payment Status <span class="float-end">:</span></div>
                            <div class="col-7" style="font-size: 11px;"><?=(($getOrderDetail->payment_status)?'SUCCESS':'FAILED')?></div>
                            <div class="col-5">Payment Mode <span class="float-end">:</span></div>
                            <div class="col-7" style="font-size: 11px;"><?=$getOrderDetail->payment_mode?></div>
                            <div class="col-5">Txn No. <span class="float-end">:</span></div>
                            <div class="col-7" style="font-size: 11px;"><?=$getOrderDetail->payment_txn_no?></div>
                            <div class="col-5">Date/Time <span class="float-end">:</span></div>
                            <div class="col-7" style="font-size: 11px;"><?=date_format(date_create($getOrderDetail->payment_date_time), "M d, Y h:i A")?></div>
                         </div>
                      </td>
                   </tr>
                   <tr>
                      <td colspan="2" class="p-0">
                         <table class="table table-sm mb-0">
                            <thead>
                               <tr class="bg-light">
                                  <td class="text-center" style="width: 50px;"><strong>#</strong></td>
                                  <td class="text-center" style="width: 80px;"><strong>Image</strong></td>
                                  <td><strong>Product Name</strong></td>
                                  <td style="width: 200px;"><strong>Variation or Size / Color</strong></td>
                                  <td style="width: 120px;"><strong>SKU</strong></td>
                                  <td class="text-center" style="width: 70px;"><strong>Qty</strong></td>
                                  <td class="text-end" style="width: 110px;"><strong>Rate</strong></td>
                                  <td class="text-end" style="width: 110px;"><strong>Amount</strong></td>
                               </tr>
                            </thead>
                            <tbody>
                               <?php
                               $orderDetails = OrderDetail::where('order_id', '=', $getOrderDetail->id)->get();
                               $sl=1;
                               $subtotal=0;
                               if($orderDetails){ foreach($orderDetails as $orderDetail){
                                  $getProduct    = Product::where('id', '=', $orderDetail->product_id)->first();
                                  $subtotal      += $orderDetail->total;
                                  $parent_id_val    = json_decode($orderDetail->parent_id_val);
                                  $child_id_val  = json_decode($orderDetail->child_id_val);
                                  $variationInfo = null;
                                  if ((int)$orderDetail->variation_id > 0) {
                                    $variationInfo = ProductVariation::select('sku')->where('id', '=', $orderDetail->variation_id)->first();
                                  }
                                  $sku = (($variationInfo && $variationInfo->sku != '') ? $variationInfo->sku : (($getProduct && $getProduct->product_sku != '') ? $getProduct->product_sku : '-'));
                                  $sizeColor = '-';
                                  if (is_array($parent_id_val) && is_array($child_id_val) && count($parent_id_val) > 0) {
                                    $sizeColorList = [];
                                    for($i=0; $i<count($parent_id_val); $i++){
                                      $attrName = trim((string)$parent_id_val[$i]);
                                      $attrVal  = trim((string)($child_id_val[$i] ?? ''));
                                      if($attrName != '' && $attrVal != ''){
                                        $sizeColorList[] = $attrName.' : '.$attrVal;
                                      } elseif($attrVal != ''){
                                        $sizeColorList[] = $attrVal;
                                      }
                                    }
                                    if(!empty($sizeColorList)){
                                      $sizeColor = implode(', ', $sizeColorList);
                                    }
                                  }
                                  if($sizeColor == '-' && $orderDetail->variation_name != ''){
                                    $sizeColor = $orderDetail->variation_name;
                                  }
                                  $productImage = (($getProduct && $getProduct->cover_image != '') ? env('UPLOADS_URL').'product/'.$getProduct->cover_image : '');
                                ?>
                                  <tr>
                                     <td class="text-center"><?=$sl++?></td>
                                     <td class="text-center">
                                        <?php if($productImage != ''){ ?>
                                          <img src="<?=$productImage?>" alt="<?=($getProduct ? $getProduct->name : 'Product')?>" style="width: 60px;height: 60px;object-fit: cover;border-radius: 4px;">
                                        <?php } else { ?>
                                          -
                                        <?php } ?>
                                     </td>
                                     <td>
                                        <?=($getProduct ? $getProduct->name : '-')?><br>
                                        <?php
                                       $external_product_link = url('product/'.($getProduct ? $getProduct->slug : '').'/'.Helper::encoded($orderDetail->product_id));
                                       
                                        ?>
                                        <?php if($external_product_link != ''){?>
                                          <a href="<?=$external_product_link?>" target="_blank"><span class="badge bg-info"><i class="fa fa-link"></i> External Product Link</span></a>
                                        <?php }?>
                                     </td>
                                     <td><?=$sizeColor?></td>
                                     <td><?=$sku?></td>
                                     <td class="text-center"><?=$orderDetail->qty?></td>
                                     <td class="text-end">$<?=number_format($orderDetail->rate,2)?></td>
                                     <td class="text-end">$<?=number_format($orderDetail->total,2)?></td>
                                  </tr>
                               <?php } }?>
                            </tbody>
                         </table>
                      </td>
                   </tr>
                   <tr class="bg-light fw-600">
                      <td class="col-7 py-1"></td>
                      <td class="col-5 py-1 pe-1">Sub Total: <span class="float-end">$<?=number_format($subtotal,2)?></span></td>
                   </tr>
                   <tr class="bg-light fw-600">
                      <td class="col-7 py-1"></td>
                      <td class="col-5 py-1 pe-1">Discount: <span class="float-end">$<?=number_format($getOrderDetail->disc_amount,2)?></span></td>
                   </tr>
                   <tr class="bg-light fw-600">
                      <td class="col-7 py-1"></td>
                      <td class="col-5 py-1 pe-1">Shipping: <span class="float-end">$<?=number_format($getOrderDetail->shipping_amt,2)?></span></td>
                   </tr>
                   <tr class="bg-light fw-600">
                      <td class="col-7 py-1"></td>
                      <td class="col-5 py-1 pe-1">Tax:<span class="float-end">$<?=number_format($getOrderDetail->tax_amt,2)?></span></td>
                   </tr>
                   <tr class="bg-light fw-600">
                      <td class="col-7 py-1"></td>
                      <td class="col-5 py-1 pe-1">Total: <span class="float-end">$<?=number_format($getOrderDetail->net_amt,2)?></span></td>
                   </tr>
                   <tr class="bg-light fw-600">
                      <td class="col-7 py-1">Tracking Number : <?=$getOrderDetail->tracking_number?></td>
                      <td class="col-5 py-1 pe-1">Amount In Words: <span class="float-end"><i><?=Helper::getIndianCurrency($getOrderDetail->net_amt)?></i></span></td>
                   </tr>
                </tbody>
             </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
