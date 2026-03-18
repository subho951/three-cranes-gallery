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
<!-- CSS -->
<style>
    .info-icon {
        display: inline-block;
        margin-left: 5px;
        color: #ffffff;
        cursor: pointer;
        font-size: 14px;
        border: 1px solid #000318;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        text-align: center;
        line-height: 16px;
        font-weight: bold;
        background: #000318;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
    }

    .modal-content {
        background: #fff;
        margin: 10% auto;
        padding: 20px;
        border-radius: 10px;
        position: relative;
    }

    .close {
        position: absolute;
        right: 10px;
        top: 10px;
        font-size: 18px;
        cursor: pointer;
    }

    .guest-delivery-address {
        border: 1px solid #0000002e;
        background: #fafafa;
        overflow: auto;
        position: sticky;
        top: 30px;
        padding: 10px;
      	margin: 0
    }
  .register-form-field{
    border: none;
    background: #fff
  }
</style>
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
<form method="POST" action="<?= url('place-order') ?>" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="mode" value="order">
    <input type="hidden" name="checkout_type" value="EXISTING">
    <section class="register-form-section login-form shipping-form-section section-padding checkout_box">
        <div class=" container-xxl container-xl container-lg container-md container-sm container">
            <div class="row justify-content-center">
                @if(session('success_message'))
                <h6 class="alert alert-success autohide">{{ session('success_message') }}</h6>
                @endif
                @if(session('error_message'))
                <h6 class="alert alert-danger autohide">{{ session('error_message') }}</h6>
                @endif

                <div class="col-12 col-lg-8 col-xl-8 col-md-12">
                    <?php
                    if (empty(session('user_id'))) {
                        $guest_checked       = 'checked';
                        $existing_checked    = '';
                    } else {
                        $guest_checked       = '';
                        $existing_checked    = 'checked';
                    }
                    ?>
                    <div class="row justify-content-center mb-3">
                        <div class="col-lg-12 ">
                            <ul class="payment_methods methods user-details d-flex gap-3">
                                <?php if (empty(session('user_id'))) { ?>
                                    <li class="payment_method_cheque">
                                        <input id="checkout_type_guest" type="radio" class="input-radio" name="checkout_type" value="GUEST" data-order_button_text="Guest Checkout" <?= $guest_checked ?> required>
                                        <label for="checkout_type_guest">Guest Checkout</label>
                                    </li>
                                <?php } ?>
                                <li class="payment_method_paypal">
                                    <input id="checkout_type_existing" type="radio" class="input-radio" name="checkout_type" value="EXISTING" data-order_button_text="Existing User" <?= $existing_checked ?> required>
                                    <label for="checkout_type_existing">Existing User ?</label>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- existing user -->
                  
                    <?php if (session('user_id')) { ?>
                        <div class="text-end mb-3">
                            <button class="btn btn-lg btn-outline-warning" data-bs-toggle="modal" data-bs-target="#address">Add New Address</button>
                        </div>
                        <div class="add-d-flex">
                            <?php $currentUrl = url('checkout/'); ?>
                            <div class="checkout-add">
                                <h5 class="mb-2">Shipping Address</h5>
                                <?php if (count($getShippingAddrs) > 0) {
                                    foreach ($getShippingAddrs as $row) { ?>
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <div class="addres-head">
                                                    <p><?= $row->title ?></p>
                                                    <input type="radio" class="existing_shipping" name="shipping" id="shipping<?= $row->id ?>" value="<?= $row->id ?>" required>
                                                    <!-- <label for="shipping<?= $row->id ?>"><span class="btn btn-sm btn-outline-secondary">Select Address</span></label> -->
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p><?= $row->address ?> <?= $row->street_no ?> <?= $row->locality ?>, <?= $row->city ?>, <?= $row->state ?> <?= $row->zipcode ?>, <?= $row->country ?></p>
                                            </div>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <p class="text-danger mt-3">No shipping address available</p>
                                <?php } ?>
                            </div>
                            <div class="checkout-add">
                                <h5 class="mb-2">Billing Address</h5>
                                <?php if (count($getBillingAddrs) > 0) {
                                    foreach ($getBillingAddrs as $row) { ?>
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <div class="addres-head">
                                                    <p><?= $row->title ?></p>
                                                    <input type="radio" class="existing_billing" name="billing" id="billing<?= $row->id ?>" value="<?= $row->id ?>" required>
                                                    <!-- <label for="billing<?= $row->id ?>"><span class="btn btn-sm btn-outline-secondary">Select Address</span></label> -->
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p><?= $row->address ?> <?= $row->street_no ?> <?= $row->locality ?>, <?= $row->city ?>, <?= $row->state ?> <?= $row->zipcode ?>, <?= $row->country ?></p>
                                            </div>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <p class="text-danger mt-3">No billing address available</p>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- existing user -->
                    <div class="row">
                      <div class="col-12">
                    <?php if (!session('user_id')) { ?>
                    <!-- guest user -->
                    <div class="register-form-field new-address-filed">
                        <div class="billing-address-filed">
                            <div class="row justify-content-center guest-delivery-address">
                                <h5>Billing Address</h5>
                                <input type="hidden" id="b_company" placeholder="Smith" class="form-control form-control-lg guest_billing" name="b_company" />
                                <input type="hidden" id="s_company" placeholder="Smith" class="form-control form-control-lg guest_billing" name="s_company" />

                                <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="b_fname">First name <span class="text-danger">*</span></label>
                                        <input type="text" id="b_fname" placeholder="First name" class="form-control form-control-lg guest_billing" name="b_fname" required />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="b_lname">Last name <span class="text-danger">*</span></label>
                                        <input type="text" id="b_lname" placeholder="Last name" class="form-control form-control-lg guest_billing" name="b_lname" required />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="b_street">Street address <span class="text-danger">*</span></label>
                                        <input type="text" id="b_street" placeholder="Street address" class="form-control form-control-lg mb-2 guest_billing" name="b_street" required />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="b_suburb">City <span class="text-danger">*</span></label>
                                        <input type="text" id="b_suburb" placeholder="City" class="form-control form-control-lg guest_billing" name="b_suburb" required />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="b_country">Country <span class="text-danger">*</span></label>
                                        <input type="text" id="b_country" placeholder="Country" class="form-control form-control-lg guest_billing" name="b_country" readonly value="<?= session('shipping_country') ?>" required />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xl-3 col-md-3 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="b_state">State <span class="text-danger">*</span></label>
                                        <input type="text" id="b_state" placeholder="State" class="form-control form-control-lg guest_billing" name="b_state" required />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xl-3 col-md-3 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="b_postcode">Zip Code <span class="text-danger">*</span></label>
                                        <input type="text" id="b_postcode" placeholder="Zip Code" class="form-control form-control-lg guest_billing" name="b_postcode" required />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="b_phone">Phone <span class="text-danger">*</span></label>
                                        <input type="text" id="b_phone" placeholder="Phone" class="form-control form-control-lg guest_billing" name="b_phone" required />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="b_email">Email <span class="text-danger">*</span></label>
                                        <input type="email" id="b_email" placeholder="Email" class="form-control form-control-lg guest_billing" name="b_email" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="Shipping-address-filed mt-3">
                            <div class="row justify-content-center guest-delivery-address">
                                <h5>Shipping Address</h5>
                                <p><input type="checkbox" id="same_as_billing" name="same_as_billing" style="margin-right: 10px;"><label for="same_as_billing">Same As Billing Address</label></p>
                                <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="s_fname">First name <span class="text-danger">*</span></label>
                                        <input type="text" id="s_fname" placeholder="First name" class="form-control form-control-lg guest_shipping" name="s_fname" required />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="s_lname">Last name <span class="text-danger">*</span></label>
                                        <input type="text" id="s_lname" placeholder="Last name" class="form-control form-control-lg guest_shipping" name="s_lname" required />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="s_street">Street address <span class="text-danger">*</span></label>
                                        <input type="text" id="s_street" placeholder="Street address" class="form-control form-control-lg guest_shipping mb-2" name="s_street" required />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="s_suburb">City <span class="text-danger">*</span></label>
                                        <input type="text" id="s_suburb" placeholder="City" class="form-control form-control-lg guest_shipping" name="s_suburb" required />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="s_country">Country <span class="text-danger">*</span></label>
                                        <input type="text" id="s_country" placeholder="Country" class="form-control form-control-lg guest_billing" name="s_country" readonly value="<?= session('shipping_country') ?>" required />
                                        <span style="font-size: 10px;">Change Shipping Country? <a href="<?= url('cart') ?>" class="text-primary">Click Here</a></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xl-3 col-md-3 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="s_state">State <span class="text-danger">*</span></label>
                                        <input type="text" id="s_state" placeholder="State" class="form-control form-control-lg guest_shipping" name="s_state" required />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xl-3 col-md-3 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="s_postcode">Zip Code <span class="text-danger">*</span></label>
                                        <input type="text" id="s_postcode" placeholder="Zip Code" class="form-control form-control-lg guest_shipping" name="s_postcode" required />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="s_phone">Phone <span class="text-danger">*</span></label>
                                        <input type="text" id="s_phone" placeholder="Phone" class="form-control form-control-lg guest_shipping" name="s_phone" required />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="s_email">Email <span class="text-danger">*</span></label>
                                        <input type="email" id="s_email" placeholder="Email" class="form-control form-control-lg guest_shipping" name="s_email" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- guest user -->
                    <?php } ?>
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
                            if (count($cartItems) > 0) {
                                foreach ($cartItems as $cartItem) {
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
                                                <img width="50" src="<?= env('UPLOADS_URL') . 'product/' . (($getProduct) ? $getProduct->cover_image : '') ?>" class="imd-fluid" alt="<?= (($getProduct) ? $getProduct->name : '') ?>">
                                            </div>
                                            <div class="product-name">
                                                <p><?= (($getProduct) ? $getProduct->name : '') ?> </p>
                                                <strong class="product-quantity">QTY : <?= $cartItem->qty ?></strong><br>
                                                <span><?= $cartItem->variation_name ?></span>
                                            </div>
                                        </div>
                                        <div class="product-total">
                                            <span class="amount">
                                                <bdi><span class="Price-currencySymbol">$ </span><?= number_format($cartItem->rate, 2) ?></bdi>
                                            </span>
                                            <!-- <sub>$48.00</sub> -->
                                        </div>
                                    </div>
                            <?php }
                            } ?>
                            <div class="cart-subtotal-list">
                                <h2>Subtotal</h2>
                                <div class="subtotal-price">
                                    <span class="Price-amount amount">
                                        <bdi><span class="Price-currencySymbol">$ </span><?= number_format($subtotal_tot, 2) ?></bdi>
                                    </span>
                                    <input type="hidden" name="subtotal" value="<?= $subtotal_tot ?>">
                                </div>
                            </div>
                            <div class="cart-subtotal-list">
                                <h2>Discount</h2>
                                <div class="subtotal-price">
                                    <span class="Price-amount amount">
                                        <bdi><span class="Price-currencySymbol">-$ </span><?= number_format($disc_tot, 2) ?></bdi>
                                    </span>
                                    <input type="hidden" name="disc_amount" value="<?= $disc_tot ?>">
                                    <input type="hidden" name="amount_after_disc" value="<?= $after_disc_tot ?>">
                                </div>
                            </div>
                            <div class="cart-subtotal-list">
                                <h2>
                                    Shipping
                                    <span class="info-icon" onclick="openModal('shippingInfo')">i</span>
                                </h2>
                                <div class="subtotal-price">
                                    <span class="Price-amount amount">
                                        <bdi><span class="Price-currencySymbol">$ </span><?= number_format($shipping_tot, 2) ?></bdi>
                                    </span>
                                    <input type="hidden" name="shipping_amt" value="<?= $shipping_tot ?>">
                                </div>
                            </div>
                            <div class="cart-subtotal-list">
                                <h2>Tax
                                    <span class="info-icon" onclick="openModal('taxInfo')">i</span>
                                </h2>
                                <div class="subtotal-price">
                                    <span class="Price-amount amount">
                                        <bdi><span class="Price-currencySymbol">$ </span><?= number_format($tax_tot, 2) ?></bdi>
                                    </span>
                                    <input type="hidden" name="tax_amt" value="<?= $tax_tot ?>">
                                </div>
                            </div>
                            <div class="cart-subtotal-list order-total">
                                <h2>Total</h2>
                                <div class="total-price">
                                    <strong>
                                        <span class="amount">
                                            <bdi><span class="Price-currency">$ </span><?= number_format($net_tot, 2) ?></bdi>
                                        </span>
                                    </strong>
                                    <input type="hidden" name="net_amt" value="<?= $net_tot ?>">
                                </div>
                            </div>
                        </div>
                        <div id="payment" class="checkout-payment">
                            <ul class=" payment_methods methods p-2">
                                <li class=" payment_method_cheque">
                                    <input id="payment_method_cheque" type="radio" class="input-radio" name="payment_method" value="STRIPE" data-order_button_text="Proceed to Card" checked required>
                                    <label for="payment_method_cheque">
                                        Pay By Stripe
                                        <img src="<?= url('public/material/frontend/images/Stripe.webp') ?>" class="img-thumbnail" style="width:80px; height:35px;">
                                        <small style="font-size: 10px;">(CREDIT & DEBIT CARD)</small>
                                    </label>
                                </li>
                                <li class=" payment_method_paypal">
                                    <input id="payment_method_paypal" type="radio" class="input-radio" name="payment_method" value="PAYPAL" data-order_button_text="Proceed to PayPal" required>
                                    <label for="payment_method_paypal">
                                        Pay By PayPal
                                        <img src="<?= url('public/material/frontend/images/Paypal.webp') ?>" class="img-thumbnail" style="width:80px; height:35px;">
                                        <small style="font-size: 10px;">(CREDIT CARD & PAYPAL)</small>
                                    </label>
                                </li>
                                <!-- <li class=" payment_method_authorize">
                                    <input id="payment_method_authorize" type="radio" class="input-radio" name="payment_method" value="AUTHORIZE.NET" data-order_button_text="Proceed to Card" required>
                                    <label for="payment_method_authorize">Pay By Authorize.Net <small style="font-size: 10px;">(CREDIT CARD)</small></label>
                                </li> -->
                            </ul>
                        </div>
                        <div id="payment" class="checkout-payment" style="background:transparent">
                            <div class="form-row place-order">
                                <!-- <button type="button" id="authorize_div" class="button btn-place-order common-btn payment-box" data-bs-toggle="modal" data-bs-target="#pay">Pay By Authorize.Net &amp; Place Order</button>-->
                                <button type="submit" id="stripe_div" class="button btn-place-order common-btn payment-box">Pay By Stripe &amp; Place Order</button>
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
                            <div class="rccs mb-3 mb-md-0" data-testid="rccs">
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
                                    <div class="form-group mb-3 mt-3">
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
<!-- Modal Structure -->
<div id="shippingInfo" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('shippingInfo')">&times;</span>
        <h5>Shipping Information</h5>
        <p>Domestic $<?= $generalSetting->domestic_shipping_single_item ?>/item (single)</p>
        <p>Domestic $<?= $generalSetting->domestic_shipping_multiple_item ?>/item (multiple)</p>
        <p>International $<?= $generalSetting->international_shipping_single_item ?>/item (single)</p>
        <p>International $<?= $generalSetting->international_shipping_multiple_item ?>/item (multiple)</p>
    </div>
</div>

<div id="taxInfo" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('taxInfo')">&times;</span>
        <h5>Tax Information</h5>
        <p>Tax is calculated at <?= $generalSetting->tax_percent ?>%</p>
    </div>
</div>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMbNCogNokCwVmJCRfefB6iCYUWv28LjQ&libraries=places&callback=initAutocomplete&libraries=places&v=weekly"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Elements in preview card
        const cardNumberEl = document.querySelector(".rccs__number");
        const cardNameEl = document.querySelector(".rccs__name");
        const cardExpiryEl = document.querySelector(".rccs__expiry__value");

        // Input fields
        const inputNumber = document.querySelector("input[name='card_number']");
        const inputName = document.querySelector("input[name='name']");
        const inputExpiry = document.querySelector("input[name='expiry']");

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
            let oldValue = this.value;
            let pos = this.selectionStart;

            // Format the expiry input
            let cleaned = oldValue.replace(/\D/g, "");
            let formatted = cleaned;
            if (cleaned.length > 2) {
                formatted = cleaned.slice(0, 2) + "/" + cleaned.slice(2, 6);
            } else if (cleaned.length > 0) {
                formatted = cleaned;
            }

            this.value = formatted;

            // Update card preview
            cardExpiryEl.textContent = formatted || "••/••••";

            // === Cursor management ===
            if (e.inputType === "insertText" && cleaned.length === 2 && !oldValue.includes("/")) {
                // Move cursor after the slash
                this.setSelectionRange(3, 3);
            } else {
                // Keep normal typing flow
                this.setSelectionRange(this.value.length, this.value.length);
            }
        });
    });
    $(document).ready(function() {
        $('input[name="payment_method"]').on('change', function() {
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
<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'block';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>
<script>
    $(document).ready(function() {
        $('#checkout_type_existing').on('change', function() {
            if (this.checked) {
                if (confirm('You will be redirected to login. Continue?')) {
                    window.location.href = '<?= url('login') ?>';
                }
            }
        });
    });
</script>
<script type="text/javascript">
    $('input[name="same_as_billing"]').click(function() {
        if ($('input[name="same_as_billing"]:checked').val() == 'on') {
            $('#s_fname').val($('#b_fname').val());
            $('#s_lname').val($('#b_lname').val());
            $('#s_phone').val($('#b_phone').val());
            $('#s_email').val($('#b_email').val());
            $('#s_company').val($('#b_company').val());
            $('#s_country').val($('#b_country').val());
            $('#s_street').val($('#b_street').val());
            $('#s_suburb').val($('#b_suburb').val());
            $('#s_state').val($('#b_state').val());
            $('#s_postcode').val($('#b_postcode').val());
        } else {
            $('#s_fname').val('');
            $('#s_lname').val('');
            $('#s_phone').val('');
            $('#s_email').val('');
            $('#s_company').val('');
            $('#s_country').val('');
            $('#s_street').val('');
            $('#s_suburb').val('');
            $('#s_state').val('');
            $('#s_postcode').val('');
        }
    });
    $(function() {
        $('#existing_panel').show();
        $('#existing_panel2').show();
        $('#guest_panel').hide();
        // $('.existing_billing').attr('required', true);
        // $('.existing_shipping').attr('required', true);
        var checkout_type = $('input[name="checkout_type"]:checked').val();
        if(checkout_type == 'GUEST'){
            $('.guest_billing').attr('required', true);
            $('.guest_shipping').attr('required', true);
        } else {
            $('.guest_billing').attr('required', false);
            $('.guest_shipping').attr('required', false);
        }
    })
    $('input[name="checkout_type"]').click(function() {
        if ($('input[name="checkout_type"]:checked').val() == 'GUEST') {
            $('#existing_panel').hide();
            $('#existing_panel2').hide();
            $('#guest_panel').show();
            // $('.existing_billing').attr('required', false);
            // $('.existing_shipping').attr('required', false);
            $('.guest_billing').attr('required', true);
            $('.guest_shipping').attr('required', true);
        } else {
            $('#existing_panel').show();
            $('#existing_panel2').show();
            $('#guest_panel').hide();
            // $('.existing_billing').attr('required', true);
            // $('.existing_shipping').attr('required', true);
            $('.guest_billing').attr('required', false);
            $('.guest_shipping').attr('required', false);
        }
    });
</script>
<script>
    document.getElementById('b_phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // remove non-digits

        if (value.length > 10) {
            value = value.substring(0, 10);
        }

        let formatted = '';

        if (value.length > 0) {
            formatted = '(' + value.substring(0, 3);
        }
        if (value.length >= 4) {
            formatted += ')' + value.substring(3, 6);
        }
        if (value.length >= 7) {
            formatted += ' - ' + value.substring(6, 10);
        }

        e.target.value = formatted;
    });
    document.getElementById('s_phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // remove non-digits

        if (value.length > 10) {
            value = value.substring(0, 10);
        }

        let formatted = '';

        if (value.length > 0) {
            formatted = '(' + value.substring(0, 3);
        }
        if (value.length >= 4) {
            formatted += ')' + value.substring(3, 6);
        }
        if (value.length >= 7) {
            formatted += ' - ' + value.substring(6, 10);
        }

        e.target.value = formatted;
    });
</script>