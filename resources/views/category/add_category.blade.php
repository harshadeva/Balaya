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
                        {{--<h6 class="text-secondary">New Category</h6>--}}
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="newCat">{{ __('New Category') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control"
                                               oninput="setCustomValidity('');" required
                                               oninvalid="this.setCustomValidity('Please Enter category name')"
                                               placeholder="Enter category name here" name="newCat" id="newCat">
                                    </div>
                                </div>
                            </div>
                            <div  style="margin-top: 27px;" class="form-group  col-md-2">
                                <button type="submit"
                                        class="btn btn-primary btn-block ">{{ __('Save Category') }}</button>
                            </div>
                            <div style="margin-top: 27px;" class="form-group  col-md-2">
                                <button type="submit" onclick="clearAll();event.preventDefault();"
                                        class="btn btn-danger btn-block ">{{ __('Cancel') }}</button>
                            </div>
                        </div>
                        <hr/>

                        <h6 class="text-secondary">Recently Added</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table class="table table-striped table-bordered"
                                               cellspacing="0"
                                               width="100%">
                                            <thead>
                                            </thead>
                                            <tbody id="categoryTBody">

                                            <tr>

                                            </tr>
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
@endsection
@section('psScript')

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            getExistingCategories();
        });

        function clearAll() {
            $('input').not(':checkbox').not('.noClear').val('');
            $(":checkbox").attr('checked', false).trigger('change');
            $('select').val('').trigger('change');
        }

        function getExistingCategories() {
            $.ajax({
                url: '{{route('loadCategoryRecent')}}',
                type: 'POST',
                success: function (data) {
                    if (data != null) {
                        let array = data;
                        $('#categoryTBody').html('');
                        let tbody = "";
                        $.each(array, function (index, value) {
                            tbody += "<tr><td>"+value.category+"</td></tr>";
                            $('#categoryTBody').html(tbody);
                        });
                    }
                    else{
                        $('#categoryTBody').html("<tr><td class='text-center'>No category added yet.</td></tr>");
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
                    url: '{{route('saveCategory')}}',
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
                                title: 'NEW CATEGORY SAVED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Category details saved successfully.'
                            });
                            $('#newCat').val('');
                            getExistingCategories();
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