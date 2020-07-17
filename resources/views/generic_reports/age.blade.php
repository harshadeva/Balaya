@extends('layouts.main')
@section('psStyle')
    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/morris/morris.css')}}">
    <style>

    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <div class="card">
                <div class="card-body">
                    <form id="form1" method="GET">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible " id="errorAlert"
                                     style="display:none">
                                </div>
                            </div>
                        </div>
                        <div class="row ">

                            <div class="form-group col-md-3">
                                <label for="age">{{ __('Enter Age Here') }}</label>
                                <div>
                                    <div class="input-group">

                                        <input autocomplete="off" type="number" class="form-control" required
                                               oninput="setCustomValidity('');this.value  = this.value < 0 ? 0 : this.value;$('#form1').submit()"
                                               value="30"
                                               oninvalid="this.setCustomValidity('Please enter age here')"
                                               placeholder="" name="age" id="age">
                                        <div class="input-group-append">
                                            <span class="input-group-text">YEARS</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group col-md-4">
                                <label for="electionDivision"
                                       class="control-label">{{ __('Election Division') }}</label>

                                <select name="electionDivision" id="electionDivision"
                                        class="select2 form-control "
                                        onchange="electionDivisionChanged(this);$('#form1').submit()"
                                >
                                    <option value="">ALL</option>

                                    @if($electionDivisions != null)
                                        @foreach($electionDivisions as $electionDivision)
                                            <option value="{{$electionDivision->idelection_division}}">{{strtoupper($electionDivision->name_en)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="pollingBooth"
                                       class="control-label">{{ __('Member Division') }}</label>

                                <select name="pollingBooth" id="pollingBooth"
                                        class="select2 form-control "
                                        onchange="pollingBoothChanged(this);$('#form1').submit()"
                                >
                                    <option value="">ALL</option>


                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="gramasewaDivision"
                                       class="control-label">{{ __('Gramasewa Divisions') }}</label>

                                <select name="gramasewaDivision" id="gramasewaDivision"
                                        class="select2 form-control "
                                        onchange="gramasewaDivisionChanged(this);$('#form1').submit()"
                                >
                                    <option value="">ALL</option>

                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="village"
                                       class="control-label">{{ __('Villages') }}</label>

                                <select name="village" id="village" onchange="$('#form1').submit()"
                                        class="select2 form-control "
                                >
                                    <option value="">ALL</option>

                                </select>
                            </div>


                            <div class="col-md-2">
                                <button type="submit" form="form1" style="margin-top: 27px;"
                                        class="btn btn-success btn-block ">{{ __('Analyse Age') }}</button>
                            </div>
                        </div>

                    </form>
                    <br/>
                    <br/>
                    <div class="row">
                        <div class='col-md-6 mb-5 text-center'>
                            <div class='text-secondary ' id='agent-chart' style='height: 250px;font-weight: 700;'>

                            </div>
                            <h6>Agents <em id="agentsCount"></em> </h6>
                        </div>
                        <div class='col-md-6 mb-5 text-center'>
                            <div class='text-secondary ' id='member-chart' style='height: 250px;font-weight: 700;'>

                            </div>
                            <h6>Members <em id="memberCount"></em> </h6>
                        </div>
                    </div>

                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->
@endsection
@section('psScript')

    <!--Morris Chart-->
    <script src="{{ URL::asset('assets/plugins/morris/morris.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/raphael/raphael-min.js')}}"></script>

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#form1').submit()
        });

        $("#form1").on("submit", function (event) {

            event.preventDefault();

            //initialize alert and variables
            $('.notify').empty();
            $('.alert').hide();
            $('.alert').html("");
            let completed = true;
            //initialize alert and variables end


            //validate user input

            //validation end

            if (completed) {

                $.ajax({
                    url: '{{route('report-age')}}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.errors != null) {
                            $('#errorAlert').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlert').append('<p><em class="fa fa-bullhorn"> ' + value + '</em></p>');
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {

                            console.log(data.success);
                            $('#agent-chart').html('');
                            $('#member-chart').html('');
                            $('#agentsCount').html(' ('+data.success.agent_count+')');
                            $('#memberCount').html(' ('+data.success.member_count+')');
                            let agent_min = data.success.agent_min;
                            let agent_max = data.success.agent_max;
                            let agent_equal = data.success.agent_equal;
                            let member_max = data.success.member_max;
                            let member_min = data.success.member_min;
                            let member_equal = data.success.member_equal;


                            new Morris.Donut({
                                // ID of the element in which to draw the chart.
                                element: 'agent-chart',
                                // Chart data records -- each entry in this array corresponds to a point on
                                // the chart.
                                data: [
                                    {
                                        value: agent_min,
                                        label: 'Bellow',
                                        labelColor: '#ff353d'
                                    },
                                    {
                                        value: agent_equal,
                                        label: 'Equal',
                                        labelColor: '#159cff'
                                    },
                                    {
                                        value: agent_max,
                                        label: 'Grater',
                                        labelColor: '#26ff68'
                                    },
                                ],
                                labelColor: ["#9CC4E4"],
                                colors: ['#E53935', 'rgb(0, 188, 212)', 'rgb(255, 152, 0)', 'rgb(0, 150, 136)']
                            });

                            new Morris.Donut({
                                // ID of the element in which to draw the chart.
                                element: 'member-chart',
                                // Chart data records -- each entry in this array corresponds to a point on
                                // the chart.
                                data: [
                                    {
                                        value: member_min,
                                        label: 'Bellow',
                                        labelColor: '#ff353d'
                                    },
                                    {
                                        value: member_equal,
                                        label: 'Equal',
                                        labelColor: '#159cff'
                                    },
                                    {
                                        value: member_max,
                                        label: 'Grater',
                                        labelColor: '#26ff68'
                                    },
                                ],
                                labelColor: ["#9CC4E4"],
                                colors: ['#E53935', 'rgb(0, 188, 212)', 'rgb(255, 152, 0)', 'rgb(0, 150, 136)']
                            });

                        }
                    }


                });
            }
            else {
                $('#errorAlert2').html('Please provide all required fields.');
                $('#errorAlert2').show();
                $('html, body').animate({
                    scrollTop: $("body").offset().top
                }, 1000);
            }
        });


        function electionDivisionChanged(el) {
            let divisions = $(el).val();
            $('#pollingBooth').html("<option value=''>ALL</option>");
            $('#gramasewaDivision').html("<option value=''>ALL</option>");
            $('#village').html("<option value=''>ALL</option>");

            $.ajax({
                url: '{{route('getPollingBoothByElectionDivision')}}',
                type: 'POST',
                data: {id: divisions},
                success: function (data) {
                    let result = data.success;
                    $.each(result, function (key, value) {
                        $('#pollingBooth').append("<option value='" + value.idpolling_booth + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }

        function pollingBoothChanged(el) {
            let booths = $(el).val();
            $('#gramasewaDivision').html("<option value=''>ALL</option>");
            $('#village').html("<option value=''>ALL</option>");

            $.ajax({
                url: '{{route('getGramasewaDivisionByPollingBooth')}}',
                type: 'POST',
                data: {id: booths},
                success: function (data) {
                    let result = data.success;
                    $.each(result, function (key, value) {
                        $('#gramasewaDivision').append("<option value='" + value.idgramasewa_division + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }

        function gramasewaDivisionChanged(el) {
            let division = el.value;
            $('#village').html("<option value=''>ALL</option>");
            $.ajax({
                url: '{{route('getVillageByGramasewaDivision')}}',
                type: 'POST',
                data: {id: division},
                success: function (data) {
                    let result = data.success;
                    $.each(result, function (key, value) {
                        $('#village').append("<option value='" + value.idvillage + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }
    </script>
@endsection