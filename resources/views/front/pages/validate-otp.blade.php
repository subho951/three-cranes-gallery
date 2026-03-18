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
                            <label class="form-label" for="otp1">OTP <span class="text-danger">*</span></label>
                            <input type="text" id="otp1" class="form-control form-control-lg" name="otp1" minlength="4" maxlength="4" placeholder="OTP" onkeypress="return isNumber(event)" required />
                        </div>
                        <!-- <div class="form-outline mb-3">
                            <label class="form-label" for="otp2">OTP 2 <span class="text-danger">*</span></label>
                            <input type="text" id="otp2" class="form-control form-control-lg" name="otp2" minlength="1" maxlength="1" min="0" oninput="moveFocus(2)" onkeypress="return isNumber(event)" required />
                        </div>
                        <div class="form-outline mb-3">
                            <label class="form-label" for="otp3">OTP 3 <span class="text-danger">*</span></label>
                            <input type="text" id="otp3" class="form-control form-control-lg" name="otp3" minlength="1" maxlength="1" min="0" oninput="moveFocus(3)" onkeypress="return isNumber(event)" required />
                        </div>
                        <div class="form-outline mb-3">
                            <label class="form-label" for="otp4">OTP 4 <span class="text-danger">*</span></label>
                            <input type="text" id="otp4" class="form-control form-control-lg" name="otp4" minlength="1" maxlength="1" min="0" oninput="moveFocus(4)" onkeypress="return isNumber(event)" required />
                        </div> -->
                        <div class="d-flex mt-4">
                            <button type="submit" class="btn common-btn">Validate</button>
                        </div>
                        <p class="reg-informaton mt-3 mb-0 ">
                            We’ve sent a 4-digit verification code to your email. Please
                            enter it below to confirm your account and continue
                            shopping. If you don’t see the email within a few minutes, be
                            sure to check your spam or junk folder.
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
<script type="text/javascript">
   function moveFocus(currentBox) {
      const nextBox = currentBox + 1;
      const maxBox = 4;  // Total number of OTP input boxes

      if (currentBox < maxBox) {
         const currentInput = document.getElementById('otp' + currentBox);
         const nextInput = document.getElementById('otp' + nextBox);

         if (currentInput.value.length === 1) {
            nextInput.focus();
         }
      }
   }
</script>