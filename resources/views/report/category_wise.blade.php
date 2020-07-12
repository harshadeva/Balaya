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

            <div class="card ">
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
                            <div class="form-group col-md-12">
                                <label for="category"
                                       class="control-label">{{ __('Category') }}</label>

                                <select name="category" id="category" onchange="$('#form1').submit()"
                                        class="select2 form-control "
                                >
                                    <option disabled value="">Choose category</option>

                                    @if($categories != null)
                                        @foreach($categories as $category)
                                            <option value="{{$category->idcategory}}">{{$category->category}}</option>
                                        @endforeach
                                    @endif
                                </select>
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
                                       class="control-label">{{ __('Polling Booth') }}</label>

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

                                <select name="village" id="village"  onchange="$('#form1').submit()"
                                        class="select2 form-control "
                                >
                                    <option value="">ALL</option>

                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-2  ml-auto">
                                <button type="submit" form="form1"
                                        class="btn btn-success btn-block ">{{ __('Analyse Location') }}</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div> <!-- ./card -->
            <br/>
            <div class="card firstPage">
                <div class="card-body">
                    <div onclick="goSecondPage()"  class="row" id="chartDiv">

                    </div>
                </div>
            </div>

            <!----------------------------------------- First Page End -------------------------------------->
            <br/>
            <div class="row secondPage">
                <div class="col-md-12 secondPage">
                    <button onclick="goFirstPage();"
                            class="btn btn-info float-right btn-md "><em
                                class="fa fa-chevron-left"></em> {{ __('Go Back') }} </button>
                </div>
                <div class="col-md-12 text-center">
                    <h3 id="catName"></h3>
                </div>

            </div>
            <div class="row secondPage">
                <div class="col-md-12">
                    <div class="table-rep-plugin">
                        <div class="table-responsive b-0" data-pattern="priority-columns">
                            <table id="categoryTable" class="table table-striped table-bordered"
                                   cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th id="firstColumn" onclick="sortTable(0)"> </th>
                                    <th onclick="sortTable(1)" style='text-align: center;'>PERCENTAGE  <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                    {{--<th onclick="sortTable(2)" style='text-align: center;'>SOLUTIONS  <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>--}}
                                    <th onclick="sortTable(3)" style='text-align: center;'>QUESTIONS  <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                    <th onclick="sortTable(4)" style='text-align: center;'>PROPOSALS  <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                    <th onclick="sortTable(5)" style='text-align: center;'>REQUESTS  <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                </tr>
                                </thead>
                                <tbody id="tBody">
                                </tbody>
                            </table>
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
    <script src="{{ URL::asset('assets/pages/dashborad.js')}}"></script>

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#category").val($("#category option:eq(1)").val()).trigger('change');
            $('#form1').submit();
        });

        let mainArray = {};
        let postsArray = {};

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

                $.ajax({
                    url: '{{route('report-categoryWise')}}',
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
                            goFirstPage();
                            $('#chartDiv').html('');

                            let categories = data.success;
//                            let posts = data.posts;
                            let mainArray = [$('#category option:selected').html(),0,0,0];

                            $.each(categories, function (key, value) {
                                if (value.idsub_category == 1) {
                                    mainArray[1] += 1;
                                }
                                else if (value.idsub_category == 2) {
                                    mainArray[2] += 1;

                                } else if (value.idsub_category == 3) {
                                    mainArray[3] += 1;
                                }
                                //mainArray = [category name, questions, proposal, requests , beneficial]
                            });

//                            $.each(posts, function (key, value) {
//                                let cat = value.beneficial_category;
//                                $.each(cat, function (catKey, catValue) {
//                                    let cat = catValue.idcategory;
//                                    if (mainArray[cat] !== undefined) {
//                                        mainArray[cat][4] += 1;
//                                    }
//                                    else {
//                                        mainArray[cat] = [catValue.category.category, 0, 0,0,1];
//                                    }
//                                });
//                            });


                            barChart();

                            function barChart() {
                                window.barChart = Morris.Bar({
                                    element: 'chartDiv',
                                    data: [
                                        { y: mainArray[0],  b: mainArray[1], c: mainArray[2] , d: mainArray[3]}
                                    ],
                                    xkey: 'y',
                                    ykeys: [ 'b','c','d'],
                                    labels: ['Questions', 'Proposals', 'Requests'],
                                    lineColors: ['#1e88e5','#ff3321'],
                                    lineWidth: '3px',
                                    resize: true,
                                    redraw: true
                                });
                            }



//                            $.each(mainArray, function (key, value) {
//                                new Morris.Donut({
//                                    // ID of the element in which to draw the chart.
//                                    element: 'donut-' + key + '',
//                                    // Chart data records -- each entry in this array corresponds to a point on
//                                    // the chart.
//                                    data: [
//                                        {
//                                            year: '2008',
//                                            value: value[1],
////                                            label: ''+value[0]+'',
//                                            label: 'Questions',
//                                            labelColor: '#ff353d'
//                                        },
//                                        {
//                                            year: '2009',
//                                            value:value[2],
//                                            label: 'Proposal',
//                                            labelColor: 'green'
//                                        },
//                                        {
//                                            year: '2009',
//                                            value:value[3],
//                                            label: 'Requests',
//                                            labelColor: 'green'
//                                        },
//                                        {
//                                            year: '2009',
//                                            value:value[4],
//                                            label: 'Beneficial',
//                                            labelColor: 'green'
//                                        },
//                                    ],
//                                    labelColor: ["#9CC4E4"],
//                                    colors: ['#E53935', 'rgb(0, 188, 212)', 'rgb(255, 152, 0)', 'rgb(0, 150, 136)']
//                                });
//
//                            });

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

        function goFirstPage() {
            sortTable(1);
            $('.secondPage').fadeOut('slow');
            $('.firstPage').fadeIn('slow');
        }

        function goSecondPage() {

            let category = $('#category').val();
            if($('#village').val() != ''){
                level = 4;
                id = $('#village').val();
            }
            else if($('#gramasewaDivision').val() != ''){
                level = 3;
                $('#firstColumn').html("Village  <em  style='opacity: 0.5;'  class='float-right mt-1 text-secondary fa fa-sort'></em>");
                id = $('#gramasewaDivision').val();
            }
            else if($('#pollingBooth').val() != ''){
                level = 2;
                $('#firstColumn').html("Gramasewa Division  <em  style='opacity: 0.5;'  class='float-right mt-1 text-secondary fa fa-sort'></em>");
                id = $('#pollingBooth').val();
            }
            else if($('#electionDivision').val() != ''){
                level = 1;
                $('#firstColumn').html("Polling Booth  <em  style='opacity: 0.5;'  class='float-right mt-1 text-secondary fa fa-sort'></em>");
                id = $('#electionDivision').val();
            }
            else{
                level = 0;
                $('#firstColumn').html("Election Divisions  <em  style='opacity: 0.5;'  class='float-right mt-1 text-secondary fa fa-sort'></em>");
                id = 0;
            }

            $('.firstPage').fadeOut('slow');
            $('.secondPage').fadeIn('slow');

            $.ajax({
                url: '{{route('report-category_data')}}',
                type: 'POST',
                data: {id:id,level:level,category:category},
                success: function (data) {

                    if (data.errors != null) {
                        location.reload();
                    }
                    if (data.success != null) {

//                        let post = data.posts;
                        let response = data.questions;
                        let total = 0;
                        console.log(response);

                        if(level == 0) {
                            $.each(response, function (key, value) {
                                mainArray[key] = [value[0].election_division.name_en, 0, 0, 0, 0, 0];
                                $.each(value, function (key1, value1) {

                                    if (value1.idsub_category == 1) {
                                        mainArray[key][1] += 1; //QUESTIONS
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL
                                    }
                                    else if (value1.idsub_category == 2) {
                                        mainArray[key][2] += 1; //PERSONAL
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL

                                    } else if (value1.idsub_category == 3) {
                                        mainArray[key][3] += 1; // REQUESTS
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL

                                    }
                                });
                                //mainArray = [category name, questions, proposal, requests , beneficial]
                            });
                        }
                        else if(level == 1) {
                            $.each(response, function (key, value) {
                                mainArray[key] = [value[0].polling_booth.name_en, 0, 0, 0, 0, 0];
                                $.each(value, function (key1, value1) {

                                    if (value1.idsub_category == 1) {
                                        mainArray[key][1] += 1; //QUESTIONS
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL
                                    }
                                    else if (value1.idsub_category == 2) {
                                        mainArray[key][2] += 1; //PERSONAL
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL

                                    } else if (value1.idsub_category == 3) {
                                        mainArray[key][3] += 1; // REQUESTS
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL

                                    }
                                });
                                //mainArray = [category name, questions, proposal, requests , beneficial]
                            });
                        }
                        else if(level == 2) {
                            $.each(response, function (key, value) {
                                mainArray[key] = [value[0].gramasewa_division.name_en, 0, 0, 0, 0, 0];
                                $.each(value, function (key1, value1) {

                                    if (value1.idsub_category == 1) {
                                        mainArray[key][1] += 1; //QUESTIONS
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL
                                    }
                                    else if (value1.idsub_category == 2) {
                                        mainArray[key][2] += 1; //PERSONAL
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL

                                    } else if (value1.idsub_category == 3) {
                                        mainArray[key][3] += 1; // REQUESTS
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL

                                    }
                                });
                                //mainArray = [category name, questions, proposal, requests , beneficial]
                            });
                        }
                        else if(level == 3) {
                            $.each(response, function (key, value) {
                                mainArray[key] = [value[0].village.name_en, 0, 0, 0, 0, 0];
                                $.each(value, function (key1, value1) {

                                    if (value1.idsub_category == 1) {
                                        mainArray[key][1] += 1; //QUESTIONS
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL
                                    }
                                    else if (value1.idsub_category == 2) {
                                        mainArray[key][2] += 1; //PERSONAL
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL

                                    } else if (value1.idsub_category == 3) {
                                        mainArray[key][3] += 1; // REQUESTS
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL

                                    }
                                });
                                //mainArray = [category name, questions, proposal, requests , beneficial]
                            });
                        }
                        else if(level == 4) {
                            $.each(response, function (key, value) {
                                mainArray[key] = [$( "#village option:selected" ).text(), 0, 0, 0, 0, 0];
                                $.each(value, function (key1, value1) {

                                    if (value1.idsub_category == 1) {
                                        mainArray[key][1] += 1; //QUESTIONS
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL
                                    }
                                    else if (value1.idsub_category == 2) {
                                        mainArray[key][2] += 1; //PERSONAL
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL

                                    } else if (value1.idsub_category == 3) {
                                        mainArray[key][3] += 1; // REQUESTS
                                        mainArray[key][5] += 1; //CATEGORY TOTAL
                                        total += 1;//TOTAL

                                    }
                                });
                                //mainArray = [category name, questions, proposal, requests , beneficial]
                            });
                        }
                        console.log(mainArray);

                        let tableDate = "";
                        $.each(mainArray, function (key, value) {
                            tableDate += "<tr >";
                            if (mainArray != null) {
                                tableDate += "<td >" + value[0] + "</td>";
                                if (total > 0) {
                                    tableDate += "<td onclick='goSecondPage(" + key + ")'  style='text-align: center;color: #0885ef;cursor: pointer;'>" + ((value[5] / total) * 100).toFixed(1) + "%</td>";

                                }
                                else {
                                    tableDate += "<td  onclick='goSecondPage(" + key + ")'  style='text-align: center;color: #0885ef;cursor: pointer;'>" + ((value[5] / 1) * 100).toFixed(1) + "%</td>";

                                }
                            }
                            else {
                                tableDate += "<td >" + value[0] + "</td>";
                                if (total > 0) {
                                    tableDate += "<td  style='text-align: center;color: #0885ef;cursor: pointer;'><a href='#' > " + ((value[5] / total) * 100).toFixed(1) + "%</a></td>";

                                }
                                else {
                                    tableDate += "<td  style='text-align: center;color: #0885ef;cursor: pointer;'><a href='#' > " + ((value[5] / 1) * 100).toFixed(1) + "%</a></td>";

                                }
                            }

//                            tableDate += "<td  style='text-align: center;'>" + value[4]  + "</td>";
                            tableDate += "<td  style='text-align: center;'>" + value[1]  + "</td>";
                            tableDate += "<td  style='text-align: center;'>" + value[2]  + "</td>";
                            tableDate += "<td  style='text-align: center;'>" + value[3]  + "</td>";
                            tableDate += "</tr>";
                        });

                        $('#tBody').html(tableDate);
                        sortTable(1);
                    }
                }
            });
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
                for (i = 1; i < (rows.length - 1); i++) {
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