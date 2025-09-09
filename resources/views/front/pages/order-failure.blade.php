<?php
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Faq;
use App\Helpers\Helper;
?>
<section class="single-page-banner-section" style="background-image: url('https://noble-kids.itiffyconsultants.com/public/uploads/category/1706076811slideshow 7.jpeg')">
   <div class="background-overlay"></div>
   <div class=" container-xxl container-xl container-lg container-md container-sm container">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12  col-12 order-md-1">
            <div class="single-page-banner-description custom-breadcrumb ">
               <h1 class="text-center"><?=$page_header?></h1>
            </div>
         </div>
      </div>
   </div>
</section>
<section class="register-form-section login-form shipping-form-section section-padding">
   <div class=" container-xxl container-xl container-lg container-md container-sm container">
      @if(session('success_message'))
         <h6 class="alert alert-success autohide">{{ session('success_message') }}</h6>
      @endif
      @if(session('error_message'))
         <h6 class="alert alert-danger autohide">{{ session('error_message') }}</h6>
      @endif
      <form method="POST" action="">
         @csrf
         <div class="row justify-content-center">
            <div class="col-12 col-lg-12 col-xl-12 col-md-12 col-sm-12">
               <table class="table table-bordered table-striped">
                  <tbody>
                     <tr>
                        <th>Order No.</th>
                        <td><?=(($getOrder)?$getOrder->order_no:'')?></td>
                     </tr>
                     <tr>
                        <th>Order Amount</th>
                        <td><?=(($getOrder)?number_format($getOrder->net_amt,2):'')?></td>
                     </tr>
                     <tr>
                        <th>Payment Amount</th>
                        <td><?=(($getOrder)?number_format($getOrder->payable_amt,2):'')?></td>
                     </tr>
                     <?php if($getOrder->due_amt > 0){?>
                        <tr>
                           <th>Due Amount</th>
                           <td><?=(($getOrder)?number_format($getOrder->due_amt,2):'')?></td>
                        </tr>
                     <?php }?>
                     <tr>
                        <th>Order Date/Time</th>
                        <td><?=(($getOrder)?date_format(date_create($getOrder->order_date), "M d, Y"):'')?> <?=(($getOrder)?date_format(date_create($getOrder->order_time), "h:i A"):'')?></td>
                     </tr>
                     <tr>
                        <th>Payment Mode</th>
                        <td><?=(($getOrder)?$getOrder->payment_mode:'')?></td>
                     </tr>
                     <tr>
                        <th>Payment Status</th>
                        <td><span class="text-danger fw-bold">FAILED</span></td>
                     </tr>
                     <tr>
                        <td colspan="2" style="text-align: center;">
                           <a href="<?=url('pay-by-paypal/'.Helper::encoded((($getOrder)?$getOrder->id:'')))?>" class="btn btn-warning btn-sm">Retry Payment</a>
                        </td>
                     </tr>
                     <tr>
                  </tbody>
               </table>
            </div>
         </div>
      </form>
   </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
   function isNumber(evt) {
       evt = (evt) ? evt : window.event;
       var charCode = (evt.which) ? evt.which : evt.keyCode;
       if (charCode > 31 && (charCode < 48 || charCode > 57)) {
           return false;
       }
       return true;
   }
</script>