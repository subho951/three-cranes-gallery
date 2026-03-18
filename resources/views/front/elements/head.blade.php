<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WMNF55CW');</script>
<!-- End Google Tag Manager -->


<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
//print_r($cat);
?>
<?php if(!empty($cat)) {?>
    <title><?= strip_tags(html_entity_decode($cat->meta_title)) ?></title>
    <meta name="description" content="{{ strip_tags(html_entity_decode($cat->meta_description)) }}">
    <meta name="keywords" content="{{ strip_tags(html_entity_decode($cat->meta_keywords)) }}">
<?php } else {?>
    <title><?= strip_tags(html_entity_decode($generalSetting->meta_title)) ?></title>
    <meta name="description" content="{{ strip_tags(html_entity_decode($generalSetting->meta_description)) }}">
    <meta name="keywords" content="{{ strip_tags(html_entity_decode($generalSetting->meta_keywords)) }}">
<?php }?>

<!-- Favicons -->
<link href="<?= env('UPLOADS_URL') . $generalSetting->site_favicon ?>" rel="icon">
<link rel="stylesheet" href="<?= env('FRONT_ASSETS_URL') ?>css/all.min.css">
<link rel="stylesheet" href="<?= env('FRONT_ASSETS_URL') ?>css/bootstrap.min.css">
<link rel="stylesheet" href="<?= env('FRONT_ASSETS_URL') ?>css/slick.css">
<link rel="stylesheet" href="<?= env('FRONT_ASSETS_URL') ?>css/slick-theme.css">
<link rel="stylesheet" href="<?= env('FRONT_ASSETS_URL') ?>css/fontawesome-all.min.css">
<link rel="stylesheet" href="<?= env('FRONT_ASSETS_URL') ?>css/swiper-bundle.min.css">
<link rel="stylesheet" href="<?= env('FRONT_ASSETS_URL') ?>css/style.css">
<link rel="stylesheet" href="<?= env('FRONT_ASSETS_URL') ?>css/responsive.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />
<style type="text/css">
    .toast-success {
        background-color: #28a745;
        color: #000 !important;
    }

    .toast-error {
        background-color: #dc3545;
        color: #000 !important;
    }

    .toast-warning {
        background-color: #ffc107;
        color: #000 !important;
    }

    .toast-info {
        background-color: #007bff;
        color: #000 !important;
    }

    .active {
        color: #ee3c23 !important;
    }
</style>
<style>
    #searchResults {
        max-height: 300px;
        width: -webkit-fill-available;
        overflow-y: auto;
        padding: 0;
        margin: 0;
        list-style: none;
        position: absolute;
        z-index: 9;
      	left: 0;
      	display: block;
      box-shadow: 0 5px 10px -5px #000;
    }
	.mid-header ul li span.search-text{
      background: transparent;
      color: #000;
      border-radius: 0;
      display: block;
      width: auto;
      height: auto;
      position: unset;
  	}
    .search-item {
        display: flex;
        align-items: center;
        padding: 10px;
        cursor: pointer;
        border-top: 1px solid #eee;
        background: #fff;
        transition: background 0.2s ease-in-out;
    }

    .search-item:hover {
        background-color: #f5f5f5;
    }

    .search-img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 10px;
    }

    .search-text {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 14px;
    }
    .product-info a {
        display: -webkit-box;
        -webkit-line-clamp: 2;   /* limit to 2 lines */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;     /* allows wrapping */
        height: 3.5em;             /* adjust based on font-size/line-height */
        line-height: 1.5em;      /* example line-height */
    }
</style>

<meta name="google-site-verification" content="U8MM60urLfsRrduGFk_YBBcTcbWbO3la9zE3e_FgdVA" />