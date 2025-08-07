<?php
use App\Models\Category;
use App\Models\Product;
use App\Models\UserReview;
?>
<section class="category_section">
    <div class="container">
        <h3 class="mb-3"><?=$page_header?></h3>
        <div class="row">
            <div class="col-md-4 col-lg-3 mb-3 mb-md-0">
                <div class="left-sidebar">
                    <h3><img src="<?=env('FRONT_ASSETS_URL')?>images/grid_icon.png" alt="icon"> All Sub Categories
                        <button>Clear</button>
                    </h3>
                    <div class="accordion">
                        <ul>
                            <?php if($subcategory){ foreach($subcategory as $subcat){?>
                                <li>
                                    <button>
                                        <?=$subcat->category_name?>
                                        <input type="checkbox" name="subcat[]" id="<?=$subcat->id?>" class="ms-auto">
                                    </button>
                                </li>
                            <?php } }?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-9">
                <div class="category_product_box">
                    <div class="row">
                        <?php
                        if($products){ foreach($products as $product){
                            $reviewCount            = UserReview::where('product_id', '=', $product->id)->where('status', '=', 1)->count();
                            $reviewSum              = UserReview::where('product_id', '=', $product->id)->where('status', '=', 1)->sum('rating');
                            $avgRating              = (($reviewCount > 0)?($reviewSum / $reviewCount):0);
                        ?>
                            <div class="col-md-6 col-lg-4">
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
                            </div>
                        <?php } }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>