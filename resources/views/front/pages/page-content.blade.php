<section class="single-page-banner-section" style="background-image: url('<?= env('FRONT_ASSETS_URL') ?>/images/slideshow 7.jpeg')">
    <div class="background-overlay"></div>
    <div class=" container-xxl container-xl container-lg container-md container-sm container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12  col-12 order-md-1">
                <div class="single-page-banner-description custom-breadcrumb ">
                    <h1 class="text-center"><?= $page_header ?></h1>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="product-details section-padding">
    <div class="container-xxl container-xl container-lg container-md container-sm container">
        <div class="row ">
            <!-- <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 ">
            <div class="left-images-about">
               <div class="inner">
                  <img src="https://noble-kids.itiffyconsultants.com/public/uploads/home_page/" class="img1 wow fadeInDown" data-wow-delay="0.7s" data-wow-duration="0.7s" style="visibility: visible; animation-duration: 0.7s; animation-delay: 0.7s; animation-name: fadeInDown;" alt="">
               </div>
            </div>
         </div> -->
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 wow slideInRight" data-wow-delay="0.7s" data-wow-duration="0.7s" style="visibility: visible; animation-duration: 0.7s; animation-delay: 0.7s; animation-name: slideInRight;">
                <!-- <div class="digi-block-right"> -->
                <!-- <h3><?= $page_header ?></h3> -->
                <p> <?= (($page) ? $page->long_description : '') ?></p>
                <!-- </div> -->
            </div>
        </div>
    </div>
</section>