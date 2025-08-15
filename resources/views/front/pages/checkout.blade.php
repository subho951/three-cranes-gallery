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
<section class="register-form-section login-form shipping-form-section section-padding">
    <div class=" container-xxl container-xl container-lg container-md container-sm container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-8 col-md-12">
                <div class="text-end mb-3"> <button class="btn btn-lg btn-outline-warning" data-bs-toggle="modal"
                        data-bs-target="#address">Add New Address</button>
                </div>
                <div class="add-d-flex">
                    <div class="checkout-add">
                        <h5 class="mb-2">Shipping Address</h5>
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="addres-head">
                                    <p>Home</p><button class="btn btn-sm btn-outline-secondary">Select
                                        Address</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <p>125c Bristol Pike, Levittown, PA 19054, USA</p>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="addres-head">
                                    <p>Home</p><button class="btn btn-sm btn-outline-secondary">Select
                                        Address</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <p>125c Bristol Pike, Levittown, PA 19054, USA</p>
                            </div>
                        </div>
                    </div>
                    <div class="checkout-add">
                        <h5 class="mb-2">Billing Address</h5>
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="addres-head">
                                    <p>Home</p><button class="btn btn-sm btn-outline-secondary">Select
                                        Address</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <p>125c Bristol Pike, Levittown, PA 19054, USA</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-12 col-12">
                <div class="checkout-review-order-table-wrapper">
                    <div class="title-product-name">Product</div>
                    <div class="shop_table ">
                        <div class="cart_item-ccheck">
                            <div class="info-product">
                                <div class="product-thumble"><img width="50"
                                        src="https://admin.threecranesgallery.com/public/uploads/product/67c03394c7a71.webp"
                                        class="imd-fluid" alt=""></div>
                                <div class="product-name">
                                    <p>Hand Painted Reverse Tie Dye Tie-Dye Maxi Short Sleeve
                                        long Maxi Dress tie dye dress Tie Dye Bohemian Dress Tie Dye Hippie Festival</p>
                                    <strong class="product-quantity">QTY : 1</strong><br><span>M US women's
                                        letter</span>
                                </div>
                            </div>
                            <div class="product-total"><span class="amount"><bdi><span
                                            class="Price-currencySymbol">$ </span>48.00</bdi></span><sub>$
                                    48.00</sub></div>
                        </div>
                        <div class="cart_item-ccheck">
                            <div class="info-product">
                                <div class="product-thumble"><img width="50"
                                        src="https://admin.threecranesgallery.com/public/uploads/product/677d970da0e90.avif"
                                        class="imd-fluid" alt=""></div>
                                <div class="product-name">
                                    <p>Hand Made 100% Merino Wool Flip Top Snowboard Finger less Ski Polar Fleece Lined Fingerless Nepalese Mittens Convertible Texting Gloves</p>
                                    <strong class="product-quantity">QTY : 1</strong><br><span>L Unisex
                                        Adults</span>
                                </div>
                            </div>
                            <div class="product-total"><span class="amount"><bdi><span
                                            class="Price-currencySymbol">$ </span>15.00</bdi></span><sub>$
                                    24.00</sub></div>
                        </div>
                        <div class="cart_item-ccheck">
                            <div class="info-product">
                                <div class="product-thumble"><img width="50"
                                        src="https://admin.threecranesgallery.com/public/uploads/product/677d7633d490c.jpg"
                                        class="imd-fluid" alt=""></div>
                                <div class="product-name">
                                    <p>Hand Made 100% Merino Wool Flip Top Snowboard Finger less Ski Polar Fleece Lined Fingerless Nepalese Mittens Convertible Texting Gloves</p><strong class="product-quantity">QTY : 1</strong><br><span></span>
                                </div>
                            </div>
                            <div class="product-total"><span class="amount"><bdi><span
                                            class="Price-currencySymbol">$ </span>24.00</bdi></span><sub>$
                                    24.00</sub></div>
                        </div>
                        <div class="cart-subtotal-list">
                            <h2>Subtotal</h2>
                            <div class="subtotal-price"><span class="Price-amount amount"><bdi><span
                                            class="Price-currencySymbol">$ </span>87.00</bdi></span></div>
                        </div>
                        <div class="cart-subtotal-list">
                            <h2>Shipping</h2>
                            <div class="subtotal-price"><span class="Price-amount amount"><bdi><span
                                            class="Price-currencySymbol">$ </span>9.00</bdi></span></div>
                        </div>
                        <div class="cart-subtotal-list">
                            <h2>Tax</h2>
                            <div class="subtotal-price"><span class="Price-amount amount"><bdi><span
                                            class="Price-currencySymbol">$ </span>5.22</bdi></span></div>
                        </div>
                        <div class="cart-subtotal-list">
                            <h2>Discount</h2>
                            <div class="subtotal-price"><span class="Price-amount amount"><bdi><span
                                            class="Price-currencySymbol">-$ </span>0.00</bdi></span></div>
                        </div>
                        <div class="cart-subtotal-list order-total">
                            <h2>Total</h2>
                            <div class="total-price"><strong><span class="amount"><bdi><span
                                                class="Price-currency">$ </span>101.22</bdi></span></strong></div>
                        </div>
                    </div>
                    <div id="payment" class="checkout-payment">
                        <div class="form-row place-order"><button type="submit"
                                class="button btn-place-order common-btn" data-bs-toggle="modal"
                                data-bs-target="#pay">Pay &amp; Place order</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- address-model-1 -->
<div class="modal fade" id="address" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title h4">Add Address</div> <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row form-style1">
                    <div class="col-lg-6">
                        <div class="form-group"><label>Address Type</label><br><input type="radio" value="Home"
                                checked="" name="type"> Home &nbsp;<input type="radio" value="Work" name="type">
                            Work</div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group"><label>Address For</label><br><input type="radio"
                                value="SHIPPING" checked="" name="for"> Shipping &nbsp;<input type="radio"
                                value="BILLING" name="for"> Billing</div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group"><label>Name *</label><input class="form-control"
                                placeholder="Enter Full Name" type="text" value=""></div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group"><label>Phone *</label><input class="form-control"
                                placeholder="Phone" type="text" value=""></div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group"><label>Email *</label><input class="form-control"
                                placeholder="Email" type="email" value=""></div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group"><label>Address</label>
                            <div><input placeholder="Enter Address" class="form-control pac-target-input"
                                    type="text" autocomplete="off"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group"><label>Country</label><input class="form-control"
                                placeholder="Country" type="text" value=""></div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group"><label>State</label><input class="form-control"
                                placeholder="State" type="text" value=""></div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group"><label>City</label><input class="form-control"
                                placeholder="City" type="text" value=""></div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group"><label>Zipcode *</label><input class="form-control"
                                placeholder="Zipcode" type="text" value=""></div>
                    </div>
                </div><button class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button><button
                    class="btn btn-primary ms-2">Add Address</button>
            </div>
        </div>
    </div>
</div>

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
                                        <div class="rccs__expiry__value">••/••</div>
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
                                <div class="form-group mb-3"><input placeholder="Card Number"
                                        class="form-control" maxlength="16" type="text" value="" name="number">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group mb-3"><input placeholder="Card Name" class="form-control"
                                        type="text" value="" name="name"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-3"><input placeholder="MM/YY" class="form-control"
                                        value="" name="expiry"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-3"><input placeholder="CVC" class="form-control"
                                        maxlength="3" type="text" value="" name="cvc"></div>
                            </div>
                            <div class="col-lg-12 text-end"><button class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button><button
                                    class="btn btn-primary ms-2">Pay Now</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>