<?php
use App\Helpers\Helper;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\OrderDetail;
$controllerRoute = $module['controller_route'];
?>
<div class="pagetitle">
  <h1><?=$page_header?></h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=url('admin/dashboard')?>">Home</a></li>
      <li class="breadcrumb-item active"><?=$page_header?></li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<section class="section">
  <div class="row">
    <div class="col-xl-12">
      @if(session('success_message'))
        <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show autohide" role="alert">
          {{ session('success_message') }}
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if(session('error_message'))
        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show autohide" role="alert">
          {{ session('error_message') }}
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
    </div>
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title pt-0">
            
          </h5>
          <!-- Table with stripped rows -->
          <table class="table datatable global_table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Order No</th>
                <th scope="col">Customer</th>
                <th scope="col">Date/Time<br>Checkout Type</th>
                <th scope="col">Net Amount</th>
                <!-- <th scope="col">Payment</th> -->
                <th scope="col">Status</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if($rows){ $sl=1; foreach($rows as $row){?>
                <tr>
                  <th scope="row"><?=$sl++?></th>
                  <td><a href="<?=url('admin/' . $controllerRoute . '/order-details/'.Helper::encoded($row->id))?>" target="_blank"><?=$row->order_no?></a></td>
                  <td>
                    <?=$row->cust_fname.' '.$row->cust_lname?><br>
                    <?=$row->cust_phone?><br>
                    <?=$row->cust_email?>
                  </td>
                  <td>
                    <?=date_format(date_create($row->order_date), "M d, Y").' '.date_format(date_create($row->order_time), "h:i A")?><br>
                    <?=$row->checkout_type?>
                  </td>
                  <td>
                    $<?=$row->net_amt?>
                  </td>
                  <!-- <td>
                    <?=(($row->payment_status)?'<span class="text-success fw-bold">PAYMENT SUCCESS</span>':'<span class="text-danger fw-bold">PAYMENT FAILED</span>')?><br>
                    <?=$row->payment_mode?><br>
                    <?=$row->payment_txn_no?><br>
                    <?=date_format(date_create($row->payment_date_time), "M d, Y h:i A")?>
                  </td> -->
                  <td>
                    <?=(($row->payment_status)?'<span class="text-success fw-bold">PAYMENT SUCCESS</span>':'<span class="text-danger fw-bold">PAYMENT FAILED</span>')?><br>
                    <?php
                    if($row->status == 1 && $row->is_cancel_request == 0){
                      $statusName = 'New';
                    } elseif($row->status == 2 && $row->is_cancel_request == 0){
                      $statusName = 'Processing';
                    } elseif($row->status == 3 && $row->is_cancel_request == 0){
                      $statusName = 'Incomplete';
                    } elseif($row->status == 4 && $row->is_cancel_request == 0){
                      $statusName = 'Shipped';
                    } elseif($row->status == 5 && $row->is_cancel_request == 0){
                      $statusName = 'Complete';
                    } elseif($row->status == 6 && $row->is_cancel_request == 0){
                      $statusName = 'Rejected';
                    } elseif($row->status == 1 && $row->is_cancel_request == 1){
                      $statusName = 'Cancelled';
                    } elseif($row->status == 7 && $row->is_cancel_request == 1){
                      $statusName = 'Cancelled';
                    }
                    ?>
                    <h6 class="badge bg-info"><?=$statusName?></h6>
                    <p>
                       <?php if($row->status == 7){?>
                          <h6><?=$row->cancel_order_reason?></h6>
                          <h6><?=$row->cancel_order_description?></h6>
                          <span class="badge bg-success">Approved Cancel Request</span>
                          <h6><small>Approved : <?=date_format(date_create($row->cancel_request_timestamp), "M d, Y h:i A")?></small></h6>
                       <?php } elseif(($row->status == 1) && ($row->is_cancel_request == 1) && ($row->cancel_approve_reject_timestamp == '')){?>
                          <h6><?=$row->cancel_order_reason?></h6>
                          <h6><?=$row->cancel_order_description?></h6>
                          <span class="badge bg-warning">Pending Cancel Request</span>
                          <h6><small>Requested : <?=date_format(date_create($row->cancel_request_timestamp), "M d, Y h:i A")?></small></h6>
                       <?php } elseif(($row->status == 1) && ($row->is_cancel_request == 1) && ($row->cancel_approve_reject_timestamp != '')){?>
                          <h6><?=$row->cancel_order_reason?></h6>
                          <h6><?=$row->cancel_order_description?></h6>
                          <span class="badge bg-danger">Rejected Cancel Request</span>
                          <h6><small>Rejected : <?=date_format(date_create($row->cancel_approve_reject_timestamp), "M d, Y h:i A")?></small></h6>
                       <?php }?>
                    </p>
                    <?php if($row->is_cancel_request == 0){?>
                      <form method="GET" action="<?=url('admin/orders/status-update/'.Helper::encoded($row->id).'/'.Helper::encoded(0))?>">
                        <?php if($row->status >= 2){?>
                          <input type="text" class="form-control mb-2" placeholder="Tracking No." name="tracking_number" value="<?=$row->tracking_number?>" required>
                        <?php }?>
                        <select class="form-control" name="status" onchange="this.form.submit();">
                          <option value="" selected disabled hidden>Select Status</option>
                          <option value="1" <?=(($row->status == 1)?'selected':'')?>>New</option>
                          <option value="2" <?=(($row->status == 2)?'selected':'')?>>Processing</option>
                          <option value="3" <?=(($row->status == 3)?'selected':'')?>>Incomplete</option>
                          <option value="4" <?=(($row->status == 4)?'selected':'')?>>Shipped</option>
                          <option value="5" <?=(($row->status == 5)?'selected':'')?>>Complete</option>
                          <?php if($row->status != 3){?>
                            <option value="6" <?=(($row->status == 6)?'selected':'')?>>Rejected</option>
                          <?php }?>
                          <!-- <option value="7" <?=(($row->status == 7)?'selected':'')?>>Cancelled</option> -->
                        </select>
                      </form>
                    <?php }?>
                  </td>
                  <td>
                    <?php if($row->payment_status){?>
                      <!-- <a href="<?=url('admin/' . $controllerRoute . '/print-invoice/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>" target="_blank"><i class="fa fa-print"></i> Print Invoice PDF</a> -->
                      <a href="<?=env('UPLOADS_URL').'orders/'.$row->invoice_pdf?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>" target="_blank"><i class="fa fa-print"></i> Print Invoice</a>
                      <br><br>
                    <?php }?>
                    
                    <?php if(($row->is_cancel_request == 1) && ($row->status == 1) && ($row->cancel_approve_reject_timestamp == '')){?>
                      <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id).'/'.Helper::encoded(1))?>" class="btn btn-success btn-sm" title="Edit <?=$module['title']?>" onclick="return confirm('Do you want to approve this cancel request ?');"><i class="fa fa-check"></i> Approve</a>
                      <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id).'/'.Helper::encoded(0))?>" class="btn btn-danger btn-sm" title="Edit <?=$module['title']?>" onclick="return confirm('Do you want to reject this cancel request ?');"><i class="fa fa-times"></i> Reject</a>
                    <?php }?>
                  </td>
                </tr>
              <?php } }?>
            </tbody>
          </table>
          <!-- End Table with stripped rows -->
        </div>
      </div>
    </div>
  </div>
</section>