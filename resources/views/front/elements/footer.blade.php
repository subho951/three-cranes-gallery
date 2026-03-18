<div class="container">
  <div class="row">
    <div class="col-lg-6">
      <div class="fotter-content">
        <h3>Join our club, Get 15% off for your Birthday</h3>
        <div class="email-box ">
          <div class="email-area">
            <form action="" id="subscribeForm">
              <input type="text" class="form-control" placeholder="Enter Your Email Address" id="subscribe_email">
              <button type="submit"><img src="<?= env('FRONT_ASSETS_URL') ?>images/arrow_forward.png" alt="logo"></button>
            </form>
          </div>
        </div>
        <!-- <div class="submit-p">
          <form action="">
            <input type="text" name="email" id="email">
          </form>
          <p>By Submittng your email, you agree to receive advertising emails from Modimal.</p>
        </div> -->
        <div class="footer-contact-hold">
          <div class="footer-contact ">
            <span><img src="<?= env('FRONT_ASSETS_URL') ?>images/Group 132136.png" alt="call"></span>
            <div class="footer-contact-right">
              <label><?= $generalSetting->timing ?></label>
              <b><a href="tel:<?= $generalSetting->site_phone ?>"><?= $generalSetting->site_phone ?></a></b>
            </div>
          </div>
          <div class="footer-contact email-info ">
            <span><img src="<?= env('FRONT_ASSETS_URL') ?>images/Vector.png" alt="call"></span>
            <div class="footer-contact-right">
              <label>Need help with your order?</label>
              <b><a href="mailTo:<?= $generalSetting->site_mail ?>"><?= $generalSetting->site_mail ?></a></b>
            </div>
          </div>
        </div>
        <div class="social-media-box">
          <ul class="social-footer">
            <li><a target="_blank" href="<?= $generalSetting->instagram_profile ?>">
                <img src="<?= env('FRONT_ASSETS_URL') ?>images/Group.png" alt="icon"></a>
            </li>
            <li><a target="_blank" href="<?= $generalSetting->facebook_profile ?>">
                <img src="<?= env('FRONT_ASSETS_URL') ?>images/Social media (2).png" alt="icon"></a>
            </li>
            <li><a target="_blank" href="<?= $generalSetting->twitter_profile ?>">
                <img src="<?= env('FRONT_ASSETS_URL') ?>images/Vector (2).png" alt="icon"></a>
            </li>
            <li><a target="_blank" href="<?= $generalSetting->linkedin_profile ?>">
                <img src="<?= env('FRONT_ASSETS_URL') ?>images/Group (1).png" alt="icon"></a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="row ">
        <div class="col-lg-4 col-sm-4">
          <div class="footer-widget">
            <h3>Store</h3>
            <ul>
              <?php
              $footer_link_name = json_decode($generalSetting->footer_link_name);
              $footer_link      = json_decode($generalSetting->footer_link);
              if (!empty($footer_link_name)) {
                for ($k = 0; $k < count($footer_link_name); $k++) {
              ?>
                  <li><a href="<?= url('/') . '/' . $footer_link[$k] ?>" target="_blank"><?= $footer_link_name[$k] ?></a></li>
              <?php }
              } ?>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-sm-4">
          <div class="footer-widget">
            <h3>About</h3>
            <ul>
              <?php
              $footer_link_name2 = json_decode($generalSetting->footer_link_name2);
              $footer_link2      = json_decode($generalSetting->footer_link2);
              if (!empty($footer_link_name2)) {
                for ($k = 0; $k < count($footer_link_name2); $k++) {
              ?>
                  <li><a href="<?= url('/') . '/' . $footer_link2[$k] ?>" target="_blank"><?= $footer_link_name2[$k] ?></a></li>
              <?php }
              } ?>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-sm-4">
          <div class="footer-widget">
            <h3>Help & Support</h3>
            <ul>
              <?php
              $footer_link_name3 = json_decode($generalSetting->footer_link_name3);
              $footer_link3      = json_decode($generalSetting->footer_link3);
              if (!empty($footer_link_name3)) {
                for ($k = 0; $k < count($footer_link_name3); $k++) {
              ?>
                  <li><a href="<?= url('/') . '/' . $footer_link3[$k] ?>" target="_blank"><?= $footer_link_name3[$k] ?></a></li>
              <?php }
              } ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="copy-right">
        <div class="submit-p">
          <p class="d-flex align-items-center gap-2 p-0 mt-1 mt-md-3"> <?= $generalSetting->footer_text ?></p>
        </div>
      </div>
    </div>
  </div>
</div>