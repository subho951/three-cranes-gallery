<?php
use App\Helpers\Helper;
use Illuminate\Support\Facades\Route;
$currentFullURL = url()->full();
$pageName     = explode("/", $currentFullURL);
// Helper::pr($pageName);
if(count($pageName) < 4){
   $pageSegment  = '';
} elseif(count($pageName) >= 3){
   $pageSegment  = $pageName[4];
} else {
   $pageSegment  = $pageName[4];
}
// echo $pageSegment;
?>
<section class="panel">
    <header class="panel-heading">My Account</header>
    <div class="panel-body ">
        <ul class="nav-side">
            <?php $currentUrl = url('user/addresses/'); ?>
            <li>
                <a href="<?=url('user/dashboard')?>" <?=(($pageSegment == 'dashboard')?'class="active"':'')?>>
                    Dashboard 
                    <svg aria-hidden="true"
                        focusable="false" data-prefix="fas" data-icon="gauge"
                        class="svg-inline--fa fa-gauge " role="img"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm320 96c0-26.9-16.5-49.9-40-59.3L280 88c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 204.7c-23.5 9.5-40 32.5-40 59.3c0 35.3 28.7 64 64 64s64-28.7 64-64zM144 176a32 32 0 1 0 0-64 32 32 0 1 0 0 64zm-16 80a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm288 32a32 32 0 1 0 0-64 32 32 0 1 0 0 64zM400 144a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z">
                        </path>
                    </svg>
                </a>
            </li>
            <li class="">
                <a href="<?=url('user/order-list')?>" <?=(($pageSegment == 'order-list')?'class="active"':'')?>>
                    Orders 
                    <svg aria-hidden="true"
                        focusable="false" data-prefix="fas" data-icon="bag-shopping"
                        class="svg-inline--fa fa-bag-shopping " role="img"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M160 112c0-35.3 28.7-64 64-64s64 28.7 64 64l0 48-128 0 0-48zm-48 48l-64 0c-26.5 0-48 21.5-48 48L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-208c0-26.5-21.5-48-48-48l-64 0 0-48C336 50.1 285.9 0 224 0S112 50.1 112 112l0 48zm24 48a24 24 0 1 1 0 48 24 24 0 1 1 0-48zm152 24a24 24 0 1 1 48 0 24 24 0 1 1 -48 0z">
                        </path>
                    </svg>
                </a>
            </li>
            <li class="">
                <a href="<?=url('user/wishlist')?>" <?=(($pageSegment == 'wishlist')?'class="active"':'')?>>
                    Wishlist 
                    <svg aria-hidden="true"
                        focusable="false" data-prefix="fas" data-icon="heart"
                        class="svg-inline--fa fa-heart " role="img"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z">
                        </path>
                    </svg>
                </a>
            </li>
            <li class="">
                <a href="<?=url('user/reviews')?>" <?=(($pageSegment == 'reviews')?'class="active"':'')?>>
                    Reviews 
                    <svg aria-hidden="true"
                        focusable="false" data-prefix="fas" data-icon="comment"
                        class="svg-inline--fa fa-comment " role="img"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M512 240c0 114.9-114.6 208-256 208c-37.1 0-72.3-6.4-104.1-17.9c-11.9 8.7-31.3 20.6-54.3 30.6C73.6 471.1 44.7 480 16 480c-6.5 0-12.3-3.9-14.8-9.9c-2.5-6-1.1-12.8 3.4-17.4c0 0 0 0 0 0s0 0 0 0s0 0 0 0c0 0 0 0 0 0l.3-.3c.3-.3 .7-.7 1.3-1.4c1.1-1.2 2.8-3.1 4.9-5.7c4.1-5 9.6-12.4 15.2-21.6c10-16.6 19.5-38.4 21.4-62.9C17.7 326.8 0 285.1 0 240C0 125.1 114.6 32 256 32s256 93.1 256 208z">
                        </path>
                    </svg>
                </a>
            </li>
            <li class="">
                <a href="<?=url('user/addresses/')?>" <?=(($pageSegment == 'addresses')?'class="active"':'')?>>
                    Address 
                    <svg aria-hidden="true"
                        focusable="false" data-prefix="fas" data-icon="house"
                        class="svg-inline--fa fa-house " role="img"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <path fill="currentColor"
                            d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z">
                        </path>
                    </svg>
                </a>
            </li>
            <li class="">
                <a href="<?=url('user/account')?>" <?=(($pageSegment == 'account')?'class="active"':'')?>>
                    Account details 
                    <svg aria-hidden="true"
                        focusable="false" data-prefix="fas" data-icon="user"
                        class="svg-inline--fa fa-user " role="img"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z">
                        </path>
                    </svg>
                </a>
            </li>
            <li class="logout">
                <button class="" data-bs-toggle="modal" data-bs-target="#logout">
                    Logout 
                    <svg aria-hidden="true" focusable="false"
                        data-prefix="fas" data-icon="right-from-bracket"
                        class="svg-inline--fa fa-right-from-bracket " role="img"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z">
                        </path>
                    </svg>
                </button>
            </li>
        </ul>
    </div>
</section>