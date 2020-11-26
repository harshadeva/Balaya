@extends('layouts.main')
@section('psStyle')
    <style>

    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-rep-plugin">
                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                    <table class="table table-striped table-bordered"
                                           cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>EVENT NAME</th>
                                            <th>STATUS</th>
                                            <th>DISTRICT</th>
                                            <th>ELECTION DIVISION</th>
                                            <th>MEMBER DIVISION</th>
                                            <th>GRAMASEWA DIVISION</th>
                                            <th>VILLAGE</th>
                                            <th>LOCATION</th>
                                            <th>DATE</th>
                                            <th>TIME</th>
                                            <th>OPTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($events))
                                            @if(count($events) > 0)
                                                @foreach($events as $event)
                                                    <tr id="{{$event->idevent}}">
                                                        <td>{{strtoupper($event->name_en)}} </td>
                                                        @if($event->status == 1)
                                                            <td nowrap><em
                                                                        class="mdi mdi-checkbox-blank-circle text-success "></em>
                                                                PENDING
                                                            </td>
                                                        @elseif($event->status == 2)
                                                            <td nowrap><em
                                                                        class="mdi mdi-checkbox-blank-circle text-danger"></em>
                                                                ON GOING
                                                            </td>
                                                        @elseif($event->status == 3)
                                                            <td nowrap><em
                                                                        class="mdi mdi-checkbox-blank-circle text-danger"></em>
                                                                FINISHED
                                                            </td>
                                                        @else
                                                            <td nowrap><em
                                                                        class="mdi mdi-checkbox-blank-circle text-danger"></em>
                                                                CANCELLED
                                                            </td>
                                                        @endif
                                                        <td>{{strtoupper($event->district->name_en)}} </td>
                                                        <td>{{$event->electionDivision != null ? strtoupper($event->electionDivision->name_en) : 'ALL'}} </td>
                                                        <td>{{$event->pollingBooth != null ? strtoupper($event->pollingBooth->name_en) : 'ALL'}} </td>
                                                        <td>{{$event->gramasewaDivision != null ? strtoupper($event->gramasewaDivision->name_en) : 'ALL'}} </td>
                                                        <td>{{$event->village != null ? strtoupper($event->village->name_en) : 'ALL'}} </td>
                                                        <td >{{$event->location_en}}</td>
                                                        <td >{{$event->date}}</td>
                                                        <td >{{date('H:i', strtotime($event->time))}}</td>

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
                                                                       class="dropdown-item">Start
                                                                    </a>
                                                                    <a href="#"
                                                                       class="dropdown-item">Finish
                                                                    </a>
                                                                    <a href="#"
                                                                       class="dropdown-item">Cancel
                                                                    </a>
                                                                </div>


                                                            </div>
                                                        </td>
                                                    </tr>

                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="11"
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
                    @if(isset($events))
                        {{$events->links()}}
                    @endif

                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->
@endsection
@section('psScript')

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {

        });

    </script>
@endsection