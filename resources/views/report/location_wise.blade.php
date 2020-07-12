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

                                <select name="village" id="village" onchange="$('#form1').submit()"
                                        class="select2 form-control "
                                >
                                    <option value="">ALL</option>

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
                                                <th onclick="sortTable(0)">CATEGORY <em  style="opacity: 0.5;"  class="float-right mt-1 text-secondary fa fa-sort"></em> </th>
                                                <th onclick="sortTable(1)" style='text-align: center;'>PERCENTAGE  <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
                                                <th onclick="sortTable(2)" style='text-align: center;'>SOLUTIONS  <em style="opacity: 0.5;" class="float-right mt-1 text-secondary fa fa-sort"></em></th>
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
                            <div class='col-md-6'>
                                <div id="chartDiv" class="row"></div>
                            </div>
                            <div id="postData" class='col-md-6 mb-5 text-center'></div>
                        </div>
                    </form>

                </div>
            </div> <!-- ./card -->
            <br/>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->



    <!-- modal start -->

    <div class="modal fade" id="viewPost" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">View Post</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-secondary" id="modelTitle"></h4>
                        </div>
                    </div>
                    {{--<h6 class="text-secondary">Post Text</h6>--}}
                    <hr/>
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-secondary" id="modelEnglish"></h6>
                        </div>
                    </div>
                    {{--<h6 class="text-secondary">Post Attachments</h6>--}}
                    <hr/>
                    <div class="row">
                        <div class="col-md-12" id="modalAttachments"></div>

                    </div>


                </div>
            </div>
        </div>
    </div>
    <!-- modal end -->
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
            goFirstPage();
            $('#form1').submit()
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
                    url: '{{route('report-locationWise')}}',
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

                            let categories = data.success;
                            let posts = data.posts;

                            let total = 0;


                            $.each(categories, function (key, value) {
                                mainArray[key] = [value[0].category.category, 0, 0, 0, 0, 0];
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


                            $.each(posts, function (key, value) {
                                let cat = value.beneficial_category;
                                $.each(cat, function (catKey, catValue) {
                                    let category = catValue.idcategory;

                                    if (postsArray[category] !== undefined) {
                                        postsArray[category].push({value});

                                    }
                                    else {
                                        postsArray[category] = [{value}];
                                    }

                                    if (mainArray[category] !== undefined) {
                                        mainArray[category][4] += 1;
                                    }
                                    else {
                                        mainArray[category] = [catValue.category.category, 0, 0, 0, 1, 0];
                                    }
                                });
                            });

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

                                tableDate += "<td  style='text-align: center;'>" + value[4]  + "</td>";
                                tableDate += "<td  style='text-align: center;'>" + value[1]  + "</td>";
                                tableDate += "<td  style='text-align: center;'>" + value[2]  + "</td>";
                                tableDate += "<td  style='text-align: center;'>" + value[3]  + "</td>";
                                tableDate += "</tr>";
                            });

                            $('#tBody').html(tableDate);
                            goFirstPage();
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

        function goFirstPage() {
            sortTable(1);
            $('.secondPage').fadeOut('slow');
            $('.firstPage').fadeIn('slow');
        }

        function goSecondPage(id) {
            $('#chartDiv').html('');
            let name = mainArray[id][0];
            $('#catName').html(name);
            $('.firstPage').fadeOut('slow');
            $('.secondPage').fadeIn('slow');
            console.log(mainArray[id]);
            if(mainArray[id][5] > 0 ) {
                new Morris.Donut({
                    // ID of the element in which to draw the chart.
                    element: 'chartDiv',
                    // Chart data records -- each entry in this array corresponds to a point on
                    // the chart.
                    data: [
                        {
                            value: mainArray[id][1],
//                                            label: ''+mainArray[id][0]+'',
                            label: 'Questions',
                            labelColor: '#ff353d'
                        },
                        {
                            value: mainArray[id][2],
                            label: 'Proposal',
                            labelColor: 'green'
                        },
                        {
                            value: mainArray[id][3],
                            label: 'Requests',
                            labelColor: 'green'
                        },
                    ],
                    labelColor: ["#9CC4E4"],
                    colors: ['#E53935', 'rgb(0, 188, 212)', 'rgb(255, 152, 0)', 'rgb(0, 150, 136)']
                });
            }
            else{
                $('#chartDiv').html('<div class="col-md-12 text-center"> <p >No community response in this category!</p></div>');
            }
            let divData = "";

            $.each(postsArray[id], function (key, value) {
                divData += "<div onclick='showPostContent(" + value.value.idPost + ")' class='row  postContainer m-2 p-3'>";
                divData += "<div class='col-md-12'>" + value.value.title_en + "</div>";
                divData += "</div>";
            });
            $('#postData').html('').html(divData);
        }


        function showPostContent(id) {
            $.ajax({
                url: '{{route('viewPostAdmin')}}',
                type: 'POST',
                data: {id: id},
                success: function (data) {
                    let postAttachments = data.success.attachments;
                    let postTextEnglish = data.success.text_en;
                    let postTitleEnglish = data.success.title_en;

                    let modalEnglish = $('#modelEnglish');
                    let modalAttachment = $('#modalAttachments');
                    let modalTitle = $('#modelTitle');
                    modalAttachment.html('');
                    modalEnglish.html('');

                    //post title
                    modalTitle.html(postTitleEnglish);
                    //post title end

                    // post text
                    modalEnglish.html(postTextEnglish);
                    //post text end

                    //post attachments
                    $.each(postAttachments, function (key, value) {
                        if (value.file_type == 1) {
                            modalAttachment.append("<img class='rounded postImage m-2' src='" + value.full_path + "' height='200px' width='200px'>");
                        } else if (value.file_type == 2) {
                            modalAttachment.append("" +
                                "<video width='400' controls>" +
                                "<source src='" + value.full_path + "' type='video/mp4'>" +
                                "<source src='" + value.full_path + "' type='video/ogg'> " +
                                "Your browser does not support HTML video." +
                                "</video>" +
                                "");
                        }
                        else if (value.file_type == 3) {
                            modalAttachment.append("" +
                                "<audio width='400' controls>" +
                                "<source src='" + value.full_path + "' type='audio/ogg'>" +
                                "<source src='" + value.full_path + "' type='audio/mpeg'> " +
                                "Your browser does not support the audio element." +
                                "</audio>" +
                                "");
                        }
                        //post attachments end
                    });
                    $('#viewPost').modal('show');
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