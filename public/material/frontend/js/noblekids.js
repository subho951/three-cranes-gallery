var origin   = window.location.origin;
if(origin == 'https://noble-kids.itiffyconsultants.com'){
    var base_url    = 'https://noble-kids.itiffyconsultants.com/api/';
    var baseURL     = 'https://noble-kids.itiffyconsultants.com/';
} else {
    var base_url    = 'http://localhost/qarp-shop/api/';
    var baseURL     = 'http://localhost/qarp-shop/';
}
var projectKey  = '4e1c3ee6861ac425437fa8b662651cde';
var source      = 'WEB';
var dataJson    = {};
dataJson.key    = projectKey;
dataJson.source = source;
$(function() {
    switch (page) {
        
    }
});
$("#subscriptionForm").submit(function (e) {
	e.preventDefault();
    let flag = commonFormChecking(true, 'requiredContact');
    if (flag) {
		if (flag) {
        	var formData = new FormData(this);
            dataJson.email      = $('#email').val();
			$.ajax({
				type: "POST",
				url: base_url + "store-subscriber",
				data: JSON.stringify(dataJson),
				cache: false,
				contentType: false,
				processData: false,
				dataType: "JSON",
				beforeSend: function () {
					$("#subscriptionForm").loading();
				},
				success: function (res) {
					$("#subscriptionForm").loading("stop");					
					if(res.status){
						$('#subscriptionForm').trigger("reset");
						toastAlert("success", res.message);
					}else{
						$('#subscriptionForm').trigger("reset");
						toastAlert("error", res.message);
					}
				},
                error:function (xhr, ajaxOptions, thrownError){
                    $("#subscriptionForm").loading("stop");
                    var res = xhr.responseJSON;
                    if(!res.status) {
                    	$('#subscriptionForm').trigger("reset");
                        toastAlert("error", res.message);
                    }
                }
			});
		}
    }
});
$("#contact-form").submit(function (e) {
	e.preventDefault();
    let flag = commonFormChecking(true, 'requiredContact');
    if (flag) {
		if (flag) {
        	var formData 			= new FormData(this);
            dataJson.name      		= $('#name').val();
            dataJson.email      	= $('#email').val();
            dataJson.phone      	= $('#phone').val();
            dataJson.subject      	= $('#subject').val();
            dataJson.message      	= $('#message').val();
			$.ajax({
				type: "POST",
				url: base_url + "store-contact-enquiry",
				data: JSON.stringify(dataJson),
				cache: false,
				contentType: false,
				processData: false,
				dataType: "JSON",
				beforeSend: function () {
					$("#contact-form").loading();
				},
				success: function (res) {
					$("#contact-form").loading("stop");					
					if(res.status){
						$('#contact-form').trigger("reset");
						toastAlert("success", res.message);
					}else{
						$('#contact-form').trigger("reset");
						toastAlert("error", res.message);
					}
				},
                error:function (xhr, ajaxOptions, thrownError){
                    $("#contact-form").loading("stop");
                    var res = xhr.responseJSON;
                    if(!res.status) {
                        $('#contact-form').trigger("reset");
                        toastAlert("error", res.message);
                    }
                }
			});
		}
    }
});
$("#signup_form").submit(function (e) {
    e.preventDefault();
    let flag = commonFormChecking(true, 'requiredCheck');
    if (flag) {
        flag = checkPassword('password', 'confirmPassword');
        if (flag) {
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: base_url + "signup",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function () {
                    $("#signup_form").loading();
                },
                success: function (res) {
                    $("#signup_form").loading("stop");             
                    if(res.status){
                        $('#signup_form').trigger("reset");
                        toastAlert("success", res.message, true, res.data.redirectUrl);
                    }else{
                        toastAlert("error", res.message);
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    $("#signup_form").loading("stop");
                    var res = xhr.responseJSON;
                    if(!res.status) {
                        // $('#signup_form').trigger("reset");
                        toastAlert("error", res.message);
                    }
                }
            });
        }
    }
});
$("#fpwd_form").submit(function (e) {
    e.preventDefault();
    let flag = commonFormChecking(true, 'requiredCheck');
    if (flag) {
        if (flag) {
            var formData = new FormData(this);
            dataJson.email              = $('#email').val();
            $.ajax({
                type: "POST",
                url: base_url + "forgot-password",
                data: JSON.stringify(dataJson),
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function () {
                    $("#fpwd_form").loading();
                },
                success: function (res) {
                    $("#fpwd_form").loading("stop");             
                    if(res.status){
                        $('#fpwd_form').trigger("reset");
                        localStorage.setItem('user_id', res.data.id);
                        toastAlert("success", res.message, true, res.data.redirectUrl);
                    }else{
                        toastAlert("error", res.message);
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    $("#fpwd_form").loading("stop");
                    var res = xhr.responseJSON;
                    if(!res.status) {
                        $('#fpwd_form').trigger("reset");
                        toastAlert("error", res.message);
                    }
                }
            });
        }
    }
});
$("#validateotp_form").submit(function (e) {
    e.preventDefault();
    let flag = commonFormChecking(true, 'requiredCheck');
    if (flag) {
        if (flag) {
            var formData = new FormData(this);
            dataJson.id                 = localStorage.getItem('user_id');
            var otp1                    = $('#otp1').val();
            var otp2                    = $('#otp2').val();
            var otp3                    = $('#otp3').val();
            var otp4                    = $('#otp4').val();
            dataJson.otp                = otp1 + otp2 + otp3 + otp4;
            $.ajax({
                type: "POST",
                url: base_url + "validate-OTP",
                data: JSON.stringify(dataJson),
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function () {
                    $("#validateotp_form").loading();
                },
                success: function (res) {
                    $("#validateotp_form").loading("stop");             
                    if(res.status){
                        $('#validateotp_form').trigger("reset");
                        toastAlert("success", res.message, true, res.data.redirectUrl);
                    }else{
                        toastAlert("error", res.message);
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    $("#validateotp_form").loading("stop");
                    var res = xhr.responseJSON;
                    if(!res.status) {
                        $('#validateotp_form').trigger("reset");
                        toastAlert("error", res.message);
                    }
                }
            });
        }
    }
});
$("#resetpwd_form").submit(function (e) {
    e.preventDefault();
    let flag = commonFormChecking(true, 'requiredCheck');
    if (flag) {
        flag = checkPassword('password', 'confirmPassword');
        if (flag) {
            var formData                = new FormData(this);
            dataJson.id                 = localStorage.getItem('user_id');
            dataJson.password           = $('#password').val();
            dataJson.confirm_password   = $('#confirmPassword').val();
            $.ajax({
                type: "POST",
                url: base_url + "reset-password",
                data: JSON.stringify(dataJson),
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function () {
                    $("#resetpwd_form").loading();
                },
                success: function (res) {
                    $("#resetpwd_form").loading("stop");             
                    if(res.status){
                        $('#resetpwd_form').trigger("reset");
                        localStorage.removeItem('user_id');
                        toastAlert("success", res.message, true, res.data.redirectUrl);
                    }else{
                        toastAlert("error", res.message);
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    $("#resetpwd_form").loading("stop");
                    var res = xhr.responseJSON;
                    if(!res.status) {
                        $('#resetpwd_form').trigger("reset");
                        toastAlert("error", res.message);
                    }
                }
            });
        }
    }
});
$("#signin_form").submit(function (e) {
    e.preventDefault();
    let flag = commonFormChecking(true, 'requiredCheck');
    if (flag) {
        if (flag) {
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: base_url + "signin",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function () {
                    $("#signin_form").loading();
                },
                success: function (res) {
                    $("#signin_form").loading("stop");             
                    if(res.status){
                        localStorage.setItem('user_id', res.data.user_id);
                        localStorage.setItem('name', res.data.name);
                        localStorage.setItem('email', res.data.email);
                        localStorage.setItem('phone', res.data.phone);
                        localStorage.setItem('device_type', res.data.device_type);
                        localStorage.setItem('device_token', res.data.device_token);
                        localStorage.setItem('app_access_token', res.data.app_access_token);
                        $('#signin_form').trigger("reset");
                        toastAlert("success", res.message, true, baseURL + 'mydashboard');
                    }else{
                        toastAlert("error", res.message);
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    $("#signin_form").loading("stop");
                    var res = xhr.responseJSON;
                    if(!res.status) {
                        $('#signin_form').trigger("reset");
                        toastAlert("error", res.message);
                    }
                }
            });
        }
    }
});
$("#signOut").click(function (e) {
    e.preventDefault();
    dataJson.app_access_token    = app_access_token;
    $.ajax({
        type: "POST",
        url: base_url + "signout",
        data: JSON.stringify(dataJson),
        // cache: false,
        // contentType: false,
        // processData: false,
        dataType: "JSON",
        beforeSend: function () {
            $("#signOut").loading();
        },
        success: function (res) {
            $("#signOut").loading("stop");             
            if(res.status){
                localStorage.removeItem('user_id');
                localStorage.removeItem('name');
                localStorage.removeItem('email');
                localStorage.removeItem('phone');
                localStorage.removeItem('device_type');
                localStorage.removeItem('device_token');
                localStorage.removeItem('app_access_token');
                toastAlert("success", res.message, true, baseURL + 'signin');
            }else{
                toastAlert("error", res.message);
            }
        },
        error:function (xhr, ajaxOptions, thrownError){
            $("#signOut").loading("stop");
            var res = xhr.responseJSON;
            if(!res.status) {
                toastAlert("error", res.message);
            }
        }
    });
});
$("#cp_form").submit(function (e) {
    e.preventDefault();
    let flag = commonFormChecking(true, 'requiredCheckCP');
    if (flag) {
        flag = checkPassword('pass_log_id', 'confirm_password');
        if (flag) {
            var formData                = new FormData(this);
            dataJson.old_password       = $('#old_password').val();
            dataJson.new_password       = $('#pass_log_id').val();
            dataJson.confirm_password   = $('#confirm_password').val();
            $.ajax({
                type: "POST",
                url: base_url + "change-password",
                data: JSON.stringify(dataJson),
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function () {
                    $("#cp_form").loading();
                },
                success: function (res) {
                    $("#cp_form").loading("stop");             
                    if(res.status){
                        $('#cp_form').trigger("reset");
                        toastAlert("success", res.message, true, res.data.redirectUrl);
                    }else{
                        toastAlert("error", res.message);
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    $("#cp_form").loading("stop");
                    var res = xhr.responseJSON;
                    if(!res.status) {
                        $('#cp_form').trigger("reset");
                        toastAlert("error", res.message);
                    }
                }
            });
        }
    }
});
$("#profile_form").submit(function (e) {
    e.preventDefault();
    let flag = commonFormChecking(true, 'requiredCheckUF');
    if (flag) {
        if (flag) {
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: base_url + "update-profile",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function () {
                    $("#profile_form").loading();
                },
                success: function (res) {
                    $("#profile_form").loading("stop");             
                    if(res.status){
                        $('#profile_form').trigger("reset");
                        toastAlert("success", res.message, true, res.data.redirectUrl);
                    }else{
                        toastAlert("error", res.message);
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    $("#profile_form").loading("stop");
                    var res = xhr.responseJSON;
                    if(!res.status) {
                        // $('#profile_form').trigger("reset");
                        toastAlert("error", res.message);
                    }
                }
            });
        }
    }
});

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}