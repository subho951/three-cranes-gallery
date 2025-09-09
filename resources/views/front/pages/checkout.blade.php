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
<style type="text/css">
    .pac-container {
        z-index: 10000 !important;
    }
</style>
<script>
    let autocomplete;
    let address1Field;
    let address2Field;
    let postalField;

    function initAutocomplete() {
        address1Field = document.querySelector("#address1");
        address2Field = document.querySelector("#street_no1");
        postalField = document.querySelector("#zipcode1");
        autocomplete = new google.maps.places.Autocomplete(address1Field, {
            componentRestrictions: {
                country: ["us", "ca"]
            },
            fields: ["address_components", "geometry", "formatted_address"],
            types: ["address"],
        });
        address1Field.focus();
        autocomplete.addListener("place_changed", fillInAddress);
    }

    function fillInAddress() {
        const place = autocomplete.getPlace();
        let address1 = "";
        let postcode = "";
        for (const component of place.address_components) {
            const componentType = component.types[0];
            switch (componentType) {
                case "postal_code": {
                    postcode = `${component.long_name}${postcode}`;
                    break;
                }
                case "postal_code_suffix": {
                    postcode = `${postcode}-${component.long_name}`;
                    break;
                }
                case "street_number": {
                    document.querySelector("#street_no1").value = component.long_name;
                    break;
                }
                case "route": {
                    document.querySelector("#locality1").value = component.long_name;
                    break;
                }
                case "locality": {
                    document.querySelector("#city1").value = component.long_name;
                    break;
                }
                case "administrative_area_level_1": {
                    document.querySelector("#state1").value = component.short_name;
                    break;
                }
                case "country":
                    document.querySelector("#country1").value = component.short_name;
                    break;
            }
        }
        address1Field.value = place.formatted_address;
        postalField.value = postcode;
        document.querySelector("#lat1").value = place.geometry.location.lat();
        document.querySelector("#lng1").value = place.geometry.location.lng();
        address2Field.focus();
    }
    window.initAutocomplete = initAutocomplete;
