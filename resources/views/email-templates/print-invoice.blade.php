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

// function image_to_base64($file_path) {
//     $imageData = file_get_contents($file_path);
//     return 'data:image/png;base64,' . base64_encode($imageData);
// }
// $image = image_to_base64('https://admin.threecranesgallery.com/public/uploads/product/677d8f799ea3f.avif'); // Convert WebP to Base64
// echo $html = '<img src="' . $image . '" height="70" />';die;

// function image_to_base64($url) {
//     $imageData = file_get_contents($url);
//     $base64 = base64_encode($imageData);
//     return 'data:image/png;base64,' . $base64; // Convert AVIF to PNG
// }

// $base64_image = image_to_base64('https://admin.threecranesgallery.com/public/uploads/product/677d970da0e90.avif');
// echo $html = '<img src="' . $base64_image . '" />';die;
?>
<!DOCTYPE html>
<html lang="en" xmlns="" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="x-apple-disable-message-reformatting">
    <title>Threecranes Invoice</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
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
            font-size: 12px;
        }
        .margin {
            display: block;
            /*margin: 17px 0px;*/
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
          font-size:14px;
        }
        .footer-info span:last-child {
          padding-right: 0;
        }
        .page-container {
          display: none;
        }
        .cart-table tr td:last-child {
            text-align: right;
            width: 16%;
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
        .table-head td{
            font-weight: 600;
            background: #f5f5f5;
            font-size: 11px;
        }
        .muted-red{
            color: #b94a48;
            font-size: 11px;
            font-weight: 600;
            margin: 3px 0;
        }
    </style>
</head>
<body style="margin:0;padding:0;">
    <table style="width: 700px;margin: 0 auto;">
        <tr>
            <td >
                <div class="logo-container">
                    <img style="height:86px" src="data:image/svg+xml;base64,<?php echo base64_encode(file_get_contents(base_path('public/uploads/' . $generalSetting->site_logo))); ?>" alt="<?=$generalSetting->site_name?>">
                  </div>
            </td>
            <td style="vertical-align: middle; text-align: right;">
                <h3>Order #<?=$getOrderDetail->order_no?>
                </h3>
                <p>
                    <?=$getOrderDetail->b_fname.' '.$getOrderDetail->b_lname?> (<?=$getOrderDetail->b_email?>)<br>
                    <?=$getOrderDetail->b_phone?>
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
                <b>Bill to
                </b>
                <p>
                    <?=$getOrderDetail->b_fname.' '.$getOrderDetail->b_lname?>
                    <?=$getOrderDetail->b_street?>, <?=$getOrderDetail->b_suburb?>
                    <?=$getOrderDetail->b_state?> <?=$getOrderDetail->b_postcode?>
                    <?=$getOrderDetail->b_country?><br>
                  	<?=$getOrderDetail->b_phone?><br>
                  	<?=$getOrderDetail->b_email?>
                </p>
                <b>Ship to
                </b>
                <p>
                  	<?=$getOrderDetail->s_fname.' '.$getOrderDetail->s_lname?>
                    <?=$getOrderDetail->s_street?>, <?=$getOrderDetail->s_suburb?>
                    <?=$getOrderDetail->s_state?> <?=$getOrderDetail->s_postcode?>
                    <?=$getOrderDetail->s_country?><br>
                  	<?=$getOrderDetail->s_phone?><br>
                  	<?=$getOrderDetail->s_email?>
                </p>
                <!--<div class="margin"></div>
                <b>Scheduled to ship by
                </b>
                <p>Jan 30, 2025</p>-->
                <div class="margin"></div>
                	<b>Shop</b>
                	<p><?=$generalSetting->site_name?></p>
                <div class="margin"></div>
                	<b>Order date</b>
                	<p><?=date_format(date_create($getOrderDetail->order_date), "M d, Y")?> <?=date_format(date_create($getOrderDetail->order_time), "h:i A")?></p>
                <div class="margin"></div>
                	<b>Payment method</b>
                	<p>Paid via <?=$getOrderDetail->payment_mode?></p>
                <!--<div class="margin"></div>
                <b>Shipping method</b>
                	<p>USPS Parcel Select Ground</p>-->
                <!--<div class="margin"></div>
                	<b>Packaging</b>
                	<p>Package/Thick Envelope (15 x 12 x 2 in, 1lb)</p>-->
              	<?php if($getOrderDetail->tracking_number != ''){?>
                  <div class="margin"></div>
                  <b>Tracking</b>
                  <p><?=$getOrderDetail->tracking_number?> via USPS</p>
              	<?php } ?>
            </td>
            <td style="width: 75%;">
                <table style="width: 100%;" class="cart-table">
                    <tr class="table-head">
                        <td style="width: 12%;">Image</td>
                        <td style="width: 44%;">Product</td>
                        <td style="width: 18%;">Size / Color</td>
                        <td style="width: 12%;">SKU</td>
                        <td style="width: 14%; text-align: right;">Amount</td>
                    </tr>
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
                        $sku = (($variationInfo && $variationInfo->sku != '') ? $variationInfo->sku : (($getProduct && $getProduct->product_sku != '') ? $getProduct->product_sku : 'N/A'));
                        $sizeColor = 'N/A';
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
                        if($sizeColor == 'N/A' && $orderDetail->variation_name != ''){
                            $sizeColor = $orderDetail->variation_name;
                        }
                    ?>
                      <tr>
                          <td style="width:12%">
                            <?php
                            $url = env('UPLOADS_URL').'product/'.(($getProduct)?$getProduct->cover_image:'');
                            $headers = @get_headers($url);
                            if ($headers && strpos($headers[0], '200')) {
                                $imageLink        = rawurlencode(env('UPLOADS_URL').'product/'.(($getProduct)?$getProduct->cover_image:''));
                                $resizeImageLink  = 'https://res.cloudinary.com/ddv59fl2y/image/fetch/w_300/' . $imageLink;
                                $imageData        = file_get_contents($resizeImageLink);
                                $generatedImage   = 'data:image/png;base64,' . base64_encode($imageData);
                                echo $html        = '<img src="' . $generatedImage . '" style="height:auto !important; width: 50px !important;" class="img-fluid" alt="<?=$getProduct->name?>" />';
                            } else {
                            ?>
                                <img src="<?=env('UPLOADS_URL').'product/'.(($getProduct)?$getProduct->cover_image:'')?>" style="height:auto !important; width: 50px !important;" class="img-fluid" alt="<?=$getProduct->name?>">
                            <?php } ?>
                          </td>
                          <td style="width:44%">
                              <small><?=(($getProduct)?$getProduct->name:'')?></small><br>
                              <small><?=(($getProduct)?$getProduct->variation_name:'')?></small><br>  
                              <small><?=(($getProduct)?$getProduct->product_sku:'')?></small>
                          </td>
                          <td style="width:18%">
                              <p class="muted-red"><?=$sizeColor?></p>
                          </td>
                          <td style="width:12%">
                              <p class="muted-red"><?=$sku?></p>
                          </td>
                          <td style="width:14%; text-align: right;">
                              <?=$orderDetail->qty?> x $<?=number_format($orderDetail->rate,2)?>
                          </td>
                      </tr>
                    <?php } }?>
                    <tr>
                        <td colspan="3" class="border-none text-right"></td>
                        <td class="border-none text-right">
                            Item total 
                        </td>
                        <td class="border-none text-right">
                            $<?=number_format($subtotal,2)?>
                        </td>
                    </tr>
                   	<tr>
                        <td colspan="3" class="border-none text-right"></td>
                        <td class="border-none text-right">Discount</td>
                        <td class="border-none text-right">
                            $<?=number_format($getOrderDetail->disc_amount,2)?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border-none text-right"></td>
                        <td class="border-none text-right">Shipping total</td>
                        <td class="border-none text-right">
                            $<?=number_format($getOrderDetail->shipping_amt,2)?>
                        </td>
                    </tr>
                   	<tr>
                        <td colspan="3" class="border-none text-right"></td>
                        <td class="border-none text-right">Tax</td>
                        <td class="border-none text-right">
                            $<?=number_format($getOrderDetail->tax_amt,2)?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border-none text-right"></td>
                        <td class="border-none text-right"><strong>Order total</strong></td>
                        <td class="border-none text-right">
                           <strong> $<?=number_format($getOrderDetail->net_amt,2)?></strong>
                        </td>
                    </tr>
                   
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="footer">
                    <div class="footer-info">
                      <span><?=$generalSetting->site_mail?></span> |
                      <span><?=$generalSetting->site_phone?></span> |
                      <span><?=$generalSetting->site_url?></span>
                    </div>
                    <div class="footer-thanks">
                      <img src="data:image/svg+xml;base64,<?php echo base64_encode(file_get_contents('https://github.com/anvilco/html-pdf-invoice-template/raw/main/img/heart.png')); ?>" alt="heart">
                      <span>Thank you!</span>
                    </div>
                  </div>
            </td>
        </tr>
    </table>
</body>
</html>
