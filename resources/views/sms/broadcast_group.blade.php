@extends('layouts.main')
@section('psStyle')
    <style>

    </style>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.2/css/bootstrap-slider.min.css"
          integrity="sha256-G3IAYJYIQvZgPksNQDbjvxd/Ca1SfCDFwu2s2lt0oGo=" crossorigin="anonymous"/>

@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">
            <form class="form-horizontal" id="form1" role="form">

                <div class="card">
                    <div class="card-body">


                        <div class="card firstPage">
                            <div class="card-body">


                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-dismissible " id="errorAlert1"
                                             style="display:none">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="form-group col-md-5">
                                        <label for="group" class="control-label">{{ __('Group Name') }}</label>
                                        <div>
                                            <div class="input-group">

                                                <div  class="flex-fill">
                                                    <select id="group" name="group" class="form-control noClear select2"
                                                            onchange="setCustomValidity('')"
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


                                    <div class="form-group col-md-12 toggle englishToggle">
                                        <label for="body" class="control-label"><h6
                                                    class="text-secondary">{{ __('Message Body') }}</h6></label>
                                        <div>
                                <textarea id="body" name="body" placeholder="Type message content here"
                                          class="form-control"
                                          rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card firstPage">
                            <div class="card-body">
                                <hr/>
                                <div class="row">
                                    <div class="col-md-8 .d-sm-none ">
                                    </div>
                                    <div class="col-md-2  p-sm-2">
                                        <button onclick="goFirstPage();event.preventDefault();"
                                                class="btn btn-primary float-right btn-block "><em
                                                    style="margin-top: 5px;"
                                                    class="fa fa-chevron-left"></em> {{ __('Previous') }} </button>
                                    </div>
                                    <div class="col-md-2  p-sm-2">
                                        <button onclick="goThirdPage();event.preventDefault();"
                                                class="btn btn-primary float-right btn-block ">{{ __('Next') }} <em
                                                    style="margin-top: 5px;" class="fa fa-chevron-right"></em>
                                        </button>
                                    </div>


                                </div>
                            </div><!-- /card body -->
                        </div><!-- /card -->


                        <!-------------------------------------------- FIRST PAGE END ----------------------------------------->


                        <div class="thirdPage" style="display: none;">
                            <h5 class="text-secondary">Message Summery</h5>
                            <hr/>
                            <div class="card  thirdPage">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger alert-dismissible " id="errorAlert"
                                                 style="display:none">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-secondary"><em class="mdi mdi-account-multiple"></em> No of Recipients
                                                &nbsp; &nbsp;#<em id="recipient"></em>
                                            </h5>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-secondary"><em class="fa fa-envelope"></em> Your Messages Limit
                                                &nbsp; &nbsp;#<em id="limit"></em>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-secondary"><em class="mdi mdi-book-open-page-variant"></em> Message Length
                                                &nbsp; &nbsp;#<em id="pageLength"></em>
                                            </h5>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-secondary"><em class="fa  fa-envelope-open-o"></em> Used Messages
                                                &nbsp; &nbsp;#<em id="used"></em>
                                            </h5>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2  p-sm-2">
                                            <button onclick="goFirstPage();event.preventDefault();"
                                                    class="btn btn-primary float-right btn-block "><em
                                                        style="margin-top: 5px;"
                                                        class="fa fa-chevron-left"></em> {{ __('Previous') }} </button>
                                        </div>
                                        <div class="col-md-2  p-sm-2">
                                            <button type="submit" id="submitBtn"
                                                    class="btn btn-info float-right btn-block ">{{ __('Send') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form> <!-- /form -->

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->
@endsection
@section('psScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.2/bootstrap-slider.min.js"
            integrity="sha256-oj52qvIP5c7N6lZZoh9z3OYacAIOjsROAcZBHUaJMyw=" crossorigin="anonymous"></script>

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            initializeDate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#age').slider({
                formatter: function (value) {
                    if (value.length == 2) {
                        $('#minAgeLabel').html(value[0] + ' Y');
                        $('#minAge').val(value[0]);
                        $('#maxAgeLabel').html(value[1] + ' Y');
                        $('#maxAge').val(value[1]);
                    }
                }
            });
        });


        function goFirstPage() {
            $('.firstPage').fadeIn('slow');
            $('.thirdPage').fadeOut('slow');

        }

        function goThirdPage() {
           let  validated = true;
            if ($('#group').val() == '' || $('#group').val() == null) {
                validated = false;
                $('#errorAlert1').html("<p><em class='fa fa-genderless'></em> Please select group name</p>").show();
            }
            if(validated) {
                $('.firstPage').fadeOut('slow');
                $('.thirdPage').fadeIn('slow');
                $.ajax({
                    url: '{{route('getNumberOfReceiversGroup')}}',
                    type: 'POST',
                    data: $('#form1').serialize(),
                    success: function (data) {
                        if (data.errors != null) {
                            $('#errorAlert').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlert').append("<p><em class='fa fa-genderless'></em> " + value + "</p>");
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {

                            $('#recipient').html(data.success.recipient);
                            $('#pageLength').html(data.success.totalPages);
                            $('#used').html(data.success.used);
                            $('#limit').html(data.success.limit);
                        }
                    }
                });
            }

        }


        $("#form1").on("submit", function (event) {

            event.preventDefault();
            $('#publishBtns').hide();
            $('#uploadingBtn').show();
            $('#submitBtn').attr('disabled',true);

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
                    url: '{{route('storeSms',['type'=>3])}}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        $('#submitBtn').attr('disabled',false);

                        $('#errorAlert').show();
                        if (data.errors != null) {
                            $('#errorAlert').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlert').append("<p><em class='fa fa-genderless'></em> " + value + "</p>");
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'SMS SAVED SUCCESSFULLY.',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Sms will be sent after administrator confirmation.'
                            });
                            clearAll();
                            goFirstPage();
                        }
                    }


                });
            }
            else {
                $('#errorAlert').html("<em class='fa fa-genderless'></em> Please provide all required fields.");
                $('#errorAlert').show();
                $('html, body').animate({
                    scrollTop: $("body").offset().top
                }, 1000);
            }
        });


    </script>
@endsection