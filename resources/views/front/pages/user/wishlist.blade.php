<?php
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Faq;
use App\Helpers\Helper;
?>
<div class="table-responsive custom-table">
    @if(session('success_message'))
    <h6 class="alert alert-success autohide">{{ session('success_message') }}</h6>
    @endif
    @if(session('error_message'))
    <h6 class="alert alert-danger autohide">{{ session('error_message') }}</h6>
    @endif
    <table class="table table-striped table-bordered table-hover w-100">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Product Image</th>
                <th scope="col">Product Name</th>
                <th scope="col">Product Price</th>
                <th scope="col">Date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($wishlistItems) > 0) {
                $sl = 1;
                foreach ($wishlistItems as $row) { ?>
                    <?php
                    $getProduct    = Product::where('id', '=', $row->product_id)->first();
                    ?>
                    <tr width="100%">
                        <td>
                            <?= $sl++ ?>
                        </td>
                        <td>
                            <a href="<?= url('product-details/' . Helper::encoded($row->product_id)) ?>" target="_blank"><img src="<?= env('UPLOADS_URL') . 'product/' . (($getProduct) ? $getProduct->cover_image : '') ?>" alt="<?= (($getProduct) ? $getProduct->name : '') ?>" class="img-thumbnail" style="width: 100px; height: 100px;"></a>
                        </td>
                        <td>
                            <a href="<?= url('product-details/' . Helper::encoded($row->product_id)) ?>" target="_blank"><?= (($getProduct) ? $getProduct->name : '') ?></a>
                        </td>
                        <td>
                            <span>$<?= (($getProduct) ? number_format($getProduct->base_price, 2) : 0.00) ?></span>
                        </td>
                        <td>
                            <?= date_format(date_create($row->created_at), "M d, Y h:i A") ?>
                        </td>
                        <td class="text-center">
                            <a href="<?= url('user/wishlist-product-delete/' . Helper::encoded($row->id)) ?>" onclick="return confirm('Do you want to delete this product from wishlist ?');">
                                <button class="btn btn-sm btn-danger">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="trash" class="svg-inline--fa fa-trash " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path fill="currentColor"
                                            d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z">
                                        </path>
                                    </svg>
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6" style="text-align:center; color: red;">Your wishlist is empty… for now.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>