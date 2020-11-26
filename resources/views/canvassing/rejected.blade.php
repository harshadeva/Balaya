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
                            <form action="{{route('rejectedCanvassing')}}" method="GET">
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
                                                    <th>TIME</th>
                                                    <th>CREATED AT</th>
                                                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)
                                                    <th>OPTION</th>
                                                    @endif
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($canvassings))
                                                    @if($canvassings != null)
                                                        @foreach($canvassings as $canvassing)
                                                            <tr id="{{$canvassing->idcanvassing}}">
                                                                <td>{{$canvassing->name_en}}</td>
                                                                <td>{{$canvassing->type->name_en}}</td>
                                                                <td>{{$canvassing->location_en}}</td>
                                                                <td>
                                                                    @foreach($canvassing->village as $village)
                                                                        {{$village->village->name_en}}<br/>
                                                                    @endforeach
                                                                </td>
                                                                <td>{{$canvassing->date}}</td>
                                                                <td>{{$canvassing->time}}</td>
                                                                <td>{{$canvassing->created_at}}</td>
                                                                @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)
                                                                    <td>
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary btn-sm dropdown-toggle"
                                                                                type="button" id="dropdownMenuButton"
                                                                                data-toggle="dropdown"
                                                                                aria-haspopup="true"
                                                                                aria-expanded="false">
                                                                            Option
                                                                        </button>
                                                                        <div class="dropdown-menu"
                                                                             aria-labelledby="dropdownMenuButton">
                                                                            <a href="#"
                                                                               onclick="approve({{$canvassing->idcanvassing}})"
                                                                               class="dropdown-item">Approve
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                    @endif
                                                            </tr>

                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="10"
                                                                style="text-align: center;font-weight: 500">Sorry No
                                                                Results Found.
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(isset($canvassings))
                                {{$canvassings->links()}}
                            @endif
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
        });

        function approve(id) {
            swal({
                title: 'Approve This Canvassing?',
                text: 'All agent and members will be notified.',
                type:'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Approve',
                cancelButtonText: 'No, cancel',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger m-l-10',
                buttonsStyling: false
            }).then(function () {

                $.ajax({
                    url: '{{route('approveCanvassing')}}',
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        if (data.errors != null) {
                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'APPROVING PROCESS INVALID!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Something wrong with process.contact administrator..'
                            });
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'CANVASSING APPROVED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Canvassing approved successfully.'
                            });
                            $('#'+id).remove();
                        }

                    }
                });

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
//                if (dismiss === 'cancel') {
//                    swal(
//                        'Cancelled',
//                        'Process has been cancelled',
//                        'error'
//                    )
//                }
            })
        }


    </script>
@endsection