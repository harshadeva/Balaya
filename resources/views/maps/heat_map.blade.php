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
{{--AIzaSyB9MNUmm4zcer3FKKpx6Q825yXbUuFoxng&libraries=visualization&callback=loadMapData">--}}
    {{--</script>--}}
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=
AIzaSyByRK6y5xlI2LZJllQE-0z9JnYCIl61794&libraries=visualization&callback=loadMapData">
    </script>
    <script language="JavaScript" type="text/javascript">
        // This example requires the Visualization library. Include the libraries=visualization
        // parameter when you first load the API. For example:
        // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=visualization">


        let map, flavored, same, noneFlavored , floating ;
        let flavoredDataArray = [];
        let sameDataArray = [];
        let nonFlavoredDataArray = [];
        let floatingDataArray = [];
        let center;

        function loadMapData() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            getVotingData();
        }

        function getVotingData(id) {
            $.ajax({
                url: '{{route('getVotingData')}}',
                type: 'POST',
                data:{id:id},
                success: function (data) {
                    let houses = data.success;
                    $.each(houses, function (key, value) {
                        center = {lat:value.lat,lng:value.long};
                       if(value.idvoting_condition == 1){

                           flavoredDataArray.push(new google.maps.LatLng(value.lat, value.long),);
                       }
                       else if(value.idvoting_condition == 2){

                           sameDataArray.push(new google.maps.LatLng(value.lat, value.long),);
                       }
                       else if(value.idvoting_condition == 7){

                           floatingDataArray.push(new google.maps.LatLng(value.lat, value.long),);
                       }
                       else{
                           nonFlavoredDataArray.push(new google.maps.LatLng(value.lat, value.long),);
                       }
                    });
                    initMap();
                }
            });
        }


        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10,
                center: { lat: 7.085870, lng: 80.000630 }
            });

            same = new google.maps.visualization.HeatmapLayer({
                data: sameData(),
                map: map
            });

            noneFlavored = new google.maps.visualization.HeatmapLayer({
                data: noneFlavoredData(),
                map: map
            });

            floating = new google.maps.visualization.HeatmapLayer({
                data: floatingData(),
                map: map
            });

            flavored = new google.maps.visualization.HeatmapLayer({
                data: flavoredData(),
                map: map
            });
            changeRadius(20);
            changeGradient();
        }

        function toggleHeatmap() {
            heatmap.setMap(heatmap.getMap() ? null : map);
        }

        function changeGradient() {
            let flavoredG = [
                "rgba(0, 255, 255, 0)",
                "#bb5709",
                "#bb5709",
            ];

            let sameG = [
                "rgba(0, 255, 255, 0)",
                "#bb9925",
                "#bb9925",
            ];
            let floatingG = [
                "rgba(0, 255, 255, 0)",
                "#0ebbba",
                "#0ebbba",
            ];

            let noneFlavoredG = [
                "rgba(0, 255, 255, 0)",
                "#bb19b4",
                "#bb19b4",
            ];

            flavored.set("gradient", flavored.get("gradient") ? null : flavoredG);
            same.set("gradient", same.get("gradient") ? null : sameG);
            floating.set("gradient", floating.get("gradient") ? null : floatingG);
            noneFlavored.set("gradient", noneFlavored.get("gradient") ? null : noneFlavoredG);

        }

        function changeRadius(radius) {
            flavored.set("radius", flavored.get("radius") ? null : radius);
            same.set("radius", same.get("radius") ? null : radius);
            floating.set("radius", floating.get("radius") ? null : radius);
            noneFlavored.set("radius", noneFlavored.get("radius") ? null : radius);
        }

        function changeOpacity() {
            heatmap.set("opacity", heatmap.get("opacity") ? null : 0.2);
        }

        // Heatmap data: 500 Points
        function flavoredData() {
            return flavoredDataArray;
        }

        function sameData() {
            return sameDataArray;
        }

        function noneFlavoredData() {
            return nonFlavoredDataArray;
        }

        function floatingData() {
            return flavoredDataArray;
        }
    </script>
@endsection