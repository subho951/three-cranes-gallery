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
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Product</th>
                <th scope="col">Customer</th>
                <th scope="col">Rating</th>
                <th scope="col">Title</th>
                <th scope="col">Comment</th>
                <th scope="col">Review Date</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($reviewItems) > 0) {
                $sl = 1;
                foreach ($reviewItems as $row) { ?>
                    <?php
                    $getProduct    = Product::where('id', '=', $row->product_id)->first();
                    ?>
                    <tr width="100%">
                        <td>
                            <?= $sl++ ?>
                        </td>
                        <td>
                            <a href="<?= url('product-details/' . Helper::encoded($row->product_id)) ?>" target="_blank"><img src="<?= env('UPLOADS_URL') . 'product/' . (($getProduct) ? $getProduct->cover_image : '') ?>" alt="<?= (($getProduct) ? $getProduct->name : '') ?>" class="img-thumbnail" style="width: 100px; height: 100px;"></a><br>
                            <a href="<?= url('product-details/' . Helper::encoded($row->product_id)) ?>" target="_blank"><?= (($getProduct) ? $getProduct->name : '') ?></a><br>
                            <span>$<?= (($getProduct) ? number_format($getProduct->base_price, 2) : 0.00) ?></span>
                        </td>
                        <td>
                            <?= $row->name ?><br>
                            <?= $row->email ?>
                        </td>
                        <td>
                            <?= $row->rating ?>
                        </td>
                        <td>
                            <?= $row->title ?>
                        </td>
                        <td>
                            <?= $row->comment ?>
                        </td>
                        <td>
                            <?= date_format(date_create($row->created_at), "M d, Y h:i A") ?>
                        </td>
                        <td>
                            <?php if ($row->status == 0) { ?>
                                <span class="badge bg-warning">PENDING</span>
                            <?php } elseif ($row->status == 1) { ?>
                                <span class="badge bg-success">APPROVED</span><br>
                                <small class="mt-3"><?= date_format(date_create($row->approve_reject_timestamp), "M d, Y h:i A") ?></small>
                            <?php } elseif ($row->status == 3) { ?>
                                <span class="badge bg-danger">REJECTED</span><br>
                                <small class="mt-3"><?= date_format(date_create($row->approve_reject_timestamp), "M d, Y h:i A") ?></small>
                            <?php } ?>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="8" style="text-align:center; color: red;">No Reviews Found!</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>