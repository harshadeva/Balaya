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
                        <h6 class="text-secondary">Account Details</h6>
                        <hr/>
                        <div class="row">

                            <div class="form-group col-md-3">
                                <label for="total">{{ __('No of SMS') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="fa fa-envelope"></em></span>
                                        </div>
                                        <input autocomplete="off" readonly type="text" class="form-control noClear" required
                                           value="{{$limit != null ? $limit->limit : 0}}"    placeholder="0.00" name="total" id="total">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="used">{{ __('Used') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="fa fa-envelope-open-o"></em></span>
                                        </div>
                                        <input autocomplete="off" readonly type="text" class="form-control noClear" required
                                               value="{{$limit != null ? $limit->current : 0}}"     placeholder="0.00" name="used" id="used">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="message">{{ __('Welcome SMS') }}</label>
                                <div>
                                        <textarea rows="3"  type="text" class="form-control "
                                               required onchange="setCustomValidity('')"
                                                  title="Please remove extra spaces and maintain one white space after eveery full stop mark"
                                               oninvalid="this.setCustomValidity('Please enter welcome message')"
                                                  placeholder="Eg : Congratulations! You have successfully registered with below NIC number." name="message" id="message">{{$welcome->body or ''}}</textarea>
                                </div>
                            </div>

                        </div>

                        <hr/>
                        <div class="row">
                            <div class="form-group col-md-2">
                                <button type="submit"
                                        class="btn btn-primary btn-block ">{{ __('Save SMS') }}</button>
                            </div>
                            <div class="form-group col-md-2">
                                <button type="submit" onclick="clearAll();event.preventDefault();"
                                        class="btn btn-danger btn-block ">{{ __('Cancel') }}</button>
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
            initializeDate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });

        function clearAll() {
            $('textarea').val('');
            $('.alert').hide().html('');
        }

        $("#form1").on("submit", function (event) {
            event.preventDefault();

            //initialize alert and variables
            $('.notify').empty();
            $('.alert').hide();
            $('.alert').html("");
            let completed = true;
            let sms = $('#message').val();
            //initialize alert and variables end


            //validate user input

            //validation end

            if (completed) {

                $.ajax({
                    url: '{{route('saveWelcomeSms')}}',
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
                                title: 'WELCOME SMS SAVED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Welcome SMS saved successfully.'
                            });
                            clearAll();
                            $('#message').val(sms);
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