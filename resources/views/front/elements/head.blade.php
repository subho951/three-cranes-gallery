<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?></title>
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
        overflow-y: auto;
        padding: 0;
        margin: 0;
        list-style: none;
        border: 1px solid #ccc;
        border-top: none;
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
</style>