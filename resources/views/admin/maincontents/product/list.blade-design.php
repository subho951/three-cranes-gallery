<?php
   use App\Models\Category;
   use App\Models\Product;
   use App\Models\ProductImage;
   use App\Models\ProductAttribute;
   use App\Helpers\Helper;
   $controllerRoute = $module['controller_route'];
   ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
<style type="text/css">
   .choices__list--multiple .choices__item {
   background-color: #d81636;
   border: 1px solid #d81636;
   }
   .very-small{
   font-size: 10px;
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
</div>
<!-- End Page Title -->
<!-- Modal -->
<div class="modal fade add-product-modal-details" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">First, tell us about your listing</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <p>This basic info helps us understand your listing and how it meets our policies. Next you can dive into the full listing form to add all the details that make your item special.</p>
            <div class="col-lg-12 mt-3">
               <h5 class="sub-title pt-2">
                  Paragraphs<span style="color:red;">*</span>
               </h5>
            </div>
            <div class="add-modal-product-listing mb-3">
               <div class="row">
                  <div class="col-lg-6 mt-3">
                     <div class="add-modal-product-list border rounded ">
                        <div class="renuel-radio mb-2">
                           <input type="radio" id="test3" name="radio-group" checked="">
                           <label for="test3">Automatic</label>
                        </div>
                        <div class="core-product-image">
                           <img src="https://threecranesgallery.itiffyconsultants.com/public/uploads/1726130627logo.jpg" alt="ThreeCranesGallery" class="img-fluid"/>
                        </div>
                        <h5 class="sub-title">
                           Etsy Ads
                        </h5>
                        <p>Promote this listing on Etsy as part of your Etsy Ads campaign.</p>
                     </div>
                  </div>
                  <div class="col-lg-6 mt-3">
                     <div class="add-modal-product-list border rounded ">
                        <div class="renuel-radio mb-2">
                           <input type="radio" id="test4" name="radio-group" >
                           <label for="test4">Automatic</label>
                        </div>
                        <div class="core-product-image">
                           <img src="https://threecranesgallery.itiffyconsultants.com/public/uploads/1726130627logo.jpg" alt="ThreeCranesGallery" class="img-fluid"/>
                        </div>
                        <h5 class="sub-title">
                           Etsy Ads
                        </h5>
                        <p>Promote this listing on Etsy as part of your Etsy Ads campaign.</p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-12 mb-4">
               <h5 class="sub-title">
                  Who made it? <span style="color:red;">*</span>
               </h5>
            </div>
            <div class="col-lg-12 mb-4">
               <div class="renuel-radio mb-2">
                  <input type="radio" id="test5" name="radio-group" checked="">
                  <label for="test5">I did</label>
               </div>
               <div class="renuel-radio mb-2">
                  <input type="radio" id="test6" name="radio-group">
                  <label for="test6">A member of my shop</label>
               </div>
               <div class="renuel-radio">
                  <input type="radio" id="test7" name="radio-group">
                  <label for="test7">Another company or person</label>
               </div>
            </div>
            <div class="col-lg-12 mb-4">
               <h5 class="sub-title">
                  What is it? <span style="color:red;">*</span>
               </h5>
            </div>
            <div class="col-lg-12 mb-4">
               <div class="renuel-radio mb-2">
                  <input type="radio" id="test8" name="radio-group" checked="">
                  <label for="test8">A finished product</label>
               </div>
               <div class="renuel-radio">
                  <input type="radio" id="test9" name="radio-group">
                  <label for="test9">A supply or tool to make things</label>
               </div>
            </div>
            <div class="col-lg-6 mb-3">
               <label for="exampleInputEmail1" class="form-label">When was it made?</label>
               <select class="form-select" aria-label="Default select example">
                  <option selected="">When did you make it ?</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
               </select>
            </div>
            <div class="col-lg-12 mb-3">
               <div class="return-option border rounded  ">
                  <div class="d-flex align-items-center justify-content-between">
                     <div class="col-lg-7">
                        <h5 class="sub-title mb-3">
                           Production partners for this listing
                        </h5>
                        <p>A production partner is anyone who’s not a part of your Etsy shop who helps you physically produce your items. Is this required for you?
                        </p>
                     </div>
                     <div class="col-lg-5">
                        <a class="new-btn-style bg-new-btn" href="">+ Add Production partners</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer d-flex justify-content-between" style="border:none;padding-top:0;">
            <a style="color:#000;" href="#" data-bs-target="#exampleModalToggle" data-bs-toggle="modal" data-bs-dismiss="modal">Cancel</a>
            <ul class="footer-btn-group">
               <li><a class="bg" href="">Continue </a></li>
            </ul>
         </div>
      </div>
   </div>
</div>
<!-------------------- Modal-end------------------------- -->
<section class="section shop-list">
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
   </div>
   <div class="shop-list-section-1 ">
      <div class="row align-items-center">
         <div class="col-lg-6">
            <h2>COLLECTION</h2>
         </div>
         <div class="col-lg-6">
            <div class="row">
               <div class="col-lg-7">
                  <form  class="header-search border rounded-5 " method="POST" >
                     <button type="submit" class="btn btn-search"><i class="search-icon fas fa-search"></i></button>
                     <input type="text" list="browsers" id="searchText" placeholder="Search Products" name="search">
                  </form>
               </div>
               <div class="col-lg-5">
                  <a class="new-btn-style add-listing" href=""  data-bs-toggle="modal" data-bs-target="#staticBackdrop">+Add a Listing</a>
               </div>
            </div>
         </div>
      </div>
   </div>
   <hr>
   <div class="shop-list-section-2 mt-4 pt-2">
      <div class="row">
         <div class="col-lg-8">
            <div class="product-left-side">
               <div class="product-left-side-top-bar">
                  <ul>
                     <li>
                        <div class="dropdown">
                           <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike"/>
                           <button class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></button>
                           <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                              <li><a class="dropdown-item" href="#">Action</a></li>
                              <li><a class="dropdown-item" href="#">Another action</a></li>
                              <li><a class="dropdown-item" href="#">Something else here</a></li>
                           </ul>
                        </div>
                     </li>
                     <li>
                        <a href="">Deactive </a>
                     </li>
                     <li>
                        <a href="">Delete</a>
                     </li>
                     <li>
                        <div class="dropdown">
                           <button class="dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">Editing option</button>
                           <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                              <li><a class="dropdown-item" href="#">Action</a></li>
                              <li><a class="dropdown-item" href="#">Another action</a></li>
                              <li><a class="dropdown-item" href="#">Something else here</a></li>
                           </ul>
                        </div>
                     </li>
                  </ul>
               </div>
			     <!-------------------- list-view-start------------------------- -->
               <div class="listing_box">
                  <div class="product-items-list">
                     <div class="row align-items-center">
                        <div class="col-lg-1">
                           <div class="product-items-checkbox">
                              <input type="checkbox" id="product1" name="vehicle1" value="Bike"/>
                           </div>
                        </div>
                        <div class="col-lg-2">
                           <div class="product-items-images">
                              <img src="https://threecranesgallery.itiffyconsultants.com/public/uploads/1726130627logo.jpg" alt="ThreeCranesGallery" style="width: 100%; height:100px;">
                           </div>
                        </div>
                        <div class="col-lg-9">
                           <div class="product-items-descr">
                              <div class="items-descr-name">
                                 <h2>Netanyahu To Address UN With Families Of Hamas Hostages</h2>
                              </div>
                              <div class="items-descr-rating-setting">
                                 <div class="items-descr-rating">								 
                                    <i class="fa-solid fa-star"></i>
                                 </div>
                                 <div class="items-setting">
                                    <div class="dropdown">
                                       <button class="dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-gear"></i></button>
                                       <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                                          <li><a class="dropdown-item" href="#">Action</a></li>
                                          <li><a class="dropdown-item" href="#">Another action</a></li>
                                          <li><a class="dropdown-item" href="#">Something else here</a></li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <ul class="stoke-details">
                              <li>2in stoke</li>
                              <li>$52.00</li>
                              <li>05394</li>
                              <li>Auto-renews Jan 20, 2020 </li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="product-items-list bg-color">
                     <div class="row align-items-center">
                        <div class="col-lg-1">
                           <div class="product-items-checkbox">
                              <input type="checkbox" id="product1" name="vehicle1" value="Bike"/>
                           </div>
                        </div>
                        <div class="col-lg-2">
                           <div class="product-items-images">
                              <img src="https://threecranesgallery.itiffyconsultants.com/public/uploads/1726130627logo.jpg" alt="ThreeCranesGallery" style="width: 100%; height:100px;">
                           </div>
                        </div>
                        <div class="col-lg-9">
                           <div class="product-items-descr">
                              <div class="items-descr-name">
                                 <h2>Netanyahu To Address UN With Families Of Hamas Hostages</h2>
                              </div>
                              <div class="items-descr-rating-setting">
                                 <div class="items-descr-rating">								 
                                    <i class="fa-solid fa-star"></i>
                                 </div>
                                 <div class="items-setting">
                                    <div class="dropdown">
                                       <button class="dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-gear"></i></button>
                                       <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                                          <li><a class="dropdown-item" href="#">Action</a></li>
                                          <li><a class="dropdown-item" href="#">Another action</a></li>
                                          <li><a class="dropdown-item" href="#">Something else here</a></li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <ul class="stoke-details">
                              <li>2in stoke</li>
                              <li>$52.00</li>
                              <li>05394</li>
                              <li>Auto-renews Jan 20, 2020 </li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="product-items-list">
                     <div class="row align-items-center">
                        <div class="col-lg-1">
                           <div class="product-items-checkbox">
                              <input type="checkbox" id="product1" name="vehicle1" value="Bike"/>
                           </div>
                        </div>
                        <div class="col-lg-2">
                           <div class="product-items-images">
                              <img src="https://threecranesgallery.itiffyconsultants.com/public/uploads/1726130627logo.jpg" alt="ThreeCranesGallery" style="width: 100%; height:100px;">
                           </div>
                        </div>
                        <div class="col-lg-9">
                           <div class="product-items-descr">
                              <div class="items-descr-name">
                                 <h2>Netanyahu To Address UN With Families Of Hamas Hostages</h2>
                              </div>
                              <div class="items-descr-rating-setting">
                                 <div class="items-descr-rating">								 
                                    <i class="fa-solid fa-star"></i>
                                 </div>
                                 <div class="items-setting">
                                    <div class="dropdown">
                                       <button class="dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-gear"></i></button>
                                       <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                                          <li><a class="dropdown-item" href="#">Action</a></li>
                                          <li><a class="dropdown-item" href="#">Another action</a></li>
                                          <li><a class="dropdown-item" href="#">Something else here</a></li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <ul class="stoke-details">
                              <li>2in stoke</li>
                              <li>$52.00</li>
                              <li>05394</li>
                              <li>Auto-renews Jan 20, 2020 </li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="product-items-list bg-color">
                     <div class="row align-items-center">
                        <div class="col-lg-1">
                           <div class="product-items-checkbox">
                              <input type="checkbox" id="product1" name="vehicle1" value="Bike"/>
                           </div>
                        </div>
                        <div class="col-lg-2">
                           <div class="product-items-images">
                              <img src="https://threecranesgallery.itiffyconsultants.com/public/uploads/1726130627logo.jpg" alt="ThreeCranesGallery" style="width: 100%; height:100px;">
                           </div>
                        </div>
                        <div class="col-lg-9">
                           <div class="product-items-descr">
                              <div class="items-descr-name">
                                 <h2>Netanyahu To Address UN With Families Of Hamas Hostages</h2>
                              </div>
                              <div class="items-descr-rating-setting">
                                 <div class="items-descr-rating">								 
                                    <i class="fa-solid fa-star"></i>
                                 </div>
                                 <div class="items-setting">
                                    <div class="dropdown">
                                       <button class="dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-gear"></i></button>
                                       <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                                          <li><a class="dropdown-item" href="#">Action</a></li>
                                          <li><a class="dropdown-item" href="#">Another action</a></li>
                                          <li><a class="dropdown-item" href="#">Something else here</a></li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <ul class="stoke-details">
                              <li>2in stoke</li>
                              <li>$52.00</li>
                              <li>05394</li>
                              <li>Auto-renews Jan 20, 2020 </li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
			     <!-------------------- list-view-end------------------------- -->
			   <!-------------------- grid-view-start------------------------- -->
			   <div class="row">
				   <div class="col-lg-6">
					  <div class="product-items-grid border mb-2">
						 <div class="product-img">
							<img src="https://threecranesgallery.itiffyconsultants.com/public/uploads/1726130627logo.jpg" alt="ThreeCranesGallery"/>
						 </div>
						 <div class="product-grid-details">
							<div class="p-3">
							   <h2>Netanyahu To Address UN With Families Of Hamas Hostages</h2>
							   <div class="grid-stoke-details">
								  <p>2in stoke</p>
								  <p>$52.00</p>
								  <p>05394</p>
								  <p>Auto-renews Jan 20, 2020 </p>
							   </div>
							</div>
							<div class="product-grid-footer border-top">
							   <ul>
								  <li>
									 <div class="form-check">
										<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault2">
									 </div>
								  </li>
								  <li><i class="fa-solid fa-star"></i></li>
								  <li class="items-setting">
									 <div class="dropdown">
										<button class="dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-gear"></i></button>
										<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
										   <li><a class="dropdown-item" href="#">Action</a></li>
										   <li><a class="dropdown-item" href="#">Another action</a></li>
										   <li><a class="dropdown-item" href="#">Something else here</a></li>
										</ul>
									 </div>
								  </li>
							   </ul>
							</div>
						 </div>
					  </div>
				   </div>
				   <div class="col-lg-6">
					  <div class="product-items-grid border mb-2">
						 <div class="product-img">
							<img src="https://threecranesgallery.itiffyconsultants.com/public/uploads/1726130627logo.jpg" alt="ThreeCranesGallery"/>
						 </div>
						 <div class="product-grid-details">
							<div class="p-3">
							   <h2>Netanyahu To Address UN With Families Of Hamas Hostages</h2>
							   <div class="grid-stoke-details">
								  <p>2in stoke</p>
								  <p>$52.00</p>
								  <p>05394</p>
								  <p>Auto-renews Jan 20, 2020 </p>
							   </div>
							</div>
							<div class="product-grid-footer border-top">
							   <ul>
								  <li>
									 <div class="form-check">
										<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault2">
									 </div>
								  </li>
								  <li><i class="fa-solid fa-star"></i></li>
								  <li class="items-setting">
									 <div class="dropdown">
										<button class="dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-gear"></i></button>
										<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
										   <li><a class="dropdown-item" href="#">Action</a></li>
										   <li><a class="dropdown-item" href="#">Another action</a></li>
										   <li><a class="dropdown-item" href="#">Something else here</a></li>
										</ul>
									 </div>
								  </li>
							   </ul>
							</div>
						 </div>
					  </div>
				   </div>
				</div>
				  <!-------------------- grid-view-end------------------------- -->
            </div>
         </div>
         <div class="col-lg-4">
            <div class="product-right-side border">
               <div class="product-right-side-top-1">
                  <ul>
                     <li>
                        <div class="form-check form-switch">
                           <label class="form-check-label" for="flexSwitchCheckChecked">Stats</label>
                           <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                        </div>
                     </li>
                     <li>
                        <div class="shop-tab">
                           <a href="#" >
                              <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="grid-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-grid-2 ">
                                 <path fill="currentColor" d="M224 80c0-26.5-21.5-48-48-48L80 32C53.5 32 32 53.5 32 80l0 96c0 26.5 21.5 48 48 48l96 0c26.5 0 48-21.5 48-48l0-96zm0 256c0-26.5-21.5-48-48-48l-96 0c-26.5 0-48 21.5-48 48l0 96c0 26.5 21.5 48 48 48l96 0c26.5 0 48-21.5 48-48l0-96zM288 80l0 96c0 26.5 21.5 48 48 48l96 0c26.5 0 48-21.5 48-48l0-96c0-26.5-21.5-48-48-48l-96 0c-26.5 0-48 21.5-48 48zM480 336c0-26.5-21.5-48-48-48l-96 0c-26.5 0-48 21.5-48 48l0 96c0 26.5 21.5 48 48 48l96 0c26.5 0 48-21.5 48-48l0-96z" class=""></path>
                              </svg>
                           </a>
                           <a href="#" >
                           <i class="fa-solid fa-list"></i>
                           </a>
                        </div>
                     </li>
                  </ul>
               </div>
               <div class="row">
                  <div class="col-lg-12 mb-3">
                     <label for="exampleInputEmail1" class="form-label">Sort</label>
                     <select class="form-select" aria-label="Default select example">
                        <option selected="">None</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                     </select>
                  </div>
                  <div class="col-lg-12 mb-3">
                     <h5>Listing Status</h5>
                     <div class="renuel-radio mb-2">
                        <input type="radio" id="Status1" name="radio-group" checked="">
                        <label for="Status1">Active 588</label>
                     </div>
                     <div class="renuel-radio mb-2">
                        <input type="radio" id="Status2" name="radio-group" >
                        <label for="Status2">Draft 55</label>
                     </div>
                     <div class="renuel-radio mb-2">
                        <input type="radio" id="Status3" name="radio-group" >
                        <label for="Status3">Expire 5</label>
                     </div>
                     <div class="renuel-radio mb-2">
                        <input type="radio" id="Status4" name="radio-group" >
                        <label for="Status4">Sold out 10</label>
                     </div>
                     <div class="renuel-radio mb-2">
                        <input type="radio" id="Status5" name="radio-group" >
                        <label for="Status5">Inactive 10</label>
                     </div>
                  </div>
                  <div class="col-lg-12 mb-3">
                     <ul class="feature-listing">
                        <li>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                              <label class="form-check-label" for="flexCheckDefault">
                              Default checkbox
                              </label>
                           </div>
                        </li>
                        <li>
                           <a href="#">Manage</a>
                        </li>
                     </ul>
                  </div>
                  <div class="col-lg-12 mb-3">
                     <label for="exampleInputEmail1" class="form-label">Search Factors</label>
                     <select class="form-select" aria-label="Default select example">
                        <option selected="">-None-</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                     </select>
                  </div>
                  <div class="col-lg-12 mb-3">
                     <ul class="feature-listing">
                        <li>
                           <label for="exampleInputEmail1" class="form-label">Sections</label>
                        </li>
                        <li> <a href="#">Manage</a></li>
                     </ul>
                     <select class="form-select" aria-label="Default select example">
                        <option selected="">-None-</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                     </select>
                  </div>
                  <div class="col-lg-12 mb-3">
                     <ul class="feature-listing">
                        <li>
                           <label for="exampleInputEmail1" class="form-label">Shipping Profile</label>
                        </li>
                        <li> <a href="#">Manage</a></li>
                     </ul>
                     <select class="form-select" aria-label="Default select example">
                        <option selected="">-None-</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                     </select>
                  </div>
                  <div class="col-lg-12 mb-3">
                     <ul class="feature-listing">
                        <li>
                           <label for="exampleInputEmail1" class="form-label">Return & exchange Policies</label>
                        </li>
                        <li> <a href="#">Manage</a></li>
                     </ul>
                     <select class="form-select" aria-label="Default select example">
                        <option selected="">-None-</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                     </select>
                  </div>
                  <div class="col-lg-12 mb-2">
                     <h5>Listing Videos</h5>
                     <div class="renuel-radio mb-2">
                        <input type="radio" id="video1" name="radio-group" checked="">
                        <label for="video1">All 58</label>
                     </div>
                     <div class="renuel-radio mb-2">
                        <input type="radio" id="video2" name="radio-group" >
                        <label for="video2">With video 0</label>
                     </div>
                     <div class="renuel-radio mb-2">
                        <input type="radio" id="video3" name="radio-group" >
                        <label for="video3">With out video 5</label>
                     </div>
                  </div>
                  <div class="col-lg-12 ">
                     <label for="exampleInputEmail1" class="form-label">Tags</label>
                     <select class="form-select" aria-label="Default select example">
                        <option selected="">-None-</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                     </select>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
