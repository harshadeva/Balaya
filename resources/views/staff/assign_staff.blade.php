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
                        <div class="form-group col-md-6 ">
                            <label for="searchName">Search by name</label>
                            <input class="form-control " type="text" id="searchName"
                                 placeholder="Type name here"      name="searchName">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-rep-plugin">
                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                    <table class="table table-striped table-bordered"
                                           cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>FULL NAME</th>
                                            <th># OF ASSIGNED DIVISIONS</th>
                                            <th style="text-align: center;">OPTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($users))
                                            @if(count($users) > 0)
                                                @foreach($users as $user)
                                                    <tr id="{{$user->idUser}}">
                                                        <td>{{$user->userTitle->name_en}} {{$user->fName}} {{$user->lName}}</td>
                                                        <td>{{$user->assignedElectionDivisions()->count()}} DIVISIONS
                                                        </td>
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
                                                                       onclick="assignStaff({{$user->idUser}})"
                                                                       class="dropdown-item">Assign New
                                                                    </a>
                                                                    <a href="#"
                                                                       onclick="viewAssignedDivision({{$user->idUser}})"
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
                    @if(isset($users))
                        {{$users->links()}}
                    @endif

                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->


    <!-- modal start -->
    <div class="modal fade" id="assignModal" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Assign Staff</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form class="form-horizontal" id="form1" role="form">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-dismissible " id="errorAlertAssign"
                                             style="display:none">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6  form-group">
                                        <label for="electionDivisions"
                                               class="control-label">{{ __('Election Divisions') }}</label>

                                        <select name="electionDivisions[]" id="electionDivisions"
                                                class="select2 form-control select2-multiple" multiple="multiple"
                                                multiple
                                                data-placeholder="Choose ...">
                                            @if($electionDivisions != null)
                                                @foreach($electionDivisions as $electionDivision)
                                                    <option value="{{$electionDivision->idelection_division}}">{{strtoupper($electionDivision->name_en)}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <input type="hidden" name="staffId" id="staffId">
                                    <div style="margin-top: 27px;" class="form-group col-md-3 ml-auto">
                                        <button type="submit"
                                                class="btn btn-primary btn-block ">{{ __('Assign  Staff') }}</button>
                                    </div>
                                    <div style="margin-top: 27px;" class="form-group col-md-3">
                                        <button type="submit" onclick="closeModal();event.preventDefault();"
                                                class="btn btn-danger btn-block ">{{ __('Cancel') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- modal end -->


    <!-- modal start -->
    <div class="modal fade" id="viewModal" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
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

        function assignStaff(id) {
            $('#staffId').val(id);
            $('.alert').hide();
            $('.alert').html("");
            $('#assignModal').modal('show');
        }



        function closeModal() {
            $('#electionDivisions').val('').trigger('change');
            $('#staffId').val('');
            $('#assignModal').modal('hide');
            $('.alert').hide();
            $('.alert').html("");
        }


        $("#form1").on("submit", function (event) {
            event.preventDefault();

            //initialize alert and variables
            $('.notify').empty();
            $('.alert').hide();
            $('.alert').html("");
            let completed = true;
            let id = $('#staffId').val();
            //initialize alert and variables end


            //validate user input

            //validation end

            if (completed) {

                $.ajax({
                    url: '{{route('assignStaff')}}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        console.log(data);
                        if (data.errors != null) {
                            $('#errorAlertAssign').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlertAssign').append('<p>' + value + '</p>');
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {
                            $('#' + id).find("td").eq(1).html(data.count + ' ELECTION DIVISIONS');
                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'STAFF ASSIGNED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Staff assigned successfully.'
                            });
                            closeModal();
                        }
                    }


                });
            }
            else {
                $('#errorAlert').html('Please provide all required fields.');
                $('#errorAlert').show();
                $('html, body').animate({
                    scrollTop: $("body").offset().top
                }, 1000);
            }
        });

        function viewAssignedDivision(id) {
            $.ajax({
                url: '{{route('viewAssignedDivision')}}',
                type: 'POST',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    if (data.errors != null) {
                        $('#errorAlertAssign').show();
                        $.each(data.errors, function (key, value) {
                            notify({
                                type: "success", //error | success | error | warning | info
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
                                '<td>' + value.election_division.name_en + '</td>' +
                                '</tr>');
                        });
                        $('#viewModal').modal('show');
                    }
                }
            });
        }

        $("#searchName").keyup(function () {

            // Retrieve the input field text and reset the count to zero
            var filter = $(this).val();

            // Loop through the comment list
            $("tbody tr").each(function () {

                // If the list item does not contain the text phrase fade it out
                if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                    $(this).fadeOut();

                    // Show the list item if the phrase matches and increase the count by 1
                } else {
                    $(this).show();
                }
            });
        });

        $('.modal').on('hidden.bs.modal', function () {
            $('select').val('').trigger('change') ;
        });

    </script>
@endsection