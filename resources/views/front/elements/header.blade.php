<div class="top-header">
  <div class="container">
      <div class="row">
          <div class="col">
              <div>
                  <p>We deliver to you <span><?=$generalSetting->topbar_text?></span></p>
              </div>
          </div>
      </div>
  </div>
</div>
<div class="header_sign">
  <div class="container">
      <div class="row">
          <div class="col-lg-4 col-md-4 col-sm-4">
              <div class="header_sign_content mb-3 mb-sm-0">
                  <a href="">Sign Up for Email</a>
              </div>
          </div>
          <div class="col-lg-8 col-md-8 col-sm-8">
              <div class="header_location">
                  <ul>
                      <li><i class="fa-solid fa-location-dot"></i></i>Our Store</li>
                      <ul>
                          <li> <i class="fa-solid fa-earth-americas"></i></li>
                          <li class="nav-item dropdown">
                              <a class="nav-link dropdown-toggle p-0" href="#" id="navbarDropdown" role="button"
                                  aria-expanded="false">
                                  IN ($)
                              </a>
                              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                  <li><a class="dropdown-item" href="#">Action</a></li>
                                  <li><a class="dropdown-item" href="#">Another action</a></li>
                              </ul>
                          </li>
                      </ul>

                      <li><i class="fa-solid fa-circle-user"></i>Account</li>
                  </ul>
              </div>
          </div>
      </div>
  </div>
</div>
<div class="mid-header">
  <div class="container">
      <div class="row align-items-center">
          <div class="col-lg-4 col-md-3 col-12">
              <div class="logo d-none d-md-block">
                  <a href="<?=url('/')?>">
                    <img alt="logo" class="logo img-fluid" src="<?=env('UPLOADS_URL').$generalSetting->site_logo?>">
                  </a>
              </div>
          </div>
          <div class="col-lg-8 col-md-9 col-12">
              <div class="search-box">
                  <div class="search-area">
                      <form action="">  <input type="text" class="form-control" placeholder="What are you looking for..."
                          value="">
                      </form>
                    
                      <button><img src="<?=env('FRONT_ASSETS_URL')?>images/search_icon.png" alt="logo"></button>
                  </div>
                  <ul>
                      <li><a href="#"> <img src="<?=env('FRONT_ASSETS_URL')?>images/heart_icon.png" alt="logo"></a></li>
                      <li><a href="#"> <img src="<?=env('FRONT_ASSETS_URL')?>images/cart_icon.png" alt="logo"></a></li>
                  </ul>
              </div>
          </div>
      </div>
  </div>
</div>
<div class="menu-header">
  <div class="container">
      <div class="row">
          <div class="col">
              <div class="main_menu">
                  <nav class="navbar navbar-expand-md">
                      <a class="navbar-brand d-block d-md-none" href="#">
                          <img alt="logo" class="logo img-fluid" src="<?=env('FRONT_ASSETS_URL')?>images/logo.png">
                      </a>
                      <div class="button_container d-block d-md-none" id="toggle" type="button"
                          data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                          aria-controls="navbarNavDropdown" aria-expanded="false"
                          aria-label="Toggle navigation">
                          <span class="top"></span>
                          <span class="middle"></span>
                          <span class="bottom"></span>
                          <span class="bottom-last"></span>
                      </div>
                      <div class="collapse navbar-collapse" id="navbarNavDropdown">
                          <ul class="navbar-nav">
                              <li class="nav-item dropdown">
                                  <a class="nav-link active dropdown-toggle" aria-current="page" href="#"
                                      id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown"
                                      aria-expanded="false">Home</a>
                                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                      <li><a class="dropdown-item" href="#">Action</a></li>
                                      <li><a class="dropdown-item" href="#">Another action</a></li>
                                      <li><a class="dropdown-item" href="#">Something else here</a>
                                      </li>
                                  </ul>
                              </li>
                              <li class="nav-item dropdown">
                                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                      role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                      Summer Wear</a>
                                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                      <li><a class="dropdown-item" href="#">Action</a></li>
                                      <li><a class="dropdown-item" href="#">Another action</a></li>
                                      <li><a class="dropdown-item" href="#">Something else here</a>
                                      </li>
                                  </ul>
                              </li>
                              <li class="nav-item dropdown">
                                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                      role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                      Winter Wear</a>
                                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                      <li><a class="dropdown-item" href="#">Action</a></li>
                                      <li><a class="dropdown-item" href="#">Another action</a></li>
                                      <li><a class="dropdown-item" href="#">Something else here</a>
                                      </li>
                                  </ul>
                              </li>
                              <li class="nav-item dropdown">
                                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                      role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                      Sculpture</a>
                                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                      <li><a class="dropdown-item" href="#">Action</a></li>
                                      <li><a class="dropdown-item" href="#">Another action</a></li>
                                      <li><a class="dropdown-item" href="#">Something else here</a>
                                      </li>
                                  </ul>
                              </li>
                              <li class="nav-item dropdown">
                                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                      role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                      What’s New</a>
                                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                      <li><a class="dropdown-item" href="#">Action</a></li>
                                      <li><a class="dropdown-item" href="#">Another action</a></li>
                                      <li><a class="dropdown-item" href="#">Something else here</a>
                                      </li>
                                  </ul>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" href="#" id="navbarDropdownMenuLink"
                                      role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                      Specials</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" href="#" id="navbarDropdownMenuLink"
                                      role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                      Contact</a>
                              </li>
                          </ul>
                      </div>
                  </nav>
              </div>
          </div>
      </div>
  </div>
</div>