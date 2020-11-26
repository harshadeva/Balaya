@extends('layouts.main')
@section('psStyle')
    <style>

        #membersV {
            height: 50px;
            width: 50px;
            border-radius: 10px;
            border: solid 1px #4c7bff;
            text-align: center;
        }
    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <div class="card secondPage">
                <form id="form1" method="GET">

                    <div class="card-body">
                        <div class="row secondPage">

                            <div class="form-group col-md-4 ">
                                <label for="taskType" style="margin-left: 5px;"
                                       class="control-label">{{ __('Estimation Type') }}</label>

                                <select class="form-control noClear" name="taskType"
                                        onchange="loadBudget()"
                                        id="taskType" required>
                                    <option value="" disabled selected>Select type</option>
                                    @foreach($taskTypes as $taskType)
                                        <option value="{{$taskType->idtask_type}}">{{$taskType->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="totalBudget" style="margin-left: 5px;"
                                       class="control-label">{{ __('Total Estimation') }}</label>
                                <div class="input-group">

                                    <input class="form-control totalBudget" type="number"
                                           oninput="this.value = this.value < 0 ? 0 : this.value" min="0"
                                           id="totalBudget"
                                           onchange="setCustomValidity('')"
                                           oninvalid="this.setCustomValidity('Please enter estimated amount')"
                                           required name="totalBudget">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible " id="errorAlert2"
                                     style="display:none">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12" id="budgetDetails">

                            </div>
                        </div>
                    </div>
                </form>
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



        function clearAll() {
            $('input').not(':checkbox').not('.noClear').not(':radio').val('');
            $('#budgetDetails').html('');
        }

        function loadBudget() {
            let taskType = $('#taskType').val();
            clearAll();
            $.ajax({
                url: '{{route('viewBudgetByType')}}',
                type: 'POST',
                data: {taskType: taskType},
                success: function (data) {
                    console.log(data);

                    if (data.errors != null) {
                        $('#budgetDetails').append("" +
                            "<p>You have not defined this estimation type yet.</p>");
                    }
                    if (data.success != null) {
                        if(data.success.details.length > 0) {
                            $('#totalBudget').val(data.success.total);
                            $(data.success.details).each(function (key, value) {
                                if (value.data.length > 0) {
                                    $('#budgetDetails').append("" +
                                        "<h5>" + value.title + "</h5>");
                                    $(value.data).each(function (key1, value1) {
                                        $('#budgetDetails').append("" +
                                            "<p>" + value1.label + ": " + value1.value + " </p>");
                                    })
                                }
                            })
                        }else{
                            $('#budgetDetails').append("" +
                                "<p>You have not defined this estimation type yet.</p>");
                        }

                    }
                }
            });
        }
    </script>
@endsection