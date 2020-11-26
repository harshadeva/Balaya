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
                        <div class="col-lg-12">
                            <div class="card m-b-20">
                                <div class="card-body">
                                    <form action="{{route('pendingComments')}}" method="GET">
                                        <div class="row">
                                            {{ csrf_field() }}



                                            <div class="form-group col-md-4 ">
                                                <label for="searchCol">Search By</label>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <select class="form-control  " name="searchCol"
                                                                id="searchCol" required>
                                                            <option value="1" selected>USER NAME</option>
                                                            <option value="2">POST NO</option>
                                                        </select>
                                                    </div>
                                                    <input class="form-control " type="text" id="searchText"
                                                           name="searchText">

                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="searchDivision">Election division</label>

                                                <select class="form-control select2" name="searchDivision"
                                                        id="searchDivision">
                                                    <option value="" disabled selected>Select division
                                                    </option>
                                                    @if($staffDivisions != null)
                                                        @foreach($staffDivisions as $staffDivision)
                                                            <option value="{{$staffDivision->idelection_division}}">{{$staffDivision->name_en}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Created Date</label>

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
                                                            <th>RESPONSE NO</th>
                                                            <th>USER</th>
                                                            <th>POST NO</th>
                                                            <th>ELECTION D.</th>
                                                            <th>MEDIA TYPE</th>
                                                            <th>CREATED AT</th>
                                                            <th>OPTION</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if(isset($responses))
                                                            @if(count($responses) > 0)
                                                                @foreach($responses as $response)

                                                                    <tr id="{{$response->idpost_response}}">

                                                                        <td>{{sprintf('%06d',$response->idpost_response)}} </td>
                                                                        <td>{{strtoupper($response->user->fName)}} </td>
                                                                        <td>{{sprintf('%06d',$response->post->post_no)}} </td>
                                                                        <td>{{strtoupper($response->user->getType->electionDivision->name_en)}} </td>
                                                                        @if($response->response_type == 1)
                                                                            <td nowrap><p><em
                                                                                            class="mdi mdi-checkbox-blank-circle text-success "></em>
                                                                                    TEXT</p></td>
                                                                        @elseif($response->response_type == 2)
                                                                            <td nowrap><p><em
                                                                                            class="mdi mdi-checkbox-blank-circle  "
                                                                                            style="color:#d613a2;"></em>
                                                                                    IMAGE</p></td>
                                                                        @elseif($response->response_type == 3)
                                                                            <td nowrap><p><em
                                                                                            class="mdi mdi-checkbox-blank-circle  "
                                                                                            style="color:#dae115;"></em>
                                                                                    VIDEO</p></td>
                                                                        @elseif($response->response_type == 4)
                                                                            <td nowrap><p><em
                                                                                            class="mdi mdi-checkbox-blank-circle  "
                                                                                            style="color:#15dd35;"></em>
                                                                                    AUDIO</p></td>
                                                                        @else
                                                                            <td nowrap><em
                                                                                        class="mdi mdi-checkbox-blank-circle "
                                                                                        style="color:black;"></em>
                                                                                UNKNOWN
                                                                            </td>
                                                                        @endif
                                                                        <td>{{strtoupper($response->created_at)}} </td>
                                                                        <td>
                                                                            <div class="dropdown">
                                                                                <button onclick="$('#form-{{$response->idpost_response}}').submit()"
                                                                                        class="btn btn-info btn-md "
                                                                                        type="button">
                                                                                    View
                                                                                </button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <form action="{{route('viewPendingResponse')}}"
                                                                          id="form-{{$response->idpost_response}}"
                                                                          method="POST">
                                                                        {{csrf_field()}}
                                                                        <input type="hidden" name="responseId" value="{{$response->idpost_response}}">
                                                                    </form>

                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td colspan="10"
                                                                        style="text-align: center;font-weight: 500">
                                                                        Sorry No
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
                                    {{--@if(isset($responses))--}}
                                    {{--{{$responses->links()}}--}}
                                    {{--@endif--}}
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

        });

    </script>
@endsection