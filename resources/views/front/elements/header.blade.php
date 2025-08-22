<?php
use App\Models\Category;
use App\Models\Product;
?>
<div class="top-header">
    <div class="container">
        <div class="row">
            <div class="col">
                <div>
                    <p>We deliver to you <span><?= $generalSetting->topbar_text ?></span></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="header_sign">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="header_sign_content mb-3 mb-sm-0">
                    <!-- <a href="">Sign Up for Email</a> -->
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="header_location">
                    <ul>
                        <li><a class="nav-link p-0" href="<?= url('page/our-store') ?>"><i class="fa-solid fa-location-dot"></i></i>Our Store</a></li>
                        <ul>
                            <li> <i class="fa-solid fa-earth-americas"></i></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link p-0" href="javascript:void(0);">
                                    US ($)
                                </a>
                                <!-- <a class="nav-link dropdown-toggle p-0" href="#" id="navbarDropdown" role="button"
                                  aria-expanded="false">
                                  IN ($)
                              </a> -->
                                <!-- <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                  <li><a class="dropdown-item" href="#">Action</a></li>
                                  <li><a class="dropdown-item" href="#">Another action</a></li>
                              </ul> -->
                            </li>
                        </ul>
                        <li>
                            <?php if(session('user_id')) {?>
                                <a class="nav-link p-0" href="<?= url('user/dashboard') ?>"><i class="fa-solid fa-circle-user"></i>Welcome <?=session('name')?></a>
                            <?php } else {?>
                                <a class="nav-link p-0" href="<?= url('login') ?>"><i class="fa-solid fa-circle-user"></i>Signup/Signin</a>
                            <?php }?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mid-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 col-md-3 col-12">
                <div class="logo d-none d-md-block">
                    <a href="<?= url('/') ?>">
                        <img alt="logo" class="logo img-fluid" src="<?= env('UPLOADS_URL') . $generalSetting->site_logo ?>">
                    </a>
                </div>
            </div>
            <div class="col-lg-8 col-md-9 col-12">
                <div class="search-box">
                    <div class="search-area">
                        <form action="" id="searchForm">
                            <input type="text" class="form-control" placeholder="What are you looking for..." id="searchInput">
                        </form>
                        <button><img src="<?= env('FRONT_ASSETS_URL') ?>images/search_icon.png" alt="logo"></button>
                        <ul id="searchResults">
                            
                        </ul>
                    </div>
                    <ul>
                        <?php if(session('user_id')) {?>
                            <li><a href="<?=url('user/wishlist')?>"> <img src="<?= env('FRONT_ASSETS_URL') ?>images/heart_icon.png" alt="logo"></a></li>
                        <?php }?>
                        <li><a href="<?=url('cart')?>"> <img src="<?= env('FRONT_ASSETS_URL') ?>images/cart_icon.png" alt="logo"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="menu-header">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="main_menu">
                    <nav class="navbar navbar-expand-md">
                        <a class="navbar-brand d-block d-md-none" href="#">
                            <img alt="logo" class="logo img-fluid" src="<?= env('FRONT_ASSETS_URL') ?>images/logo.png">
                        </a>
                        <div class="button_container d-block d-md-none" id="toggle" type="button"
                            data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                            aria-controls="navbarNavDropdown" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="top"></span>
                            <span class="middle"></span>
                            <span class="bottom"></span>
                            <span class="bottom-last"></span>
                        </div>
                        <div class="collapse navbar-collapse" id="navbarNavDropdown">
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link active" aria-current="page" href="<?= url('/') ?>" id="navbarDropdownMenuLink" role="button" aria-expanded="false">Home</a>
                                </li>

                                <?php
                                $mainCats = Category::select('id', 'category_name', 'slug')->where('parent_id', '=', 0)->where('status', '=', 1)->get();
                                if($mainCats){ foreach($mainCats as $mainCat){
                                    $subCats = Category::select('id', 'category_name', 'slug')->where('parent_id', '=', $mainCat->id)->where('status', '=', 1)->get();
                                ?>
                                    <li class="nav-item dropdown">
                                        <!-- <a class="nav-link dropdown-toggle" href="<?=url('products/' . $mainCat->slug)?>" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"> -->
                                        <a class="nav-link dropdown-toggle" href="<?=url('products/' . $mainCat->slug)?>" id="navbarDropdownMenuLink" role="button">
                                            <?=$mainCat->category_name?>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                            <?php if($subCats){ foreach($subCats as $subCat){?>
                                                <li><a class="dropdown-item" href="<?=url('products/' . $mainCat->slug . '/' . $subCat->slug)?>"><?=$subCat->category_name?></a></li>
                                            <?php } }?>
                                        </ul>
                                    </li>
                                <?php } }?>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="<?= url('whats-new') ?>" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        What's New
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <?php
                                        $newProducts = Product::select('id', 'name', 'slug')->where('is_new', '=', 1)->where('status', '=', 1)->limit(10)->get();
                                        if($newProducts){ foreach($newProducts as $product){
                                        ?>
                                            <li><a class="dropdown-item" href="<?=url('/product/' . $product->slug)?>"><?=$product->name?></a></li>
                                        <?php } }?>
                                    </ul>
                                </li>

                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="<?= url('specials') ?>" id="navbarDropdownMenuLink" role="button"> Specials</a>
                                </li> -->
                                
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= url('faq') ?>" id="navbarDropdownMenuLink" role="button"> FAQs</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="<?= url('contact') ?>" id="navbarDropdownMenuLink" role="button"> Contact</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>