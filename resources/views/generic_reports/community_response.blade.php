@extends('layouts.main')
@section('psStyle')

    <!-- DataTables -->
    <link href="{{ URL::asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('assets/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>

    <style>

    </style>
@endsection
@section('psContent')
    <div class="page-content-wrapper">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <form action="{{route('report-post-response')}}" id="form1" method="GET">

                        <div class="card m-b-20">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-dismissible " id="errorAlert"
                                             style="display:none">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="category"
                                               class="control-label">{{ __('Category') }}</label>

                                        <select name="category" id="category"
                                                class="select2 form-control "
                                                onchange="categoryChanged(this)">
                                            <option value="">ALL</option>
                                            <option value="1">Religion</option>
                                            <option value="2">Ethnicity</option>
                                            <option value="3">Career</option>
                                            <option value="4">Educational Qualification</option>
                                            <option value="5">Nature of Income</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="column"
                                               class="control-label">{{ __('Value') }}</label>

                                        <select name="column" id="column"
                                                class="select2 form-control ">
                                            <option value="">ALL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card m-b-20">
                            <div class="card-body">


                                <div class="row">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="rows" id="rows">


                                    <div class="form-group col-md-4">
                                        <label for="electionDivision"
                                               class="control-label">{{ __('Election Division') }}</label>

                                        <select name="electionDivision" id="electionDivision"
                                                class="select2 form-control "
                                                onchange="electionDivisionChanged(this)"
                                        >
                                            <option value="">ALL</option>

                                            @if($electionDivisions != null)
                                                @foreach($electionDivisions as $electionDivision)
                                                    <option value="{{$electionDivision->idelection_division}}">{{strtoupper($electionDivision->name_en)}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="pollingBooth"
                                               class="control-label">{{ __('Polling Booth') }}</label>

                                        <select name="pollingBooth" id="pollingBooth"
                                                class="select2 form-control "
                                                onchange="pollingBoothChanged(this)"
                                        >
                                            <option value="">ALL</option>


                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="gramasewaDivision"
                                               class="control-label">{{ __('Gramasewa Divisions') }}</label>

                                        <select name="gramasewaDivision" id="gramasewaDivision"
                                                class="select2 form-control "
                                                onchange="gramasewaDivisionChanged(this)"
                                        >
                                            <option value="">ALL</option>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="village"
                                               class="control-label">{{ __('Villages') }}</label>

                                        <select name="village" id="village"
                                                class="select2 form-control "
                                        >
                                            <option value="">ALL</option>

                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Response Date</label>

                                        <div class="input-daterange input-group" id="date-range">
                                            <input placeholder="dd/mm/yy" type="text" autocomplete="off"
                                                   class="form-control" value="" id="startDate" name="start"/>
                                            <input placeholder="dd/mm/yy" type="text" autocomplete="off"
                                                   class="form-control" value="" id="endDate" name="end"/>

                                        </div>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <button type="submit"
                                                class="btn form-control text-white btn-info waves-effect waves-light"
                                                style="margin-top: 27px;">Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">
                            <div class="table-rep-plugin">
                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                    <table id="table" class="table table-striped table-bordered"
                                           cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>POST</th>
                                            <th>TOTAL</th>
                                            <th>ETHNICITY</th>
                                            <th>RELIGION</th>
                                            <th>CAREER</th>
                                            <th>EDUCATION</th>
                                            <th>INCOME</th>
                                            <th>JOB SECTOR</th>
                                            <th>GENDER</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbody">
                                        @if(isset($posts))
                                            @if($posts != null)
                                                @foreach($posts as $post)
                                                    <tr id="{{$post->idPost}}">
                                                        <td><a href="#" onclick="showPostContent({{$post->post_no}})"><p
                                                                        class="font-weight-bold">{{$post->title_en}}</p>
                                                            </a></td>
                                                        <td nowrap="true">{!! \App\PostResponse::TOTAL_COUNT($post->idPost,isset($_REQUEST['start']) ? $_REQUEST['start'] : null,isset($_REQUEST['end']) ? $_REQUEST['end'] : null,isset($_REQUEST['village']) ? $_REQUEST['village'] : null,isset($_REQUEST['gramasewaDivision']) ? $_REQUEST['gramasewaDivision'] : null,isset($_REQUEST['pollingBooth']) ? $_REQUEST['pollingBooth'] : null,isset($_REQUEST['electionDivision']) ? $_REQUEST['electionDivision'] : null) !!}</td>
                                                        <td nowrap="true">{!! \App\PostResponse::ETHNICITY_COUNT($post->idPost,4,isset($_REQUEST['start']) ? $_REQUEST['start'] : null,isset($_REQUEST['end']) ? $_REQUEST['end'] : null,isset($_REQUEST['village']) ? $_REQUEST['village'] : null,isset($_REQUEST['gramasewaDivision']) ? $_REQUEST['gramasewaDivision'] : null,isset($_REQUEST['pollingBooth']) ? $_REQUEST['pollingBooth'] : null,isset($_REQUEST['electionDivision']) ? $_REQUEST['electionDivision'] : null) !!}</td>
                                                        <td nowrap="true">{!! \App\PostResponse::RELIGION_COUNT($post->idPost,4,isset($_REQUEST['start']) ? $_REQUEST['start'] : null,isset($_REQUEST['end']) ? $_REQUEST['end'] : null,isset($_REQUEST['village']) ? $_REQUEST['village'] : null,isset($_REQUEST['gramasewaDivision']) ? $_REQUEST['gramasewaDivision'] : null,isset($_REQUEST['pollingBooth']) ? $_REQUEST['pollingBooth'] : null,isset($_REQUEST['electionDivision']) ? $_REQUEST['electionDivision'] : null) !!}</td>
                                                        <td nowrap="true">{!! \App\PostResponse::CAREER_COUNT($post->idPost,4,isset($_REQUEST['start']) ? $_REQUEST['start'] : null,isset($_REQUEST['end']) ? $_REQUEST['end'] : null,isset($_REQUEST['village']) ? $_REQUEST['village'] : null,isset($_REQUEST['gramasewaDivision']) ? $_REQUEST['gramasewaDivision'] : null,isset($_REQUEST['pollingBooth']) ? $_REQUEST['pollingBooth'] : null,isset($_REQUEST['electionDivision']) ? $_REQUEST['electionDivision'] : null) !!}</td>
                                                        <td nowrap="true">{!! \App\PostResponse::EDUCATION_COUNT($post->idPost,4,isset($_REQUEST['start']) ? $_REQUEST['start'] : null,isset($_REQUEST['end']) ? $_REQUEST['end'] : null,isset($_REQUEST['village']) ? $_REQUEST['village'] : null,isset($_REQUEST['gramasewaDivision']) ? $_REQUEST['gramasewaDivision'] : null,isset($_REQUEST['pollingBooth']) ? $_REQUEST['pollingBooth'] : null,isset($_REQUEST['electionDivision']) ? $_REQUEST['electionDivision'] : null) !!}</td>
                                                        <td nowrap="true">{!! \App\PostResponse::INCOME_COUNT($post->idPost,4,isset($_REQUEST['start']) ? $_REQUEST['start'] : null,isset($_REQUEST['end']) ? $_REQUEST['end'] : null,isset($_REQUEST['village']) ? $_REQUEST['village'] : null,isset($_REQUEST['gramasewaDivision']) ? $_REQUEST['gramasewaDivision'] : null,isset($_REQUEST['pollingBooth']) ? $_REQUEST['pollingBooth'] : null,isset($_REQUEST['electionDivision']) ? $_REQUEST['electionDivision'] : null) !!}</td>
                                                        <td nowrap="true">{!! \App\PostResponse::JOB_SECTOR_COUNT($post->idPost,4,isset($_REQUEST['start']) ? $_REQUEST['start'] : null,isset($_REQUEST['end']) ? $_REQUEST['end'] : null,isset($_REQUEST['village']) ? $_REQUEST['village'] : null,isset($_REQUEST['gramasewaDivision']) ? $_REQUEST['gramasewaDivision'] : null,isset($_REQUEST['pollingBooth']) ? $_REQUEST['pollingBooth'] : null,isset($_REQUEST['electionDivision']) ? $_REQUEST['electionDivision'] : null) !!}</td>
                                                        <td nowrap="true">{!! \App\PostResponse::GENDER($post->idPost,4,isset($_REQUEST['start']) ? $_REQUEST['start'] : null,isset($_REQUEST['end']) ? $_REQUEST['end'] : null,isset($_REQUEST['village']) ? $_REQUEST['village'] : null,isset($_REQUEST['gramasewaDivision']) ? $_REQUEST['gramasewaDivision'] : null,isset($_REQUEST['pollingBooth']) ? $_REQUEST['pollingBooth'] : null,isset($_REQUEST['electionDivision']) ? $_REQUEST['electionDivision'] : null) !!}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{--@if(isset($posts))--}}
                                {{--{{$posts->links()}}--}}
                            {{--@endif--}}
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->

    <!-- modal start -->

    <div class="modal fade" id="viewPost" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">View Post</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-secondary" id="modelTitle"></h4>
                        </div>
                    </div>
                    {{--<h6 class="text-secondary">Post Text</h6>--}}
                    <hr/>
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-secondary" id="modelEnglish"></h6>
                        </div>
                    </div>
                    {{--<h6 class="text-secondary">Post Attachments</h6>--}}
                    <hr/>
                    <div class="row">
                        <div class="col-md-12" id="modalAttachments"></div>

                    </div>


                </div>
            </div>
        </div>
    </div>
    <!-- modal end -->

