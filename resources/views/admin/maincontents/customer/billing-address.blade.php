<?php
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<style>
.datatable-table thead tr{
    border-radius: 5px
}
.datatable-table thead th{
    background: #dfcdaf;
    color: #8B2525;
    padding: 12px;
    font-size: 14px;
}
.datatable-table th:first-child{
    border-top-left-radius: 5px;
}
.datatable-table th:last-child{
    border-top-right-radius: 5px;
}
.datatable-table tbody tr:nth-child(odd){
    background: #dfcdaf24;
}
</style>

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
                <th scope="col">Title</th>
                <th scope="col">Address</th>
                <th scope="col">Country</th>
                <th scope="col">State</th>
                <th scope="col">City</th>
                <th scope="col">Locality</th>
                <th scope="col">Street</th>
                <th scope="col">Post Code</th>
              </tr>
            </thead>
            <tbody>
              <?php if($rows){ $sl=1; foreach($rows as $row){?>
                <tr>
                  <th scope="row"><?=$sl++?></th>
                  <td><?=$row->title?></td>
                  <td><?=$row->address?></td>
                  <td><?=$row->country?></td>
                  <td><?=$row->state?></td>
                  <td><?=$row->city?></td>
                  <td><?=$row->locality?></td>
                  <td><?=$row->street_no?></td>
                  <td><?=$row->zipcode?></td>
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