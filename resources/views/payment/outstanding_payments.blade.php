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
                            <form action="{{route('viewOutstandingPayments')}}" method="GET">
                                <div class="row">
                                    {{ csrf_field() }}


                                    <div class="form-group col-md-3">
                                        <label for="office" class="control-label">{{ __('Office') }}</label>
                                        <select id="office" name="office" class="form-control">
                                            <option value="" disabled selected>Select Office</option>
                                            @if($offices != null)
                                                @foreach($offices as $office)
                                                    <option value="{{$office->idoffice}}">{{$office->office_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <button type="submit"
                                                class="btn form-control text-white btn-info waves-effect waves-light"
                                                style="margin-top: 21px;">Search
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
                                                    <th>OFFICE</th>
                                                    <th style="text-align: right;">MONTHLY PAYMENT</th>
                                                    <th>LAST PAID MONTH</th>
                                                    <th style="text-align: right;">TOTAL OUTSTANDING</th>
                                                    {{--<th>OPTION</th>--}}
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($outstandingOffices))
                                                    @if(count($outstandingOffices) > 0)
                                                        @foreach($outstandingOffices as $office)
                                                            <tr id="{{$office->idoffice}}">
                                                                <td>{{strtoupper($office->office_name)}} </td>
                                                                <td style="text-align: right;">{{number_format($office->monthly_payment,2)}} </td>
                                                                <td>{{$office->last_payment_date}}</td>
                                                                <td style="text-align: right;">{{number_format($office->outstanding_total,2)}}</td>
                                                                {{--<td>--}}
                                                                    {{--<div class="dropdown">--}}
                                                                        {{--<button class="btn btn-secondary btn-sm dropdown-toggle"--}}
                                                                                {{--type="button" id="dropdownMenuButton"--}}
                                                                                {{--data-toggle="dropdown"--}}
                                                                                {{--aria-haspopup="true"--}}
                                                                                {{--aria-expanded="false">--}}
                                                                            {{--Option--}}
                                                                        {{--</button>--}}

                                                                        {{--<div class="dropdown-menu"--}}
                                                                             {{--aria-labelledby="dropdownMenuButton">--}}
                                                                            {{--<a href="#" class="dropdown-item">Edit--}}
                                                                            {{--</a>--}}
                                                                        {{--</div>--}}


                                                                    {{--</div>--}}
                                                                {{--</td>--}}
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
                            @if(isset($outstandingOffices))
                                {{$outstandingOffices->links()}}
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

        });

    </script>
@endsection