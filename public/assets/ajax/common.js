/**
 * Created by Ishara on 8/9/2017.
 */

function getAjaxObject() {
    var xmlhttp = new XMLHttpRequest()
    // if (window.XMLHttpRequest) {
    //     // code for modern browsers
    //     xmlhttp = new XMLHttpRequest();
    // } else {
    //     // code for old IE browsers
    //     xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    // }
    return xmlhttp;
}

function setImage() {

    var xhttpx = getAjaxObject();
    xhttpx.onreadystatechange = function () {
        if (xhttpx.readyState == 4 && xhttpx.status == 200) {
            var tx = xhttpx.responseText;

            document.getElementById('MyImage').setAttribute("src",tx);
            // document.getElementById('MyImage').setAttribute("src","img/default-avatar.png");

        }
    };
    xhttpx.open("POST", "routes/usersDataCollector.php", true);
    xhttpx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpx.send("type=4");

}


function checkEmpty(val) {
    return (val === undefined || val == null || val.length <= 0) ? true : false;
}

function disableEnable(id, table) {

    var xhttp = getAjaxObject();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var tx = xhttp.responseText;

            // if (!(tx == 0 || tx == 1)) {
            //     notification.showNotification('top', 'right', tx, 4, 2000);
            // }
            var tx = xhttp.responseText.split(",");
            for (var i = 0; i < tx.length - 1; i++) {
                var v = tx[i].split("-");
                notification.showNotification('top', 'right', v[1], v[0], 500);
            }

        }
    };
    xhttp.open("POST", "routes/commonDataCollector.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("type=1&tableName=" + table + "&dataID=" + id);
}

function logout() {
    var xhttp = getAjaxObject();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var tx = xhttp.responseText;
            alert(tx);
            if (tx = "Done") {
                location.href="login.php"

            }

        }
    };
    xhttp.open("POST", "routes/usersDataCollector.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("type=3&abc=5");
}

