@include('includes.header_start')

@include('includes.common_styles')

<style>

    #map {
        height: 100Vh;
    }

    #HouseContent {
        width: 120px;
        height: 110px;
    }
</style>

@include('includes.header_end')

<div class="container-fluid">

    <div  class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12" id="map"></div>
            </div>

        </div>
    </div>

</div> <!-- ./container -->

@include('includes.common_scripts')

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
        getMapData('{{$canvassingId}}');

    });

    let map;
    let center = { lat: 7.8731, lng: 80.7718 };
    let zoom = 8;
    let colors = [];

    let houseImage = '{{ \Illuminate\Support\Facades\URL::asset('assets/images/markers/house.webp')}}';

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: center,
            zoom: zoom,
            gestureHandling: 'greedy'
        });
    }


    function addMarker(marker,coordination,image,contactNo,time) {

        let contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
//                '<h6 id="firstHeading" class="firstHeading">'+marker+'</h6>'+
            '<div id="bodyContent">'+
            '<p><b>'+marker+'</b></p>' +
            '<p style="margin-top: -8px;">'+contactNo+'</p>' +
            '<p style="margin-top: -8px;">'+time+'</p>' +
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

    function addHouseMarker(name,coordination,image,no,elders,young,first) {
        if(!name){
            name = 'Name'
        }
        let contentString = '<div id="HouseContent">'+
            '<div id="siteNotice">'+
            '</div>'+
//                '<h6 id="firstHeading" class="firstHeading">'+marker+'</h6>'+
            '<div id="bodyContent">'+
            '<p><b>'+name+'</b></p>' +
            '<p style="margin-bottom: 1px;">House No : '+no+'</p>' +
            '<p style="margin-bottom: 1px;">Elder : '+elders+'</p>' +
            '<p style="margin-bottom: 1px;">Young : '+young+'</p>' +
            '<p style="margin-bottom: 1px;">First : '+first+'</p>' +
            '</div>';

        let iconHouse = {
            url: houseImage,
            scaledSize: new google.maps.Size(35, 35), // scaled size
//            origin: new google.maps.Point(0,0), // origin
//            anchor: new google.maps.Point(0, 0) // anchor
        };


        no = new google.maps.Marker({
            position:coordination,
            map:map,
            title: name,
            icon: iconHouse
        });

        let infowindow = new google.maps.InfoWindow({
            content: contentString
        });

        no.addListener('click', function() {
            infowindow.open(map, no);
        });
    }


    function getMapData(id) {
        $.ajax({
            url: '{{route('getScreenMapData')}}',
            type: 'POST',
            data:{id:id},
            success: function (data) {
                let users = data.success.path;
                let houses = data.success.houses;

                let usersLoop = 0;
                $.each(users, function (key1, coordinates) {
                    let loop = 0;
                    if(!colors[usersLoop]) {
                        colors[usersLoop] = Math.floor(Math.random() * 16777215).toString(16);
                    }

                    drawLine(key1,coordinates,colors[usersLoop]);

                    $.each(coordinates, function (key, value) {
                        center = { lat: parseFloat(value.lat), lng: parseFloat(value.long) };
                        loop++;
//                        if(loop == coordinates.length || loop == 1 || loop%1 == 0 ){
                        if(loop == 1  ){
                            addMarker(value.name,{  lat: parseFloat(value.lat), lng: parseFloat(value.long)},'',value.contact,value.time);
                        }
                    });
                    usersLoop++;
                });

                $.each(houses, function (key2, house) {
                    addHouseMarker(house.houseName,{  lat: parseFloat(house.lat), lng: parseFloat(house.long)},'',house.houseNo,house.elderVoters,house.youngVoters,house.firstVoters);
                });
                map.panTo(center);
                map.setZoom(13);
                setTimeout(function() {
                    getMapData(id);
                }, 60 * 1000); // 60 * 1000 milsec
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
        name.setMap(null);
        name.setMap(map);
    }
</script>

</body>
</html>