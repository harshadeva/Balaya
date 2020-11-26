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
                        <div class="col-md-6">                    <h5><em class="mdi mdi-android"></em> Android {{$setting->android}}</h5>
                        </div>
                        <div class="col-md-6">                    <h5><em class="mdi mdi-apple"></em> Ios {{$setting->ios}}</h5>
                        </div>
                        <div class="col-md-6">                     <small>Las Update at : {{$setting->android_date}}</small>
                        </div>
                        <div class="col-md-6">                     <small>Las Update at : {{$setting->ios_date}}</small>
                        </div>
                    </div>

                </div>
            </div>
            <br/>
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" id="form1" role="form">

                        <div class="row">
                            <div class="form-group col-md-3">
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="mdi mdi-android"></em></span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control" required
                                               oninput="setCustomValidity('')" value="{{$setting->android}}"
                                               oninvalid="this.setCustomValidity('Please enter android version')"
                                               placeholder="Android" name="android" id="android">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="mdi mdi-apple"></em></span>
                                        </div>
                                        <input autocomplete="off" type="text" class="form-control" required
                                               oninput="setCustomValidity('')" value="{{$setting->ios}}"
                                               oninvalid="this.setCustomValidity('Please enter ios version')"
                                               placeholder="ios" name="ios" id="ios">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <button type="submit"
                                        class="btn btn-primary btn-block ">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
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
                    url: '{{route('storeAppVersion')}}',
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
                                title: 'VERSION UPDATED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Version details updated successfully.'
                            });
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