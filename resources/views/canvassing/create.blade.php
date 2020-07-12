@extends('layouts.main')
@section('psStyle')
    <style>
        .attachmentBox {
            height: 100px;
            width: 100px;
            position: relative;;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            border-radius: 10px;
        }

        .fa-3x {
            position: absolute;;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .pointer {
            cursor: pointer;
        }

        .attachmentPreview {
            display: inline;
            height: 100px;
            width: 100px;
            align-items: center;
            border-radius: 10px;
        }

        .audioVideoPreview {
            height: 100px;
            border-radius: 10px;

        }

        .center {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.2/css/bootstrap-slider.min.css" integrity="sha256-G3IAYJYIQvZgPksNQDbjvxd/Ca1SfCDFwu2s2lt0oGo=" crossorigin="anonymous" />
@endsection
@section('psContent')

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <form class="form-horizontal" id="form1" role="form" enctype="multipart/form-data">


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
                                <label for="title_en" class="control-label"><h6
                                            class="text-secondary">{{ __('Canvassing Name') }}</h6></label>
                                <div>
                                    <input
                                            id="name_en" name="name_en" placeholder="Name in english"
                                            class="form-control">
                                </div>
                            </div>

                            <div class="form-group col-md-12 toggle sinhalaToggle">
                                <label for="name_si" class="control-label"><h6
                                            class="text-secondary">Canvassing Name In Sinhala</h6></label>
                                <div>
                                    <input id="name_si" name="name_si" placeholder="නාමය සිංහලෙන්"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group col-md-12 toggle tamilToggle">
                                <label for="name_ta" class="control-label"><h6
                                            class="text-secondary">Canvassing Name In Tamil</h6></label>
                                <div>
                                    <input id="name_ta" name="name_ta" placeholder="சிங்களத்தில் உள்ளடக்கம்"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12  toggle englishToggle">
                                <label for="location_en" class="control-label"><h6
                                            class="text-secondary">{{ __('Canvassing Location') }}</h6></label>
                                <div>
                                    <input
                                            id="location_en" name="location_en" placeholder="Location in english"
                                            class="form-control">
                                </div>
                            </div>

                            <div class="form-group col-md-12 toggle sinhalaToggle">
                                <label for="location_si" class="control-label"><h6
                                            class="text-secondary">Canvassing Location In Sinhala</h6></label>
                                <div>
                                    <input id="location_si" name="location_si" placeholder="ස්ථානය සිංහලෙන්"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group col-md-12 toggle tamilToggle">
                                <label for="location_ta" class="control-label"><h6
                                            class="text-secondary">Canvassing Location In Tamil</h6></label>
                                <div>
                                    <input id="location_ta" name="location_ta" placeholder="சிங்களத்தில் உள்ளடக்கம்"
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
                                <textarea id="description_si" name="description_si" placeholder="විස්තර සිංහලෙන්"
                                          class="form-control"
                                          rows="5"></textarea>
                                </div>
                            </div>

                            <div class="form-group col-md-12  toggle  tamilToggle">
                                <label for="description_ta" class="control-label"><h6
                                            class="text-secondary">Description in Tamil</h6></label>
                                <div>
                                <textarea id="description_ta" name="description_ta" placeholder="சிங்களத்தில் உள்ளடக்கம்"
                                          class="form-control"
                                          rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="card firstPage">
                    <div class="card-body">
                        <div id="previewCard" class="row">

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

                    <div class="card  secondPage">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger alert-dismissible " id="villageErrorAlert"
                                         style="display:none">
                                    </div>
                                </div>
                            </div>
                            <h5 class="text-secondary"><em class="mdi mdi-map-marker-multiple"></em> Assign Villages</h5>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="electionDivision"
                                           class="control-label">{{ __('Election Division') }}</label>
                                    <div>
                                        <div class="input-group " >
                                            <div  class="input-group-append">
                                                <span style="padding: 10px;" class="input-group-text"><em class="mdi mdi-bank"></em></span>
                                            </div>
                                            <div class="flex-fill">
                                                <select  id="electionDivision" name="electionDivision" class="form-control select2"
                                                         onchange="electionDivisionChanged(this)" >
                                                    <option value="" disabled  selected>Select Division</option>
                                                    @if($electionDivisions != null)
                                                        @foreach($electionDivisions as $electionDivision)
                                                            <option value="{{$electionDivision->idelection_division}}">{{strtoupper($electionDivision->name_en)}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group col-md-6">
                                    <label for="pollingBooth" class="control-label">{{ __('Member Division') }}</label>
                                    <div>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span  style="padding: 10px;"  class="input-group-text"><em class="mdi mdi-bank"></em></span>
                                            </div>
                                            <div class="flex-fill">
                                                <select id="pollingBooth" name="pollingBooth" class="form-control noClear select2"
                                                        onchange="pollingBoothChanged(this)"
                                                >
                                                    <option value=""  selected>Select member division</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group col-md-6">
                                    <label for="gramasewaDivision" class="control-label">{{ __('Gramasewa Division') }}</label>
                                    <div>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span style="padding: 10px;"  class="input-group-text"><em class="mdi mdi-bank"></em></span>
                                            </div>
                                            <div  class="flex-fill">
                                                <select id="gramasewaDivision" name="gramasewaDivision" class="form-control noClear select2"
                                                        onchange="gramasewaDivisionsChanged(this)">
                                                    <option value=""  selected>Select  gramasewa division</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="village"
                                           class="control-label">{{ __('Villages') }}</label>

                                    <select name="village" id="village"
                                            class="select2 form-control "
                                    >
                                        <option value=""  selected>Select  village</option>

                                    </select>
                                </div>
                                <div class="col-md-2  p-sm-2">
                                    <button onclick="addVillage();event.preventDefault();"
                                            class="btn btn-primary float-right btn-block ">{{ __('Add Village') }}
                                    </button>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive b-0" data-pattern="priority-columns">
                                            <table class="table table-striped table-bordered"
                                                   cellspacing="0"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>VILLAGE</th>
                                                    <th>AGENT</th>
                                                    <th>NO OF HOUSES</th>
                                                    <th style='text-align: center;'>DELETE</th>
                                                </tr>
                                                </thead>
                                                <tbody id="villageTbody">

                                                </tbody>
                                            </table>
                                        </div>
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
                    <div class="card  thirdPage">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger alert-dismissible " id="errorAlert3"
                                         style="display:none">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="text-secondary"><em class="fa fa-calendar"></em> Canvassing Date / Time
                                    </h5>
                                    <hr/>
                                    <div class="row">
                                    <div class="form-group col-md-6 ">
                                        <label for="date">{{ __('Canvassing Date') }}</label>
                                        <div>
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><em
                                                                class="mdi mdi-calendar"></em></span>
                                                </div>
                                                <input autocomplete="off" type="text"
                                                       class="form-control datepicker-autoclose"
                                                       onchange="setCustomValidity('')"
                                                       oninvalid="this.setCustomValidity('Please set post expire date')"
                                                       placeholder="mm/dd/yyyy" name="date" id="date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 ">
                                        <label for="time">{{ __('Canvassing Time') }}</label>
                                        <div>
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><em
                                                                class="mdi mdi-av-timer"></em></span>
                                                </div>
                                                <input autocomplete="off" type="time"
                                                       class="form-control "
                                                       onchange="setCustomValidity('')"
                                                       oninvalid="this.setCustomValidity('Please set post expire date')"
                                                       name="time" id="time">
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-secondary"><em class="mdi mdi-account-convert"></em> Canvassing Type
                                    </h5>
                                    <hr/>
                                    <div class="row">
                                    <div class="col-md-8">
                                        <label for="canvassingType">{{ __('Canvassing Type') }}</label>
                                        <select id="canvassingType" class="form-control" name="canvassingType">
                                            @if($canvassingTypes != null)
                                                @foreach($canvassingTypes as $canvassingType)
                                                    <option value="{{$canvassingType->idcanvassing_type}}">{{$canvassingType->name_en}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-secondary"><em class="mdi mdi-book-open-page-variant"></em> Detailed Form
                                    </h5>
                                    <hr/>

                                    <div class="form-group">
                                        <label><em class="fa fa-exclamation-circle"></em> Turning on this may collect more details about houses.But will spend more time.</label>
                                        <div class="row">
                                            <div class="col-md-6 ">
                                                <input name="isDetail" type="checkbox" checked class="noClear"
                                                       id="isDetail" switch="none"/>
                                                <label for="isDetail" data-on-label="On"
                                                       data-off-label="Off"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card thirdPage">
                        <div class="card-body">
                            <hr/>
                            <div class="row" id="publishBtns">
                                <div class="col-md-8 .d-sm-none ">
                                </div>
                                <div class="col-md-2  p-sm-2">
                                    <button
                                            onclick="goSecondPage();event.preventDefault();"
                                            class="btn btn-primary float-right btn-block "><em
                                                style="margin-top: 5px;"
                                                class="fa fa-chevron-left"></em> {{ __('Previous') }} </button>
                                </div>
                                <div class="col-md-2  p-sm-2">
                                    <button type="submit" form="form1"
                                            class="btn btn-success btn-block ">{{ __('Publish Canvassing') }}</button>
                                </div>

                            </div>
                        </div><!-- /card body -->
                    </div><!-- /card -->
                </div>
            </form> <!-- /form -->
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
            clearAll();

        });

        function clearAll() {
            deleteTempValues();
            $('input').not('.noClear').not(':checkbox').val('');
            $('textarea').val('');
            $(":checkbox").not('.noClear').attr('checked', false).trigger('change');
            $(":radio").not('.noClear').attr('checked', false).trigger('change');
            $('select').not('.noClear').val('').trigger('change');
            $('#englishToggle').click();
            $('html, body').animate({
                scrollTop: $("body").offset().top
            }, 1000);
            $("#canvassingType").val($("#canvassingType option:first").val()).trigger('change');

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
            $('#pollingBooth').html("<option value='' selected disabled>Select member division</option>");
            $('#gramasewaDivision').html("<option value='' selected disabled>Select gramasewa division</option>");
            $('#village').html("<option value='' selected disabled>Select village</option>");

            if(divisions) {
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
        }

        function pollingBoothChanged(el) {
            let booths = $(el).val();
            $('#gramasewaDivision').html("<option value='' selected disabled>Select gramasewa division</option>");
            $('#village').html("<option value='' selected disabled>Select village</option>");

            if(booths) {
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
        }

        function gramasewaDivisionsChanged(el) {
            let divisions = $(el).val();
            $('#village').html("<option value='' selected disabled>Select village</option>");
            if(divisions) {
                $.ajax({
                    url: '{{route('getVillageByGramasewaDivision')}}',
                    type: 'POST',
                    data: {id: divisions},
                    success: function (data) {
                        let result = data.success;
                        $.each(result, function (key, value) {
                            $('#village').append("<option value='" + value.idvillage + "'>" + value.name_en + "</option>");
                        });
                    }
                });
            }
        }

        function goFirstPage() {
            $('.secondPage').fadeOut('slow');
            $('.thirdPage').fadeOut('slow');
            $('.firstPage').fadeIn('slow');
        }

        function goSecondPage() {
            let validated = true;
            $('.alert').html('');
            $('.alert').hide();
            loadVillageTemp();

            if (validated) {
                $('.firstPage').fadeOut('slow');
                $('.thirdPage').fadeOut('slow');
                $('.secondPage').fadeIn('slow');
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
                    url: '{{route('saveCanvassing')}}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        console.log(data);
                        if (data.errors != null) {
                            $('#errorAlert3').show();
                            $.each(data.errors, function (key, value) {
                                $('#errorAlert3').append("<p><em class='fa fa-genderless'></em> " + value + "</p>");
                            });
                            $('html, body').animate({
                                scrollTop: $("body").offset().top
                            }, 1000);
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'CANVASSING SAVED SUCCESSFULLY.',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Canvassing saved successfully.'
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

        function addVillage() {
            let village = $('#village').val();
            $('#villageErrorAlert').html('').hide();
            $.ajax({
                url: '{{route('addVillageToCanvassing')}}',
                type: 'POST',
                data: {village: village},
                success: function (data) {
                    if (data.errors != null) {
                        $('#villageErrorAlert').show();
                        $.each(data.errors, function (key, value) {
                            $('#villageErrorAlert').append("<p><em class='fa fa-genderless'></em> " + value + "</p>");
                        });
                        $('html, body').animate({
                            scrollTop: $("body").offset().top
                        }, 1000);
                    }
                    if (data.success != null) {
                        loadVillageTemp();
                    }

                }
            });
        }

        function loadVillageTemp() {
            $.ajax({
                url: '{{route('loadVillageTemp')}}',
                type: 'POST',
                success: function (data) {
                    $('#villageTbody').html('');
                    if(data.length > 0){

                        $('#electionDivision').attr('disabled',true);
                        $('#pollingBooth').attr('disabled',true);
                    }
                    else{
                        $('#electionDivision').attr('disabled',false);
                        $('#pollingBooth').attr('disabled',false);
                    }
                    $.each(data, function (key, value) {
                        $('#villageTbody').append("" +
                            "<tr>" +
                            "<td>"+value.name+"</td>" +
                            "<td>"+value.agent+"</td>" +
                            "<td>"+value.houses+"</td>" +
                            "<td style='text-align: center;'>" +
                            "<p>" +
                            " <button  title='Can not use this option on confirmed records'  type='button' " +
                            "onclick='deleteTempVillage("+value.idcanvassing_village_temp+")' " +
                            "class='btn btn-sm btn-muted text-white bg-danger waves-effect waves-light'>" +
                            " <i class='fa fa-trash'></i>" +
                            "</button>" +
                            " </p>" +
                            " </td>" +
                            "</tr>"+
                            "</tr>");
                    });
                }
            });
        }

        function deleteTempVillage(id) {
            $.ajax({
                url: '{{route('deleteVillageTempCanvassing')}}',
                type: 'POST',
                data: {id: id},
                success: function () {
                    loadVillageTemp();
                }
            });
        }

        function deleteTempValues() {
            $.ajax({
                url: '{{route('deleteCanvassingTempValues')}}',
                type: 'POST',
                success: function () {
                    loadVillageTemp();
                }
            });
        }
    </script>
@endsection