@extends('layouts.main')
@section('psStyle')
    <style>

    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">
                            <form action="{{route('finishedCanvassing')}}" method="GET">
                                <div class="row">
                                    {{ csrf_field() }}

                                    <div class="form-group col-md-3 ">
                                        <label for="officeName">By Village Name</label>
                                        <input class="form-control " type="text" placeholder="Search village name here"
                                               id="name"
                                               name="name">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="type">By Type</label>

                                        <select class="form-control select2" name="type"
                                                id="type">
                                            <option value="" disabled selected>Select type
                                            </option>
                                            @if($canvassingTypes != null)
                                                @foreach($canvassingTypes as $canvassingType)
                                                    <option value="{{$canvassingType->idcanvassing_type}}">{{$canvassingType->name_en}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>By Date</label>
                                        <div class="input-daterange input-group" id="date-range">
                                            <input placeholder="dd/mm/yy" type="text" autocomplete="off"
                                                   class="form-control" value="" id="startDate" name="start"/>
                                            <input placeholder="dd/mm/yy" type="text" autocomplete="off"
                                                   class="form-control" value="" id="endDate" name="end"/>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <button type="submit"
                                                class="btn form-control text-white btn-info waves-effect waves-light"
                                                style="margin-top: 27px;">Search
                                        </button>
                                    </div>

                                </div>
                            </form>

                            <div class="row">
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
                                                    <th>VILLAGES</th>
                                                    <th>DATE</th>
                                                    <TH>STARTED</TH>
                                                    <TH>FINISHED</TH>
                                                    <th>HOUSES</th>
                                                    <th>PARTICIPATION</th>
                                                    <th nowrap="true">VOTER CATEGORY</th>
                                                    <th nowrap="true">HOUSE CONDITION</th>
                                                    <th nowrap="true">TOTAL VOTERS</th>
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
                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->

@endsection
@section('psScript')

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            loadTable();
        });


        function loadTable() {
            let name = '{{isset($_REQUEST['name']) ? $_REQUEST['name'] : ''}}';
            let type = '{{isset($_REQUEST['type']) ? $_REQUEST['type'] : ''}}';
            let start = '{{isset($_REQUEST['start']) ? $_REQUEST['start'] : ''}}';
            let end = '{{isset($_REQUEST['end']) ? $_REQUEST['end'] : ''}}';
            $.ajax({
                url: '{{route('finishedCanvassing')}}',
                type: 'POST',
                data:{name:name,type:type,start:start,end:end},
                success: function (data) {
                    let result = data.success;

                    $('#tableBody').html('');
                    $.each(result, function (key, value) {

                        $('#tableBody').append("" +
                            "<tr id='"+value.idcanvassing+"'>" +
                            "<td>"+value.no+" <br/>" +
                            ""+value.name_en+"" +
                            "</td>" +
                            "<td>"+value.canvassingType+"</td>" +
                            "<td>"+value.location_en+"</td>" +
                            "<td>"+value.villages+"</td>" +
                            "<td>"+value.date+"</td>" +
                            "<td>"+value.started+"</td>" +
                            "<td>"+value.finished+"</td>" +
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

                                   }
            });
        }
    </script>
@endsection