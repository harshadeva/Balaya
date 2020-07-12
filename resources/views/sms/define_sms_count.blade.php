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
                        <div class="col-md-12">
                            <div class="table-rep-plugin">
                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                    <table class="table table-striped table-bordered"
                                           cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>OFFICE NAME</th>
                                            <th>SMS MODULE</th>
                                            <th>OFFICE  STATUS</th>
                                            <th>LIMIT</th>
                                            <th>USED</th>
                                            <th>OPTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($offices))
                                            @if(count($offices) > 0)
                                                @foreach($offices as $office)
                                                    <tr id="{{$office->idoffice}}">
                                                        <td>{{strtoupper($office->office_name)}} </td>
                                                        @if($office->sms_module)
                                                            <td nowrap><em class="mdi mdi-checkbox-blank-circle text-success"></em> ACTIVATED</td>
                                                        @else
                                                            <td nowrap><em class="mdi mdi-checkbox-blank-circle text-danger"></em> DEACTIVATED</td>
                                                        @endif
                                                        @if($office->status == 1)
                                                            <td nowrap><p><em  class="mdi mdi-checkbox-blank-circle text-success "></em> LIVE</p></td>
                                                        @else
                                                            <td nowrap><em class="mdi mdi-checkbox-blank-circle text-danger"></em> DISABLED</td>
                                                        @endif

                                                        <td id="limitRow-{{$office->idoffice}}">{{$office->smaLimit != null ? $office->smaLimit->limit : 0}}</td>
                                                        <td>{{$office->smaLimit != null ? $office->smaLimit->current : 0}}</td>
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
                                                                       onclick="showUpdateModal({{$office->idoffice}})"
                                                                       class="dropdown-item">Edit
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
                    @if(isset($offices))
                        {{$offices->links()}}
                    @endif
                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->


    <!-- modal start -->
    <div class="modal fade" id="updateModal" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Update SMS Limit</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="updateForm" role="form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible " id="errorAlertUpdate"
                                     style="display:none">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" class="noClear" name="updateId" id="updateId">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="limit">{{ __('Limit of SMS') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="fa fa-envelope"></em></span>
                                        </div>
                                        <input autocomplete="off"  type="text" class="form-control" required
                                               placeholder="0" name="limit" id="limit">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit"
                                        class="btn btn-primary btn-block ">{{ __('Update Limit') }}</button>
                            </div>
                        </div>
                    </form> <!-- /form -->
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

        function showUpdateModal(id) {
            $('.alert').html('').hide();
            $('#updateId').val(id);
            $('#limit').val($('#limitRow-'+id).html());
            $('#updateModal').modal('show');

        }

        $("#updateForm").on("submit", function (event) {
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
                    url: '{{route('updateSmsLimit')}}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.errors != null) {
                            $('#errorAlertUpdate').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlertUpdate').append('<p>' + value + '</p>');
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'SMS LIMIT!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'SMS limit updated successfully.'
                            });
                            $('#updateModal').modal('hide');
                            location.reload();
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
    </script>
@endsection