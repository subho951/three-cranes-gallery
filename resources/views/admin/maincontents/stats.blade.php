<?php
use App\Models\UserView;
use App\Models\UserVisit;
use App\Models\UserWebsiteActivity;
use App\Models\UserWishlist;
use App\Models\UserReview;
use App\Models\OrderDetail;
?>
<div class="pagetitle">
   <h1><?=$page_header?></h1>
   <nav>
      <ol class="breadcrumb">
         <li class="breadcrumb-item"><a href="<?=url('dashboard')?>">Home</a></li>
         <li class="breadcrumb-item active"><?=$page_header?></li>
      </ol>
   </nav>
</div>
<!-- End Page Title -->
<section class="section dashboard">
   <ul class="stats-overview-list">
      <li>
         <h6>Total Views</h6>
         <b><?=$total_view?></b>
         <!-- <span><i class="fa-solid fa-arrow-up"></i> 13% Yoy</span> -->
         <label><i class="fa-regular fa-clock"></i> Just Now</label>
      </li>
      <li>
         <h6>Total Visits</h6>
         <b><?=$total_visit?></b>
         <!-- <span><i class="fa-solid fa-arrow-up"></i> 13% Yoy</span> -->
         <label><i class="fa-regular fa-clock"></i> Just Now</label>
      </li>
      <li>
         <h6>Total Orders</h6>
         <b><?=$total_orders?></b>
         <!-- <span><i class="fa-solid fa-arrow-up"></i> 13% Yoy</span> -->
         <label><i class="fa-regular fa-clock"></i> Just Now</label>
      </li>
      <li>
         <h6>Total Revenue</h6>
         <b><?=number_format($total_sales,2)?></b>
         <!-- <span><i class="fa-solid fa-arrow-up"></i> 13% Yoy</span> -->
         <label><i class="fa-regular fa-clock"></i> Just Now</label>
      </li>
   </ul>
   <canvas id="myChart" width="100%" height="20px"></canvas>

   <h3 class="dashboard-title">Shopper Stats</h3>
   <p>Get a snapshot of how buyers interacted with your shop—stats are based on the date range set at the top of the page.</p>
   <div class="row mt-3">
      <div class="col-lg-4">
         <div class="card">
            <div class="card-header bg-light">
               Item favorites
               <br>
               <b><?=$total_wishlist?></b>
            </div>
            <div class="card-body">
               Hearts galore! 566 shoppers favorited 224 of your items. 350 offers were emailed.
            </div>
         </div>
      </div>
      <div class="col-lg-4">
         <div class="card">
            <div class="card-header bg-light">
               Reviews
               <br>
               <b><?=$total_review?></b>
            </div>
            <div class="card-body">
               You had a 4.9 star average for that date range. Read and reply to reviews to keep your customer service top notch.
            </div>
         </div>
      </div>
      <div class="col-lg-4">
         <div class="card">
            <div class="card-header bg-light">
               Abandoned carts
               <br>
               <b><?=$total_cart?></b>
            </div>
            <div class="card-body">
               Those carts added up to $13,361, and 145 offers were emailed to nudge shoppers to check out.
            </div>
         </div>
      </div>
   </div>
   <!-- <div class="card">
      <div class="card-body">
         <h3 class="dashboard-title">How to Shopper found you</h3>
         <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
         <div class="row mb-3">
            <div class="col-lg-6">
               <h5>How to Shopper found you</h5>
            </div>
            <div class="col-lg-6">
               <h5>How to Shopper found you</h5>
            </div>
         </div>
         <div class="row">
            <div class="col-lg-6">
               <div class="share-point">
                  <h6>How to Shopper found you</h6>
                  <div class="share-right">
                     <span>2000</span>
                     <i class="fa-solid fa-circle-up up-market"></i>
                     <label class="up-market">50%</label>
                  </div>
               </div>
            </div>
            <div class="col-lg-6">
               <div class="share-point">
                  <h6>How to Shopper found you</h6>
                  <div class="share-right">
                     <span>2000</span>
                     <i class="fa-solid fa-circle-down down-market"></i>
                     <label class="down-market">50%</label>
                  </div>
               </div>
            </div>
            <div class="col-lg-6">
               <div class="share-point">
                  <h6>How to Shopper found you</h6>
                  <div class="share-right">
                     <span>2000</span>
                     <i class="fa-solid fa-circle-up up-market"></i>
                     <label class="up-market">50%</label>
                  </div>
               </div>
            </div>
            <div class="col-lg-12 mt-5">
               <div class="row">
                  <div class="col-lg-6">
                     <h5>Lorem Ipsum is simply</h5>
                     <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                  </div>
                  <div class="col-lg-6">
                     <h5>Lorem Ipsum is simply</h5>
                     <ul class="share-info">
                        <li>
                           <span>Lorem</span>
                           <b>12</b>
                        </li>
                        <li>
                           <span>Lorem</span>
                           <b>12</b>
                        </li>
                        <li>
                           <span>Lorem</span>
                           <b>12</b>
                        </li>
                        <li>
                           <span>Lorem</span>
                           <b>12</b>
                        </li>
                     </ul>
                  </div>
               </div>
               <h5>Lorem Ipsum is simply</h5>
               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            </div>
         </div>
      </div>
   </div> -->
   <h3 class="dashboard-title">Shoppers viewed your <?=($total_active_products + $total_deactive_products + $total_draft_products)?> listings</h3>
   <!-- <small>That's an average of 2.12 listing views per visit.</small> -->
   <div class="table-responsive">
      <table class="table display global_table">
         <thead>
            <tr>
               <th>Listing</th>
               <th>Views</th>
               <th>Favourites</th>
               <th>Orders</th>
               <th>Revenue</th>
            </tr>
         </thead>
         <tbody>
            <?php if($products){ foreach($products as $row){?>
               <tr>
                  <td>
                     <div class="table-item">
                        <img src="<?=env('UPLOADS_URL').'product/'.$row->cover_image?>" alt="<?=$row->name?>" width="40" style="width: 60px; height: 60px; border-radius: 50%;    object-fit: contain; background: #fff;">
                        <div>
                           <a href="<?=url('product/edit/'.Helper::encoded($row->id))?>"><?=wordwrap($row->name,130,"<br>\n")?></a>
                        </div>
                     </div>
                  </td>
                  <td>
                     <?php
                     echo $product_view = UserView::where('product_id', '=', $row->id)->count();
                     ?>
                  </td>
                  <td>
                     <?php
                     echo $product_wishlist = UserWishlist::where('product_id', '=', $row->id)->count();
                     ?>
                  </td>
                  <td>
                     <?php
                     echo $product_order = OrderDetail::where('product_id', '=', $row->id)->count();
                     ?>
                  </td>
                  <td>
                     $
                     <?php
                     $revenue = OrderDetail::where('product_id', '=', $row->id)->sum('net_amt');
                     echo number_format($revenue,2);
                     ?>
                  </td>
               </tr>
            <?php } }?>
         </tbody>
      </table>
      <div class="pagination-links mt-3">
         @if ($products->onFirstPage())
             <span>Previous</span>
         @else
             <a href="{{ $products->previousPageUrl() }}" class="btn btn-sm">Previous</a>
         @endif

         @if ($products->hasMorePages())
             <a href="{{ $products->nextPageUrl() }}" class="btn btn-sm">Next</a>
         @else
             <span>Next</span>
         @endif
      </div>
   </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
   const ctx = document.getElementById('myChart');
   
   new Chart(ctx, {
     type: 'line',
     data: {
       labels: [<?=implode(", ", $viewMonths)?>],
       datasets: [{
         label: 'number of views (per month)',
         data: [<?=implode(',', $viewCounts)?>],
         borderWidth: 1,
         fill: false,
         borderColor: 'rgb(75, 192, 192)',
         tension: 0.1
       }]
     },
     options: {
       scales: {
         y: {
           beginAtZero: true
         }
       }
     }
   });
</script>