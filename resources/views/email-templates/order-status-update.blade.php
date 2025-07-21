<?php
use App\Models\GeneralSetting;
$generalSetting             = GeneralSetting::find('1');
?>
<!doctype html>
<html lang="en">
  <head>
    <title><?=$generalSetting->site_name?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  </head>
  <body style="padding: 0; margin: 0; box-sizing: border-box;">
    <section style="padding: 80px 0; margin: 0 15px;">
        <div style="max-width: 600px; background: #ffffff; margin: 0 auto; border-radius: 15px; padding: 20px 15px; box-shadow: 0 0 30px -5px #ccc;">
          <div style="text-align: center;">
              <img src="<?=env('UPLOADS_URL').$generalSetting->site_logo?>" alt="" style=" width: 100%; max-width: 250px;">
          </div>
          <div>
            <h3 style="text-align: center; font-size: 25px; color: #5c5b5b; font-family: sans-serif;">Hi, Welcome to <?=$generalSetting->site_name?>!</h3>
            <h5 style="text-align: center; font-size: 15px; color: green; font-family: sans-serif;"><?=$mailHeader?></h5>
            <table style="width: 100%;  border-spacing: 2px;">
              <tbody>
                <tr>
                  <th style="background: #ccc; color: #000; padding: 10px; text-align: left; font-family: sans-serif; font-size: 14px;">Billing Info</th>
                  <td style="padding: 10px; background: #ccc; text-align: left; color: #000;font-family: sans-serif;font-size: 15px;">
                    <span style="font-size: 12px;"><?=$getOrder->b_fname.' '.$getOrder->b_lname?></span><br>
                    <span style="font-size: 12px;"><?=$getOrder->b_email?></span><br>
                    <span style="font-size: 12px;"><?=$getOrder->b_phone?></span><br>
                    <span style="font-size: 12px;"><?=$getOrder->b_street?> <?=$getOrder->b_suburb?> <?=$getOrder->b_state?> <?=$getOrder->b_postcode?> <?=$getOrder->b_country?></span>
                  </td>
                </tr>
                <tr>
                  <th style="background: #ccc; color: #000; padding: 10px; text-align: left; font-family: sans-serif; font-size: 14px;">Delivery Info</th>
                  <td style="padding: 10px; background: #ccc; text-align: left; color: #000;font-family: sans-serif;font-size: 15px;">
                    <span style="font-size: 12px;"><?=$getOrder->s_fname.' '.$getOrder->s_lname?></span><br>
                    <span style="font-size: 12px;"><?=$getOrder->s_email?></span><br>
                    <span style="font-size: 12px;"><?=$getOrder->s_phone?></span><br>
                    <span style="font-size: 12px;"><?=$getOrder->s_street?> <?=$getOrder->s_suburb?> <?=$getOrder->s_state?> <?=$getOrder->s_postcode?> <?=$getOrder->s_country?></span>
                  </td>
                </tr>
                <tr>
                  <th style="background: #ccc; color: #000; padding: 10px; text-align: left; font-family: sans-serif; font-size: 14px;">Order No.</th>
                  <td style="padding: 10px; background: #ccc; text-align: left; color: #000;font-family: sans-serif;font-size: 15px;"><?=$getOrder->order_no?></td>
                </tr>
                <tr>
                  <th style="background: #ccc; color: #000; padding: 10px; text-align: left; font-family: sans-serif; font-size: 14px;">Order Date/Time</th>
                  <td style="padding: 10px; background: #ccc; text-align: left; color: #000;font-family: sans-serif;font-size: 15px;"><?=date_format(date_create($getOrder->order_date), "M d, Y")?> <?=date_format(date_create($getOrder->order_time), "h:i A")?></td>
                </tr>
                <tr>
                  <th style="background: #ccc; color: #000; padding: 10px; text-align: left; font-family: sans-serif; font-size: 14px;">Order Amount</th>
                  <td style="padding: 10px; background: #ccc; text-align: left; color: #000;font-family: sans-serif;font-size: 15px;"><?=$getOrder->net_amt?></td>
                </tr>
                <tr>
                  <th style="background: #ccc; color: #000; padding: 10px; text-align: left; font-family: sans-serif; font-size: 14px;">Payment Info</th>
                  <td style="padding: 10px; background: #ccc; text-align: left; color: #000;font-family: sans-serif;font-size: 15px;">
                    <span style="font-size: 12px;"><?=$getOrder->payment_mode?></span><br>
                    <span style="font-size: 12px;color:green;">SUCCESS</span><br>
                    <span style="font-size: 12px;"><?=date_format(date_create($getOrder->payment_date_time), "M d, Y h:i A")?></span><br>
                    <span style="font-size: 12px;"><?=$getOrder->payment_txn_no?></span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div style="border-top: 2px solid #ccc; margin-top: 50px; text-align: center; font-family: sans-serif;">
          <div style="text-align: center; margin: 15px 0 10px;">All right reserved: © <?=date('Y')?> <?=$generalSetting->site_name?></div>
        </div>
      </div>
    </section>
  </body>
</html>