</script>
<form method="POST" action="<?=url('place-order')?>" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="mode" value="order">
    <input type="hidden" name="checkout_type" value="EXISTING">
    <section class="register-form-section login-form shipping-form-section section-padding">
        <div class=" container-xxl container-xl container-lg container-md container-sm container">
            <div class="row justify-content-center">
                @if(session('success_message'))
                <h6 class="alert alert-success autohide">{{ session('success_message') }}</h6>
                @endif
                @if(session('error_message'))
                <h6 class="alert alert-danger autohide">{{ session('error_message') }}</h6>
                @endif
                <div class="col-12 col-lg-8 col-xl-8 col-md-12">
                    <div class="text-end mb-3">
                        <button class="btn btn-lg btn-outline-warning" data-bs-toggle="modal" data-bs-target="#address">Add New Address</button>
                    </div>
                    <div class="add-d-flex">
                        <?php $currentUrl = url('checkout/'); ?>
                        <div class="checkout-add">
                            <h5 class="mb-2">Shipping Address</h5>
                            <?php if(count($getShippingAddrs) > 0){ foreach($getShippingAddrs as $row){?>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <div class="addres-head">
                                            <p><?=$row->title?></p>
                                            <input type="radio" class="existing_shipping" name="shipping" id="shipping<?=$row->id?>" value="<?=$row->id?>" required>
                                            <label for="shipping<?=$row->id?>"><span class="btn btn-sm btn-outline-secondary">Select Address</span></label>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p><?=$row->address?> <?=$row->street_no?> <?=$row->locality?>, <?=$row->city?>, <?=$row->state?> <?=$row->zipcode?>, <?=$row->country?></p>
                                    </div>
                                </div>
                            <?php } }?>
                        </div>
                        <div class="checkout-add">
                            <h5 class="mb-2">Billing Address</h5>
                            <?php if(count($getBillingAddrs) > 0){ foreach($getBillingAddrs as $row){?>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <div class="addres-head">
                                            <p><?=$row->title?></p>
                                            <input type="radio" class="existing_billing" name="billing" id="billing<?=$row->id?>" value="<?=$row->id?>" required>
                                            <label for="billing<?=$row->id?>"><span class="btn btn-sm btn-outline-secondary">Select Address</span></label>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p><?=$row->address?> <?=$row->street_no?> <?=$row->locality?>, <?=$row->city?>, <?=$row->state?> <?=$row->zipcode?>, <?=$row->country?></p>
                                    </div>
                                </div>
                            <?php } }?>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5 col-md-12 col-12">
                    <div class="checkout-review-order-table-wrapper">
                        <div class="title-product-name">Product</div>
                        <div class="shop_table">
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
                                <div class="cart_item-ccheck">
                                    <div class="info-product">
                                        <div class="product-thumble">
                                            <img width="50" src="<?=env('UPLOADS_URL').'product/'.(($getProduct)?$getProduct->cover_image:'')?>" class="imd-fluid" alt="<?=(($getProduct)?$getProduct->name:'')?>">
                                        </div>
                                        <div class="product-name">
                                            <p><?=(($getProduct)?$getProduct->name:'')?> </p>
                                            <strong class="product-quantity">QTY : <?=$cartItem->qty?></strong><br>
                                            <span><?=$cartItem->variation_name?></span>
                                        </div>
                                    </div>
                                    <div class="product-total">
                                        <span class="amount">
                                            <bdi><span class="Price-currencySymbol">$ </span><?=number_format($cartItem->rate,2)?></bdi>
                                        </span>
                                        <!-- <sub>$48.00</sub> -->
                                    </div>
                                </div>
                            <?php } }?>
                            <div class="cart-subtotal-list">
                                <h2>Subtotal</h2>
                                <div class="subtotal-price">
                                    <span class="Price-amount amount">
                                        <bdi><span class="Price-currencySymbol">$ </span><?=number_format($subtotal_tot,2)?></bdi>
                                    </span>
                                    <input type="hidden" name="subtotal" value="<?=$subtotal_tot?>">
                                </div>
                            </div>
                            <div class="cart-subtotal-list">
                                <h2>Discount</h2>
                                <div class="subtotal-price">
                                    <span class="Price-amount amount">
                                        <bdi><span class="Price-currencySymbol">-$ </span><?=number_format($disc_tot,2)?></bdi>
                                    </span>
                                    <input type="hidden" name="disc_amount" value="<?=$disc_tot?>">
                                    <input type="hidden" name="amount_after_disc" value="<?=$after_disc_tot?>">
                                </div>
                            </div>
                            <div class="cart-subtotal-list">
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
                                        <bdi><span class="Price-currencySymbol">$ </span><?=number_format($shipping_tot,2)?></bdi>
                                    </span>
                                    <input type="hidden" name="shipping_amt" value="<?=$shipping_tot?>">
                                </div>
                            </div>
                            <div class="cart-subtotal-list">
                                <h2>Tax
                                    <br>
                                    <small style="font-size: 12px;color: #8b2525;font-weight: bold;">(@ <?=$generalSetting->tax_percent?>%)</small>
                                </h2>
                                <div class="subtotal-price">
                                    <span class="Price-amount amount">
                                        <bdi><span class="Price-currencySymbol">$ </span><?=number_format($tax_tot,2)?></bdi>
                                    </span>
                                    <input type="hidden" name="tax_amt" value="<?=$tax_tot?>">
                                </div>
                            </div>
                            <div class="cart-subtotal-list order-total">
                                <h2>Total</h2>
                                <div class="total-price">
                                    <strong>
                                        <span class="amount">
                                            <bdi><span class="Price-currency">$ </span><?=number_format($net_tot,2)?></bdi>
                                        </span>
                                    </strong>
                                    <input type="hidden" name="net_amt" value="<?=$net_tot?>">
                                </div>
                            </div>
                        </div>
                        <div id="payment" class="checkout-payment">
                            <ul class=" payment_methods methods">
                                <li class=" payment_method_authorize">
                                    <input id="payment_method_authorize" type="radio" class="input-radio" name="payment_method" value="AUTHORIZE.NET" data-order_button_text="Proceed to Card" checked required>
                                    <label for="payment_method_authorize">Pay By Authorize.Net</label>
                                </li>
                                <li class=" payment_method_cheque">
                                    <input id="payment_method_cheque" type="radio" class="input-radio" name="payment_method" value="STRIPE" data-order_button_text="Proceed to Card" required>
                                    <label for="payment_method_cheque">Pay By Stripe</label>
                                </li>
                                <li class=" payment_method_paypal">
                                    <input id="payment_method_paypal" type="radio" class="input-radio" name="payment_method" value="PAYPAL" data-order_button_text="Proceed to PayPal" required>
                                    <label for="payment_method_paypal">
                                    Pay By PayPal</label>
                                </li>
                            </ul>
                        </div>
                        <div id="payment" class="checkout-payment">
                            <div class="form-row place-order">
                                <button type="button" id="authorize_div" class="button btn-place-order common-btn payment-box" data-bs-toggle="modal" data-bs-target="#pay">Pay By Authorize.Net &amp; Place Order</button>
                                <button type="submit" id="stripe_div" class="button btn-place-order common-btn payment-box" style="display: none;">Pay By Stripe &amp; Place Order</button>
                                <button type="submit" id="paypal_div" class="button btn-place-order common-btn payment-box" style="display: none;">Pay By PayPal &amp; Place Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- pay-model- -->
    <div class="modal fade" id="pay" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title h4">Card Details</div><button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="rccs" data-testid="rccs">
                                <div data-testid="rccs__card" class="rccs__card rccs__card--unknown">
                                    <div class="rccs__card--front">
                                        <div class="rccs__card__background"></div>
                                        <div class="rccs__issuer"></div>
                                        <div class="rccs__cvc__front"></div>
                                        <div class="rccs__number">•••• •••• •••• ••••</div>
                                        <div class="rccs__name">YOUR NAME HERE</div>
                                        <div class="rccs__expiry">
                                            <div class="rccs__expiry__valid">valid thru</div>
                                            <div class="rccs__expiry__value">••/••••</div>
                                        </div>
                                        <div class="rccs__chip"></div>
                                    </div>
                                    <div class="rccs__card--back">
                                        <div class="rccs__card__background"></div>
                                        <div class="rccs__stripe"></div>
                                        <div class="rccs__signature"></div>
                                        <div class="rccs__cvc"></div>
                                        <div class="rccs__issuer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <input placeholder="Card Number" class="form-control" maxlength="16" minlength="16" type="text" name="card_number" id="card_number" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <input placeholder="Card Name" class="form-control" type="text" name="name" id="card_name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <input placeholder="MM/YYYY" class="form-control" name="expiry" id="expiry" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <input placeholder="CVC" class="form-control" maxlength="3" minlength="3" type="password" name="cvc" id="cvc" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-end">
                                    <button class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary ms-2">Pay Now</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- address-model-1 -->
