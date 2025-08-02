<?php
use App\Models\UserReview;
?>
<!-- banner-slide-start -->
<section class="banner-section_start">
  <div id="slider">
    <?php if($banners1){ foreach($banners1 as $banner1){?>
      <div class="slide">
        <img src="<?=env('UPLOADS_URL').'/banner/' . $banner1->banner_image?>" alt="<?=$banner1->banner_image?>">
      </div>
    <?php } }?>
  </div>
</section>
<!-- banner-slide-end -->
<!-- secend-slider-start -->
<section class="secend-slider-start">
  <div class="secent_slider_box">
    <div id="slider2">
      <?php if($banners2){ foreach($banners2 as $banner2){?>
        <div class="slide">
          <img src="<?=env('UPLOADS_URL').'/banner/' . $banner2->banner_image?>" alt="<?=$banner2->banner_image?>">
        </div>
      <?php } }?>
    </div>
  </div>
</section>
<!-- secend-slider-end -->
<!-- summer-section-start -->
<section class="summer-section-start">
  <div class="container">
    <div class="row">
      <?php if($sections2){ foreach($sections2 as $section2){?>
        <div class="col-lg-4 col-md-4">
          <div class="summer-img">
            <img src="<?=env('UPLOADS_URL').'/home_page/' . $section2->icon?>" alt="<?=$section2->name?>" class="img-fluid">
            <p><?=$section2->name?></p>
          </div>
        </div>
      <?php } }?>
    </div>
  </div>
</section>
<!-- summer-section-end -->
<!-- new-callection-start -->
<section class="new-callection-start">
  <!-- arivel product section start -->
  <div class="product-sec">
    <div class="pageHeading">
      <h2><?=(($home_page)?$home_page->sec4_title:'')?></h2>
    </div>
    <div class="prduct_slider_box">
      <div class="product-slider">
        <?php
        if($products){ foreach($products as $product){
        ?>
          <div class="product-box">
            <div class="product-img">
              <div class="product-img-box">
                <a href="<?=url('/product/' . $product->slug)?>">
                  <img src="<?=env('UPLOADS_URL').'/product/' . $product->cover_image?>" class="img-fluid" alt="<?=$product->name?>">
                </a>
              </div>
              <div class="add-callection">
                <a href="<?=url('/product/' . $product->slug)?>">Add to cart</a>
              </div>
              <div class="whist_icon">
                <a href="<?=url('/product/' . $product->slug)?>">
                  <img src="<?=env('FRONT_ASSETS_URL')?>images/heart_icon.png" alt="" class="heart_icon">
                </a>
              </div>
            </div>
            <div class="product-info">
              <a href="<?=url('/product/' . $product->slug)?>"><?=$product->name?></a>
            </div>
            <div class="product-info-t">
              <h5>$ <?=number_format($product->discounted_price,2)?>+</h5>
              <?php
              $reviewCount            = UserReview::where('product_id', '=', $product->id)->where('status', '=', 1)->count();
              $reviewSum              = UserReview::where('product_id', '=', $product->id)->where('status', '=', 1)->sum('rating');
              $avgRating              = (($reviewCount > 0)?($reviewSum / $reviewCount):0);
              ?>
              <ul>
                <?php if($avgRating > 0 && $avgRating <= 1){?>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                <?php } elseif($avgRating > 1 && $avgRating <= 2){?>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                <?php } elseif($avgRating > 2 && $avgRating <= 3){?>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                <?php } elseif($avgRating > 3 && $avgRating <= 4){?>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                <?php } elseif($avgRating > 4 && $avgRating <= 5){?>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                  <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                <?php }?>
              </ul>
            </div>
          </div>
        <?php } }?>
      </div>
    </div>
  </div>
</section>
<section class="classic_section">
  <?php if($sections5){?>
    <div class="classic_row">
      <?php if($sections5[0]->size == 'BIG'){?>
        <div class="classic_left_box">
          <img src="<?=env('UPLOADS_URL').'/home_page/' . $sections5[0]->icon?>" alt="<?=$sections5[0]->name?>" class="img-fluid">
          <div class="classic_info_box">
            <h4><?=$sections5[0]->name?></h4>
            <a href="<?=$sections5[0]->section2_link?>">Shop Now</a>    
          </div>
        </div>
      <?php }?>

      <div class="classic_mid_box">
        <?php if($sections5[2]->size == 'SMALL'){?>
          <div class="classic_mid_top">
            <img src="<?=env('UPLOADS_URL').'/home_page/' . $sections5[2]->icon?>" alt="<?=$sections5[2]->name?>" class="img-fluid">
            <div class="classic_info_box">
              <h4><?=$sections5[2]->name?></h4>
              <a href="<?=$sections5[2]->section2_link?>">Shop Now</a>    
            </div>
          </div>
        <?php }?>
        <?php if($sections5[3]->size == 'SMALL'){?>
          <div class="classic_mid_bottom">
            <img src="<?=env('UPLOADS_URL').'/home_page/' . $sections5[3]->icon?>" alt="<?=$sections5[3]->name?>" class="img-fluid">
            <div class="classic_info_box">
              <h4><?=$sections5[3]->name?></h4>
              <a href="<?=$sections5[3]->section2_link?>">Shop Now</a>    
            </div>
          </div>
        <?php }?>
      </div>

      <?php if($sections5[1]->size == 'BIG'){?>
        <div class="classic_right_box">
          <img src="<?=env('UPLOADS_URL').'/home_page/' . $sections5[1]->icon?>" alt="<?=$sections5[1]->name?>" class="img-fluid">
          <div class="classic_info_box">
            <h4><?=$sections5[1]->name?></h4>
            <a href="<?=$sections5[1]->section2_link?>">Shop Now</a>    
          </div>
        </div>
      <?php }?>
    </div>
  <?php }?>
</section>