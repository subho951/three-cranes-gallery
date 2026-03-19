<?php
use App\Models\Category;
use App\Models\Product;
use App\Models\UserReview;
use App\Helpers\Helper;
?>
<style>
   .active>.page-link, .page-link.active {
       background-color: #f9bb23;
       border-color: #f9bb23;
   }
   .page-link {
       color: #000306;
   }
</style>
<?php
$current_url = url()->full();
?>
<section class="category_section">
    <div class="container">
        <h3 class="mb-3"><?=$page_header?></h3>
        <div class="row">
            <div class="col-md-4 col-lg-3 mb-3 mb-md-0">
                <div class="left-sidebar">
                    <h3>
                        <img src="<?=env('FRONT_ASSETS_URL')?>images/grid_icon.png" alt="icon"> All Sub Categories
                        <a href="<?= $current_url ?>"><button>Clear</button></a>
                    </h3>
                    @if(session('success_message'))
                        <h6 class="alert alert-success autohide mt-3">{{ session('success_message') }}</h6>
                    @endif
                    @if(session('error_message'))
                        <h6 class="alert alert-danger autohide mt-3">{{ session('error_message') }}</h6>
                    @endif
                    <div class="accordion">
                        <form action="" method="POST">
                            @csrf
                            <ul>
                                <?php if($subcategory){ foreach($subcategory as $subcat){?>
                                    <li>
                                        <button>
                                            <?=$subcat->category_name?>
                                            <input type="checkbox" name="subcat[]" value="<?=$subcat->id?>" id="<?=$subcat->id?>" class="ms-auto" <?= ((in_array($subcat->id, $filter_subcat))?'checked':'') ?>>
                                        </button>
                                    </li>
                                <?php } }?>
                            </ul>
                            <?php if(count($subcategory) > 0){?>
                                <button type="submit" class="btn common-btn" style="display: flex;margin: 0 auto;">Filter</button>
                            <?php } ?>
                        </form>
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
                                            <a href="<?=url('/product/' . $product->slug . '/' . Helper::encoded($product->id))?>">
                                                <?php
                                                $url = env('UPLOADS_URL').'product/'.$product->cover_image;
                                                $headers = @get_headers($url);
                                                if ($headers && strpos($headers[0], '200')) {
                                                    $imageLink        = rawurlencode(env('UPLOADS_URL').'product/'.$product->cover_image);
                                                    $resizeImageLink  = 'https://res.cloudinary.com/ddv59fl2y/image/fetch/w_300/' . $imageLink;
                                                    $imageData        = file_get_contents($resizeImageLink);
                                                    $generatedImage   = 'data:image/png;base64,' . base64_encode($imageData);
                                                    echo $html        = '<img src="' . $generatedImage . '" class="img-fluid" alt="<?=$product->name?>" />';
                                                } else {
                                                ?>
                                                    <img src="<?=env('UPLOADS_URL').'product/'.$product->cover_image?>" class="img-fluid" alt="<?=$product->name?>">
                                                <?php } ?>
                                                <!-- <img src="<?=env('UPLOADS_URL').'/product/' . $product->cover_image?>" class="img-fluid" alt="<?=$product->name?>"> -->
                                            </a>
                                        </div>
                                        <div class="add-callection">
                                            <a href="<?=url('/product/' . $product->slug . '/' . Helper::encoded($product->id))?>">Add to cart</a>
                                        </div>
                                        <div class="whist_icon">
                                            <a href="<?=url('/product/' . $product->slug . '/' . Helper::encoded($product->id))?>">
                                                <img src="<?=env('FRONT_ASSETS_URL')?>images/heart_icon.png" alt="" class="heart_icon">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-info">
                                        <a href="<?=url('/product/' . $product->slug . '/' . Helper::encoded($product->id))?>"><?=$product->name?></a>
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

                        {{-- Bootstrap styled pagination --}}
                        <div class="d-flex justify-content-center mt-3">
                            {{ $products->links() }}
                        </div>

                        {{-- Show page info --}}
                        <p class="text-muted text-center mt-2 fw-bold">
                            Page {{ $products->currentPage() }} of {{ $products->lastPage() }}  
                            (Total records: {{ $products->total() }})
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>