<div class="modal fade" id="address" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title h4">Add Billing/Shipping Address</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="mode" value="address">
                    <div class="row form-style1">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="type1">Type *</label>
                                <select name="type" id="type1" class="form-control form-control-lg" required>
                                    <option value="" selected>Select Type</option>
                                    <option value="BILLING">BILLING</option>
                                    <option value="SHIPPING">SHIPPING</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="title1">Title *</label>
                                <input type="text" name="title" id="title1" class="form-control form-control-lg" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label" for="address1">Address *</label>
                                <input type="text" name="address" id="address1" class="form-control form-control-lg" required>
                                <input type="hidden" name="lat" id="lat1">
                                <input type="hidden" name="lng" id="lng1">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="country1">Country *</label>
                                <input type="text" name="country" id="country1" class="form-control form-control-lg" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="state1">State *</label>
                                <input type="text" name="state" id="state1" class="form-control form-control-lg" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="city1">City *</label>
                                <input type="text" name="city" id="city1" class="form-control form-control-lg" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="locality1">Locality *</label>
                                <input type="text" name="locality" id="locality1" class="form-control form-control-lg">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="street_no1">Street No *</label>
                                <input type="text" name="street_no" id="street_no1" class="form-control form-control-lg">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="zipcode1">Zipcode *</label>
                                <input type="text" name="zipcode" id="zipcode1" class="form-control form-control-lg">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary ms-2 cus-btn" type="submit">Add Address</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMbNCogNokCwVmJCRfefB6iCYUWv28LjQ&libraries=places&callback=initAutocomplete&libraries=places&v=weekly"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Elements in preview card
    const cardNumberEl = document.querySelector(".rccs__number");
    const cardNameEl   = document.querySelector(".rccs__name");
    const cardExpiryEl = document.querySelector(".rccs__expiry__value");

    // Input fields
    const inputNumber  = document.querySelector("input[name='card_number']");
    const inputName    = document.querySelector("input[name='name']");
    const inputExpiry  = document.querySelector("input[name='expiry']");

    // Format card number into groups of 4
    function formatCardNumber(num) {
        return num.replace(/\D/g, "")
                  .replace(/(.{4})/g, "$1 ")
                  .trim();
    }

    // Format expiry as MM/YYYY
    function formatExpiry(value) {
        let cleaned = value.replace(/\D/g, ""); // only digits
        if (cleaned.length >= 2) {
            let mm = cleaned.substring(0, 2);
            let yyyy = cleaned.substring(2, 6);
            return yyyy ? mm + "/" + yyyy : mm + "/";
        }
        return cleaned;
    }

    // Event listeners
    inputNumber.addEventListener("input", function() {
        cardNumberEl.textContent = this.value ? formatCardNumber(this.value) : "•••• •••• •••• ••••";
    });

    inputName.addEventListener("input", function() {
        cardNameEl.textContent = this.value ? this.value.toUpperCase() : "YOUR NAME HERE";
    });

    inputExpiry.addEventListener("input", function(e) {
        let cursorPos = this.selectionStart;
        let formatted = formatExpiry(this.value);

        this.value = formatted;
        cardExpiryEl.textContent = formatted || "••/••••";

        // keep cursor at right place
        this.selectionEnd = cursorPos + (formatted.length - this.value.length);
    });
});
$(document).ready(function () {
    $('input[name="payment_method"]').on('change', function () {
        // Hide all
        $('.payment-box').hide();

        // Show based on selected
        if ($(this).val() === 'AUTHORIZE.NET') {
            $('#authorize_div').show();
            $('#stripe_div').hide();
            $('#paypal_div').hide();

            $('#card_number').attr('required', true);
            $('#card_name').attr('required', true);
            $('#expiry').attr('required', true);
            $('#cvc').attr('required', true);
        } else if ($(this).val() === 'STRIPE') {
            $('#authorize_div').hide();
            $('#stripe_div').show();
            $('#paypal_div').hide();

            $('#card_number').attr('required', false);
            $('#card_name').attr('required', false);
            $('#expiry').attr('required', false);
            $('#cvc').attr('required', false);
        } else if ($(this).val() === 'PAYPAL') {
            $('#authorize_div').hide();
            $('#stripe_div').hide();
            $('#paypal_div').show();

            $('#card_number').attr('required', false);
            $('#card_name').attr('required', false);
            $('#expiry').attr('required', false);
            $('#cvc').attr('required', false);
        }
    });
});
</script>

