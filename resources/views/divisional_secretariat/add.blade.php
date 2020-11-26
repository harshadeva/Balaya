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
                    <form class="form-horizontal" id="saveForm" role="form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible " id="errorAlert" style="display:none">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="district">{{ __('District') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="mdi mdi-account"></em></span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control noClear" readonly
                                               value="{{\Illuminate\Support\Facades\Auth::user()->office->district->name_en}}"
                                               name="district"
                                               id="district">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="secretariat">{{ __('Secretariat Name') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">EN</span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control" required
                                               oninput="setCustomValidity('')"
                                               oninvalid="this.setCustomValidity('Please enter secretariat name')"
                                               placeholder="Secretariat name in english" name="secretariat"
                                               id="secretariat">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="secretariat_si">ප්‍රාදේශීය ලේකම් කාර්යාලය</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">SI</span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control"
                                               oninput="setCustomValidity('')" required
                                               oninvalid="this.setCustomValidity('Please enter secretariat name')"
                                               placeholder="Secretariat name in sinhala"
                                               name="secretariat_si"
                                               id="secretariat_si">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="secretariat_ta">பிரதேச செயலகம்</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">TA</span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control"
                                               oninput="setCustomValidity('')" required
                                               oninvalid="this.setCustomValidity('Please enter secretariat name')"
                                               placeholder="Secretariat name in tamil" name="secretariat_ta"
                                               id="secretariat_ta">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-2" style="margin-top: 27px;">
                                <button type="submit"
                                        class="btn btn-primary btn-block ">{{ __('Add Secretariat') }}</button>
                            </div>
                            <div class="form-group col-md-2" style="margin-top: 27px;">
                                <button type="button" onclick="clearAll();event.preventDefault();"
                                        class="btn btn-danger btn-block ">{{ __('Cancel') }}</button>
                            </div>

                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-secondary">Divisional Secretariat</h6>
                            </div>
                            <div class="col-md-4 mb-1">
                                <input type="text" placeholder="Search secretariat name here"
                                       class="float-right form-control" id="searchBox">
                            </div>

                            <div class="col-md-12">
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table class="table table-striped table-bordered"
                                               cellspacing="0"
                                               width="100%">
                                            <thead>
                                            <tr>
                                                <th>DISTRICT</th>
                                                <th>ENGLISH</th>
                                                <th>SINHALA</th>
                                                <th>TAMIL</th>
                                                <th style='text-align:center;'>OPTIONS</th>
                                            </tr>
                                            </thead>
                                            <tbody id="divisionalSecretariatTBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 ">
                                <button type="button" id="confirmBtn" onclick="confirm();event.preventDefault();"
                                        class="btn btn-primary btn-md float-right">{{ __('Confirm All Secretariats') }}</button>
                            </div>
                        </div>
                    </form> <!-- /form -->
                </div><!-- /card body -->
            </div><!-- /card -->

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->



    <!-- modal start -->

    <div class="modal fade" id="updateModal" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Update Divisional Secretariat</h5>
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
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="district">{{ __('District') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="mdi mdi-account"></em></span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control noClear" readonly
                                               value="{{\Illuminate\Support\Facades\Auth::user()->office->district->name_en}}"
                                               name="district"
                                               id="districtU">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="divisionalSecretariatU">{{ __('Divisional Secretariat Name') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">EN</span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control" required
                                               oninput="setCustomValidity('')"
                                               oninvalid="this.setCustomValidity('Please enter divisional secretariat name')"
                                               placeholder="Divisional Secretariat name in english" name="divisionalSecretariat"
                                               id="divisionalSecretariatU">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="divisionalSecretariat_siU">ප්‍රාදේශීය ලේකම් කාර්යාලය</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">SI</span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control"
                                               oninput="setCustomValidity('')" required
                                               oninvalid="this.setCustomValidity('Please enter divisional secretariat name')"
                                               placeholder="Divisional Secretariat name in sinhala"
                                               name="divisionalSecretariat_si"
                                               id="divisionalSecretariat_siU">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="divisionalSecretariat_taU">பிரதேச செயலகம்</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">TA</span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control"
                                               oninput="setCustomValidity('')" required
                                               oninvalid="this.setCustomValidity('Please enter divisional secretariat name')"
                                               placeholder="Divisional Secretariat name in tamil" name="divisionalSecretariat_ta"
                                               id="divisionalSecretariat_taU">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="updateId" id="updateId">
                            <div class="form-group col-md-6" style="margin-top: 20px;">
                                <button type="button" onclick="clearAll();event.preventDefault();"
                                        class="btn btn-danger btn-block ">{{ __('Cancel') }}</button>
                            </div>
                            <div class="form-group col-md-6" style="margin-top: 20px;">
                                <button type="submit" form="updateForm"
                                        class="btn btn-primary btn-block ">{{ __('Update Division') }}</button>
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
            showTableData();
        });

        function clearAll() {
            $('input').not(".noClear").val('');
            $(":checkbox").attr('checked', false).trigger('change');
            $('select').val('').trigger('change');
            $('#updateModal').modal('hide');
        }

        function showTableData() {


            $.ajax({
                url: '{{route('getDivisionalSecretariatByAuth')}}',
                type: 'POST',
                success: function (data) {

                    if (data.success != null) {
                        let array = data.success;
                        if (array.length == 0) {
                            $('#confirmBtn').hide();
                        }
                        else {
                            $('#confirmBtn').show();
                        }
                        $('#divisionalSecretariatTBody').html('');
                        $.each(array, function (key, value) {
                            if (value.status == 2) {
                                $('#divisionalSecretariatTBody').append(
                                    "<tr id='" + value.iddivisional_secretariat + "'>" +
                                    "<td>{{ strtoupper( \Illuminate\Support\Facades\Auth::user()->office->district->name_en)}}</td>" +
                                    "<td>" + value.name_en.toUpperCase() + "</td>" +
                                    "<td>" + value.name_si.toUpperCase() + "</td>" +
                                    "<td>" + value.name_ta.toUpperCase() + "</td>" +
                                    " <td style='text-align:center;'>" +
                                    "<p>" +
                                    " <button type='button' " +
                                    "class='btn btn-sm btn-warning  waves-effect waves-light'" +
                                    "onclick='showUpdateModal(" + value.iddivisional_secretariat + ")'>" +
                                    " <i class='fa fa-edit'></i>" +
                                    "</button>" +
                                    " <button type='button' " +
                                    "class='btn btn-sm btn-danger  waves-effect waves-light'" +
                                    "onclick='deleteThis(" + value.iddivisional_secretariat + ")'>" +
                                    " <i class='fa fa-trash'></i>" +
                                    "</button>" +
                                    " </p>" +
                                    " </td>" +
                                    "</tr>"
                                );
                            }
                            else {
                                $('#divisionalSecretariatTBody').append(
                                    "<tr id='" + value.iddivisional_secretariat + "'>" +
                                    "<td>{{ strtoupper( \Illuminate\Support\Facades\Auth::user()->office->district->name_en)}}</td>" +
                                    "<td>" + value.name_en.toUpperCase() + "</td>" +
                                    "<td>" + value.name_si.toUpperCase() + "</td>" +
                                    "<td>" + value.name_ta.toUpperCase() + "</td>" +
                                    " <td style='text-align:center;'>" +
                                    "<p>" +
                                    " <button title='Can not use this option on confirmed records' disabled type='button' " +
                                    "class='btn btn-sm btn-muted  waves-effect waves-light'>" +
                                    " <i class='fa fa-edit'></i>" +
                                    "</button>" +
                                    " <button  title='Can not use this option on confirmed records'  disabled type='button' " +
                                    "class='btn btn-sm btn-muted  waves-effect waves-light'>" +
                                    " <i class='fa fa-trash'></i>" +
                                    "</button>" +
                                    " </p>" +
                                    " </td>" +
                                    "</tr>"
                                );
                            }
                        });
                    }
                    else {
                        //initialize alert and variables
                        $('.notify').empty();
                        $('.alert').hide();
                        $('.alert').html("");
                        //initialize alert and variables end
                        $('#errorAlert').append('<p>Something Wrong!</p>');
                        $('html, body').animate({
                            scrollTop: $("body").offset().top
                        }, 1000);
                    }
                }


            });
        }

        $("#saveForm").on("submit", function (event) {
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
                    url: '{{route('saveDivisionalSecretariat')}}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.errors != null) {
                            $('#errorAlert').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlert').append('<p>' + value + '</p>');
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'DIVISIONAL SECRETARIAT SAVED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Divisional secretariat saved successfully.'
                            });
                            clearAll();
                            showTableData();
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


        $("#searchBox").keyup(function () {

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


        function showUpdateModal(id) {
            $('#updateId').val(id);
            $('#divisionalSecretariatU').val($('#' + id).find("td").eq(1).html());
            $('#divisionalSecretariat_siU').val($('#' + id).find("td").eq(2).html());
            $('#divisionalSecretariat_taU').val($('#' + id).find("td").eq(3).html());
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
                    url: '{{route('updateDivisionalSecretariat')}}',
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
                                title: 'DIVISIONAL SECRETARIAT UPDATED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Divisional secretariat updated successfully.'
                            });
                            clearAll();
                            $('#updateModal').modal('hide');
                            showTableData();
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

        function confirm() {
            swal({
                title: 'Confirm All?',
                text: 'You Will Need Administrator Permission To Revert This Process!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Confirm',
                cancelButtonText: 'No, cancel',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger m-l-10',
                buttonsStyling: false
            }).then(function () {

                $.ajax({
                    url: '{{route('confirmDivisionalSecretariat')}}',
                    type: 'POST',
                    success: function (data) {
                        if (data.errors != null) {
                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'PROCESS INVALID!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
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
                                title: 'DIVISIONAL SECRETARIAT CONFIRMED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Divisional secretariat confirmed successfully.'
                            });
                            showTableData();
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

        function deleteThis(id) {
            swal({
                title: 'Delete?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Keep it',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-success m-l-10',
                buttonsStyling: false
            }).then(function () {

                $.ajax({
                    url: '{{route('deleteDivisionalSecretariat')}}',
                    data: {id: id},
                    type: 'POST',
                    success: function (data) {
                        if (data.errors != null) {
                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'PROCESS INVALID!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
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
                                title: 'DIVISIONAL SECRETARIATE DELETED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Divisional Secreateriat deleted successfully.'
                            });
                            showTableData();
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