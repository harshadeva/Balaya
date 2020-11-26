@extends('layouts.main')
@section('psStyle')
    <style>

        #map {
            height: 500px;
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

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=
AIzaSyAQNrIiVxt0HaB1KTfBgPv8H1yZWUPz838&callback=initMap">
    </script>
    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            getCanvassingPath(9);
        });

        let map;
        let center = { lat: 7.8731, lng: 80.7718 };
        let coordination = {} ;
        let zoom = 8;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: center,
                zoom: zoom
            });
        }

        function addMarker(marker,coordination,image) {
            let contentString = '<div id="content">'+
                '<div id="siteNotice">'+
                '</div>'+
//                '<h6 id="firstHeading" class="firstHeading">'+marker+'</h6>'+
                '<div id="bodyContent">'+
                '<p><b>'+marker+'</b></p>' +
                '</div>'+
                '</div>';


            marker = new google.maps.Marker({
                position:coordination,
                map:map,
                title: marker
            });

            let infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });
        }


        function getCanvassingPath(id) {
            $.ajax({
                url: '{{route('getCanvassingPath')}}',
                type: 'POST',
                data:{id:id},
                success: function (data) {
                    let users = data.success;
                    console.log(users);
                    $.each(users, function (key1, coordinates) {
                        let loop = 0;
                        let randomColor = Math.floor(Math.random()*16777215).toString(16);
                        drawLine(key1,coordinates,randomColor);
                        $.each(coordinates, function (key, value) {
                            center = { lat: parseFloat(value.lat), lng: parseFloat(value.long) };
                            loop++;
                            if(loop == coordinates.length || loop == 1 || loop%10 == 0 ){
                                addMarker(value.name,{  lat: parseFloat(value.lat), lng: parseFloat(value.long)},'');
                            }
                        });
                    });
                    map.panTo(center);
                    map.setZoom(18);
//                    drawLine(users);
                }
            });
        }

        function drawLine(user,values,color) {
            let name = user+'name';
            name = new google.maps.Polyline({
                path: values,
                geodesic: true,
                strokeColor: "#" +color,
                strokeOpacity: 1.0,
                strokeWeight: 3
            });
            name.setMap(map);
        }
    </script>
@endsection