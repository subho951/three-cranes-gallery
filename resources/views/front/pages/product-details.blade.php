<?php
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\UserReview;
?>
<?php if($product){?>
    <?php
    $main_category  = $product->main_category;
    $mainCat        = Category::select('id', 'category_name', 'slug')->where('id', '=', $main_category)->where('status', '=', 1)->first();

    $sub_category   = $product->sub_category;
    $subCat         = Category::select('id', 'category_name', 'slug')->where('id', '=', $sub_category)->where('status', '=', 1)->first();
    ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrames mb-3 mb-md-4">
                    <ul>
                        <li><a href="<?=url('/')?>">Home </a></li>
                        <?php if(!empty($mainCat)){?>
                            <li><img src="<?=env('FRONT_ASSETS_URL')?>images/right_arrow.png" alt="icon"></li>
                            <li><a href="<?=url('products/' . $mainCat->slug)?>"><?=$mainCat->category_name?></a></li>
                        <?php } ?>
                        <?php if(!empty($mainCat) && !empty($subCat)){?>
                            <li><img src="<?=env('FRONT_ASSETS_URL')?>images/right_arrow.png" alt="icon"></li>
                            <li><a href="<?=url('products/' . $mainCat->slug . '/' . $subCat->slug)?>"><?=$subCat->category_name?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <section class="product_dtl">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="gallery-container">
                        <!-- Main Vertical Slider -->
                        <div class="swiper swiper-main">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><img src="<?=env('UPLOADS_URL').'/product/' . $product->cover_image?>" alt="<?=$product->name?>"></div>
                                <?php
                                $product_other_images = ProductImage::select('image')->where('status', '=', 1)->where('is_cover_image', '=', 0)->where('product_id', '=', $product->id)->get();
                                if($product_other_images){ foreach($product_other_images as $product_other_image){
                                ?>
                                    <div class="swiper-slide"><img src="<?=env('UPLOADS_URL').'/product/' . $product_other_image->image?>" alt="<?=$product->name?>"></div>
                                <?php } }?>
                            </div>

                            <!-- Navigation -->
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>

                        <!-- Thumbnail Horizontal Slider -->
                        <div class="swiper swiper-thumbs">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><img src="<?=env('UPLOADS_URL').'/product/' . $product->cover_image?>" alt="<?=$product->name?>"></div>
                                <?php
                                if($product_other_images){ foreach($product_other_images as $product_other_image){
                                ?>
                                <div class="swiper-slide"><img src="<?=env('UPLOADS_URL').'/product/' . $product_other_image->image?>" alt="<?=$product->name?>"></div>
                                <?php } }?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="product-details">
                        <h2 class="mt-3 mt-lg-0"><?=$product->name?></h2>
                        <ul class="rating-list mt-3 mt-lg-0">
                            <li>
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
                            </li>
                            <li><span><?=$avgRating?></span></li>
                            <li><label><?=$reviewCount?> <button>(Reviews)</button></label></li>
                            <li><label>SKU:</label><b> <?=$product->product_sku?></b></li>
                        </ul>
                        <div>
                            <?=$product->short_description?>
                        </div>
                        <h5>$<!-- --><?=number_format($product->discounted_price,2)?> <span>$ <?=number_format($product->markup_price,2)?></span></h5>
                        <ul class="product-varient mt-2">
                            <li><label> Select Size</label><select class="form-control">
                                    <option>M</option>
                                    <option value="128">L Unisex Adults</option>
                                    <option value="127">M Unisex Adults</option>
                                    <option value="126">S Unisex Adults</option>
                                    <option value="129">XL Unisex Adults</option>
                                    <option value="130">XXL Unisex Adults</option>
                                </select>
                            </li>
                        </ul>
                        <ul class="quantity-add">
                            <li>
                                <div class="quantity-box">
                                    <button disabled="">
                                        <img src="<?=env('FRONT_ASSETS_URL')?>images/minus_icon.png">
                                    </button>
                                    <input type="text" placeholder="QTY" value="1">
                                    <button>
                                        <img src="<?=env('FRONT_ASSETS_URL')?>images/plus_icon.png">
                                    </button>
                                </div>
                            </li>
                            <li>
                                <button class="addtocartBtn">
                                    <img src="<?=env('FRONT_ASSETS_URL')?>images/buy_icon.png">
                                    Add to cart
                                </button>
                            </li>
                        </ul>
                        <ul class="wishlist-sec">
                            <li>
                                <button>
                                    <img src="<?=env('FRONT_ASSETS_URL')?>images/heart_icon.png">
                                    Add to wishlist
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="pro-tab-details mt-5">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                    aria-selected="true">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Reviews (<?=$reviewCount?>)</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                    type="button" role="tab" aria-controls="contact"
                                    aria-selected="false">Specification</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="dsc_box">
                                    <?=$product->long_description?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <h5 class="mt-3">Reviews</h5>
                                <?php
                                $reviews            = UserReview::where('product_id', '=', $product->id)->where('status', '=', 1)->orderBy('id', 'DESC')->get();
                                if($reviews){ foreach($reviews as $review){
                                ?>
                                    <div class="reviewList">
                                        <div class="user-i"><img src="<?=env('FRONT_ASSETS_URL')?>images/testimonial-img.png">
                                            <div><b><?=$review->name?></b><span><?=$review->email?></span></div>
                                        </div>
                                        <div class="user-r">
                                            <h5><?=$review->title?></h5>
                                            <p><?=$review->comment?></p>
                                        </div>
                                        <ul>
                                            <?php if($review->rating > 0 && $review->rating <= 1){?>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                                            <?php } elseif($review->rating > 1 && $review->rating <= 2){?>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                                            <?php } elseif($review->rating > 2 && $review->rating <= 3){?>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                                            <?php } elseif($review->rating > 3 && $review->rating <= 4){?>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/star_default.png"></li>
                                            <?php } elseif($review->rating > 4 && $review->rating <= 5){?>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                                <li><img src="<?=env('FRONT_ASSETS_URL')?>images/start_fill.png"></li>
                                            <?php }?>
                                        </ul>
                                    </div>
                                <?php } }?>
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="productattr">
                                    <h5 class="mt-3">Main Category</h5>
                                    <ol>
                                        <li><?=$mainCat->category_name?></li>
                                    </ol>
                                </div>
                                <div class="productattr">
                                    <h5>Sub Category</h5>
                                    <ol>
                                        <li><?=$subCat->category_name?></li>
                                    </ol>
                                </div>
                                <div class="productattr">
                                    <h5>Product SKU</h5>
                                    <ol>
                                        <li><?=$product->product_sku?></li>
                                    </ol>
                                </div>
                                <div class="productattr">
                                    <h5>Tags</h5>
                                    <?php $tags = explode(",", $product->tags); ?>
                                    <ul>
                                        <?php if(!empty($tags)){ for($k=0;$k<count($tags);$k++){?>
                                            <li><?=$tags[$k]?></li>
                                        <?php } }?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <section class="new-callection-start">
                    <!-- arivel product section start -->
                    <div class="product-sec">
                        <div class="pageHeading">
                            <h2>Related Products </sub></h2>
                        </div>
                        <div class="prduct_slider_box">
                            <div class="product-slider">
                                <?php
                                if($similar_products){ foreach($similar_products as $product){
                                    $reviewCount            = UserReview::where('product_id', '=', $product['id'])->where('status', '=', 1)->count();
                                    $reviewSum              = UserReview::where('product_id', '=', $product['id'])->where('status', '=', 1)->sum('rating');
                                    $avgRating              = (($reviewCount > 0)?($reviewSum / $reviewCount):0);
                                ?>
                                    <div class="product-box">
                                        <div class="product-img">
                                            <div class="product-img-box">
                                                <a href="<?=url('/product/' . $product['slug'])?>">
                                                    <img src="<?=env('UPLOADS_URL').'/product/' . $product['cover_image']?>" class="img-fluid" alt="<?=$product['name']?>">
                                                </a>
                                            </div>
                                            <div class="add-callection">
                                                <a href="<?=url('/product/' . $product['slug'])?>">Add to cart</a>
                                            </div>
                                            <div class="whist_icon">
                                                <a href="<?=url('/product/' . $product['slug'])?>">
                                                    <img src="<?=env('FRONT_ASSETS_URL')?>images/heart_icon.png" alt="" class="heart_icon">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-info">
                                            <a href="<?=url('/product/' . $product['slug'])?>"><?=$product['name']?></a>
                                        </div>
                                        <div class="product-info-t">
                                            <h5>$ <?=number_format($product['discounted_price'],2)?>+</h5>
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
            </div>
        </div>
    </section>
<?php }?>