<!-- jQuery  -->
<script src="{{ URL::asset('assets/js/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/popper.min.js')}}"></script><!-- Popper for Bootstrap -->
<script src="{{ URL::asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/modernizr.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/jquery.slimscroll.js')}}"></script>
<script src="{{ URL::asset('assets/js/waves.js')}}"></script>
<script src="{{ URL::asset('assets/js/jquery.nicescroll.js')}}"></script>
<script src="{{ URL::asset('assets/js/jquery.scrollTo.min.js')}}"></script>

<!-- App js -->
<script src="{{ URL::asset('assets/js/app.js')}}"></script>


<!-- Plugins js : form elements-->
<script src="{{ URL::asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js')}}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js')}}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js')}}" type="text/javascript"></script>

<!-- Plugins Init js : form elements -->
<script src="{{ URL::asset('assets/pages/form-advanced.js')}}"></script>

<!-- notify alert -->
<script src="{{ URL::asset('assets/js/jquery.notify.min.js')}}"></script>

<!-- sweet alert -->
<script src="{{ URL::asset('assets/plugins/sweet-alert2/sweetalert2.min.js')}}"></script>

<!-- light box -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js" integrity="sha256-jGAkJO3hvqIDc4nIY1sfh/FPbV+UK+1N+xJJg6zzr7A=" crossorigin="anonymous"></script>

<!-- Insert these scripts at the bottom of the HTML, but before you use any Firebase services -->

<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.15.4/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.15.4/firebase-messaging.js"></script>
<script>

    var firebaseConfig = {
        apiKey: "AIzaSyCsp5AKxTTPAutYo_WKYof3QXey1_shD24",
        authDomain: "electionplanner-15848.firebaseapp.com",
        databaseURL: "https://electionplanner-15848.firebaseio.com",
        projectId: "electionplanner-15848",
        storageBucket: "electionplanner-15848.appspot.com",
        messagingSenderId: "197251280346",
        appId: "1:197251280346:web:bebf9fac87f1f9c1e3e4e3",
        measurementId: "G-0TT02QSJ59"
    };

    firebase.initializeApp(firebaseConfig);

    const messaging = firebase.messaging();

    function initializeFirebaseMessaging(){

        messaging.requestPermission().
        then(function () {
            console.log('Notification allowed.');
            return messaging.getToken();
        }).then(function (token) {
            console.log('Token : '+token)
        })
            .catch(function (reason) {
                console.log(reason);
            })

    }

    messaging.onMessage(function (payload) {
        console.log(payload);

        const notificationOption = {
            body:payload.notification.body,
            icon:payload.notification.icon
        }

        if(Notification.permission = "granted"){
            var notification = new Notification(payload.notification.title,notificationOption);
//                notification.onclick(function (ev) {
//                    ev.preventDefault();
//                    window.open(payload.notification.click_action);
//                    notification.close();
//                })
            notification.onclick = function(ev){
                ev.preventDefault();
                window.open(payload.notification.click_action);
                notification.close();
            };
        }
    });

    messaging.onTokenRefresh(function () {
        messaging.getToken().then(function (newToken) {
            console.log('New token is :'+newToken);
        }).catch(function (reason) {
            console.log(reason);
        })
    });

    initializeFirebaseMessaging();
</script>


<script language="JavaScript" type="text/javascript">
    $('.gtZero').on('input',function () {
        this.value = this.value < 0 ? 0 : this.value;
    })

    $('.monthPicker').datepicker({
        autoclose: true,
        minViewMode: 1,
        format: 'mm/dd/yyyy'
    }).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('.to').datepicker('setStartDate', startDate);
    });

    function initializeDate() {
        jQuery('.datepicker-autoclose').datepicker({
            autoclose: true,
            todayHighlight: true
        });

    }

    function clearAll() {
        $('input').not(':checkbox').not('.noClear').val('');
        $('textarea').not('.noClear').val('');
        $(":checkbox").not('.noClear').attr('checked', false).trigger('change');
        $(":radio").not('.noClear').attr('checked', false).trigger('change');
        $('select').not('.noClear').val('').trigger('change');
    }


    $(document).on("wheel", "input[type=number]", function (e) {
        $(this).blur();
    });

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            onShown: function() {
                $('.modal').hide();
            },
            onHidden: function() {
                $('.modal').show();
            },
        });
    });
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-154895234-4"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-154895234-4');
</script>





