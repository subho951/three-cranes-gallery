<section class="register-form-section login-form section-padding">
    <div class="container-xxl container-xl container-lg container-md container-sm container">
        <div class="row justify-content-center">
            <div class="col-2 col-lg-2 col-xl-2 col-md-2 col-sm-2"></div>
            <div class="col-8 col-lg-8 col-xl-8 col-md-8 col-sm-8">
                <div class="register-form-field">
                    <h3><?=$page_header?></h3>
                    <small class="text-danger">* All fields are mandatory</small>
                    @if(session('success_message'))
                        <h6 class="alert alert-success autohide">{{ session('success_message') }}</h6>
                    @endif
                    @if(session('error_message'))
                        <h6 class="alert alert-danger autohide">{{ session('error_message') }}</h6>
                    @endif
                    <form method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="form-outline mb-3">
                            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" class="form-control form-control-lg" name="email" required />
                        </div>
                        <div class="d-flex mt-4">
                            <button type="submit" class="btn common-btn">Submit</button>
                        </div>
                        <p class="reg-informaton mt-3 mb-0 ">
                            “Trouble logging in? Just type in your email, and we’ll help you reset your password.”
                        </p>
                    </form>
                </div>
            </div>
            <div class="col-2 col-lg-2 col-xl-2 col-md-2 col-sm-2"></div>
        </div>
    </div>
</section>
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