@extends('layouts.main')
@section('psStyle')
    <style>

    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">
            <form class="form" id="form1" role="form" >

            <div class="card firstPage">
                <div class="card-body">


                        <div class="row firstPage">
                            <div class="col-lg-12">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons" style="width: 100%">
                                    <label class="btn btn-primary active englishToggle">
                                        <input type="radio" name="options" class="toggleBtn"
                                               data-id="englishToggle"
                                               id="englishToggle" autocomplete="off" checked> English
                                    </label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" class="toggleBtn"
                                               data-id="sinhalaToggle"
                                               id="sinhalaToggle" autocomplete="off"> Sinhala
                                    </label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" class="toggleBtn"
                                               data-id="tamilToggle"
                                               id="tamilToggle" autocomplete="off"> Tamil
                                    </label>
                                </div>
                            </div>
                        </div>
                        <br/>

                        <div class="card firstPage">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-dismissible " id="errorAlert"
                                             style="display:none">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12  toggle englishToggle">
                                        <label for="event_en" class="control-label"><h6
                                                    class="text-secondary">{{ __('Event name') }}</h6></label>
                                        <div>
                                            <input
                                                    id="event_en" name="event_en" placeholder="Event in english"
                                                    class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12 toggle sinhalaToggle">
                                        <label for="event_si" class="control-label"><h6
                                                    class="text-secondary">Event name in Sinhala</h6></label>
                                        <div>
                                            <input id="event_si" name="event_si" placeholder="සිංහලෙන්"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12 toggle tamilToggle">
                                        <label for="event_ta" class="control-label"><h6
                                                    class="text-secondary">Event name in Tamil</h6></label>
                                        <div>
                                            <input id="event_ta" name="event_ta" placeholder="உள்ளடக்கம்"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12  toggle englishToggle">
                                        <label for="location_en" class="control-label"><h6
                                                    class="text-secondary">{{ __('Location') }}</h6></label>
                                        <div>
                                            <input
                                                    id="location_en" name="location_en" placeholder="Location in english"
                                                    class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12 toggle sinhalaToggle">
                                        <label for="location_si" class="control-label"><h6
                                                    class="text-secondary">Location in Sinhala</h6></label>
                                        <div>
                                            <input id="location_si" name="location_si" placeholder="සිංහලෙන්"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12 toggle tamilToggle">
                                        <label for="location_ta" class="control-label"><h6
                                                    class="text-secondary">Location in Tamil</h6></label>
                                        <div>
                                            <input id="location_ta" name="location_ta" placeholder="உள்ளடக்கம்"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="form-group col-md-12 toggle englishToggle">
                                        <label for="description_en" class="control-label"><h6
                                                    class="text-secondary">{{ __('Description') }}</h6></label>
                                        <div>
                                <textarea id="description_en" name="description_en" placeholder="Description in english"
                                          class="form-control"
                                          rows="5"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12 toggle  sinhalaToggle">
                                        <label for="description_si" class="control-label"><h6
                                                    class="text-secondary">Description in Sinhala</h6></label>
                                        <div>
                                <textarea id="description_si" name="description_si" placeholder="අන්තර්ගතය සිංහලෙන්"
                                          class="form-control"
                                          rows="5"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12  toggle  tamilToggle">
                                        <label for="description_ta" class="control-label"><h6
                                                    class="text-secondary">Description in Tamil</h6></label>
                                        <div>
                                <textarea id="description_ta" name="description_ta"
                                          placeholder="சிங்களத்தில் உள்ளடக்கம்"
                                          class="form-control"
                                          rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="date">{{ __('Event Date') }}</label>
                                        <div>
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><em
                                                                class="mdi mdi-calendar"></em></span>
                                                </div>
                                                <input autocomplete="off" type="text"
                                                       class="form-control datepicker-autoclose"
                                                       required onchange="setCustomValidity('')"
                                                       oninvalid="this.setCustomValidity('Please enter event date')"
                                                       placeholder="mm/dd/yyyy" name="date" id="date">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3 ">
                                        <label for="time" class="control-label">Start Time</label>
                                        <div>
                                            <input type="time" id="time" name="time" placeholder="12.00"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2  p-sm-2">
                                        <button onclick="clearAll();event.preventDefault();" style="margin-top: 22px"
                                                class="btn btn-danger float-right btn-block ">{{ __('Cancel') }}</button>
                                    </div>
                                    <div class="col-md-2  p-sm-2">
                                        <button
                                                onclick="goSecondPage();event.preventDefault();"
                                                style="margin-top: 22px"
                                                class="btn btn-primary float-right btn-block ">{{ __('Next') }} <em
                                                    class="fa fa-chevron-right"></em></button>
                                    </div>


                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <!-------------------------------------------- FIRST PAGE END ----------------------------------------->

            <div class="secondPage" style="display: none;">
                <h5 class="text-secondary">Event Community Control</h5>
                <hr/>
                <div class="card  secondPage">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible " id="errorAlert2"
                                     style="display:none">
                                </div>
                            </div>
                        </div>
                        <h5 class="text-secondary"><em class="mdi mdi-map-marker-multiple"></em> Geographical Area
                            Wise</h5>
                        <hr/>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="electionDivision"
                                       class="control-label">{{ __('Election Division') }}</label>

                                <select name="electionDivision" id="electionDivision"
                                        class="select2 form-control "
                                        onchange="electionDivisionChanged(this)">
                                    <option selected  value=''>ALL</option>
                                @if($electionDivisions != null)
                                        @foreach($electionDivisions as $electionDivision)
                                            <option value="{{$electionDivision->idelection_division}}">{{strtoupper($electionDivision->name_en)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="pollingBooth"
                                       class="control-label">{{ __('Polling Booth') }}</label>

                                <select name="pollingBooth" id="pollingBooth"
                                        class="select2 form-control "
                                        onchange="pollingBoothChanged(this)">
                                    <option selected value=''>ALL</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="gramasewaDivision"
                                       class="control-label">{{ __('Gramasewa Division') }}</label>

                                <select name="gramasewaDivision" id="gramasewaDivision"
                                        class="select2 form-control "
                                        onchange="gramasewaDivisionChanged(this)">
                                    <option selected value=''>ALL</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="village"
                                       class="control-label">{{ __('Villages') }}</label>

                                <select name="village" id="village"
                                        class="select2 form-control ">
                                    <option selected value=''>ALL</option>
                                </select>
                            </div>

                        </div>
                        <div class="row secondPage">
                            <div class="col-md-2  secondPage">
                                <button  style="margin-top: 27px;" onclick="goFirstPage();event.preventDefault();"
                                        class="btn btn-primary float-right btn-block "><em
                                            style="margin-top: 5px;"
                                            class="fa fa-chevron-left"></em> {{ __('Previous') }} </button>
                            </div>
                            <div class="col-md-2 secondPage  ">
                                <button style="margin-top: 27px;"
                                        onclick="clearAll();event.preventDefault();"
                                        class="btn btn-danger float-right btn-block "> {{ __('Clear All') }} </button>
                            </div>
                            <div class="col-md-2 ">
                                <button style="margin-top: 27px;" type="submit" form="form1"
                                        class="btn btn-success btn-block ">{{ __('Save Event') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>


        </div> <!-- ./container -->
    </div><!-- ./wrapper -->
@endsection
@section('psScript')

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            $('.toggle').hide();
            $('.englishToggle').show();
            initializeDate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });
        function clearAll() {
            $('input').not(':checkbox').val('');
            $('textarea').val('');
            $(":checkbox").attr('checked', false).trigger('change');
            $(":radio").attr('checked', false).trigger('change');
            $('select').val('').trigger('change');
            $('#englishToggle').click();
            $('#previewCard').html('');
            $('#responsePanel').val(1).trigger('change');
            $('html, body').animate({
                scrollTop: $("body").offset().top
            }, 1000);
        }

        $('.toggleBtn').change(function () {
            let id = $(this).attr('data-id');
            $('.toggle').each(function () {
                $(this).hide();
            });
            $('.' + id).each(function () {
                $(this).show();
            });
        });

        function electionDivisionChanged(el) {
            let divisions = $(el).val();
            $('#pollingBooth').html("<option selected value=''>ALL</option>");
            $('#gramasewaDivision').html("<option selected value=''>ALL</option>");
            $('#village').html("<option selected value=''>ALL</option>");

            $.ajax({
                url: '{{route('getPollingBoothByElectionDivision')}}',
                type: 'POST',
                data: {id: divisions},
                success: function (data) {
                    let result = data.success;
                    $.each(result, function (key, value) {
                        $('#pollingBooth').append("<option value='" + value.idpolling_booth + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }

        function pollingBoothChanged(el) {
            let booths = $(el).val();
            $('#gramasewaDivision').html("<option selected value=''>ALL</option>");
            $('#village').html("<option selected value=''>ALL</option>");

            $.ajax({
                url: '{{route('getGramasewaDivisionByPollingBooth')}}',
                type: 'POST',
                data: {id: booths},
                success: function (data) {
                    let result = data.success;
                    $.each(result, function (key, value) {
                        $('#gramasewaDivision').append("<option value='" + value.idgramasewa_division + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }

        function gramasewaDivisionChanged(el) {
            let division = el.value;
            $('#village').html("<option selected value=''>ALL</option>");
            $.ajax({
                url: '{{route('getVillageByGramasewaDivision')}}',
                type: 'POST',
                data: {id: division},
                success: function (data) {
                    let result = data.success;
                    $.each(result, function (key, value) {
                        $('#village').append("<option value='" + value.idvillage + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }
        function goFirstPage() {
            $('.secondPage').fadeOut('slow');
            $('.firstPage').fadeIn('slow');
        }

        function goSecondPage() {
            let validated = true;
            $('.alert').html('');
            $('.alert').hide();

            if ($('#event_en').val() == '') {
                validated = false;
                $('#errorAlert').html("<em class='fa fa-genderless'></em> Please fill event name in english").show();
            }
            else if ($('#location_en').val() == '') {
                validated = false;
                $('#errorAlert').html("<em class='fa fa-genderless'></em> Please fill location in english").show();
            }
            else if ($('#date').val() == '') {
                validated = false;
                $('#errorAlert').html("<em class='fa fa-genderless'></em> Event date should be provided.").show();
            }
            else if ($('#time').val() == '') {
                validated = false;
                $('#errorAlert').html("<em class='fa fa-genderless'></em> Start time should be provided.").show();
            }

            if (validated) {
                $('.firstPage').fadeOut('slow');
                $('.secondPage').fadeIn('slow');
            } else {
                $('html, body').animate({
                    scrollTop: $("#errorAlert").offset().top - 100
                }, 1000);
            }
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
                    url: '{{route('save-event')}}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.errors != null) {
                            $('#errorAlert').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlert2').append("<p><em class='fa fa-genderless'></em> " + value + "</p>");
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'EVENT SAVED SUCCESSFULLY.',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Event saved successfully.'
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