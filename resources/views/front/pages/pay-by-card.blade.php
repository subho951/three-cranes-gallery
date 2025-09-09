<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Faq;
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
<section class="single-page-banner-section" style="background-image: url('<?= url('public/uploads/Pay-By-Card.png') ?>')">
   <div class="background-overlay"></div>
   <div class=" container-xxl container-xl container-lg container-md container-sm container">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12  col-12 order-md-1">
            <div class="single-page-banner-description custom-breadcrumb ">
               <h1 class="text-center">Pay By Card</h1>
            </div>
         </div>
      </div>
   </div>
</section>
<section class="register-form-section login-form shipping-form-section section-padding">
   <div class=" container-xxl container-xl container-lg container-md container-sm container">
      @if(session('success_message'))
      <h6 class="alert alert-success autohide">{{ session('success_message') }}</h6>
      @endif
      @if(session('error_message'))
      <h6 class="alert alert-danger autohide">{{ session('error_message') }}</h6>
      @endif
      <!-- <form method="POST" action="">
         @csrf -->
      <input type="hidden" name="order_id" value="<?= (($getOrder) ? $getOrder->id : 0) ?>">
      <input type="hidden" name="net_amt" value="<?= (($getOrder) ? $getOrder->net_amt : 0) ?>">
      <div class="row justify-content-center">
         <div class="col-xl-3 col-lg-3 col-md-3 col-3"></div>
         <div class="col-xl-6 col-lg-6 col-md-6 col-6">
            <div class="checkout-review-order-table-wrapper">
               <div class="title-product-name">Products</div>
               <div class="shop_table">
                  <?php
                  $subtotal_tot           = 0;
                  $disc_tot               = 0;
                  $after_disc_tot         = 0;
                  $shipping_tot           = 0;
                  $tax_tot                = 0;
                  $net_tot                = 0;
                  if (count($cartItems) > 0) {
                     foreach ($cartItems as $cartItem) {
                        $getProduct    = Product::select('id', 'name', 'cover_image')->where('id', '=', $cartItem->product_id)->first();
                        $subtotal_tot        += $cartItem->subtotal;
                        $disc_tot            += $cartItem->disc_amount;
                        $after_disc_tot      += $cartItem->amount_after_disc;
                        $shipping_tot        += $cartItem->shipping_amt;
                        $tax_tot             += $cartItem->tax_amt;
                        $net_tot             += $cartItem->net_amt;

                        $parent_id_val       = json_decode($cartItem->parent_id_val);
                        $child_id_val        = json_decode($cartItem->child_id_val);
                  ?>
                        <div class="cart_item-ccheck">
                           <div class="info-product">
                              <div class="product-thumble">
                                 <img width="50" height="450" src="<?= env('UPLOADS_URL') . 'product/' . (($getProduct) ? $getProduct->cover_image : '') ?>" class="imd-fluid" alt="<?= (($getProduct) ? $getProduct->name : '') ?>">
                              </div>
                              <div class="product-name">
                                 <b><?= (($getProduct) ? $getProduct->name : '') ?></b>&nbsp; <strong class="product-quantity">QTY : <?= $cartItem->qty ?></strong>
                                 <!-- <ul> -->
                                 <?php $parantAttrs = [];
                                 if (!empty($parent_id_val)) {
                                    for ($a = 0; $a < count($parent_id_val); $a++) { ?>
                                       <!-- <li><small><b><?= $parent_id_val[$a] ?></b> </small></li> -->
                                       <?php $parantAttrs[] = $parent_id_val[$a]; ?>
                                 <?php }
                                 } ?>
                                 <!-- </ul> -->
                                 <!-- <h6><b><?= implode(", ", $parantAttrs) ?></b></h6>
                              <?= formatCartItems($child_id_val); ?> -->
                              </div>
                           </div>
                           <div class="product-total">
                              <span class=" amount"><bdi><span class="Price-currencySymbol">$</span><?= number_format($cartItem->total, 2) ?></bdi></span>
                           </div>
                        </div>
                  <?php }
                  } ?>
                  <div class="cart-subtotal-list">
                     <h2>Subtotal</h2>
                     <div class="subtotal-price">
                        <span class="Price-amount amount">
                           <bdi><span class="Price-currencySymbol">$</span><?= number_format($subtotal_tot, 2) ?></bdi>
                           <input type="hidden" name="subtotal" value="<?= $subtotal_tot ?>">
                        </span>
                     </div>
                  </div>
                  <div class="cart-subtotal-list">
                     <h2>Discount</h2>
                     <div class="subtotal-price">
                        <span class="Price-amount amount">
                           <bdi><span class="Price-currencySymbol"><span class="text-danger">(-)</span>$</span><?= number_format($disc_tot, 2) ?></bdi>
                           <input type="hidden" name="disc_amount" value="<?= $disc_tot ?>">
                        </span>
                     </div>
                  </div>
                  <div class="cart-subtotal-list">
                     <h2>After Discount</h2>
                     <div class="subtotal-price">
                        <span class="Price-amount amount">
                           <bdi><span class="Price-currencySymbol">$</span><?= number_format($after_disc_tot, 2) ?></bdi>
                           <input type="hidden" name="amount_after_disc" value="<?= $after_disc_tot ?>">
                        </span>
                     </div>
                  </div>
                  <div class="cart-subtotal-list shipping-totals">
                     <h2>
                        Shipping
                        <br>
                        <small style="font-size: 12px;color: #8b2525;font-weight: bold;">Domestic $9/item (single)</small><br>
                        <small style="font-size: 12px;color: #8b2525;font-weight: bold;">Domestic $6/item (multiple)</small><br>
                        <small style="font-size: 12px;color: #8b2525;font-weight: bold;">International $40/item (single)</small><br>
                        <small style="font-size: 12px;color: #8b2525;font-weight: bold;">International $25/item (multiple)</small>
                     </h2>
                     <div class="subtotal-price">
                        <span class="Price-amount amount">
                           <bdi><span class="Price-currencySymbol">$</span><?=number_format($shipping_tot,2)?></span></bdi>
                           <input type="hidden" name="shipping_amt" value="<?= $shipping_tot ?>">
                        </span>
                     </div>
                  </div>
                  <div class="cart-subtotal-list shipping-totals">
                     <h2>Tax
                        <br>
                        <small style="font-size: 12px;color: #8b2525;font-weight: bold;">(@ <?=$generalSetting->tax_percent?>%)</small>
                     </h2>
                     <div class="subtotal-price">
                        <span class="Price-amount amount">
                           <bdi><span class="Price-currencySymbol">$</span><?= number_format($tax_tot, 2) ?></bdi>
                           <input type="hidden" name="tax_amt" value="<?= $tax_tot ?>">
                        </span>
                     </div>
                  </div>
                  <div class="cart-subtotal-list order-total">
                     <h2>Total</h2>
                     <div class="total-price">
                        <strong><span class="amount"><bdi><span class="Price-currency">$</span><?= number_format($net_tot, 2) ?></bdi></span></strong>
                        <input type="hidden" name="net_amt" value="<?= $net_tot ?>">
                     </div>
                  </div>
               </div>
               <div id="payment" class="checkout-payment">
                  <div class="form-row place-order">
                     <!-- <button type="submit" class="button btn-place-order common-btn">Pay $<?= (($getOrder) ? number_format($getOrder->net_amt, 2) : 0) ?></button> -->
                     <?php
                     $order_no = (($getOrder) ? $getOrder->order_no : '');
                     $orderid = (($getOrder) ? $getOrder->id : '');
                     ?>
                     <a href="{{ route('stripe.checkout',['price' => $net_tot,'product' => $order_no,'orderid' => $orderid]) }}" class="button btn-place-order common-btn text-center">Pay $<?= number_format($net_tot, 2) ?></a>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xl-3 col-lg-3 col-md-3 col-3"></div>
      </div>
      <!-- </form> -->
   </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
   function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
         return false;
      }
      return true;
   }
</script>