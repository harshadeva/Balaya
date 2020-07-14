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
                            <form action="{{route('divisionalSecretariat-view')}}" method="GET">
                                <div class="row">
                                    {{ csrf_field() }}


                                    <div class="form-group col-md-4 ">
                                        <label for="secretariatName">Secretariat Name</label>
                                        <input class="form-control " type="text"
                                               placeholder="Search secretariat name here"
                                               id="secretariatName"
                                               name="secretariatName">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>By Created Date</label>

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
                                                    <th>SECRETARIAT NAME</th>
                                                    <th>SINHALA</th>
                                                    <th>TAMIL</th>
                                                    <th>CREATED AT</th>
                                                    <th>DIVISIONS COUNT</th>
                                                    <th style="text-align: center;">OPTIONS</th>
                                                </tr>
                                                </thead>
                                                <tbody id="mainTBody">
                                                @if(isset($secretariats))
                                                    @if(count($secretariats) > 0)
                                                        @foreach($secretariats as $secretariat)
                                                            <tr id="{{$secretariat->iddivisional_secretariat}}">
                                                                <td>{{strtoupper($secretariat->name_en)}} </td>
                                                                <td>{{$secretariat->name_si}} </td>
                                                                <td>{{$secretariat->name_ta}} </td>
                                                                <td>{{$secretariat->created_at}} </td>
                                                                <td>{{$secretariat->divisions()->where('status',1)->count(). ' DIVISIONS'}} </td>
                                                                <td style="text-align: center;">
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
                                                                               onclick="assignedDivision({{$secretariat->iddivisional_secretariat}})"
                                                                               class="dropdown-item">Assign New
                                                                            </a>
                                                                            <a href="#"
                                                                               onclick="viewAssignedDivision({{$secretariat->iddivisional_secretariat}})"
                                                                               class="dropdown-item">View
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td>
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
                            @if(isset($secretariats))
                                {{$secretariats->links()}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->


    <!-- modal start -->
    <div class="modal fade" id="viewModal" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">View Assigned Divisions</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
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
                                                    <th>ELECTION DIVISION</th>
                                                    <th>MEMBER DIVISION</th>
                                                    <th>GRAMASEWA DIVISION</th>
                                                </tr>
                                                </thead>
                                                <tbody id="viewTBody">
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
        </div>
    </div>
    <!-- modal end -->


    <!-- modal start -->
    <div class="modal fade" id="assignModal" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Assign Gramasewa Division</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <input type="text" placeholder="Search election division"
                                           class="float-right form-control filters" id="searchE">
                                </div>
                                <div class="col-md-4 mb-1">
                                    <input type="text" placeholder="Search member division"
                                           class="float-right form-control filters" id="searchP">
                                </div>
                                <div class="col-md-4 mb-1">
                                    <input type="text" placeholder="Search gramasewa division"
                                           class="float-right form-control filters" id="searchG">
                                </div>
                                <div class="col-md-12">
                                    <button style="display: none;" id="assignBtn"
                                           onclick="assignDivision()" class="btn btn-primary float-right m-2">Save Selected
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" id="secretariatId" >
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive b-0" data-pattern="priority-columns">
                                            <table class="table table-striped table-bordered"
                                                   cellspacing="0"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>ELECTION DIVISION</th>
                                                    <th>MEMBER DIVISION</th>
                                                    <th>GRAMASEWA DIVISION</th>
                                                    <th>ACTIONS</th>
                                                </tr>
                                                </thead>
                                                <tbody id="assignTBody">
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
        </div>
    </div>
    <!-- modal end -->
@endsection
@section('psScript')

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            initializeDate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        let divisionsArray = [];

        function viewAssignedDivision(id) {
            $.ajax({
                url: '{{route('getGramasewaBySecretariat')}}',
                type: 'POST',
                data: {id: id},
                success: function (data) {
                    if (data.errors != null) {
                        $('#errorAlertAssign').show();
                        $.each(data.errors, function (key, value) {
                            notify({
                                type: "error", //error | success | error | warning | info
                                title: 'PROCESS FAILED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: '' + value + ''
                            });
                        });
                        $('html, body').animate({
                            scrollTop: $("body").offset().top
                        }, 1000);
                    }
                    if (data.success != null) {

                        let divisions = data.success;
                        $('#viewTBody').html('');
                        $.each(divisions, function (key, value) {
                            $('#viewTBody').append('' +
                                '<tr>' +
                                '<td>' + value.polling_booth.election_division.name_en + '</td>' +
                                '<td>' + value.polling_booth.name_en + '</td>' +
                                '<td>' + value.name_en + '</td>' +
                                '</tr>');
                        });
                        $('#viewModal').modal('show');
                    }
                }
            });
        }

        function assignedDivision(id) {
            $('#secretariatId').val(id);
            $('.alert').hide();
            $('.alert').html("");

            $.ajax({
                url: '{{route('getUnAssignedGramasewaDivisions')}}',
                type: 'POST',
                data: {id: id},
                success: function (data) {
                    if (data.errors != null) {
                        $('#errorAlertAssign').show();
                        $.each(data.errors, function (key, value) {
                            notify({
                                type: "error", //error | success | error | warning | info
                                title: 'PROCESS FAILED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: '' + value + ''
                            });
                        });
                        $('html, body').animate({
                            scrollTop: $("body").offset().top
                        }, 1000);
                    }
                    if (data.success != null) {

                        let divisions = data.success;
                        $('#assignTBody').html('');
                        if(divisions.length > 0) {
                            $.each(divisions, function (key, value) {
                                $('#assignTBody').append("" +
                                    "<tr>" +
                                    "<td>" + value.polling_booth.election_division.name_en + "</td>" +
                                    "<td>" + value.polling_booth.name_en + "</td>" +
                                    "<td>" + value.name_en + "</td>" +
                                    "<td> <input type='checkbox' onchange='divisionSelected(" + value.idgramasewa_division + ")' id='check-" + value.idgramasewa_division + "' name='gramasewa'>" +
                                    "</tr>");
                            });
                        }
                        else{
                            $('#assignTBody').html('<tr><td class="text-center" colspan="8">All Gramasewa divisions has assigned in this district.</td> </tr>');
                        }
                        divisionsArray = [];
                        $('#assignBtn').hide();
                        $('#assignModal').modal('show');
                    }

                }
            });
        }

        function divisionSelected(id) {
            let isChecked = $('#check-' + id).prop("checked");
            if (isChecked == true) {
                if ($.inArray(id, divisionsArray) == -1) {

                    divisionsArray.push(id);
                }
            }
            else {
                divisionsArray = jQuery.grep(divisionsArray, function (value) {
                    return value != id;
                });
            }

            if (divisionsArray.length > 0) {

                $('#assignBtn').show();
            }
            else {
                $('#assignBtn').hide();

            }

        }

        $('.modal').on('hidden.bs.modal', function () {
            divisionsArray = [];
            $('#assignBtn').hide();
        });


//        $(".filters").keyup(function () {
//
//            $("#assignTBody tr").each(function () {
//                var filter0 = $('#searchE').val();
//                // If the list item does not contain the text phrase fade it out
//                if ($(this).find("td").eq(0).text().search(new RegExp(filter0, "i")) < 0) {
//                    $(this).fadeOut();
//
//                    // Show the list item if the phrase matches and increase the count by 1
//                } else {
//                    // Retrieve the input field text and reset the count to zero
//                    var filter = $('#searchP').val();
//
//                    // Loop through the comment list
//                    $("#assignTBody tr").each(function () {
//
//                        // If the list item does not contain the text phrase fade it out
//                        if ($(this).find("td").eq(1).text().search(new RegExp(filter, "i")) < 0) {
//                            $(this).fadeOut();
//
//                            // Show the list item if the phrase matches and increase the count by 1
//                        } else {
//                            // Retrieve the input field text and reset the count to zero
//                            var filter3 = $('#searchG').val();
//
//                            // Loop through the comment list
//                            $("#assignTBody tr").each(function () {
//
//                                // If the list item does not contain the text phrase fade it out
//                                if ($(this).find("td").eq(2).text().search(new RegExp(filter3, "i")) < 0) {
//                                    $(this).fadeOut();
//
//                                    // Show the list item if the phrase matches and increase the count by 1
//                                } else {
//                                    $(this).show();
//                                }
//                            });
//                        }
//                    });
//                }
//            });
//        });

        $("#searchE").keyup(function () {

            // Retrieve the input field text and reset the count to zero
            var filter = $(this).val();

            // Loop through the comment list
            $("#assignTBody tr").each(function () {

                // If the list item does not contain the text phrase fade it out
                if ($(this).find("td").eq(0).text().search(new RegExp(filter, "i")) < 0) {
                    $(this).fadeOut();

                    // Show the list item if the phrase matches and increase the count by 1
                } else {
                    $(this).show();
                }
            });
        });

        $("#searchP").keyup(function () {

            // Retrieve the input field text and reset the count to zero
            var filter = $(this).val();

            // Loop through the comment list
            $("#assignTBody tr").each(function () {

                // If the list item does not contain the text phrase fade it out
                if ($(this).find("td").eq(1).text().search(new RegExp(filter, "i")) < 0) {
                    $(this).fadeOut();

                    // Show the list item if the phrase matches and increase the count by 1
                } else {
                    $(this).show();
                }
            });
        });

        $("#searchG").keyup(function () {

            // Retrieve the input field text and reset the count to zero
            var filter = $(this).val();

            // Loop through the comment list
            $("#assignTBody tr").each(function () {

                // If the list item does not contain the text phrase fade it out
                if ($(this).find("td").eq(2).text().search(new RegExp(filter, "i")) < 0) {
                    $(this).fadeOut();

                    // Show the list item if the phrase matches and increase the count by 1
                } else {
                    $(this).show();
                }
            });
        });

        function assignDivision() {
            let id = $('#secretariatId').val();
            $('.alert').hide();
            $('.alert').html("");

            $.ajax({
                url: '{{route('assignedGramasewaDivisions')}}',
                type: 'POST',
                data: {id: id,divisionsArray:divisionsArray},
                success: function (data) {
                    if (data.errors != null) {
                        $('#errorAlertAssign').show();
                        $.each(data.errors, function (key, value) {
                            notify({
                                type: "error", //error | success | error | warning | info
                                title: 'PROCESS FAILED!',
                                autoHide: true, //true | false
                                delay: 5000, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: '' + value + ''
                            });
                        });
                        $('html, body').animate({
                            scrollTop: $("body").offset().top
                        }, 1000);
                    }
                    if (data.success != null) {

                        notify({
                            type: "success", //error | success | error | warning | info
                            title: 'GRAMASEWA DIVISION ASSIGNED!',
                            autoHide: true, //true | false
                            delay: 5000, //number ms
                            position: {
                                x: "right",
                                y: "top"
                            },
                            icon: '<em class="mdi mdi-check-circle-outline"></em>',

                            message: 'Gramasewa division assigned successfully!'
                        });
                    }
                    $('#' + id).find("td").eq(3).html(data.count + ' DIVISIONS');
                    $('#assignModal').modal('hide');

                }
            });
        }
    </script>
@endsection