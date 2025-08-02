<!DOCTYPE html>
<html lang="en">
  <head>
    <?=$head?>
  </head>
  <body>
    <!-- header start -->
    <div class="header">
      <?=$header?>
    </div>
    <!-- header end -->
    <?=$maincontent?>
    <!-- ============footer_start========== -->
    <div class="footer-sec" id="Footer">
      <?=$footer?>
    </div>
    <!--===== footer-end ======-->
    <!-- js link     -->
    <script src="<?=env('FRONT_ASSETS_URL')?>js/jquery.min.js"></script>
    <script src="<?=env('FRONT_ASSETS_URL')?>js/bootstrap.bundle.min.js"></script>
    <script src="<?=env('FRONT_ASSETS_URL')?>js/slick.min.js"></script>
    <script src="<?=env('FRONT_ASSETS_URL')?>js/main.js"></script>
  </body>
</html>