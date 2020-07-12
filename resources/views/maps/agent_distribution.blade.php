@extends('layouts.main')
@section('psStyle')
    <style>

        #map {
            height: 400px;
        }

        .markerPTag{
            margin-top: -10px;
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

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: center,
                zoom: zoom,
                gestureHandling: 'greedy'
            });
        }

        function addMarker(marker,coordination,image,name,contact) {
            let contentString = '<div id="content">'+
                '<div id="siteNotice">'+
                '</div>'+
//                '<h6 id="firstHeading" class="firstHeading">'+marker+'</h6>'+
                '<div id="bodyContent">'+
                '<p ><b>'+marker+'</b></p>' +
                '<p class="markerPTag">'+name+'</p>' +
                '<a href="tel:'+contact+'" class="markerPTag">'+contact+'</a>' +
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


        function getVillageLocations() {
            $.ajax({
                url: '{{route('getVillageLocations')}}',
                type: 'POST',
                success: function (data) {
                   let villages = data.success;
                    $.each(villages, function (key, value) {
                        center  =  { lat: value.lat, lng: value.long };
                         addMarker( value.name_en,{ lat:value.lat, lng: value.long },'',value.name,value.contact);
                    });
                    map.panTo(center);
                    map.setZoom(13);
                }


            });
        }

    </script>
@endsection