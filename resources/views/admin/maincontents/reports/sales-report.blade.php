<?php
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<div class="pagetitle">
  <h1><?=$page_header?></h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=url('admin/dashboard')?>">Home</a></li>
      <li class="breadcrumb-item active"><a href="<?=url('admin/' . $controllerRoute . '/list/')?>"><?=$module['title']?> List</a></li>
      <li class="breadcrumb-item active"><?=$page_header?></li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<section class="section profile">
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
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-3">
                <label for="from_date">From Date</label>
                <input type="date" name="from_date" class="form-control" id="from_date" value="<?=$fdate?>" required>
              </div>
              <div class="col-md-3">
                <label for="to_date">To Date</label>
                <input type="date" name="to_date" class="form-control" id="to_date" value="<?=$tdate?>" required>
              </div>
              <div class="col-md-3">
                <label for="product_id">Products</label>
                <select name="product_id" class="form-control" id="product_id">
                  <option value="all">All</option>
                  <?php if($products){ foreach($products as $product){?>
                    <option value="<?=$product->id?>" <?=(($product->id == $product_id)?'selected':'')?>><?=$product->name?></option>
                  <?php } }?>
                </select>
              </div>
              <div class="col-md-3">
                <button type="submit" class="btn btn-primary" style="margin-top: 23px;"><i class="fa fa-paper-plane"></i> GENERATE</button>
                <?php if($is_search){?><a href="<?=url('admin/reports/sales-report')?>" class="btn btn-secondary" style="margin-top: 23px;"><i class="fa fa-refresh"></i> RESET</a><?php }?>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <table class="table datatable global_table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Order No</th>
                <th scope="col">Customer</th>
                <th scope="col">Date/Time<br>Checkout Type</th>
                <th scope="col">Net Amount</th>
                <th scope="col">Payment</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if(count($rows)>0){ $sl=1; foreach($rows as $row){?>
                <tr>
                  <th scope="row"><?=$sl++?></th>
                  <td><?=$row->order_no?></td>
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
                  <td>
                    <?=(($row->payment_status)?'<span class="text-success fw-bold">SUCCESS</span>':'<span class="text-danger fw-bold">FAILED</span>')?><br>
                    <?=$row->payment_mode?><br>
                    <?=$row->payment_txn_no?><br>
                    <?=date_format(date_create($row->payment_date_time), "M d, Y h:i A")?>
                  </td>
                  <td>
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
                    } elseif($row->status == 7 && $row->is_cancel_request == 0){
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
                  </td>
                </tr>
              <?php } } else {?>
                <tr>
                  <td colspan="7" style="text-align: center; color: red;">No Orders Found !!!</td>
                </tr>
              <?php }?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</section>