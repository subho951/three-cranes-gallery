<?php
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\CancelOrderReason;
use App\Helpers\Helper;

$generalSetting = GeneralSetting::find(1);
?>

<!DOCTYPE html>
<html lang="en" xmlns="" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="x-apple-disable-message-reformatting">
    <title>Threecranes Invoice</title>
    <style>
    
    @import url('admin/https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

body {
  font-size: 16px;
  font-family: "Poppins", serif;
}

table {
  width: 100%;
  border-collapse: collapse;
}
h3 {
    margin: 0 0 5px 0;
    font-size: 15px;
}
h6 {
    margin: 0;
    font-size: 12px;
}
p{
    margin: 0 0 5px 0;
    font-size: 13px;
}
b {
    font-size: 14px;
    font-weight: 600;
}
table tr td {
    padding: 8px;
    vertical-align: top;
    font-size: 14px;
}
.margin {
    display: block;
    margin: 17px 0px;
}

.bold {
  font-weight: bold;
}

.right {
  text-align: right;
}

.large {
  font-size: 1.75em;
}

.total {
  font-weight: bold;
  color: #fb7578;
}


.invoice-info-container {
  font-size: 0.875em;
}
.invoice-info-container td {
  padding: 4px 0;
}

.client-name {
  font-size: 1.5em;
  vertical-align: top;
}

.line-items-container {
  margin: 70px 0;
  font-size: 0.875em;
}

.line-items-container th {
  text-align: left;
  color: #999;
  border-bottom: 2px solid #ddd;
  padding: 10px 0 15px 0;
  font-size: 0.75em;
  text-transform: uppercase;
}

.line-items-container th:last-child {
  text-align: right;
}

.line-items-container td {
  padding: 15px 0;
}

.line-items-container tbody tr:first-child td {
  padding-top: 25px;
}

.line-items-container.has-bottom-border tbody tr:last-child td {
  padding-bottom: 25px;
  border-bottom: 2px solid #ddd;
}

.line-items-container.has-bottom-border {
  margin-bottom: 0;
}

.line-items-container th.heading-quantity {
  width: 50px;
}
.line-items-container th.heading-price {
  text-align: right;
  width: 100px;
}
.line-items-container th.heading-subtotal {
  width: 100px;
}

.payment-info {
  width: 38%;
  font-size: 0.75em;
  line-height: 1.5;
}

.footer {
  margin-top: 100px;
}

.footer-thanks {
  font-size: 1.125em;
}

.footer-thanks img {
  display: inline-block;
  position: relative;
  top: 1px;
  width: 16px;
  margin-right: 4px;
}

.footer-info {
  float: right;
  margin-top: 5px;
  font-size: 0.75em;
  color: #ccc;
}

.footer-info span {
  padding: 0 5px;
  color: black;
}

.footer-info span:last-child {
  padding-right: 0;
}

.page-container {
  display: none;
}
.cart-table tr td:last-child {
    text-align: right;
    width: 18%;
}
.cart-table tr td{
border: 1px solid #ccc;
}
.border-none {
    border: none !important;
    padding: 3px 0 3px 3px;
}
.text-right{
    text-align: right;
}



</style>
</head>
<body style="margin:0;padding:0;">


    <table style="width: 700px;margin: 0 auto;">

        <tr>
            <td >
                <div class="logo-container">
                    <img
                      style="height:86px"
                      src="https://admin.threecranesgallery.com/public/uploads/1726130627logo.jpg"
                    >
                  </div>
            </td>
            <td style="vertical-align: middle;
    text-align: right;">
                <h3>Order #3580293614
                </h3>
                <p>Anita Chomyn (i1px9toywk7ej1mi)
                </p>
            </td>
        </tr>
     
        <tr>
            <td colspan="2">
                <div class="margin"></div>
            </td>
        </tr>
        <tr>
            <td style="width: 25%;">
                <b>Ship to
                </b>
                <p>--------Anita Chomyn
                    4091 Milford Landing Drive
                    Apt, suite, floor, etc.
                    MILFORD, PA 18337
                    United States
                    
                </p>
                <div class="margin"></div>
                <b>Scheduled to ship by
                </b>
                <p>Jan 30, 2025</p>
                <div class="margin"></div>
                <b>Shop
                </b>
                <p>ThreeCranesGallery</p>
                <div class="margin"></div>
                <b>Order date
                </b>
                <p>Jan 29, 2025</p>
                <div class="margin"></div>
                <b>Payment method

                </b>
                <p>Paid via Etsy Payments</p>
                <div class="margin"></div>
                <b>Shipping method
                </b>
                <p>USPS Parcel Select Ground</p>
                <div class="margin"></div>
                <b>Packaging
                </b>
                <p>Package/Thick Envelope (15
                    x 12 x 2 in, 1lb)</p>
                <div class="margin"></div>
                <b>Tracking
                </b>
                <p>9405509206094668034990
                    via USPS</p>
        
            </td>
            <td style="width: 75%;">
                <table style="width: 100%;" class="cart-table">
                   
                    <tr>
                        <td>
                            <img src="https://admin.threecranesgallery.com/public/uploads/product/6787b100ad817.webp"  height="70" />
                        </td>
                        <td>
                            <h6>Hand Knitted 100% Merino Wool Snowboard Earflap Ski Peru Chullo Hat Nepalese Snowboard Polar Fleece Lined Hat Woolen Stocking Cap Sherpa Hat</h6>
                        </td>
                        <td>
                            1 x $24.00
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <img src="https://admin.threecranesgallery.com/public/uploads/product/6787b100ad817.webp"  height="70" />
                        </td>
                        <td>
                            <h6>Hand Knitted 100% Merino Wool Snowboard Earflap Ski Peru Chullo Hat Nepalese </h6>
                        </td>
                        <td>
                            1 x $24.00
                        </td>
                    </tr>
                    <tr>
                        <td class="border-none text-right"></td>
                        <td class="border-none text-right">
                            Item total 
                        </td>
                        <td class="border-none text-right">
                            $24.00
                        </td>
                    </tr>
                    <tr>
                        <td class="border-none text-right"></td>
                        <td class="border-none text-right">Shipping total</td>
                        <td class="border-none text-right">
                            $2400.00
                        </td>
                    </tr>
                    <tr>
                        <td class="border-none text-right"></td>
                        <td class="border-none text-right"><strong>Order total</strong></td>
                        <td class="border-none text-right">
                           <strong> $32.55</strong>
                        </td>
                    </tr>
                   
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="footer">
                    <div class="footer-info">
                      <span>threecranesgallery@comcast.net</span> |
                      <span>+1 215 862 5626</span> |
                      <span>threecranesgallery.com</span>
                    </div>
                    <div class="footer-thanks">
                      <img src="https://github.com/anvilco/html-pdf-invoice-template/raw/main/img/heart.png" alt="heart">
                      <span>Thank you!</span>
                    </div>
                  </div>
            </td>
        </tr>
    </table>





</body>
</html>
