@include('includes.header_start')

@include('includes.common_styles')

<style>
td {
    /*font-size: 15px;*/
}
    td{
        /*background-color: rgba(93, 89, 10, 0.46);*/
       /*color: white;*/
    }
</style>

@include('includes.header_end')

{{--@include('includes.left_bar')--}}

{{--@include('includes.top_bar')--}}

{{--<div class="page-content-wrapper">--}}
<div class="container-fluid">

    <div class="card">
        <div class="card-body">


            <div class="row firstPage">
                <div class="col-lg-10">
                    <div class="btn-group "  style="width: 100%">
                        <a target="_self" href="{{route('pendingCanvassingScreen')}}" >
                            <label  class="btn btn-primary">
                                PENDING
                            </label>
                        </a>
                        <a target="_self" href="{{route('todayCanvassingScreen')}}" >
                            <label class="btn btn-primary">
                                TODAY
                            </label>
                        </a>
                        <a target="_self" href="{{route('ongoingCanvassingScreen')}}" >
                            <label style="background-color: #1f868b" class="btn btn-primary">
                                ONGOING
                            </label>
                        </a>
                    </div>
                </div>

                <div class="col-md-1">
                    <a style="margin-top: 5px;" target="_self" href="{{route('dashboard')}}" >
                        <label style="border-radius: 10px;background-color: rgba(37,125,151,0.97);color: white" class="btn">
                            <em class="mdi mdi-chevron-double-left">BACK</em>
                        </label>
                    </a>
                </div>
                <div style="margin-top: 3px;"  class="col-md-1">
                    <a class="btn-lg text-secondary" href="#" id="fullScreen">
                        <i class="mdi mdi-fullscreen noti-icon"></i>
                    </a>
                </div>
            </div>
            <br/>

            <div class="row toggle pendingToggle">
                <div class="col-md-12">
                    <div class="table-rep-plugin">
                        <div class="table-responsive b-0" data-pattern="priority-columns">
                            <table class="table table-striped table-bordered"
                                   cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>NAME</th>
                                    <th>TYPE</th>
                                    <th>LOCATION</th>
                                    <th nowrap="true">VILLAGES</th>
                                    <th>STARTED</th>
                                    <th>HOUSES</th>
                                    <th>PARTICIPATION</th>
                                    <th nowrap="true">VOTER CATEGORY</th>
                                    <th nowrap="true">HOUSE CONDITION</th>
                                    <th>TOTAL VOTERS</th>
                                    <th style="text-align: center;">MAP</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!-- ./container -->
{{--</div><!-- ./wrapper -->--}}


<footer style="transform: translateX(-10%)"  class="footer">
    <p> © <?php echo date('Y') ?> Developed By <img
                style="margin-top: 5px;padding-bottom: 3px;height: 20px;"
                src="{{ URL::asset('assets/images/resources/logo.svg') }}"/></p>
</footer>


@include('includes.common_scripts')


<script language="JavaScript" type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#fullScreen').click(event);
        loadTable();
    });


    $('#fullScreen').on("click", function (e) {
        e.preventDefault();

        if (!document.fullscreenElement && /* alternative standard method */ !document.mozFullScreenElement && !document.webkitFullscreenElement) {  // current working methods
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            }
        } else {
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            }
        }
    });


    function loadTable() {
        $.ajax({
            url: '{{route('ongoingScreenTable')}}',
            type: 'POST',
            success: function (data) {
                let result = data.success;

                $('#tableBody').html('');
                $.each(result, function (key, value) {

                    $('#tableBody').append("" +
                        "<tr id='"+value.idcanvassing+"'>" +
                        "<td>"+value.no+" - <br/>" +
                        ""+value.name_en+"" +
                        "</td>" +
                        "<td>"+value.canvassingType+"</td>" +
                        "<td>"+value.location_en+"</td>" +
                        "<td>"+value.villages+"</td>" +
                        "<td>"+value.started+"</td>" +
                        "<td style='text-align: center;'>"+value.houses+"</td>" +
                        "<td style='text-align: center;'>"+value.attendance+"</td>" +
                        "<td style='text-align: left;'>" +
                        "Elders :  "+value.elders+"<br/>" +
                        "Young  :  "+value.young+"<br/>" +
                        "First  V :  "+value.first+"<br/>" +
                        "</td>" +
                        "<td style='text-align: left;'>" +
                        "Samurdhi :  "+value.samurdhi+"<br/>" +
                        "Low Class  :  "+value.low+"<br/>" +
                        "Middle Class :  "+value.middle+"<br/>" +
                        "Luxury :  "+value.luxury+"<br/>" +
                        "Super Luxury :  "+value.super+"<br/>" +
                        "</td>" +
                        "<td>"+value.totalVoters+"</td>" +
                        "<td style='text-align: center'><a href='{{route('canvassingRouteOnMap')}}?reference="+value.idcanvassing+"' class='text-primary' ><em class='mdi mdi-map-marker-radius mdi-24px'></em></a></td>" +
                        "</tr>");
                });
                setTimeout(loadTable, 60000);

            }
        });
    }
</script>


</body>
</html>