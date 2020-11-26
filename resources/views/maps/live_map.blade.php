@extends('layouts.main')
@section('psStyle')
    <style>

        #map {
            height: 400px;
        }

        .markerPTag{
            margin-top: -8px;
        }
    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <div  class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12" id="map"></div>
                    </div>

                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->
@endsection
@section('psScript')

    {{--<script async defer--}}
            {{--src="https://maps.googleapis.com/maps/api/js?key=--}}
{{--AIzaSyB9MNUmm4zcer3FKKpx6Q825yXbUuFoxng&callback=initMap">--}}
    {{--</script>--}}
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=
AIzaSyByRK6y5xlI2LZJllQE-0z9JnYCIl61794&callback=initMap">
    </script>
    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            getVillageLocations();
        });

        let map;
        let center = { lat: 7.8731, lng: 80.7718 };
        let zoom = 8;
        let markers = {};
        let loop = 1;

        function CenterControl(controlDiv, map) {
            // Set CSS for the control border.
            var controlUI = document.createElement("div");
            controlUI.style.backgroundColor = "#fff";
            controlUI.style.border = "2px solid #fff";
            controlUI.style.borderRadius = "3px";
            controlUI.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
            controlUI.style.cursor = "pointer";
            controlUI.style.marginBottom = "22px";
            controlUI.style.marginRight = "10px";
            controlUI.style.width = "40px";
            controlUI.style.textAlign = "center";
            controlUI.title = "Click to recenter the map";
            controlDiv.appendChild(controlUI);

            // Set CSS for the control interior.
            var controlText = document.createElement("div");
            controlText.style.color = "rgb(25,25,25)";
            controlText.style.fontFamily = "Roboto,Arial,sans-serif";
            controlText.style.fontSize = "16px";
            controlText.style.lineHeight = "38px";
            controlText.style.paddingLeft = "5px";
            controlText.style.paddingRight = "5px";
            controlText.style.marginTop = "1px";
            controlText.innerHTML = "<em class='mdi mdi-target mdi-18px'></em>";
            controlUI.appendChild(controlText);

            // Setup the click event listeners: simply set the map to Chicago.
            controlUI.addEventListener("click", function() {
                map.setCenter(center);
            });
        }

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: center,
                zoom: zoom,
                gestureHandling: 'greedy'
            })

            // Create the DIV to hold the control and call the CenterControl()
            // constructor passing in this DIV.
            var centerControlDiv = document.createElement("div");
            var centerControl = new CenterControl(centerControlDiv, map);

            centerControlDiv.index = 1;
            map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(centerControlDiv);
        }

        function addMarker(marker,coordination,image,name,contact,id,voters,elder,young,first) {
            let contentString = '<div id="content">'+
                '<div id="siteNotice">'+
                '</div>'+
                '<div id="bodyContent">'+
                '<p ><b>'+marker+'</b></p>' +
                '<p style="font-weight: 600;" class="markerPTag" ><b>'+voters+'</b></p>' +
                '<p class="markerPTag" ><b>Elders Voters : '+elder+'</b></p>' +
                '<p class="markerPTag" ><b>Young Voters : '+young+'</b></p>' +
                '<p class="markerPTag" ><b>First Voters : '+first+'</b></p>' +
                '<p class="markerPTag">'+name+'</p>' +
                '<p class="markerPTag"><a href="tel:'+contact+'" class="markerPTag">'+contact+'</a></p>' +
                '</div>'+
                '</div>';

            let liveMarkerImage = '{{ \Illuminate\Support\Facades\URL::asset('assets/images/markers/car.png')}}';
            let liveMarker = {
                url: liveMarkerImage,
                scaledSize: new google.maps.Size(35, 35), // scaled size
            };

            let agentMarkerImage = '{{ \Illuminate\Support\Facades\URL::asset('assets/images/markers/user.png')}}';
            let agentMarker = {
                url: agentMarkerImage,
                scaledSize: new google.maps.Size(40, 40), // scaled size
            };
            let icon;
            if(id == 2){
                icon = liveMarker;
            }
            else{
                icon = agentMarker;
            }
            marker = new google.maps.Marker({
                position:coordination,
                map:map,
                title: marker,
                icon: icon
            });

            markers[id] = marker;
            if(id == 2){
               //
            }
            else{
                let infowindow = new google.maps.InfoWindow({
                    content: contentString
                });

                marker.addListener('click', function() {
                    infowindow.open(map, marker);
                });
            }

        }

        function getVillageLocations() {
            $.ajax({
                url: '{{route('getVillageLocations')}}',
                type: 'POST',
                success: function (data) {
                    let villages = data.success;
                    $.each(villages, function (key, value) {
                        center  =  { lat: value.lat, lng: value.long };
                        addMarker( value.name_en,{ lat:value.lat, lng: value.long },'',value.name,value.contact,1,value.voters,value.elder,value.young,value.first);
                    });
                    map.panTo(center);
                    map.setZoom(15);
                    markLiveLocation();
                }
            });
        }
        function markLiveLocation() {
            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    delMarker(2);
                    addMarker( 'My Location',pos,'','','',2,'','','','');

                    center = pos;
                    if(loop == 1){
                        map.setCenter(center);
                    }
                    loop++;
                }, function() {
//                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
//                 Browser doesn't support Geolocation
//                handleLocationError(false, infoWindow, map.getCenter());
            }
            setTimeout(markLiveLocation,3000);
        }

//        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
//            infoWindow.setPosition(pos);
//            infoWindow.setContent(browserHasGeolocation ?
//                'Error: The Geolocation service failed.' :
//                'Error: Your browser doesn\'t support geolocation.');
//            infoWindow.open(map);
//        }

        function delMarker(id) {
            marker = markers[id];
            if(marker){
                marker.setMap(null);
            }
        }
    </script>
@endsection