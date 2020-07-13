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
                                            class="text-secondary">{{ __('Post Title') }}</h6></label>
                                <div>
                                    <input
                                            id="title_en" name="title_en" placeholder="Title in english"
                                            class="form-control">
                                </div>
                            </div>

                            <div class="form-group col-md-12 toggle sinhalaToggle">
                                <label for="title_si" class="control-label"><h6
                                            class="text-secondary">Post Title in Sinhala</h6></label>
                                <div>
                                    <input id="title_si" name="title_si" placeholder="මාතෘකාව සිංහලෙන්"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group col-md-12 toggle tamilToggle">
                                <label for="title_ta" class="control-label"><h6
                                            class="text-secondary">Post Title in Tamil</h6></label>
                                <div>
                                    <input id="title_ta" name="title_ta" placeholder="சிங்களத்தில் உள்ளடக்கம்"
                                           class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col-md-12 toggle englishToggle">
                                <label for="text_en" class="control-label"><h6
                                            class="text-secondary">{{ __('Post Text') }}</h6></label>
                                <div>
                                <textarea id="text_en" name="text_en" placeholder="Content in english"
                                          class="form-control"
                                          rows="5"></textarea>
                                </div>
                            </div>

                            <div class="form-group col-md-12 toggle  sinhalaToggle">
                                <label for="text_si" class="control-label"><h6
                                            class="text-secondary">Post Text in Sinhala</h6></label>
                                <div>
                                <textarea id="text_si" name="text_si" placeholder="අන්තර්ගතය සිංහලෙන්"
                                          class="form-control"
                                          rows="5"></textarea>
                                </div>
                            </div>

                            <div class="form-group col-md-12  toggle  tamilToggle">
                                <label for="text_ta" class="control-label"><h6
                                            class="text-secondary">Post Text in Tamil</h6></label>
                                <div>
                                <textarea id="text_ta" name="text_ta" placeholder="சிங்களத்தில் உள்ளடக்கம்"
                                          class="form-control"
                                          rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card firstPage">
                    <div class="card-body">

                        <h6 class="text-secondary">Post Attachments</h6>
                        <hr/>
                        <input type="file" style="display: none" id="imageFiles" onchange="readURL(this,1)"
                               name="imageFiles[]" multiple accept="image/*">
                        <input type="file" style="display: none" id="videoFiles" onchange="readURL(this,2)"
                               name="videoFiles[]" multiple
                               accept="video/*">
                        <input type="file" style="display: none" id="audioFiles" name="audioFiles[]"
                               onchange="readURL(this,3)"
                               multiple
                               accept="audio/*">

                        <div class="row">
                            <div class="col-md-4 text-center p-1">
                                <div class="attachmentBox bg-primary  pointer" onclick="$('#imageFiles').click();">
                                    <em style="width: 100%" class="fa fa-image (alias) fa-3x text-white"></em>

                                </div>

                            </div>
                            <div class="col-md-4 text-center p-1">
                                <div class="attachmentBox bg-primary  pointer" onclick="$('#videoFiles').click();">
                                    <em style="width: 100%" class="fa fa-file-video-o fa-3x text-white"></em>


                                </div>

                            </div>
                            <div class="col-md-4 text-center p-1">
                                <div class="attachmentBox bg-primary  pointer" onclick="$('#audioFiles').click();">
                                    <em style="width: 100%" class="fa fa-microphone fa-3x text-white"></em>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
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
                    <h5 class="text-secondary">Post Community Control</h5>
                    <hr/>
                    <div class="card  secondPage">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger alert-dismissible " id="errorAlertUpdate"
                                         style="display:none">
                                    </div>
                                </div>
                            </div>
                            <h5 class="text-secondary"><em class="mdi mdi-map-marker-multiple"></em> Geographical Area
                                Wise</h5>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="electionDivisions"
                                           class="control-label">{{ __('Election Divisions') }}</label>

                                    <select name="electionDivisions[]" id="electionDivisions"
                                            class="select2 form-control select2-multiple" multiple="multiple" multiple
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
                                           class="control-label">{{ __('Polling Booths') }}</label>

                                    <select name="pollingBooths[]" id="pollingBooths"
                                            class="select2 form-control select2-multiple" multiple="multiple" multiple
                                            onchange="pollingBoothChanged(this)"
                                            data-placeholder="Choose ...">

                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="gramasewaDivisions"
                                           class="control-label">{{ __('Gramasewa Divisions') }}</label>

                                    <select name="gramasewaDivisions[]" id="gramasewaDivisions"
                                            class="select2 form-control select2-multiple" multiple="multiple" multiple
                                            onchange="gramasewaDivisionsChanged(this)"
                                            data-placeholder="Choose ...">

                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="villages"
                                           class="control-label">{{ __('Villages') }}</label>

                                    <select name="villages[]" id="villages"
                                            class="select2 form-control select2-multiple" multiple="multiple" multiple
                                            data-placeholder="Choose ...">

                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card  secondPage">
                        <div class="card-body">
                            <h5 class="text-secondary"><em class="mdi mdi-account-multiple"></em> Community Type Wise
                            </h5>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="ethnicities"
                                           class="control-label">{{ __('Ethnicity') }}</label>

                                    <select name="ethnicities[]" id="ethnicities"
                                            class="select2 form-control select2-multiple" multiple="multiple" multiple
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
                                            class="select2 form-control select2-multiple" multiple="multiple" multiple
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
                                            class="select2 form-control select2-multiple" multiple="multiple" multiple
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
                                            class="select2 form-control select2-multiple" multiple="multiple" multiple
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
                                            class="select2 form-control select2-multiple" multiple="multiple" multiple
                                            data-placeholder="Choose ...">
                                        @if($careers != null)
                                            @foreach($careers as $career)
                                                <option value="{{$career->idcareer}}">{{strtoupper($career->name_en)}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label style="margin-left: 5px;" class="control-label">{{ __('Gender') }}</label>
                                    <div class="row">
                                        <label style="margin-left: 10px;" class="radio-inline noClear"><input
                                                    style="margin-left: 10px;" type="radio" value="" name="gender"
                                                    checked>&nbsp;{{ __('All') }}
                                        </label>
                                        <label style="margin-left: 5px;" class="radio-inline noClear"><input
                                                    style="margin-left: 5px;" type="radio" value="0"
                                                    name="gender">&nbsp;{{ __('Male') }}
                                        </label> &nbsp;
                                        &nbsp;
                                        <label style="margin-left: 5px;" class="radio-inline noClear"><input
                                                    style="margin-left: 5px;" type="radio" value="1"
                                                    name="gender">&nbsp;{{ __('Female') }}</label>
                                        <label style="margin-left: 5px;" class="radio-inline"><input
                                                    style="margin-left: 5px;" type="radio" value="2"
                                                    name="gender">&nbsp;{{ __('Other') }}</label>
                                    </div>
                                </div>
                                <input type="hidden" name="minAge" id="minAge">
                                <input type="hidden" name="maxAge" id="maxAge">
                                <div class="form-group col-md-6">
                                    <label style="margin-left: 5px;" class="control-label">{{ __('Age') }}</label><br/>
                                    <b id="minAgeLabel">0 Y</b> &nbsp; <input  id="age" type="text" class="span2" value="" data-slider-min="0" data-slider-max="120" data-slider-step="1" data-slider-value="[0,120]"/>&nbsp; <b id="maxAgeLabel">120 Y</b>
                                </div>
                                <div class="form-group col-md-6">
                                    <label style="margin-left: 5px;"
                                           class="control-label">{{ __('Job Sector') }}</label>
                                    <div class="row">
                                        <label style="margin-left: 10px;" class="radio-inline"><input
                                                    style="margin-left: 10px;" type="radio" value="" name="jobSector"
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
                    <h5 class="text-secondary">Post Meta Data</h5>
                    <hr/>
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
                                <div class="col-md-6">
                                    <h5 class="text-secondary"><em class="fa fa-calendar"></em> Expire Date
                                    </h5>
                                    <hr/>
                                    <div class="form-group ">
                                        <label for="expireDate">{{ __('Set expire date') }}</label>
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
                                                       placeholder="mm/dd/yyyy" name="expireDate" id="expireDate">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-secondary"><em class="fa fa-hand-pointer-o"></em> Click only once
                                    </h5>
                                    <hr/>
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="row">
                                            <div class="col-md-6 text-center">
                                                <input name="onlyOnce" type="checkbox"
                                                       id="onlyOnce" switch="none"/>
                                                <label for="onlyOnce" data-on-label="On"
                                                       data-off-label="Off"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card  thirdPage">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="text-secondary"><em class="mdi mdi-view-grid"></em>Categorize
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="responsePanel">{{ __('Response Panel') }}</label>
                                            <select id="responsePanel" class="form-control" name="responsePanel">
                                                <option value="1">Yes / No</option>
                                                <option value="2">3 Categories</option>
                                                <option value="3">5 Ratings</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8 form-group ">
                                            <label for="cats">{{ __('Categories') }}</label>
                                            <select name="cats[]" id="cats"
                                                    class="select2 form-control select2-multiple" multiple="multiple"
                                                    multiple
                                                    data-placeholder="Choose ...">
                                                @if($categories != null)
                                                    @foreach($categories as $category)
                                                        <option value="{{$category->idcategory}}">{{$category->category}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
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
                                            class="btn btn-success btn-block ">{{ __('Publish Post') }}</button>
                                </div>

                            </div>
                            <div class="row" style="display: none;" id="uploadingBtn">
                                <div style="color: #2d8ac7" class="col-md-4 ml-auto p-sm-2">
                                    <h6><em
                                                style="color: #2d8ac7"
                                                class="fa fa-spin fa-spinner"></em> Uploading your files</h6>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.2/bootstrap-slider.min.js" integrity="sha256-oj52qvIP5c7N6lZZoh9z3OYacAIOjsROAcZBHUaJMyw=" crossorigin="anonymous"></script>
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
            // With JQuery
            $('#age').slider({
                formatter: function(value) {
                    if(value.length == 2) {
                        $('#minAgeLabel').html(value[0] + ' Y');
                        $('#minAge').val(value[0]);
                        $('#maxAgeLabel').html(value[1] + ' Y');
                        $('#maxAge').val(value[1]);
                    }
                }
            });

        });

        function clearAll() {
            $('input').not('.noClear').not('#age').not('#minAge').not('#maxAge').not(':checkbox').not(':radio').val('');
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

        function readURL(input, type) {
            if (input.files) {
                var filesAmount = input.files.length;

                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();

                    reader.onload = function (event) {
                        if (type == 1) {
                            $('#previewCard').append("<div class='col-md-3 py-2 text-center'><img alt='image preview' class='attachmentPreview' src='" + event.target.result + "'></div>");

                        }
                        else if (type == 2) {
                            $('#previewCard').append("<div class='col-md-3 py-2 text-center'><div class='bg-info  audioVideoPreview'><em style='width: 100%' class='center fa fa-file-movie-o (alias) fa-3x text-white'></em></div></div>");

                        }
                        else if (type == 3) {
                            $('#previewCard').append("<div class='col-md-3 py-2 text-center'><div class='bg-info  audioVideoPreview'><em style='width: 100%' class='center fa  fa-file-audio-o fa-3x text-white'></em></div></div>");

                        }
                    }

                    reader.readAsDataURL(input.files[i]);
                }
            }
        }

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
            $('.thirdPage').fadeOut('slow');
            $('.firstPage').fadeIn('slow');

        }

        function goSecondPage() {
            let validated = true;
            $('.alert').html('');
            $('.alert').hide();

            if ($('#title_en').val() == '') {
                validated = false;
                $('#errorAlert').html("<em class='fa fa-genderless'></em> Please fill post title in english").show();
            }
            if ($('#text_en').val() == '') {
                validated = false;
                $('#errorAlert').html("<em class='fa fa-genderless'></em> Please fill post text in english").show();
            }

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
            $('#publishBtns').hide();
            $('#uploadingBtn').show();

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
                    url: '{{route('savePost')}}',
                    type: 'POST',
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        console.log(data);
                        $('#publishBtns').show();
                        $('#uploadingBtn').hide();
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
                                title: 'POST PUBLISHED SUCCESSFULLY.',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Post published successfully.'
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