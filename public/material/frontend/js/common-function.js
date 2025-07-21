function toastAlert(type, message, redirectStatus = false, redirectUrl = ''){
    toastr.options = {
        "closeButton": true,
        "debug": true,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-left",
        "preventDuplicates": false,
        "showDuration": "3000",
        "hideDuration": "1000000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    toastr[type](message);
    if(redirectStatus){        
        setTimeout(function(){ window.location = redirectUrl; }, 3000);
    }
}
function swalAlert(text, type, timer = 2000) {
    Swal.fire({
        type: type,
        title: text,
        timer: timer
    })
}
function swalAlertThenRedirect(text, type, url, showCancelButton = false) {
    if (showCancelButton == false) {
        var confirmButtonColor = '#48cab2';
        var cancelButtonColor = '#dd6b55';
    } else {
        var confirmButtonColor = '#dd6b55';
        var cancelButtonColor = '#48cab2';
    }
    Swal.fire({
        title: text,
        type: type,
        showCancelButton: showCancelButton, // true or false
        confirmButtonColor: confirmButtonColor,
        cancelButtonColor: cancelButtonColor,
        confirmButtonText: "OK",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.value) {
            window.location = url;
        }
        /* else if (result.dismiss === Swal.DismissReason.cancel) {}*/
    });
}
function commonFormChecking(flag, cls = '', msgbox = '') {
    if (cls == '') {
        cls = 'requiredCheck';
    }
    $('.' + cls).each(function () {
        if ($(this).hasClass("ckeditor")) {
            if (CKEDITOR.instances[$(this).attr('id')].getData() == '') {
                if (msgbox != '') {
                    //$("." + msgbox).text($(this).attr('data-check') + ' is mandatory !!!');
                    $("." + msgbox).text('Please enter your ' + $(this).attr('data-check') + ' !!!');
                } else {
                    toastAlert('warning', 'Please enter your ' + $(this).attr('data-check') + ' !!!');
                }
                flag = false;
                return false;
            } else {
                if (CKEDITOR.instances[$(this).attr("id")].getData().replace(/ |\s/g, '') === '<p></p>') {
                    if (msgbox != "") {
                        $("." + msgbox).text(
                            $(this).attr("data-check") + " contains only blankspace !!!"
                        );
                    } else {
                        toastAlert("warning", $(this).attr("data-check") + " contains only blankspace !!!");
                    }
                    flag = false;
                    return false;
                }
            }
        } else {
            if ($.trim($(this).val()) == '') {
                if (msgbox != '') {
                    $("." + msgbox).text($(this).attr('data-check') + ' is mandatory !!!');
                } else {
                    //swalAlert($(this).attr('data-check') + ' is mandatory !!!', 'warning');
                    toastAlert('warning', 'Please enter your ' + $(this).attr('data-check') + ' !!!');
                }
                flag = false;
                return false;
            } else {
                if ($(this).attr('data-check') == 'Email' || $(this).attr('data-check') == 'Business Email' || $(this).attr('data-check') == 'To Email' || $(this).attr('data-check') == 'Contact Email' || $(this).attr('data-check') == 'Admin Email' || $(this).attr('data-check') == 'Email Form' ) {
                    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                    if (reg.test($.trim($(this).val())) == false) {
                        if (msgbox != '') {
                            $("." + msgbox).text('Enter valid Email address!!!');
                        } else {
                            toastAlert('warning', 'Enter valid Email address !!!');
                        }
                        flag = false;
                        return false;
                    }
                }
                if ($(this).attr('data-check') == 'Phone' || $(this).attr('data-check') == 'Mobile') {
                    if ($.trim($(this).val()).length < 10) {
                        var txt = 'Enter 10 digit phone number !!!';
                        if (msgbox != '') {
                            $("." + msgbox).text('Please enter a valid phone number !!!');
                        } else {
                            toastAlert('warning', 'Please enter a valid phone number !!!');
                        }
                        flag = false;
                        return false;
                    }
                }
                /*if ($(this).attr('data-check') == 'Zip') {
                    if ($.trim($(this).val()).length != 6) {
                        if (msgbox != '') {
                            $("." + msgbox).text('Enter 6 digit Postcode !!!');
                        } else {
                            toastAlert('warning', 'Enter 6 digit Postcode !!!');
                        }
                        flag = false;
                        return false;
                    }
                }*/
            }
        }
    });
    return flag;
}
$(".isNumber").keypress(function (evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        if (charCode == 43 || charCode == 45 || charCode == 4) {
            return true;
        }
        return false;
    }
    return true;
});
$(".isChar").keypress(function (evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if ((charCode >= 65 && charCode <= 122) || charCode == 32 || charCode == 0 || charCode == 45) {
        return true;
    }
    return false;
});
$(document).on('keyup', '.restrictSpecial', function () {
    var yourInput = $(this).val();
    var re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var isSplChar = re.test(yourInput);
    if (isSplChar) {
        var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $(this).val(no_spl_char);
    }
});
$(".allowNumberDot").keyup(function () {
    var $this = $(this);
    $this.val($this.val().replace(/[^\d.]/g, ''));
});
/* allow only letter & space */
$(".allowOnlyLetter").keypress(function (event) {
    var inputValue = event.charCode;
    if (!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 0)) {
        event.preventDefault();
    }
});
$('.checkDecimal').keypress(function (event) {
    var $this = $(this);
    if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
        ((event.which < 48 || event.which > 57) &&
            (event.which != 0 && event.which != 8))) {
        event.preventDefault();
    }
    var text = $(this).val();
    if ((event.which == 46) && (text.indexOf('.') == -1)) {
        setTimeout(function () {
            if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
            }
        }, 1);
    }
    if ((text.indexOf('.') != -1) &&
        (text.substring(text.indexOf('.')).length > 2) &&
        (event.which != 0 && event.which != 8) &&
        ($(this)[0].selectionStart >= text.length - 2)) {
        event.preventDefault();
    }
});
function callDataTable(cls) {
	$("." + cls).DataTable({
		pageLength: 10,
		searching: true,
	});
}
function commonGetTable(url, loadingDiv, tableClass, dataJson) {
	$.ajax({
		type: "POST",
		url: base_url + url,
		data: JSON.stringify(dataJson),
		dataType: "JSON",
		beforeSend: function () {
			$("." + loadingDiv).loading();
		},
		success: function (res) {
			$("." + loadingDiv).loading("stop");
			if (res.success) {
                $("." + loadingDiv).html(res.data);
                if(tableClass != ''){
                    callDataTable(tableClass);
                }
			}
		},
	});
}
$(document).on('change', '.change-priority', function (e) {
	e.preventDefault();
	let moduleCls              = $(this);
	let priorityVal         = $(this).val();
    let loading             = $(this).attr('data-loading');
    dataJson.rowId          = $(this).attr('data-row-id');
	dataJson.table          = $(this).attr("data-table");
    dataJson.tableKey       = $(this).attr("data-key");
    dataJson.priorityVal    = priorityVal;
	$.ajax({
		type: "POST",
		url: base_url + "api/change/priority",
		data: JSON.stringify(dataJson),
		dataType: "JSON",
		beforeSend: function () {
			$("." + loading).loading();
		},
		success: function (res) {
            $("." + loading).loading('stop');
            if(res.success){
                if(priorityVal == 'High'){
                    moduleCls.css({color:'#ff0000'});
                }else if(priorityVal == 'Medium'){
                    moduleCls.css({color:'#fea223'});
                }else if(priorityVal == 'Low'){
                    moduleCls.css({color:'#66afe9'});
                }else{
                    moduleCls.css({color:'#000000'});
                }
                toastAlert('success', res.message);
            }else{
                toastAlert('error', 'Something Went Wrong !!! Please Try Again !!!');
            }
		}
	});
});
function checkPassword(pwd, cnfPwd){
	let lowerCaseLetters 	= /[a-z]/g;
	let upperCaseLetters 	= /[A-Z]/g;
	let numbers 			= /[0-9]/g;
	if (!$('#' + pwd).val().match(lowerCaseLetters)) {
		//toastAlert('Password must contain one lowercase letter !!!', 'warning', 10000);
		toastAlert('warning', 'Password must contain one lower case letter, one upper case letter, one number & six(6) characters !!!');
		return false;
	}
	if (!$('#' + pwd).val().match(upperCaseLetters)) {
		//toastAlert('Password must contain one upercase letter !!!', 'warning', 10000);
		toastAlert('warning', 'Password must contain one lower case letter, one upper case letter, one number & six(6) characters !!!');
		return false;
	} 
	if (!$('#' + pwd).val().match(numbers)) {
		//toastAlert('Password must contain one number !!!', 'warning', 10000);
		toastAlert('warning', 'Password must contain one lower case letter, one upper case letter, one number & six(6) characters !!!');
		return false;
	}
	if ($('#' + pwd).val().length < 6) {
		//toastAlert('Password must contain eight(8) charecters !!!', 'warning', 10000);
		toastAlert('warning', 'Password must contain one lower case letter, one upper case letter, one number & six(6) characters !!!');
		return false;
	}
	if ($('#' + pwd).val() != $('#' + cnfPwd).val()) {
		toastAlert('error', 'Password & Confirm Password didn\'t match !!!');
		return false;
	}
	return true;
}
$(document).on("click", ".change-status", function () {
	let module          = this;
    dataJson.id         = $(this).attr("data-id");
    dataJson.table      = $(this).attr("data-table");
    dataJson.tableKey   = $(this).attr("data-key");
    $.ajax({
        type    : "POST",
        url     : base_url + $(this).attr("data-url"),
        data    : JSON.stringify(dataJson),
        success : function (resultData) {
            $(module).html(resultData);
            toastAlert("success", "Status Changed Successfully !!!");
        },
    });
});
$(document).on("click", ".genericDelete", function () {
	Swal.fire({
		title: "Are you sure want to delete?",
		type: "warning",
		showCancelButton: true, // true or false
		confirmButtonColor: "#dd6b55",
		cancelButtonColor: "#48cab2",
		confirmButtonText: "Yes !!!",
	}).then((result) => {
		if (result.value) {
			var remove_row = $(this).attr("data-row");
			var loadingDiv = $(this).attr("data-loading");
			var dataId = $(this).attr("data-id");
			dataJson.id = dataId;
			dataJson.table = $(this).attr("data-table");
			dataJson.tableKey = $(this).attr("data-key");
			$.ajax({
				type: "POST",
				url: base_url + "api/delete/row",
				data: JSON.stringify(dataJson),
				beforeSend: function () {
					$("." + loadingDiv).loading();
				},
				success: function (resultData) {
					//eval(function_name + "()");
					$("." + loadingDiv).loading("stop");
					$("." + remove_row + dataId).remove();
					toastAlert("success", "Deleted !!!");
				},
			});
		}
	});
});
function initializeMap(address, divId) {
    let map = null;
    let myMarker;
    let myLatlng;
    address = address.replace("%20", "+");
    $.getJSON('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDK4rMTf9bUlqpg1g8SF2zUnV4HQmatsVo&address='+address+'&sensor=false', null, function (data) {
        let p           = data.results[0].geometry.location;
        myLatlng        = new google.maps.LatLng(p.lat, p.lng);
        let myOptions   = {
            zoom: 16,
            zoomControl: true,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map 		= new google.maps.Map(document.getElementById(divId), myOptions);
        myMarker 	= new google.maps.Marker({
            position: myLatlng
        });
        myMarker.setMap(map);
    });
}