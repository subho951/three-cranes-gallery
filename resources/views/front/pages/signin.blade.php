<section class="register-form-section login-form section-padding">
    <div class="container-xxl container-xl container-lg container-md container-sm container">
        <div class="row justify-content-center">
            @if(session('success_message'))
                <h6 class="alert alert-success autohide">{{ session('success_message') }}</h6>
            @endif
            @if(session('error_message'))
                <h6 class="alert alert-danger autohide">{{ session('error_message') }}</h6>
            @endif
            <div class="col-12 col-lg- col-xl-6 col-md-12 col-sm-12">
                <div class="register-form-field">
                    <h3>Sign In</h3>
                    <form method="POST" action="<?=url('signin')?>" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_redirect" value="<?=$page_redirect?>">
                        <div class="register-form">
                            <div class="form-outline mb-3">
                                <label class="form-label" for="signin_email">Email*</label>
                                <input type="email" id="signin_email" placeholder="Enter Email" class="form-control form-control-lg" name="signin_email" required>
                            </div>
                            <div class="form-outline mb-3">
                                <label class="form-label" for="signin_password">Password*</label>
                                <input type="password" id="signin_password" placeholder="Enter Password" class="form-control form-control-lg" minlength="6" name="signin_password" />
                                <span class="flex justify-around items-center"
                                    style="position: absolute; top: 43px; right: 18px;">
                                    
                                </span>
                            </div>
                            <div class="log-btn-reme d-flex align-items-center mt-4">
                                <button type="submit" class="btn common-btn">Login</button>
                            </div>
                            <div class="form-outline mb-3">
                                <a class="small f-password" href="<?=url('forgot-password')?>">Forgot password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12 col-lg- col-xl-6 col-md-12 col-sm-12">
                <div class="register-form-field">
                    <h3>Sign Up</h3>
                    <small class="text-danger">All fields are mandatory</small>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="<?=url('/signup')?>">
                        @csrf
                        <div class="register-form">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-outline mb-3">
                                        <label for="first_name">First Name</label>
                                        <input class="form-control" placeholder="First Name" type="text" name="first_name" id="first_name" value="{{old('first_name')}}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-outline mb-3">
                                        <label for="last_name">Last Name</label>
                                        <input class="form-control" placeholder="Last Name" type="text" name="last_name" id="last_name" value="{{old('last_name')}}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-outline mb-3">
                                        <label for="email">Email</label>
                                        <input class="form-control" placeholder="Email" type="email" name="email" id="email" value="{{old('email')}}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-outline mb-3">
                                        <label for="phone">Phone Number</label>
                                        <input class="form-control" placeholder="Phone Number" maxlength="10" type="text" name="phone" id="phone" value="{{old('phone')}}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-outline mb-3">
                                        <label for="password">Password</label>
                                        <input class="form-control" placeholder="XXXXX" type="password" name="password" id="password" minlength="8" required>
                                        <span class="flex justify-around items-center" style="position: absolute; top: 43px; right: 18px;">
                                            
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-outline mb-3">
                                        <label for="confirm_password">Confirm Password</label>
                                        <input class="form-control" placeholder="XXXXX" type="password" name="confirm_password" id="confirm_password" minlength="8" required>
                                        <span class="flex justify-around items-center" style="position: absolute; top: 43px; right: 18px;">
                                            
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex regis-btn">
                                <button type="submit" class="btn common-btn">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>