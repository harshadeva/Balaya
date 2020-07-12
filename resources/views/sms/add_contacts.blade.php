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
                    <form class="form-horizontal" id="form1" role="form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible " id="errorAlert" style="display:none">
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="group" class="control-label">{{ __('Group Name') }}</label>
                                <div>
                                    <div class="input-group">

                                        <div  class="flex-fill">
                                            <select id="group" name="group" class="form-control noClear select2"
                                                    onchange="setCustomValidity('');showTableData()"
                                                    oninvalid="this.setCustomValidity('Please select group')"
                                                    required>
                                                <option value="" disabled selected>Select  group</option>
                                                @if($groups != null)
                                                    @foreach($groups as $group)
                                                        <option value="{{$group->idsms_group}}">{{strtoupper($group->name)}}</option>
                                                    @endforeach
                                                @endif

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="contact">{{ __('Contact Number') }}</label>
                                <div>
                                    <div class="input-group">

                                        <input autocomplete="off" type="tel" class="form-control" required
                                               oninput="setCustomValidity('')"
                                               oninvalid="this.setCustomValidity('Please enter contact number')"
                                               placeholder="Enter contact number here" name="contact"
                                               id="contact">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group col-md-3" style="margin-top: 27px;">
                                <button type="submit"
                                        class="btn btn-primary btn-block ">{{ __('Add Contact') }}</button>
                            </div>
                            <div class="form-group col-md-2" style="margin-top: 27px;">
                                <button type="submit" onclick="clearAll();event.preventDefault();"
                                        class="btn btn-danger btn-block ">{{ __('Cancel') }}</button>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-secondary">Contacts <em id="count"></em></h6>
                            </div>
                            <div class="col-md-4 mb-1">
                                <input type="text" placeholder="Search contact number here" class="float-right form-control" id="searchBox">
                            </div>

                            <div class="col-md-12">
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table class="table table-striped table-bordered"
                                               cellspacing="0"
                                               width="100%">
                                            <thead>
                                            <tr>
                                                <th>CONTACT NO</th>
                                                <th style='text-align:center;'>OPTIONS</th>
                                            </tr>
                                            </thead>
                                            <tbody id="groupTBody">
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
                    <h5 class="modal-title mt-0">Update Contact</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="updateForm" role="form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible " id="errorAlertUpdate" style="display:none">
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-md-12">
                                <label for="groupU" class="control-label">{{ __('Group Name') }}</label>
                                <div>
                                    <div class="input-group">

                                        <div  class="flex-fill">
                                            <select id="groupU" name="group" class="form-control noClear select2"
                                                    onchange="setCustomValidity('');showTableData()"
                                                    oninvalid="this.setCustomValidity('Please select group')"
                                                    required>
                                                <option value="" disabled selected>Select  group</option>
                                                @if($groups != null)
                                                    @foreach($groups as $group)
                                                        <option value="{{$group->idsms_group}}">{{strtoupper($group->name)}}</option>
                                                    @endforeach
                                                @endif

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="contactU">{{ __('Contact Number') }}</label>
                                <div>
                                    <div class="input-group">

                                        <input autocomplete="off" type="text" class="form-control" required
                                               oninput="setCustomValidity('')"
                                               oninvalid="this.setCustomValidity('Please enter contact number')"
                                               placeholder="Enter contact number here" name="contact"
                                               id="contactU">
                                    </div>
                                </div>
                            </div>

                            <input  type="hidden" name="updateId" id="updateId">
                            <div class="form-group col-md-6" style="margin-top: 20px;">
                                <button type="submit" onclick="clearAll();event.preventDefault();"
                                        class="btn btn-danger btn-block ">{{ __('Cancel') }}</button>
                            </div>
                            <div class="form-group col-md-6" style="margin-top: 20px;">
                                <button type="submit" form="updateForm"
                                        class="btn btn-primary btn-block ">{{ __('Update Contact') }}</button>
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

        function clearAll() {
            $('input').not(".noClear").val('');
            $(":checkbox").attr('checked', false).trigger('change');
            $('select').not(".noClear").val('').trigger('change');
            $('#updateModal').modal('hide');

        }

        function showTableData() {
            let id = $('#group').val();
            $.ajax({
                url: '{{route('getContactByGroup')}}',
                type: 'POST',
                data:{id:id},
                success: function (data) {
                    if (data.success != null) {
                        let array = data.success;
                        $('#groupTBody').html('');
                        $('#count').html(' ( '+array.length+' )');
                        $.each(array, function (key1, value1) {
                            if(value1.status == 2) {
                                $('#groupTBody').append(
                                    "<tr data-id='" + value1.idsms_group + "' id='" + value1.idgroup_contact + "'>" +
                                    "<td style='text-align: left;'>" + value1.contact + "</td>" +
                                    "<td style='text-align: center;'>" +
                                    "<p>" +
                                    " <button type='button' " +
                                    "class='btn btn-sm btn-warning  waves-effect waves-light' onclick='showUpdateModal(" + value1.idgroup_contact + ")'>" +
                                    " <i class='fa fa-edit'></i>" +
                                    "</button>" +
                                    " <button type='button' " +
                                    "class='btn btn-sm btn-danger  waves-effect waves-light'" +
                                    "onclick='deleteThis(" + value1.idgroup_contact + ")'>" +
                                    " <i class='fa fa-trash'></i>" +
                                    "</button>" +
                                    " </p>" +
                                    " </td>" +
                                    "</tr>"
                                );
                            }else{
                                $('#groupTBody').append(
                                    "<tr  data-id='" + value1.idsms_group + "' id='" + value1.idgroup_contact + "'>" +
                                    "<td style='text-align: left;'> " + value1.contact + "</td>" +
                                    "<td style='text-align: center;'>" +
                                    "<p>" +
                                    " <button type='button' " +
                                    "class='btn btn-sm btn-warning  waves-effect waves-light' onclick='showUpdateModal(" + value1.idgroup_contact + ")'>" +
                                    " <i class='fa fa-edit'></i>" +
                                    "</button>" +
                                    " <button type='button' " +
                                    "class='btn btn-sm btn-danger  waves-effect waves-light'" +
                                    "onclick='deleteThis(" + value1.idgroup_contact + ")'>" +
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
        $("#form1").on("submit", function (event) {
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
                    url: '{{route('saveContact')}}',
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
                                title: 'CONTACT ADDED TO GROUP!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Contact added successfully.'
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
            $('#groupU').val($('#'+id).attr('data-id')).trigger('change');
            $('#contactU').val($('#'+id).find("td").eq(0).html());
            $('#updateModal').modal('show');
        };

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
                    url: '{{route('updateContact')}}',
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
                                title: 'CONTACT NUMBER UPDATED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Contact number updated successfully.'
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
                    url: '{{route('deleteContact')}}',
                    data: {id: id},
                    type: 'POST',
                    success: function (data) {
                        if (data.errors != null) {
                            $.each(data.errors, function (key, value) {
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

                                    message: value
                                });
                            });
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'CONTACT DELETED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Contact deleted successfully.'
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