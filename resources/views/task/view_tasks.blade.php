@extends('layouts.main')
@section('psStyle')
    <style>

    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="card m-b-20">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">

                            <form action="{{route('viewTasks')}}" method="GET">
                                <div class="row">
                                    {{ csrf_field() }}
                                    <div class="form-group col-md-6 ">
                                        <label for="searchCol">Search By</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <select class="form-control  " name="searchCol"
                                                        id="searchCol" required>
                                                    <option value="1" selected>AGENT FIRST NAME</option>
                                                    <option value="1">AGENT LAST NAME</option>
                                                </select>
                                            </div>
                                            <input class="form-control " type="text" id="searchText"
                                                   name="searchText">
                                        </div>
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
                                                style="margin-top: 28px;">Search
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
                                                    <th>AGENT NAME</th>
                                                    <th>VILLAGE</th>
                                                    <th># OF TARGETS</th>
                                                    <th>ACHIEVED</th>
                                                    <th>STATUS</th>
                                                    <th>CREATED AT</th>
                                                    <th>OPTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($tasks))
                                                    @if(count($tasks) > 0)
                                                        @foreach($tasks as $task)
                                                            <tr id="{{$task->idtask}}">
                                                                <td>{{strtoupper($task->user->userTitle->name_en)}}{{strtoupper($task->user->fName)}} {{strtoupper($task->user->lName)}} </td>
                                                                <td>{{strtoupper($task->user->agent->village->name_en)}} </td>
                                                                <td>{{strtoupper($task->target)}} </td>
                                                                <td>{{strtoupper($task->completed_amount)}} </td>
                                                                @if($task->status == 2)
                                                                    <td>
                                                                        <em style="color: #00a5bb"
                                                                            class="mdi mdi-checkbox-blank-circle"></em>
                                                                        ON GOING
                                                                    </td>
                                                                @elseif($task->status == 1)
                                                                    <td>
                                                                        <em style="color: #0dbb2f"
                                                                            class="mdi mdi-checkbox-blank-circle"></em>
                                                                        COMPLETED
                                                                    </td>
                                                                @elseif($task->status == 0)
                                                                    <td>
                                                                        <em style="color: #ee1b09"
                                                                            class="mdi mdi-checkbox-blank-circle"></em>
                                                                        DEACTIVATED
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <em style="color: #000405"
                                                                            class="mdi mdi-checkbox-blank-circle"></em>
                                                                        UNKNOWN
                                                                    </td>
                                                                @endif
                                                                <td>{{$task->created_at}} </td>

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
                                                                               onclick="viewTask({{$task->idtask}})"
                                                                               class="dropdown-item">View
                                                                            </a>
                                                                            @if($task->status == 1)
                                                                                <a href="#"
                                                                                   onclick="deactivateTask({{$task->idtask}})"
                                                                                   class="dropdown-item">Deactivate
                                                                                </a>
                                                                            @elseif($task->status == 0)
                                                                                <a href="#"
                                                                                   onclick="activateTask({{$task->idtask}})"
                                                                                   class="dropdown-item">Activate
                                                                                </a>
                                                                            @endif
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
                            @if(isset($task))
                                {{$tasks->links()}}
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
                    <h5 class="modal-title mt-0">View Task</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row ">

                                <div class="form-group col-md-3 ">
                                    <label for="taskNo" style="margin-left: 5px;"
                                           class="control-label">{{ __('Task No') }}</label>
                                    <input class="form-control " type="number"
                                           readonly
                                           id="taskNo"
                                           name="taskNo">
                                </div>
                                <div class="form-group col-md-3 ">
                                    <label for="members" style="margin-left: 5px;"
                                           class="control-label">{{ __('Target') }}</label>
                                    <input class="form-control " type="text"
                                           readonly
                                           id="members"
                                           name="members">
                                </div>
                                <div class="form-group col-md-6 ">
                                    <label for="ageComparison" style="margin-left: 5px;"
                                           class="control-label">{{ __('Age') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <select class="form-control  " name="ageComparison"
                                                    onchange="ageChanged(this.value)"
                                                    readonly disabled id="ageComparison" required>
                                                <option value="" selected></option>
                                                <option value="0">EQUAL TO</option>
                                                <option value="1">LESS THAN</option>
                                                <option value="2">GRATER THAN</option>
                                                <option value="3">BETWEEN</option>
                                            </select>
                                        </div>
                                        <input class="form-control " type="number"
                                               readonly
                                               id="minAge" placeholder=""
                                               name="minAge">
                                        <input style="display: none;" class="form-control " type="number"
                                               readonly
                                               id="maxAge" placeholder=""
                                               name="maxAge">

                                    </div>
                                </div>
                                <div id="ethnicityDiv" class="form-group col-md-6">
                                    <label for="ethnicities"
                                           class="control-label">{{ __('Ethnicity') }}</label>

                                    <div id="ethnicities"></div>
                                </div>
                                <div id="religionsDiv" class="form-group col-md-6">
                                    <label for="religions"
                                           class="control-label">{{ __('Religion') }}</label>

                                    <div id="religions"></div>
                                </div>
                                <div id="incomesDiv" class="form-group col-md-6">
                                    <label for="incomes"
                                           class="control-label">{{ __('Nature of income') }}</label>

                                    <div id="incomes"></div>
                                </div>
                                <div id="educationsDiv" class="form-group col-md-6">
                                    <label for="educations"
                                           class="control-label">{{ __('Educational Qualification') }}</label>

                                    <div id="educations"></div>
                                </div>
                                <div id="careerDiv" class="form-group col-md-12">
                                    <label for="careers"
                                           class="control-label">{{ __('Career') }}</label>

                                    <div id="careers"></div>
                                </div>
                                <div id="genderDiv" class="form-group col-md-6">
                                    <label style="margin-left: 5px;"
                                           class="control-label">{{ __('Gender') }}</label>
                                    <div class="row">
                                        <label style="margin-left: 10px;" class="radio-inline"><input disabled
                                                    style="margin-left: 10px;" type="radio" value="0" name="gender"
                                                    checked>&nbsp;{{ __('Any') }}
                                        </label>
                                        <label style="margin-left: 5px;" class="radio-inline"><input disabled
                                                    style="margin-left: 5px;" type="radio" value="1"
                                                    name="gender">&nbsp;{{ __('Male') }}
                                        </label> &nbsp;
                                        &nbsp;
                                        <label style="margin-left: 5px;" class="radio-inline"><input
                                                    style="margin-left: 5px;" type="radio" value="2"
                                                    name="gender">&nbsp;{{ __('Female') }}</label>
                                        <label style="margin-left: 5px;" class="radio-inline"><input disabled
                                                    style="margin-left: 5px;" type="radio" value="3" disabled
                                                    name="gender">&nbsp;{{ __('Other') }}</label>
                                    </div>
                                </div>

                                <div id="jobDiv"  class="form-group col-md-6">
                                    <label style="margin-left: 5px;"
                                           class="control-label">{{ __('Job Sector') }}</label>
                                    <div class="row">
                                        <label style="margin-left: 10px;" class="radio-inline"><input
                                                    style="margin-left: 10px;" type="radio" value="" disabled
                                                    name="jobSector"
                                                    checked>&nbsp;{{ __('Any') }}
                                        </label>
                                        <label style="margin-left: 5px;" class="radio-inline"><input
                                                    style="margin-left: 5px;" type="radio" value="1" disabled
                                                    name="jobSector">&nbsp;{{ __('Government') }}
                                        </label> &nbsp;
                                        &nbsp;
                                        <label style="margin-left: 5px;" class="radio-inline"><input
                                                    style="margin-left: 5px;" type="radio" value="2" disabled
                                                    name="jobSector">&nbsp;{{ __('Private') }}</label>
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

        function viewTask(id) {
            $.ajax({
                url: '{{route('getTaskById')}}',
                data: {id: id},
                type: 'POST',
                success: function (data) {
                    console.log(data.success);
                    let result = data.success;
                    $('#ethnicities').html('');
                    $('#careers').html('');
                    $('#religions').html('');
                    $('#incomes').html('');
                    $('#educations').html('');

                    $('#members').val(result.target + ' Members');
                    $('#taskNo').val(result.task_no);
                    if (result.age != null) {
                        $('#ageComparison').val(result.age.comparison).trigger('change');
                        $('#minAge').val(result.age.minAge);
                        $('#maxAge').val(result.age.maxAge);
                    }
                    else {
                        $('#ageComparison').val('').trigger('change');
                        $('#minAge').val('');
                        $('#maxAge').val('');
                    }
                    $("input:radio[value='" + result.task_gender + "'][name='gender']").prop('checked', true);
                    $("input:radio[value='" + result.task_job_sector + "'][name='jobSector']").prop('checked', true);

                    if (result.ethnicities.length > 0) {
                        $.each(result.ethnicities, function (key, value) {
                            $('#ethnicities').append('<p><em class="fa fa-dot-circle-o"></em> ' + value.ethnicity.name_en + '</p>');
                        });
                    } else {
                        $('#ethnicityDiv').hide();
                    }
                    if (result.careers.length > 0) {
                        $.each(result.careers, function (key, value) {
                            $('#careers').append('<p><em class="fa fa-dot-circle-o"></em> ' + value.career.name_en + '</p>');
                        });
                    } else {
                        $('#careerDiv').hide();
                    }
                    if (result.religions.length > 0) {
                        $.each(result.religions, function (key, value) {
                            $('#religions').append('<p><em class="fa fa-dot-circle-o"></em> ' + value.religion.name_en + '</p>');
                        });
                    } else {
                        $('#religionsDiv').hide();
                    }
                    if (result.incomes.length > 0) {
                        $.each(result.incomes, function (key, value) {
                            $('#incomes').append('<p><em class="fa fa-dot-circle-o"></em> ' + value.income.name_en + '</p>');
                        });
                    } else {
                        $('#incomesDiv').hide();
                    }
                    if (result.educations.length > 0) {
                        $.each(result.educations, function (key, value) {
                            $('#educations').append('<p><em class="fa fa-dot-circle-o"></em> ' + value.education.name_en + '</p>');
                        });
                    } else {
                        $('#educationsDiv').hide();
                    }
                    $('#viewModal').modal('show');
                }
            });
        }

        function ageChanged(id) {
            if (id == 3) {
                $('#maxAge').show();
            } else {
                $('#maxAge').hide();
            }
        }

        function deactivateTask(id) {
            swal({
                title: 'Deactivate this task?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Deactivate!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-success m-l-10',
                buttonsStyling: false
            }).then(function () {

                $.ajax({
                    url: '{{route('deactivateTask')}}',
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        if (data.errors != null) {
                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'DEACTIVATE PROCESS INVALID!',
                                autoHide: true, //true | false
                                delay: 5000, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Something wrong with process.contact administrator..'
                            });
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'TASK DEACTIVATED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Task deactivated successfully.'
                            });
                            location.reload();
                        }

                    }
                });

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
//                if (dismiss === 'cancel') {
//                    swal(
//                        'Cancelled',
//                        'Process has been cancelled',
//                        'error'
//                    )
//                }
            })
        }

        function activateTask(id) {
            swal({
                title: 'Activate this task?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Activate!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-success m-l-10',
                buttonsStyling: false
            }).then(function () {

                $.ajax({
                    url: '{{route('activateTask')}}',
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        if (data.errors != null) {
                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'ACTIVATE PROCESS INVALID!',
                                autoHide: true, //true | false
                                delay: 5000, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Something wrong with process.contact administrator..'
                            });
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'TASK ACTIVATED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Task activated successfully.'
                            });
                            location.reload();
                        }

                    }
                });

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
//                if (dismiss === 'cancel') {
//                    swal(
//                        'Cancelled',
//                        'Process has been cancelled',
//                        'error'
//                    )
//                }
            })
        }

    </script>
@endsection