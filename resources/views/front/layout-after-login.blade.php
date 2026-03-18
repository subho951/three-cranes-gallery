<!DOCTYPE html>
<html lang="en">
    <head>
        <?=$head?>
    </head>
    <body>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframesrc="https://www.googletagmanager.com/ns.html?id=GTM-WMNF55CW"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        
        <!-- header start -->
        <div class="header">
            <?=$header?>
        </div>
        <!-- header end -->
        <section class="product-category-listing my-order-list section-padding">
            <div class="container-xxl container-xl container-lg container-md container-sm container">
                <div class="row ">
                    <div class="col-xl-3 col-lg-3 col-md-5 col-sm-12 ">
                        <?=$sidebar?>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-7 col-sm-12 ">
                        <?=$maincontent?>
                    </div>
                </div>
            </div>
        </section>
        <!-- ============footer_start========== -->
        <div class="footer-sec" id="Footer">
            <?=$footer?>s
        </div>
        <!--===== footer-end ======-->
        <!--- model-start ----->
        <!-- Modal -->
        <div class="modal fade" id="logout" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title h4">Signout</div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5 class="mb-4">Are you sure you want to log out?</h5>
                        <button class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                        <a href="<?=url('signout')?>"><button class="btn btn-primary ms-2">Confirm</button></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- js link     -->
        <script src="<?=env('FRONT_ASSETS_URL')?>js/jquery.min.js"></script>
        <script src="<?=env('FRONT_ASSETS_URL')?>js/bootstrap.bundle.min.js"></script>
        <script src="<?=env('FRONT_ASSETS_URL')?>js/slick.min.js"></script>
        <script src="<?=env('FRONT_ASSETS_URL')?>js/main.js"></script>
        <script>
            $(document).ready(function() {
                $(".logout").click(function() {
                    $('.logout-popup').addClass('primary-text');
                });
                $(".hide").click(function() {
                    $('.logout-popup').removeClass('primary-text');
                });
            });
        </script>
    </body>
</html>