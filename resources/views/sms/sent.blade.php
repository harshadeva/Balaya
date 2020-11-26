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
                            <form action="{{route('sentSms')}}" method="GET">
                                <div class="row">
                                    {{ csrf_field() }}

                                    <div class="form-group col-md-3">
                                        <label for="user">By Staff</label>

                                        <select class="form-control select2" name="user"
                                                id="user">
                                            <option value="" disabled selected>Select user
                                            </option>
                                            @if($users != null)
                                                @foreach($users as $user)
                                                    <option value="{{$user->idUser}}">{{$user->fName}} {{$user->lName}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>By Sent Date</label>

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
                                                    <th>STAFF</th>
                                                    <th>SMS CONTENT</th>
                                                    <th>NO OF RECEIVERS</th>
                                                    <th>TYPE</th>
                                                    <TH>CREATED AT</TH>
                                                    <TH>SENT AT</TH>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($messages))
                                                    @if($messages != null)
                                                        @foreach($messages as $message)
                                                            <tr id="{{$message->idsms}}">
                                                                <td>{{strtoupper($message->user->fName)}} {{strtoupper($message->user->lName)}} </td>
                                                                <td>{{$message->body}}</td>
                                                                <td>{{$message->receivers()->count()}}</td>
                                                                @if($message->type == 1)
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-success"></em> COMMUNITY</td>
                                                                @elseif($message->type == 1)
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-primary"></em> GROUP</td>
                                                                @else
                                                                    <td nowrap><em class="mdi mdi-checkbox-blank-circle text-info"></em> OTHER</td>
                                                                @endif
                                                                <td>{{$message->created_at}}</td>
                                                                <td>{{$message->updated_at}}</td>
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
                            @if(isset($messages))
                                {{$messages->links()}}
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

    </script>
@endsection