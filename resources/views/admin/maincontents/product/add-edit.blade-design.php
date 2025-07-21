<?php
   use App\Models\Category;
   use App\Models\Product;
   use App\Models\ProductImage;
   use App\Models\ProductAttribute;
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
</div>
<!-- End Page Title -->
<section class="section product-list">
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
   <div class="row">
      <div class="col-lg-12">
         <ul id="menu" class="top-listing-nav">
            <li class="active">
               <a href="#about-new">About</a>
            </li>
            <li >
               <a href="#Price-new">Price & Inventory </a>
            </li>
            <li>
               <a href="#variations-new">Variations </a>
            </li>
            <li>
               <a href="#details-new">Details  </a>
            </li>
            <li>
               <a href="#shipping-new">Shipping  </a>
            </li>
            <li>
               <a href="#settings-new"><i class="fa fa-cog" aria-hidden="true"></i>
               Settings  </a>
            </li>
         </ul>
      </div>
	  </div>
      <div class="card about-new" id="about-new">
         <div class="card-body ">
		  <div class="row">
            <div class="col-lg-12 col-md-12 mb-3">
               <h2 class="card-title">About </h2>
               <p >Tell the world all about your item and why they’ll love it.</p>
            </div>
            <div class="col-lg-12  col-md-12 mb-3">
               <h5 class="sub-title pt-2">
                  Title 
               </h5>
               <p class="mb-3">Include keywords that buyers would use to search for this item.</p>
               <input type="email" name="email" class="form-control" id="email" required="">
            </div>
            <div class="col-lg-12 col-md-12 mb-3">
               <div class="upload">
                  <form>
                     <fieldset class="upload_dropZone text-center mb-3 p-4">
                        <p class="small my-2">Drag &amp; Drop <i>or</i></p>
                        <input id="upload_image_background" data-post-name="image_background"  class="position-absolute invisible" type="file" multiple accept="image/jpeg, image/png, image/svg+xml" />
                        <label class="btn btn-upload mb-3 mt-2" for="upload_image_background">+ Add up to 10 photos and 1 video</label>
                        <div class="upload_gallery d-flex flex-wrap justify-content-center gap-3 mb-0"></div>
                     </fieldset>
                  </form>
               </div>
            </div>
            <div class="col-lg-12  col-md-12 mb-3">
               <h5 class="sub-title">
                  Description<span style="color:red;">*</span>
               </h5>
               <p class="mb-3">Include keywords that buyers would use to search for this item.</p>
               <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>
			</div>
            <div class="row align-items-center mb-3">
               <div class="col-lg-9  col-md-8 col-sm-12">
                  <h5 class="sub-title ">
                     Personalization<span style="color:red;">*</span>
                  </h5>
                  <p>Collect personalized information for this listing.</p>
               </div>
               <div class="col-lg-3  col-md-4 col-sm-12">
                  <a class="new-btn-style" href="">+ Add personlize</a>
               </div>
            </div>
         </div>
      </div>
      <div class="card Price-new" id="Price-new">
         <div class="card-body ">
		  <div class="row">
            <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
               <h2 class="card-title">
                  Price & Inventory 
               </h2>
               <p class="mb-3">Set a price for your item and indicate how many are available for sale.</p>
               <div class="row">
                  <div class="col-lg-3 mb-3">
                     <label for="exampleInputEmail1" class="form-label">Price<span style="color:red;">*</span></label>
                     <input type="text" class="form-control" placeholder="$">
                  </div>
               </div>
            </div>
			</div>
            <div class="row align-items-center mb-3">
               <div class="col-lg-10  col-md-9 col-sm-12">
                  <h5 class="sub-title">
                     Let buyers make offers on this listing
                  </h5>
                  <p class="mb-3">Getting offers from buyers can help you learn where the pricing “sweet spot” is to attract shoppers and still protect your bottom line.</p>
                  <p>
                     You’ll receive offers for up to 40% off<i class="fa fa-question-circle px-1" aria-hidden="true"></i>
                  </p>
               </div>
               <div class="col-lg-2  col-md-3 col-sm-12">
                  <div class="form-check form-switch form-switch-lg">
                     <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                  </div>
               </div>
            </div>
			 <div class="row">
            <div class="col-lg-3  col-md-12 col-sm-12 mb-3">
               <label for="exampleInputEmail1" class="form-label">Quantity<span style="color:red;">*</span></label>
               <input type="text" class="form-control" >
            </div>
			</div>
			 <div class="row">
            <div class="col-lg-12  col-md-12 col-sm-12 mt-2 ">
               <a class="new-btn-style add-sku bg-new-btn" href="">+Add SKU</a>
            </div>
			</div>
         </div>
      </div>
      <div class="card variations-new" id="variations-new">
         <div class="card-body">
		  <div class="row">
            <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
               <h2 class="card-title ">
                  Variations 
               </h2>
               <p>Choose a category first.</p>
            </div>
			</div>
         </div>
      </div>
      <div class="card details-new" id="details-new">
         <div class="card-body row">
		  <div class="row">
            <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
               <h2 class="card-title ">
                  Details 
               </h2>
               <p>Share a few more specifics about your item to make it easier to find in search, and to help buyers know what to expect.</p>
            </div>
			</div>
		<div class="row">
            <div class="col-lg-12 mb-3">
               <label for="exampleInputEmail1" class="form-label">Core details<span style="color:red;">*</span></label>
               <div class="core-product-list border rounded ">
                  <div class="row align-items-center ">
                     <div class="col-lg-9  col-md-8 col-sm-12">
                        <div class="core-product-items-block ">
                           <div class="core-product-image">
                              <img src="https://threecranesgallery.itiffyconsultants.com/public/uploads/1726130627logo.jpg" alt="ThreeCranesGallery" style="width: 100%; height:100px;">
                           </div>
                           <div class="core-product-description">
                              <h5 class="sub-title">
                                 description
                              </h5>
                              <p class="mb-3">Include keywords that buyers would use to search for this item.</p>
                              <p>Include keywords that buyers would use to search for this item.</p>
                              <p>Include keywords that buyers would use to search for this item.</p>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-3  col-md-4 col-sm-12">
                        <a class="new-btn-style bg-new-btn" href="">Change</a>
                     </div>
                  </div>
               </div>
            </div>
			</div>
			 <div class="row">
            <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
               <h5 class="sub-title">
                  Category<span style="color:red;">*</span>
               </h5>
               <div id="search-wrapper" class="border rounded-4 category-search mb-5">
                  <i class="search-icon fas fa-search"></i>
                  <input type="text" id="search" placeholder="Search for a category, e.g. Hats, Rings, Pillows, etc.">
               </div>
               <hr class=" mb-3">
            </div>
            <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
               <h5 class="sub-title ">
                  Attributes <i class="fa fa-question-circle px-1" aria-hidden="true"></i> 
               </h5>
               <p >
                  Choose a category first.
               </p>
            </div>
            <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
               <h5 class="sub-title">
                  Tags  
               </h5>
               <p >Add up to 13 tags to help people search for your listings.</p>
            </div>
			</div>
            <div class="row align-items-center mb-3">
               <div class="col-lg-9  col-md-8 col-sm-12">
                  <input type="text" name="text" class="form-control" id="text" required="" placeholder="Shape, color, style, function, etc.">
               </div>
               <div class="col-lg-3  col-md-4 col-sm-12">
                  <a class="add-btn" href="">Add</a>
               </div>
            </div>
			<div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
               <p >13 left</p>
            </div>
		
			<div class="col-lg-12 mb-3  col-md-12 col-sm-12">
               <h5 class="sub-title ">
                  Materials  
               </h5>
               <p >Buyers value transparency—tell them what’s used to make your item.</p>
            </div>
			</div>
            <div class="row align-items-center mb-3">
               <div class="col-lg-9  col-md-8 col-sm-12">
                  <input type="text" name="text" class="form-control" id="text" required="" placeholder="Ingredients, components, etc.">
               </div>
               <div class="col-lg-3  col-md-4 col-sm-12">
                  <a class="add-btn" href="">Add</a>
               </div>
            </div>
			<div class="row">
            <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
               <p>13 left</p>
            </div>
			</div>
         </div>
      </div>
      <div class="card shipping-new" id="shipping-new">
         <div class="card-body ">
		 <div class="row">
            <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
               <h2 class="card-title">
                  Shipping 
               </h2>
               <p>Give shoppers clear expectations about delivery time and cost by making sure your shipping info is accurate, including the shipping profile and your
                  order processing schedule. You can make updates any time in <a href="" style="color: #444444;text-decoration:underline;">Shipping settings.</a>
               </p>
            </div>
            <div class="col-lg-12  col-md-12 col-sm-12 mt-4">
               <div class="Shipping-option border rounded mb-3 ">
                  <div class="row align-items-center justify-content-between">
                     <div class="col-lg-6  col-md-4 col-sm-12">
                        <h5 class="sub-title m-0">
                           Shipping option<span style="color:red;">*</span>  
                        </h5>
                     </div>
                     <div class="col-lg-3  col-md-4 col-sm-12">
                        <a class="add-btn" href="">+ Create option</a>
                     </div>
                     <div class="col-lg-3  col-md-4 col-sm-12">
                        <a class="new-btn-style" href="">Select profile</a>
                     </div>
                  </div>
               </div>
            </div>
			</div>
            <div class="row align-items-center mb-3">
               <div class="col-lg-8  col-md-8 col-sm-12">
                  <h5 class="sub-title ">
                     Item weight and size
                  </h5>
               </div>
               <div class="col-lg-4  col-md-4 col-sm-12">
                  <a class="new-btn-style" href="">+ Add item weight and size</a>
               </div>
            </div>
			<div class="row">
            <div class="col-lg-12  col-md-12 col-sm-12 mt-4">
               <div class="shipping-price-info">
                  <div class="info-icon">
                     <i class="fa-solid fa-circle-info"></i>
                  </div>
                  <div class="info-details">
                     <h5 class="sub-title ">
                        We're still working on shipping price preview—stay tuned!
                     </h5>
                     <p>For now you can calculate shipping prices <a href="" style="color: #444444;text-decoration:underline;">here.</a></p>
                  </div>
               </div>
            </div>
			</div>
         </div>
      </div>
      <div class="card settings-new" id="settings-new">
         <div class="card-body ">
		 <div class="row">
            <div class="col-lg-12  col-md-12 col-sm-12 mb-3">
               <h2 class="card-title">
                  Settings 
               </h2>
               <p>Choose how this listing will display in your shop, how it will renew, and if you want it to be promoted in Etsy Ads.</p>
            </div>
            <div class="col-lg-12 mb-3  col-md-12 col-sm-12">
               <h5 class="sub-title pt-2">
                  Returns and exchanges&nbsp;<u style="font-weight:300;font-size:14px;">Pree field</u>  
               </h5>
               <div class="return-option border rounded  ">
                  <div class="row align-items-center justify-content-between">
                     <div class="col-lg-9  col-md-8 col-sm-12">
                        <h5 class="sub-title mb-3">
                           Returns and exchanges <i class="fa-solid fa-calendar-days"></i> 30 days
                        </h5>
                        <p>Buyer is responsible for return shipping costs and any loss in value if an item isn't returned in Change policy
                           original condition.
                        </p>
                     </div>
                     <div class="col-lg-3  col-md-4 col-sm-12">
                        <a class="new-btn-style bg-new-btn" href="">Change policy</a>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-5  col-md-12 col-sm-12 mb-3">
               <label for="exampleInputEmail1" class="form-label">Shop section</label>
               <p class="mb-3">Use shop sections to organize your products into groups shoppers can explore.</p>
               <select class="form-select" aria-label="Default select example">
                  <option selected>None</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
               </select>
            </div>
			</div>
            <div class="row align-items-center mb-4">
               <div class="col-lg-10  col-md-9 col-sm-12">
                  <h5 class="sub-title">
                     Feature this listing
                  </h5>
                  <p >Showcase this listing at the top of your shop's homepage to make it stand out.</p>
               </div>
               <div class="col-lg-2  col-md-3 col-sm-12">
                  <div class="form-check form-switch form-switch-lg">
                     <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked="">
                  </div>
               </div>
            </div>
            <div class="row align-items-center mb-4">
               <div class="col-lg-10 col-md-9 col-sm-12">
                  <h5 class="sub-title">
                     Etsy Ads
                  </h5>
                  <p >Promote this listing on Etsy as part of your Etsy Ads campaign.</p>
               </div>
               <div class="col-lg-2 col-md-3 col-sm-12">
                  <div class="form-check form-switch form-switch-lg">
                     <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked="">
                  </div>
               </div>
            </div>
			<div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
               <h5 class="sub-title">
                  Renewal options<span style="color:red;">*</span>
               </h5>
               <p >Each renewal lasts for four months or until the listing sells out. <a href="#" style="text-decoration: underline;color: #000;">Get more details on auto-renewing.</a></p>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
               <div class="renuel-radio mb-3">
                  <input type="radio" id="test1" name="radio-group" checked>
                  <label  for="test1">Automatic</label>
                  <p>Each renewal lasts for four months or until the listing sells out.</p>
               </div>
               <div class="renuel-radio">
                  <input type="radio" id="test2" name="radio-group" >
                  <label  for="test2">Manual</label>
                  <p>I'll renew expired listings myself.</p>
               </div>
            </div>
			</div>
         </div>
      </div>
	  <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
             <ul class="footer-btn-group">
               <li><a href="">Preview </a></li>
               <li><a href="">save as draft </a></li>
               <li><a class="bg" href="">Publish </a></li>
            </ul>
         </div>
      </div>
      <div class="col-lg-12">
         <h5 class="card-title">
            <a href="<?=url('admin/' . $controllerRoute . '/add/')?>" class="btn btn-outline-success btn-sm">Add <?=$module['title']?></a>
         </h5>
      </div>
   </div>
