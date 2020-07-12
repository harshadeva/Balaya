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
                            <div class="form-group col-md-6">
                                <label for="canvassing_type">{{ __('Canvassing Type') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">EN</span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control" required
                                               oninput="setCustomValidity('')"
                                               oninvalid="this.setCustomValidity('Please enter canvassing type')"
                                               placeholder="Canvassing type in english" name="canvassing_type"
                                               id="canvassing_type">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="canvassing_type_si">කැන්වසින් වර්ගය</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">SI</span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control"
                                               oninput="setCustomValidity('')" required
                                               oninvalid="this.setCustomValidity('Please enter canvassing type')"
                                               placeholder="Canvassing type in sinhala"
                                               name="canvassing_type_si"
                                               id="canvassing_type_si">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="canvassing_type_ta">பிரதேச செயலகம்</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">TA</span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control"
                                               oninput="setCustomValidity('')" required
                                               oninvalid="this.setCustomValidity('Please enter canvassing type')"
                                               placeholder="Canvassing type in tamil" name="canvassing_type_ta"
                                               id="canvassing_type_ta">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-2" style="margin-top: 27px;">
                                <button type="submit"
                                        class="btn btn-primary btn-block ">{{ __('Add Type') }}</button>
                            </div>
                            <div class="form-group col-md-2" style="margin-top: 27px;">
                                <button type="button" onclick="clearAll();event.preventDefault();"
                                        class="btn btn-danger btn-block ">{{ __('Cancel') }}</button>
                            </div>

                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-secondary">Canvassing Types (<em id="count"></em>)</h6>
                            </div>
                            <div class="col-md-4 mb-1">
                                <input type="text" placeholder="Search council type here"
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
                                                <th>ENGLISH</th>
                                                <th>SINHALA</th>
                                                <th>TAMIL</th>
                                                <th style='text-align:center;'>OPTIONS</th>
                                            </tr>
                                            </thead>
                                            <tbody id="canvassingTypeTBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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
                    <h5 class="modal-title mt-0">Update Canvassing TYpe</h5>
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
                                <label for="canvassing_typeU">{{ __('Canvassing Type') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">EN</span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control" required
                                               oninput="setCustomValidity('')"
                                               oninvalid="this.setCustomValidity('Please enter canvassing type')"
                                               placeholder="Canvassing type in english" name="canvassing_type"
                                               id="canvassing_typeU">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="canvassing_type_siU">කැන්වසින් වර්ගය</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">SI</span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control"
                                               oninput="setCustomValidity('')" required
                                               oninvalid="this.setCustomValidity('Please enter canvassing type')"
                                               placeholder="Canvassing type in sinhala"
                                               name="canvassing_type_si"
                                               id="canvassing_type_siU">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="canvassing_type_taU">பிரதேச செயலகம்</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">TA</span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control"
                                               oninput="setCustomValidity('')" required
                                               oninvalid="this.setCustomValidity('Please enter canvassing type')"
                                               placeholder="Canvassing type in tamil" name="canvassing_type_ta"
                                               id="canvassing_type_taU">
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
                                        class="btn btn-primary btn-block ">{{ __('Update Canvassing Type') }}</button>
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
                url: '{{route('getCanvassingTypeByOffice')}}',
                type: 'POST',
                success: function (data) {

                    if (data.success != null) {
                        let array = data.success;
                        let count  = data.count;
                        $('#count').html(count);
                        $('#canvassingTypeTBody').html('');
                        $.each(array, function (key, value) {
                                $('#canvassingTypeTBody').append(
                                    "<tr id='" + value.idcanvassing_type + "'>" +
                                    "<td>" + value.name_en.toUpperCase() + "</td>" +
                                    "<td>" + value.name_si.toUpperCase() + "</td>" +
                                    "<td>" + value.name_ta.toUpperCase() + "</td>" +
                                    " <td style='text-align:center;'>" +
                                    "<p>" +
                                    " <button type='button' " +
                                    "class='btn btn-sm btn-warning  waves-effect waves-light'" +
                                    "onclick='showUpdateModal(" + value.idcanvassing_type + ")'>" +
                                    " <i class='fa fa-edit'></i>" +
                                    "</button>" +
                                    " <button type='button' " +
                                    "class='btn btn-sm btn-danger  waves-effect waves-light'" +
                                    "onclick='deleteThis(" + value.idcanvassing_type + ")'>" +
                                    " <i class='fa fa-trash'></i>" +
                                    "</button>" +
                                    " </p>" +
                                    " </td>" +
                                    "</tr>"
                                );

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
                    url: '{{route('saveCanvassingType')}}',
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
                                title: 'CANVASSING TYPE SAVED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Canvassing type saved successfully.'
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
            $('#canvassing_typeU').val($('#' + id).find("td").eq(0).html());
            $('#canvassing_type_siU').val($('#' + id).find("td").eq(1).html());
            $('#canvassing_type_taU').val($('#' + id).find("td").eq(2).html());
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
                    url: '{{route('updateCanvassingType')}}',
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
                                title: 'CANVASSING TYPE UPDATED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Canvassing type updated successfully.'
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
                    url: '{{route('deleteCanvassingType')}}',
                    data: {id: id},
                    type: 'POST',
                    success: function (data) {
                        if (data.errors != null) {
                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'PROCESS INVALID!',
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
                                title: 'CANVASSING TYPE DELETED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Canvassing type deleted successfully.'
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