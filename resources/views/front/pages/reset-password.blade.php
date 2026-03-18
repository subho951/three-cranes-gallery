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
                        <input type="hidden" name="id" value="<?=$id?>">
                        <div class="form-outline mb-3">
                            <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" id="password" class="form-control form-control-lg" name="password" minlength="8" placeholder="Password" required />
                            <span class="flex justify-around items-center"
                                style="position: absolute; top: 43px; right: 18px;">

                            </span>
                            <span class="toggle-password" onclick="togglePassword('password', this)"
                                style="position:absolute; top:43px; right:18px; cursor:pointer;">
                                👁
                            </span>
                        </div>
                        <div class="form-outline mb-3">
                            <label class="form-label" for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" id="confirm_password" class="form-control form-control-lg" name="confirm_password" minlength="8" placeholder="Confirm Password" required />
                            <span class="flex justify-around items-center"
                                style="position: absolute; top: 43px; right: 18px;">

                            </span>
                            <span class="toggle-password" onclick="togglePassword('confirm_password', this)"
                                style="position:absolute; top:43px; right:18px; cursor:pointer;">
                                👁
                            </span>
                        </div>
                        <div class="d-flex mt-4">
                            <button type="submit" class="btn common-btn">Reset</button>
                        </div>
                        <p class="reg-informaton mt-3 mb-0 ">Create a new password to secure your account and complete the process.
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
<script>
    function togglePassword(fieldId, icon) {
        const input = document.getElementById(fieldId);
        if (input.type === "password") {
            input.type = "text";
            icon.textContent = "🙈"; // change icon
        } else {
            input.type = "password";
            icon.textContent = "👁"; // change back
        }
    }
</script>