</section>
<script>
   console.clear();
    ('use strict');
     (function () {
    
    'use strict';
    const preventDefaults = event => {
      event.preventDefault();
      event.stopPropagation();
    };
    
    const highlight = event =>
      event.target.classList.add('highlight');
    
    const unhighlight = event =>
      event.target.classList.remove('highlight');
    
    const getInputAndGalleryRefs = element => {
      const zone = element.closest('.upload_dropZone') || false;
      const gallery = zone.querySelector('.upload_gallery') || false;
      const input = zone.querySelector('input[type="file"]') || false;
      return {input: input, gallery: gallery};
    }
    
    const handleDrop = event => {
      const dataRefs = getInputAndGalleryRefs(event.target);
      dataRefs.files = event.dataTransfer.files;
      handleFiles(dataRefs);
    }
    
    
    const eventHandlers = zone => {
    
      const dataRefs = getInputAndGalleryRefs(zone);
      if (!dataRefs.input) return;
    
      // Prevent default drag behaviors
      ;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
        zone.addEventListener(event, preventDefaults, false);
        document.body.addEventListener(event, preventDefaults, false);
      });
    
      // Highlighting drop area when item is dragged over it
      ;['dragenter', 'dragover'].forEach(event => {
        zone.addEventListener(event, highlight, false);
      });
      ;['dragleave', 'drop'].forEach(event => {
        zone.addEventListener(event, unhighlight, false);
      });
    
      // Handle dropped files
      zone.addEventListener('drop', handleDrop, false);
    
      // Handle browse selected files
      dataRefs.input.addEventListener('change', event => {
        dataRefs.files = event.target.files;
        handleFiles(dataRefs);
      }, false);
    
    }
    
    
    // Initialise ALL dropzones
    const dropZones = document.querySelectorAll('.upload_dropZone');
    for (const zone of dropZones) {
      eventHandlers(zone);
    }
    
    
    // No 'image/gif' or PDF or webp allowed here, but it's up to your use case.
    // Double checks the input "accept" attribute
    const isImageFile = file => 
      ['image/jpeg', 'image/png', 'image/svg+xml'].includes(file.type);
    
    
    function previewFiles(dataRefs) {
      if (!dataRefs.gallery) return;
      for (const file of dataRefs.files) {
        let reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = function() {
          let img = document.createElement('img');
          img.className = 'upload_img mt-2';
          img.setAttribute('alt', file.name);
          img.src = reader.result;
          dataRefs.gallery.appendChild(img);
        }
      }
    }
    
    const imageUpload = dataRefs => {
    
      // Multiple source routes, so double check validity
      if (!dataRefs.files || !dataRefs.input) return;
    
      const url = dataRefs.input.getAttribute('data-post-url');
      if (!url) return;
    
      const name = dataRefs.input.getAttribute('data-post-name');
      if (!name) return;
    
      const formData = new FormData();
      formData.append(name, dataRefs.files);
    
      fetch(url, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        console.log('posted: ', data);
        if (data.success === true) {
          previewFiles(dataRefs);
        } else {
          console.log('URL: ', url, '  name: ', name)
        }
      })
      .catch(error => {
        console.error('errored: ', error);
      });
    }
    
    
    // Handle both selected and dropped files
    const handleFiles = dataRefs => {
    
      let files = [...dataRefs.files];
    
      // Remove unaccepted file types
      files = files.filter(item => {
        if (!isImageFile(item)) {
          console.log('Not an image, ', item.type);
        }
        return isImageFile(item) ? item : null;
      });
    
      if (!files.length) return;
      dataRefs.files = files;
    
      previewFiles(dataRefs);
      imageUpload(dataRefs);
    }
    
    })();
</script>
