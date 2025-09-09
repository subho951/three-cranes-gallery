<?php
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Faq;
use App\Models\GeneralSetting;
use App\Helpers\Helper;
function formatCartItems($items) {
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
<section class="cart-details-list section-padding">
    <div class=" container-xxl container-xl container-lg container-md container-sm container">
        <div class="row">
            @if(session('success_message'))
            <h6 class="alert alert-success autohide">{{ session('success_message') }}</h6>
            @endif
            @if(session('error_message'))
            <h6 class="alert alert-danger autohide">{{ session('error_message') }}</h6>
            @endif
            <div class="col-xl-8 col-lg-12 col-md-12 col-12">
                <div class="cart-form table-responsive">
                    <table class="shop_table cart" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="product-thumbnail">Product</th>
                                <th class="product-price">Price</th>
                                <th class="product-quantity">Quantity</th>
                                <th class="product-quantity">Discount</th>
                                <th class="product-subtotal">Subtotal</th>
                                <th class="product-remove">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $subtotal_tot        = 0;
                            $disc_tot            = 0;
                            $after_disc_tot      = 0;
                            $shipping_tot        = 0;
                            $tax_tot             = 0;
                            $net_tot             = 0;
                            if(count($cartItems) > 0){ foreach($cartItems as $cartItem){
                            $getProduct    = Product::select('id', 'name', 'slug', 'cover_image')->where('id', '=', $cartItem->product_id)->first();
                            $subtotal_tot        += $cartItem->subtotal;
                            $disc_tot            += $cartItem->disc_amount;
                            $after_disc_tot      += $cartItem->amount_after_disc;
                            $shipping_tot        += $cartItem->shipping_amt;
                            $tax_tot             += $cartItem->tax_amt;
                            $net_tot             += $cartItem->net_amt;
                            $parent_id_val       = json_decode($cartItem->parent_id_val);
                            $child_id_val        = json_decode($cartItem->child_id_val);
                            ?>
                                <tr class="cart_item">
                                    <td class="product-thumbnail">
                                        <a href="<?=url('product/'.(($getProduct)?$getProduct->slug:''))?>" class="d-flex align-items-center">
                                            <div class="cart-product-img">
                                                <img src="<?=env('UPLOADS_URL').'product/'.(($getProduct)?$getProduct->cover_image:'')?>" alt="<?=(($getProduct)?$getProduct->name:'')?>">
                                            </div>
                                            <div class="product-name ms-2">
                                                <?=(($getProduct)?$getProduct->name:'')?>    
                                                <span class="varietion"><?=$cartItem->variation_name?></span>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="product-price">
                                        <span class=" amount">
                                            <bdi><span class="currencySymbol">$</span><?=number_format($cartItem->rate,2)?></bdi>
                                        </span>
                                    </td>
                                    <td class="product-quantity">
                                        <form method="POST" name="cart<?=$cartItem->id?>" action="<?=url('update-cart-item/'.Helper::encoded($cartItem->id))?>">
                                            @csrf
                                            <div class="qty-input quantity">
                                                <?php if($cartItem->qty > 1){?>
                                                    <button class="qty-count qty-count--minus minus" data-action="minus" type="submit" onclick="incrementDecrementQty(<?=$cartItem->qty?>);">-</button>
                                                <?php }?>
                                                <!-- <input class="product-qty" min="0" max="10" readonly type="text" value="<?=$cartItem->qty?>" name="product-qty"> -->

                                                <input class="product-qty" type="number" name="qty" min="0" max="10" value="<?= $cartItem->qty ?>" readonly>

                                                <button class="qty-count qty-count--add plus" data-action="add" type="submit" onclick="incrementDecrementQty(<?=$cartItem->qty?>);">+</button>

                                            </div>
                                        </form>
                                    </td>
                                    <td class="product-subtotal" data-title="Subtotal">
                                        <span class="amount">
                                            <bdi><span class="currencySymbol">$</span><?=number_format($cartItem->disc_amount,2)?></bdi>
                                        </span>
                                    </td>
                                    <td class="product-subtotal" data-title="Subtotal">
                                        <span class="amount">
                                            <bdi><span class="currencySymbol">$</span><?=number_format($cartItem->subtotal,2)?></bdi>
                                        </span>
                                    </td>
                                    <td class="product-remove">
                                        <a href="<?=url('cart-item-remove/'.Helper::encoded($cartItem->id))?>" onclick="return confirm('Do you want to remove this item from cart ?');">
                                            <button class="remove"><i class="fa-solid fa-trash"></i> Remove</button>
                                        </a>
                                    </td>
                                </tr>
                            <?php } } else {?>
                                <tr>
                                    <td colspan="5" style="color: red; text-align: center; font-weight: bold;">No Cart Items Found !</td>
                                </tr>
                            <?php }?>
                            <tr>
                                <td colspan="6" class="actions">
                                    <div class="bottom-cart">
                                        <form method="POST" action="">
                                            @csrf
                                            <input type="hidden" name="mode" value="coupon">
                                            <div class="coupon">
                                                <input class="input-text" id="coupon_code" placeholder="Coupon code" type="text" value="<?=session('sess_coupon_code')?>" name="coupon_code">
                                                <button type="submit" class="button" id="apply_coupon">Apply coupon</button>
                                                <?php if(session('is_coupon')){?>
                                                    <a href="<?=url('remove-coupon')?>" id="remove_coupon" onclick="return confirm('Do you want to remove coupon code ?');"><button type="button" class="button" style="background: #8b2525;">Remove coupon</button></a>
                                                <?php }?>
                                            </div>
                                        </form>
                                        <h2><a href="<?=url('/')?>"><button type="button" class="button" name="update_cart" value="Update Cart">Continue Shopping</button></a></h2>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-xl-4 col-lg-12 col-md-12 col-12">
                <div class="cart-collaterals">
                    <div class="cart_totals ">
                        <h2>Cart totals</h2>
                        <div cellspacing="0" class="shop_table shop_table_responsive">
                            <div class="cart-subtotal mb-2">
                                <div class="title">Subtotal</div>
                                <div data-title="Subtotal" class="text-end">
                                    <span class="amount">
                                        <bdi><span class="Price-currencySymbol">$ </span><?=number_format($subtotal_tot,2)?></bdi>
                                    </span>
                                </div>
                            </div>
                            <div class="cart-subtotal mb-2">
                                <div class="title">Discount</div>
                                <div data-title="Subtotal" class="text-end">
                                    <span class="amount text-danger">
                                        <bdi><span class="Price-currencySymbol">- $</span><?=number_format($disc_tot,2)?></bdi>
                                    </span>
                                </div>
                            </div>
                            <div class="cart-subtotal mb-2">
                                <div class="title">Shipping<br>
                                    <small style="font-size: 12px;color: #8b2525;font-weight: bold;">Domestic $9/item (single)</small><br>
                                    <small style="font-size: 12px;color: #8b2525;font-weight: bold;">Domestic $6/item (multiple)</small><br>
                                    <small style="font-size: 12px;color: #8b2525;font-weight: bold;">International $40/item (single)</small><br>
                                    <small style="font-size: 12px;color: #8b2525;font-weight: bold;">International $25/item (multiple)</small>
                                </div>
                                <div data-title="Subtotal" class="text-end">
                                    <span class="amount">
                                        <bdi><span class="Price-currencySymbol">$ </span><?=number_format($shipping_tot,2)?></bdi>
                                    </span>
                                </div>
                            </div>
                            <div class="cart-subtotal">
                                <div class="title">Tax<br>
                                    <small style="font-size: 12px;color: #8b2525;font-weight: bold;">(@ <?=$generalSetting->tax_percent?>%)</small>
                                </div>
                                <div data-title="Subtotal" class="text-end">
                                    <span class="amount">
                                        <bdi><span class="Price-currencySymbol">$ </span><?=number_format($tax_tot,2)?></bdi>
                                    </span>
                                </div>
                            </div>
                            <div class="order-total mt-4">
                                <div class="title">Total</div>
                                <div data-title="Total" class="text-end">
                                    <strong>
                                        <span class="amount">
                                            <bdi><span class="Price-currencySymbol">$</span><?=number_format($net_tot,2)?></bdi>
                                        </span>
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <div class="checkout-btn">
                            <?php $currentUrl = url('cart/'); ?>
                            <?php if(session('user_id')){?>
                                <a href="<?=url('checkout')?>">Proceed to checkout</a>
                            <?php } else {?>
                                <a href="<?=url('signin/'.Helper::encoded($currentUrl))?>">Proceed to checkout</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    function incrementDecrementQty(id){
        document.getElementById('cart' + id).submit();
    }
</script>