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
                                        <button onclick="clearAll();event.preventDefault();"
                                                class="btn btn-danger float-right btn-block ">{{ __('Cancel') }}</button>
                                    </div>
                                    <div class="col-md-2  p-sm-2">
                                        <button
                                                onclick="goSecondPage();event.preventDefault();"
                                                class="btn btn-primary float-right btn-block ">{{ __('Next') }} <em
                                                    style="margin-top: 5px;" class="fa fa-chevron-right"></em></button>
                                    </div>
                                </div>
                            </div><!-- /card body -->
                        </div><!-- /card -->


                        <!-------------------------------------------- FIRST PAGE END ----------------------------------------->

                        <div class="secondPage" style="display: none;">
                            <h5 class="text-secondary">Receivers Control</h5>
                            <hr/>
                            <div class="card  secondPage">
                                <div class="card-body">


                                    <h5 class="text-secondary"><em class="mdi mdi-map-marker-multiple"></em>
                                        Geographical Area
                                        Wise</h5>
                                    <hr/>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="electionDivisions"
                                                   class="control-label">{{ __('Election Divisions') }}</label>

                                            <select name="electionDivisions[]" id="electionDivisions"
                                                    class="select2 form-control select2-multiple" multiple="multiple"
                                                    multiple
                                                    onchange="electionDivisionChanged(this)"
                                                    data-placeholder="Choose ...">
                                                @if($electionDivisions != null)
                                                    @foreach($electionDivisions as $electionDivision)
                                                        <option value="{{$electionDivision->idelection_division}}">{{strtoupper($electionDivision->name_en)}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="pollingBooths"
                                                   class="control-label">{{ __('Member Division') }}</label>

                                            <select name="pollingBooths[]" id="pollingBooths"
                                                    class="select2 form-control select2-multiple" multiple="multiple"
                                                    multiple
                                                    onchange="pollingBoothChanged(this)"
                                                    data-placeholder="Choose ...">

                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="gramasewaDivisions"
                                                   class="control-label">{{ __('Gramasewa Divisions') }}</label>

                                            <select name="gramasewaDivisions[]" id="gramasewaDivisions"
                                                    class="select2 form-control select2-multiple" multiple="multiple"
                                                    multiple
                                                    onchange="gramasewaDivisionsChanged(this)"
                                                    data-placeholder="Choose ...">

                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="villages"
                                                   class="control-label">{{ __('Villages') }}</label>

                                            <select name="villages[]" id="villages"
                                                    class="select2 form-control select2-multiple" multiple="multiple"
                                                    multiple
                                                    data-placeholder="Choose ...">

                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card  secondPage">
                                <div class="card-body">
                                    <h5 class="text-secondary"><em class="mdi mdi-account-multiple"></em> Community Type
                                        Wise
                                    </h5>
                                    <hr/>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="ethnicities"
                                                   class="control-label">{{ __('Ethnicity') }}</label>

                                            <select name="ethnicities[]" id="ethnicities"
                                                    class="select2 form-control select2-multiple" multiple="multiple"
                                                    multiple
                                                    data-placeholder="Choose ...">
                                                @if($ethnicities != null)
                                                    @foreach($ethnicities as $ethnicities)
                                                        <option value="{{$ethnicities->idethnicity}}">{{strtoupper($ethnicities->name_en)}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="religions"
                                                   class="control-label">{{ __('Religion') }}</label>

                                            <select name="religions[]" id="religions"
                                                    class="select2 form-control select2-multiple" multiple="multiple"
                                                    multiple
                                                    data-placeholder="Choose ...">
                                                @if($religions != null)
                                                    @foreach($religions as $religion)
                                                        <option value="{{$religion->idreligion}}">{{strtoupper($religion->name_en)}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="incomes"
                                                   class="control-label">{{ __('Nature of income') }}</label>

                                            <select name="incomes[]" id="incomes"
                                                    class="select2 form-control select2-multiple" multiple="multiple"
                                                    multiple
                                                    data-placeholder="Choose ...">
                                                @if($incomes != null)
                                                    @foreach($incomes as $income)
                                                        <option value="{{$income->idnature_of_income}}">{{strtoupper($income->name_en)}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="educations"
                                                   class="control-label">{{ __('Educational Qualification') }}</label>

                                            <select name="educations[]" id="educations"
                                                    class="select2 form-control select2-multiple" multiple="multiple"
                                                    multiple
                                                    data-placeholder="Choose ...">
                                                @if($incomes != null)
                                                    @foreach($educations as $education)
                                                        <option value="{{$education->ideducational_qualification}}">{{strtoupper($education->name_en)}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="careers"
                                                   class="control-label">{{ __('Career') }}</label>
                                            <select name="careers[]" id="careers"
                                                    class="select2 form-control select2-multiple" multiple="multiple"
                                                    multiple
                                                    data-placeholder="Choose ...">
                                                @if($careers != null)
                                                    @foreach($careers as $career)
                                                        <option value="{{$career->idcareer}}">{{strtoupper($career->name_en)}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label style="margin-left: 5px;"
                                                   class="control-label">{{ __('Gender') }}</label>
                                            <div class="row">
                                                <label style="margin-left: 10px;" class="radio-inline"><input
                                                            style="margin-left: 10px;" type="radio" value="0"
                                                            name="gender"
                                                            checked>&nbsp;{{ __('All') }}
                                                </label>
                                                <label style="margin-left: 5px;" class="radio-inline"><input
                                                            style="margin-left: 5px;" type="radio" value="1"
                                                            name="gender">&nbsp;{{ __('Male') }}
                                                </label> &nbsp;
                                                &nbsp;
                                                <label style="margin-left: 5px;" class="radio-inline"><input
                                                            style="margin-left: 5px;" type="radio" value="2"
                                                            name="gender">&nbsp;{{ __('Female') }}</label>
                                                <label style="margin-left: 5px;" class="radio-inline"><input
                                                            style="margin-left: 5px;" type="radio" value="3"
                                                            name="gender">&nbsp;{{ __('Other') }}</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="minAge" id="minAge">
                                        <input type="hidden" name="maxAge" id="maxAge">
                                        <div class="form-group col-md-6">
                                            <label style="margin-left: 5px;"
                                                   class="control-label">{{ __('Age') }}</label><br/>
                                            <b id="minAgeLabel">0 Y</b> &nbsp; <input id="age" type="text" class="span2"
                                                                                      value="" data-slider-min="0"
                                                                                      data-slider-max="120"
                                                                                      data-slider-step="1"
                                                                                      data-slider-value="[0,120]"/>&nbsp;
                                            <b id="maxAgeLabel">120 Y</b>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label style="margin-left: 5px;"
                                                   class="control-label">{{ __('Job Sector') }}</label>
                                            <div class="row">
                                                <label style="margin-left: 10px;" class="radio-inline"><input
                                                            style="margin-left: 10px;" type="radio" value="0"
                                                            name="jobSector"
                                                            checked>&nbsp;{{ __('All') }}
                                                </label>
                                                <label style="margin-left: 5px;" class="radio-inline"><input
                                                            style="margin-left: 5px;" type="radio" value="1"
                                                            name="jobSector">&nbsp;{{ __('Government') }}
                                                </label> &nbsp;
                                                &nbsp;
                                                <label style="margin-left: 5px;" class="radio-inline"><input
                                                            style="margin-left: 5px;" type="radio" value="2"
                                                            name="jobSector">&nbsp;{{ __('Private') }}</label>
                                                <label style="margin-left: 5px;" class="radio-inline"><input
                                                            style="margin-left: 5px;" type="radio" value="3"
                                                            name="jobSector">&nbsp;{{ __('Non-Government') }}</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card secondPage">
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
                        </div>
                        <!-------------------------------------------- SECOND PAGE END ----------------------------------------->

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
                                            <h5 class="text-secondary"><em class="fa fa-envelope"></em> Remaining Balance
                                                &nbsp; &nbsp;#<em id="remain"></em>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-secondary"><em class="mdi mdi-book-open-page-variant"></em> No of Pages
                                                &nbsp; &nbsp;#<em id="pageLength"></em>
                                            </h5>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-secondary"><em class="fa  fa-envelope-open-o"></em> This Message Total
                                                &nbsp; &nbsp;#<em id="thisTotal"></em>
                                            </h5>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2  p-sm-2">
                                            <button onclick="goSecondPage();event.preventDefault();"
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


        function electionDivisionChanged(el) {
            let divisions = $(el).val();
            $('#pollingBooths').html('');
            $('#gramasewaDivisions').html('');
            $('#villages').html('');

            $.ajax({
                url: '{{route('getPollingBoothByElectionDivisions')}}',
                type: 'POST',
                data: {id: divisions},
                success: function (data) {
                    let result = data.success;
                    $.each(result, function (key, value) {
                        $('#pollingBooths').append("<option value='" + value.idpolling_booth + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }

        function pollingBoothChanged(el) {
            let booths = $(el).val();
            $('#gramasewaDivisions').html('');
            $('#villages').html('');

            $.ajax({
                url: '{{route('getGramasewaDivisionByPollingBooths')}}',
                type: 'POST',
                data: {id: booths},
                success: function (data) {
                    let result = data.success;
                    $.each(result, function (key, value) {
                        $('#gramasewaDivisions').append("<option value='" + value.idgramasewa_division + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }

        function gramasewaDivisionsChanged(el) {
            let divisions = $(el).val();
            $('#villages').html('');

            $.ajax({
                url: '{{route('getVillageByGramasewaDivisions')}}',
                type: 'POST',
                data: {id: divisions},
                success: function (data) {
                    let result = data.success;
                    $.each(result, function (key, value) {
                        $('#villages').append("<option value='" + value.idvillage + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }

        function goFirstPage() {
            $('.secondPage').fadeOut('slow');
            $('.firstPage').fadeIn('slow');
            $('.thirdPage').fadeOut('slow');

        }

        function goSecondPage() {
            let validated = true;
            $('.alert').html('');
            $('.alert').hide();

            if (validated) {
                $('.firstPage').fadeOut('slow');
                $('.secondPage').fadeIn('slow');
                $('.thirdPage').fadeOut('slow');
            } else {
                $('html, body').animate({
                    scrollTop: $("#errorAlert").offset().top - 100
                }, 1000);
            }
        }

        function goThirdPage() {
            $('.firstPage').fadeOut('slow');
            $('.secondPage').fadeOut('slow');
            $('.thirdPage').fadeIn('slow');
            $.ajax({
                url: '{{route('getNumberOfReceivers')}}',
                type: 'POST',
                data: $('#form1').serialize(),
                success: function (data) {
                    console.log(data);

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
                       $('#thisTotal').html(data.success.current);
                       $('#remain').html(data.success.remain);
                    }
                }
            });
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
                    url: '{{route('storeSms',['type'=>1])}}',
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