
function validateName(id) {
    if (document.getElementById(id).value.toString().length !== 30) {
        var charCode = event.keyCode;
//        if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 8) {
        if (event.which < 48 || event.which > 57) {
            return true;
        } else {
            event.preventDefault();
            return false;
        }
    } else {
        event.preventDefault();
        notification.showNotification('top', 'right', 'Exceed maximum characters!', 3, 200);
        return false;
    }
}
function validateAddress(id) {

}
function validateQty() {
    return event.charCode >= 48 && event.charCode <= 57;
}
function priceValidation(id){
    var val=document.getElementById(id).value;
    var c = e.keyCode
    if ( (c == 190 && val.indexOf('.') > -1) || c < 48 || c > 57 ) {
        e.preventDefault();
        return;
    }
}
function mobileNoValidation(id) {
    if (document.getElementById(id).value.toString().length !== 16) {
        if ((event.charCode >= 48 && event.charCode <= 57)) {
            return true;
        } else {
            event.preventDefault();
            return false;
        }
    } else {
        notification.showNotification('top', 'right', 'Exceed maximum characters!', 3, 200);
        return false;
    }
}
function validatePassword(id) {
    var pass = document.getElementById(id).value;

    if (pass != "") {
        if (pass.length < 6) {
            notification.showNotification('top', 'right', 'Password must contain at least six characters!', 3, 1000);
            return false;
        }

        re = /[0-9]/;
        if (!re.test(pass)) {
            notification.showNotification('top', 'right', 'password must contain at least one number (0-9)!', 3, 1000);
            return false;
        }
        re = /[a-z]/;
        if (!re.test(pass)) {
            notification.showNotification('top', 'right', 'password must contain at least one lowercase letter (a-z)!', 3, 1000);
            return false;
        }
        re = /[A-Z]/;
        if (!re.test(pass)) {
            notification.showNotification('top', 'right', 'password must contain at least one uppercase letter (A-Z)!', 3, 1000);
            return false;
        }
    } else {
        notification.showNotification('top', 'right', 'Please check that you have entered your password!', 3, 1000);
        return false;
    }
    return true;


}

//function validateAllTelephoneNumbersLength() {
//    if (document.getElementById('p_number').value.toString().length === 9 && document.getElementById('p_number').value !== "") {
//        alert('We recognize this telephone is Sri lankan one...!');
//        return;
//    } else if (document.getElementById('p_number').value.toString().length > 9 <= 15 && document.getElementById('p_number').value !== "") {
//        alert('We recognize this telephone is not Sri lankan one...!');
//        return;
//    }
//}

function validatePostalCode(id) {
    if (document.getElementById(id).value.toString().length !== 5) {
        if ((event.charCode >= 48 && event.charCode <= 57)) {
            return true;
        } else {
            event.preventDefault();
            return false;
        }
    } else {
        notification.showNotification('top', 'right', 'Invalied country code!', 3, 200);
        return false;
    }
}
function validateEmail(id) {
    var x = document.getElementById(id).value;
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length) {
        notification.showNotification('top', 'right', 'Invalid E-mail address!', 4, 200);
        return false;
    } else {
        return true;
    }
}
function validateEmail1(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}