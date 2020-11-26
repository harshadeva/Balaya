@extends('layouts.main')
@section('psStyle')
    {{--<!--Morris Chart CSS -->--}}
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/morris/morris.css')}}">

    <style>
        .postContainer {
            border: solid 1px #b9b9b9;
            border-radius: 10px;
            /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#ebf1f6+0,abd3ee+50,89c3eb+51,d5ebfb+100;Blue+Gloss+%234 */
            background: #ebf1f6; /* Old browsers */
            background: -moz-linear-gradient(-45deg, #ebf1f6 0%, #abd3ee 50%, #89c3eb 51%, #d5ebfb 100%); /* FF3.6-15 */
            background: -webkit-linear-gradient(-45deg, #ebf1f6 0%, #abd3ee 50%, #89c3eb 51%, #d5ebfb 100%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(135deg, #ebf1f6 0%, #abd3ee 50%, #89c3eb 51%, #d5ebfb 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ebf1f6', endColorstr='#d5ebfb', GradientType=1); /* IE6-9 fallback on horizontal gradient */

        }

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
                        <div class="row">

                            <div class="form-group col-md-6">
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
                            <div class="form-group col-md-6">
                                <label for="pollingBooth"
                                       class="control-label">{{ __('Member Division') }}</label>

                                <select name="pollingBooth" id="pollingBooth"
                                        class="select2 form-control "
                                        onchange="pollingBoothChanged(this);$('#form1').submit()"
                                >
                                    <option value="">ALL</option>


                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="gramasewaDivision"
                                       class="control-label">{{ __('Gramasewa Divisions') }}</label>

                                <select name="gramasewaDivision" id="gramasewaDivision"
                                        class="select2 form-control "
                                        onchange="gramasewaDivisionChanged(this);$('#form1').submit()"
                                >
                                    <option value="">ALL</option>

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="village"
                                       class="control-label">{{ __('Villages') }}</label>

                                <select name="village" id="village" onchange="$('#form1').submit()"
                                        class="select2 form-control "
                                >
                                    <option value="">ALL</option>

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="voterCategory"
                                       class="control-label">{{ __('Voter Category') }}</label>

                                <select name="voterCategory" id="voterCategory"
                                        class="select2 form-control "
                                        onchange="$('#form1').submit()"
                                >
                                    <option value="">ALL</option>

                                    @if($votersConditions != null)
                                        @foreach($votersConditions as $votersCondition)
                                            <option value="{{$votersCondition->idhouse_member}}">{{strtoupper($votersCondition->name_en)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="houseCondition"
                                       class="control-label">{{ __('House Condition') }}</label>

                                <select name="houseCondition" id="houseCondition"
                                        class="select2 form-control "
                                        onchange="$('#form1').submit()"
                                >
                                    <option value="">ALL</option>

                                    @if($houseConditions != null)
                                        @foreach($houseConditions as $houseCondition)
                                            <option value="{{$houseCondition->idhouse_condition}}">{{strtoupper($houseCondition->name_en)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row firstPage">
                            <div class="col-md-12">
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table id="categoryTable" class="table table-striped table-bordered"
                                               cellspacing="0"
                                               width="100%">
                                            <thead>
                                            <tr>
                                                <th onclick="sortTable(0)">AREA<em  style="opacity: 0.5;"  class="float-right mt-1 text-secondary fa fa-sort"></em> </th>
                                                <th onclick="sortTable(1)" style='text-align: center;'>TOTAL CANVASSING<em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                                <th onclick="sortTable(2)" style='text-align: center;'>TOTAL VOTERS<em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                                <th onclick="sortTable(3)" style='text-align: center;'>FLAVORED  <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                                <th nowrap="true" onclick="sortTable(4)" style='text-align: center;'>PERCENTAGE (%) <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                                <th onclick="sortTable(5)" style='text-align: center;'>PARTY  <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                                <th onclick="sortTable(6)" style='text-align: center;'>OPPOSITE <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                                <th onclick="sortTable(7)" style='text-align: center;'>FLOATING <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                                <th onclick="sortTable(8)" style='text-align: center;'>OPTION 1 <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                                <th onclick="sortTable(9)" style='text-align: center;'>OPTION 2 <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                                <th onclick="sortTable(10)" style='text-align: center;'>OPTION 3 <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                                <th onclick="sortTable(11)" style='text-align: center;'>HOUSES <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                            </tr>
                                            </thead>
                                            <tbody id="tBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div> <!-- ./card -->
            <br/>

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

        $("#form1").on("submit", function (event) {
            mainArray = {};
            postsArray = {};
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
                $('#tBody').html('<tr style="text-align: center;"><td colspan="15">Loading...</td></tr> ');

                $.ajax({
                    url: '{{route('report-votersCount')}}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        $('#tBody').html('');
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

                            let areas = data.success;
                            let lastRow = data.lastRow;
                            console.log(lastRow);

                            let tableDate = '';

                            $.each(areas, function (key, value) {
                                tableDate += "<tr>";
                                tableDate += "<td style='color: #1b72ee;cursor: pointer;' onclick='areaClicked("+value.id+",\""+value.dropDown+"\")'>"+value.name+"</td>";
                                tableDate += "<td>"+value.canvassingCount+"</td>";
                                tableDate += "<td>"+value.totalVoters+"</td>";
                                tableDate += "<td>"+value.flavored+"</td>";
                                tableDate += "<td>"+value.percentage+"</td>";
                                tableDate += "<td>"+value.party+"</td>";
                                tableDate += "<td>"+value.opposite+"</td>";
                                tableDate += "<td>"+value.floating+"</td>";
                                tableDate += "<td>"+value.option1+"</td>";
                                tableDate += "<td>"+value.option2+"</td>";
                                tableDate += "<td>"+value.option3+"</td>";
                                tableDate += "<td>"+value.totalHouses+"</td>";
                                tableDate += "</tr>";
                            });

                            $('#tBody').html(tableDate);

                            let totalTBody = '';
                            totalTBody += "<tr id='lastRow' style='font-weight: 700;font-style: italic;'>";
                            totalTBody += "<td>TOTAL</td>";
                            totalTBody += "<td>"+lastRow.canvassingCount+"</td>";
                            totalTBody += "<td>"+lastRow.totalVoters+"</td>";
                            totalTBody += "<td>"+lastRow.flavored+"</td>";
                            totalTBody += "<td>"+lastRow.percentage+"</td>";
                            totalTBody += "<td>"+lastRow.party+"</td>";
                            totalTBody += "<td>"+lastRow.opposite+"</td>";
                            totalTBody += "<td>"+lastRow.floating+"</td>";
                            totalTBody += "<td>"+lastRow.option1+"</td>";
                            totalTBody += "<td>"+lastRow.option2+"</td>";
                            totalTBody += "<td>"+lastRow.option3+"</td>";
                            totalTBody += "<td>"+lastRow.totalHouses+"</td>";
                            totalTBody += "</tr>";

                            $('#tBody').append(totalTBody);
                            sortTable(1);
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


        function areaClicked(id,dropDown) {
            $('#'+dropDown).val(parseInt(id)).trigger('change');
        }

        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("categoryTable");
            switching = true;
            // Set the sorting direction to ascending:
            dir = "asc";
            /* Make a loop that will continue until
             no switching has been done: */
            while (switching) {
                // Start by saying: no switching is done:
                switching = false;
                rows = table.rows;
                /* Loop through all table rows (except the
                 first, which contains table headers): */
                for (i = 1; i < (rows.length - 2); i++) {
                    // Start by saying there should be no switching:
                    shouldSwitch = false;
                    /* Get the two elements you want to compare,
                     one from current row and one from the next: */
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    /* Check if the two rows should switch place,
                     based on the direction, asc or desc: */
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    /* If a switch has been marked, make the switch
                     and mark that a switch has been done: */
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    // Each time a switch is done, increase this count by 1:
                    switchcount ++;
                } else {
                    /* If no switching has been done AND the direction is "asc",
                     set the direction to "desc" and run the while loop again. */
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
@endsection