@endsection
@section('psScript')

    <!-- Required datatable js -->
    <script src="{{ URL::asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{ URL::asset('assets/plugins/datatables/dataTables.buttons.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/datatables/jszip.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/datatables/pdfmake.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/datatables/vfs_fonts.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/datatables/buttons.html5.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/datatables/buttons.print.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/datatables/buttons.colVis.min.js')}}"></script>
    <!-- Responsive examples -->
    <script src="{{ URL::asset('assets/plugins/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>

    <!-- Datatable init js -->
    <script src="{{ URL::asset('assets/pages/datatables.init.js')}}"></script>


    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function electionDivisionChanged(el) {
            let divisions = $(el).val();
            $('#pollingBooth').html("<option value=''>ALL</option>");
            $('#gramasewaDivision').html("<option value=''>ALL</option>");
            $('#village').html("<option value=''>ALL</option>");

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
            $('#gramasewaDivision').html("<option value=''>ALL</option>");
            $('#village').html("<option value=''>ALL</option>");

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
            $('#village').html("<option value=''>ALL</option>");
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

        function categoryChanged(el) {
            let value = el.value;
            $('#column').html("<option value=''>ALL</option>");
            $.ajax({
                url: '{{route('getCommunityCategoryValues')}}',
                type: 'POST',
                data: {id: value},
                success: function (data) {
                    let result = data.success;
                    console.log(result);
                    $.each(result, function (key, value) {
                        $('#column').append("<option value='" + value.id + "'>" + value.name_en + "</option>");
                    });
                }
            });
        }

        function showPostContent(id) {
            $.ajax({
                url: '{{route('viewPostAdmin')}}',
                type: 'POST',
                data: {id: id},
                success: function (data) {
                    let postAttachments = data.success.attachments;
                    let postTextEnglish = data.success.text_en;
                    let postTitleEnglish = data.success.title_en;

                    let modalEnglish = $('#modelEnglish');
                    let modalAttachment = $('#modalAttachments');
                    let modalTitle = $('#modelTitle');
                    modalAttachment.html('');
                    modalEnglish.html('');

                    //post title
                    modalTitle.html(postTitleEnglish);
                    //post title end

                    // post text
                    modalEnglish.html(postTextEnglish);
                    //post text end

                    //post attachments
                    $.each(postAttachments, function (key, value) {
                        if (value.file_type == 1) {
                            modalAttachment.append("<img class='rounded postImage m-2' src='" + value.full_path + "' height='200px' width='200px'>");
                        } else if (value.file_type == 2) {
                            modalAttachment.append("" +
                                "<video width='400' controls>" +
                                "<source src='" + value.full_path + "' type='video/mp4'>" +
                                "<source src='" + value.full_path + "' type='video/ogg'> " +
                                "Your browser does not support HTML video." +
                                "</video>" +
                                "");
                        }
                        else if (value.file_type == 3) {
                            modalAttachment.append("" +
                                "<audio width='400' controls>" +
                                "<source src='" + value.full_path + "' type='audio/ogg'>" +
                                "<source src='" + value.full_path + "' type='audio/mpeg'> " +
                                "Your browser does not support the audio element." +
                                "</audio>" +
                                "");
                        }
                        //post attachments end
                    });
                    $('#viewPost').modal('show');
                }
            });
        }
    </script>
@endsection