<?php
use App\Helpers\Helper;
use App\Models\SurveyGrades;
use App\Models\SurveyQuestion;
use App\Models\QuestionTypes;
use App\Models\SurveyFactor;
use App\Models\SurveyImage;
?>
<style>
  .inline-form {
    display: flex;
    align-items: center;
    gap: 10px; /* Space between form elements */
  }
  .inline-form label {
    margin-right: 5px;
  }
  .inline-form input[type="text"],
  .inline-form input[type="email"],
  .inline-form button {
    padding: 5px 10px;
    font-size: 14px;
  }
  .inline-form button {
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  .inline-form button:hover {
    background-color: #0056b3;
  }
  .pagination {
      display: flex;
      list-style: none;
      padding: 0;
  }
  .pagination li {
      margin: 0 5px;
  }
  .pagination a {
      text-decoration: none;
      color: #007bff;
  }
  .pagination .active span {
      font-weight: bold;
  }
  .relative .z-0 .inline-flex .shadow-sm{
   display: none;
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
          <h5 class="card-title" style="border: 1px solid #fb2d0d30;padding: 10px;border-radius: 10px; margin-bottom: 10px;">
            <form class="inline-form" method="POST" action="" enctype="multipart/form-data">
              @csrf
              <label for="image_file">Images(s)</label>
              <input type="file" id="image_file" name="image_file[]" placeholder="Select Image (s)" multiple required>
              
              <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-upload"></i> Upload</button>
            </form>
          </h5>
          <div class="card p-2">
            <div class="row">
              <?php if($rows){ $sl=1; foreach($rows as $row){?>
                <div class="col-md-3">
                  <div class="card-body" style="border: 1px solid #2c080242;border-radius: 10px;margin-bottom: 10px;">
                    <img src="<?=$row->image_link?>" id="image<?=$sl?>" style="width: 100%;height: 250px; object-fit: contain">
                    <p style="margin-top: 3px;">
                      <button type="button" class="btn btn-dark btn-sm text-white" onclick="copyToClipboard(<?=$sl?>);" style="width: 100%;"><i class="fa fa-copy"></i> Copy Image Link</button>
                    </p>
                  </div>
                </div>
              <?php $sl++; } }?>
            </div>
          </div>

          <div class="pagination-links mt-3">
            @if ($rows->onFirstPage())
                <span>Previous</span>
            @else
                <a href="{{ $rows->previousPageUrl() }}" class="btn btn-sm">Previous</a>
            @endif

            @if ($rows->hasMorePages())
                <a href="{{ $rows->nextPageUrl() }}" class="btn btn-sm">Next</a>
            @else
                <span>Next</span>
            @endif
          </div>
    
        </div>
      </div>
    </div>
    
  </div>
</section>
<script>
  function copyToClipboard(counter){
    // Get the image link
    const imageLink = document.getElementById("image" + counter).src;
    // Use the Clipboard API to copy the link
    navigator.clipboard.writeText(imageLink).then(() => {
        alert("Image link copied to clipboard: " + imageLink);
    }).catch(err => {
        console.error("Failed to copy the image link: ", err);
        alert("Failed to copy the image link.");
    });
  }
</script>