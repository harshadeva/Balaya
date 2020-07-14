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
                    <div class="card m-b-20">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 data-toggle="collapse"
                                        href="#multiCollapseExample1" aria-expanded="false"
                                        aria-controls="multiCollapseExample1"><em class="fa fa-search"></em> Search
                                        Members</h6>

                                </div>
                            </div>
                            <form action="{{route('report-members')}}" id="form1" method="GET">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-dismissible " id="errorAlert"
                                             style="display:none">
                                        </div>
                                    </div>
                                </div>

                                <div class="row collapse multi-collapse" id="multiCollapseExample1">
                                    {{ csrf_field() }}
                                    <div class="form-group col-md-4 ">
                                        <label for="searchCol">Search By</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <select class="form-control  " name="searchCol"
                                                        id="searchCol" required>
                                                    <option value="1" selected>FIRST NAME</option>
                                                    <option value="2">LAST NAME</option>
                                                    <option value="3">NIC NO</option>
                                                    <option value="4">EMAIL</option>
                                                </select>
                                            </div>
                                            <input class="form-control " type="text" min="0"
                                                   id="searchText"
                                                   name="searchText">

                                        </div>
                                    </div>
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
                                               class="control-label">{{ __('Member Division') }}</label>

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
                                        <label for="ethnicity"
                                               class="control-label">{{ __('Ethnicity') }}</label>

                                        <select name="ethnicity" id="ethnicity"
                                                class="select2 form-control"
                                        >
                                            <option selected value="">All</option>
                                            @if($ethnicities != null)
                                                @foreach($ethnicities as $ethnicities)
                                                    <option value="{{$ethnicities->idethnicity}}">{{strtoupper($ethnicities->name_en)}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="religion"
                                               class="control-label">{{ __('Religion') }}</label>

                                        <select name="religion" id="religion"
                                                class="select2 form-control"
                                        >
                                            <option selected value="">All</option>
                                            @if($religions != null)
                                                @foreach($religions as $religion)
                                                    <option value="{{$religion->idreligion}}">{{strtoupper($religion->name_en)}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="income"
                                               class="control-label">{{ __('Nature of income') }}</label>

                                        <select name="income" id="income"
                                                class="select2 form-control"
                                        >
                                            <option selected value="">All</option>
                                            @if($incomes != null)
                                                @foreach($incomes as $income)
                                                    <option value="{{$income->idnature_of_income}}">{{strtoupper($income->name_en)}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="education"
                                               class="control-label">{{ __('Educational Qualification') }}</label>

                                        <select name="education" id="education"
                                                class="select2 form-control"
                                        >
                                            <option selected value="">All</option>
                                            @if($incomes != null)
                                                @foreach($educations as $education)
                                                    <option value="{{$education->ideducational_qualification}}">{{strtoupper($education->name_en)}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="career"
                                               class="control-label">{{ __('Career') }}</label>

                                        <select name="career" id="career"
                                                class="select2 form-control"
                                        >
                                            <option selected value="">All</option>
                                            @if($careers != null)
                                                @foreach($careers as $career)
                                                    <option value="{{$career->idcareer}}">{{strtoupper($career->name_en)}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="gender">Gender</label>
                                        <select class="form-control select2" name="gender"
                                                id="gender">
                                            <option value="" disabled selected>ALL
                                            </option>
                                            <option value="1">MALE
                                            </option>
                                            <option value="2">FEMALE
                                            </option>
                                            <option value="3">OTHER
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="jobSector">Job Sector</label>
                                        <select class="form-control select2" name="jobSector"
                                                id="jobSector">
                                            <option value="" disabled selected>ALL
                                            </option>
                                            <option value="0">GOVERNMENT
                                            </option>
                                            <option value="1">PRIVATE
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Birth day</label>

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
                            </form>
                        </div>
                    </div>
                    <div class="card m-b-20">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-rep-plugin ">
                                        <div class="table-responsive b-0 table-container" data-pattern="priority-columns">
                                            <table id="table" class="table table-striped table-bordered"
                                                   cellspacing="0"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>NAME</th>
                                                    <th>STATUS</th>
                                                    <th>PROFILE ( % )</th>
                                                    <th>GENDER</th>
                                                    <th>DOB</th>
                                                    <th>NIC</th>
                                                    <th>EMAIL</th>
                                                    <th>CONTACT NO</th>
                                                    <th>ELECTION DIVISION</th>
                                                    <th>MEMBER DIVISION</th>
                                                    <th>GRAMASEWA DIVISION</th>
                                                    <th>VILLAGE</th>
                                                    <th>ETHNICITY</th>
                                                    <th>RELIGION</th>
                                                    <th>EDUCATION</th>
                                                    <th>INCOME</th>
                                                    <th>CAREER</th>
                                                    <th>JOB SECTOR</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($users))
                                                    @if(count($users) > 0)
                                                        @foreach($users as $user)
                                                            <tr id="{{$user->idUser}}">
                                                                <td>{{$user->userTitle->name_en}} {{$user->fName}} {{$user->lName}}</td>
                                                                @if(\Illuminate\Support\Facades\Auth::user()->iduser_role <= 2 )
                                                                    <td>{{strtoupper($user->office->office_name)}}</td>
                                                                @endif
                                                                @if($user->member->memberAgents()->where('idoffice',\Illuminate\Support\Facades\Auth::user()->idoffice)->first()->status == 1)
                                                                    <td nowrap><p><em
                                                                                    class="mdi mdi-checkbox-blank-circle text-success "></em>
                                                                            ACTIVATED</p></td>
                                                                @elseif($user->member->memberAgents()->where('idoffice',\Illuminate\Support\Facades\Auth::user()->idoffice)->first()->status  == 2)
                                                                    <td nowrap><p><em
                                                                                    class="mdi mdi-checkbox-blank-circle text-warning "></em>
                                                                            PENDING</p></td>
                                                                @elseif($user->member->memberAgents()->where('idoffice',\Illuminate\Support\Facades\Auth::user()->idoffice)->first()->status  == 0)
                                                                    <td nowrap><p><em
                                                                                    class="mdi mdi-checkbox-blank-circle text-danger "></em>
                                                                            DEACTIVATED</p></td>
                                                                @else
                                                                    <td nowrap><em
                                                                                class="mdi mdi-checkbox-blank-circle text-black"></em>
                                                                        UNKNOWN
                                                                    </td>
                                                                @endif
                                                                <td>{{app(\App\Http\Controllers\Api\ApiProfileController::class)->completedPercentage($user->idUser)}}</td>
                                                            @if($user->gender == 1)
                                                                    <td>MALE</td>
                                                                @elseif ($user->gender == 2)
                                                                    <td>FEMALE</td>
                                                                @elseif ($user->gender == 2)
                                                                    <td>OTHER</td>
                                                                @else
                                                                    <td>NOT SPECIFIED</td>
                                                                @endif
                                                                <td>{{$user->bday}}</td>
                                                                <td>{{$user->nic}}</td>
                                                                <td>{{$user->email}}</td>
                                                                <td>{{$user->contact_no1}}</td>
                                                                <td>{{$user->member->electionDivision->name_en}}</td>
                                                                <td>{{$user->member->pollingBooth->name_en}}</td>
                                                                <td>{{$user->member->gramasewaDivision->name_en}}</td>
                                                                <td>{{$user->member->village->name_en}}</td>
                                                                <td>{{$user->member->ethnicity->name_en}}</td>
                                                                <td>{{$user->member->religion->name_en}}</td>
                                                                <td>{{$user->member->educationalQualification->name_en}}</td>
                                                                <td>{{$user->member->natureOfIncome->name_en}}</td>
                                                                <td>{{$user->member->career->name_en}}</td>
                                                                @if($user->member->is_government == 1)
                                                                    <td>GOVERNMENT</td>
                                                                @elseif ($user->member->is_government == 2)
                                                                    <td>PRIVATE</td>
                                                                @else
                                                                    <td>FEMALE</td>
                                                                @endif
                                                            </tr>

                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="25"
                                                                style="text-align: center;font-weight: 500">Sorry No
                                                                Results Found.
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(isset($users))
                                {{$users->links()}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- ./container -->
    </div><!-- ./wrapper -->


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
            $('#rowsCount').val("{{isset($_REQUEST['rows']) && $_REQUEST['rows'] != '' ? $_REQUEST['rows'] : 10}}");

//            setParameters();
        });

        $('#table').DataTable({
            "bFilter": false,
            'bInfo': false,
            dom: 'Bfrtip',
            'order': [[0, 'desc']],
            "bPaginate": false,
            buttons: [
                {
                    extend: 'print',
                    title: 'Members Report',
                },
                {
                    extend: 'excelHtml5',
                    autoFilter: true,
                    sheetName: 'Members Report',
                    filename: 'Members Report',
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    filename: 'Members Report',
                    title: 'Agent Report',
                },
                {
                    extend: 'colvis',
                    text: 'Columns'
                },
            ]
        });

        $('.dt-buttons').append(" <select  aria-controls=\"execelTable\" class=\"bg-secondary text-white btn\" onchange=\"rowsChange(this)\"  name=\"rowsCount\" id=\"rowsCount\" >\n" +
            "                                            <option class=\"bg-secondary  text-white\" value=\"10\" >10 rows </option>\n" +
            "                                            <option class=\"bg-secondary  text-white\" value=\"25\">25 rows </option>\n" +
            "                                            <option class=\"bg-secondary  text-white\" value=\"50\">50 rows </option>\n" +
            "                                            <option class=\"bg-secondary  text-white\" value=\"100\">100 rows </option>\n" +
            "                                            <option class=\"bg-secondary  text-white\" value=\"all\">All rows</option>\n" +
            " </select>");


        function rowsChange(el) {
            let rows = el.value;
            $('#rows').val(rows);


            $('#form1').submit();
        }

        function setParameters(){
            let rows = "{{isset($_REQUEST['rows']) && $_REQUEST['rows'] != '' ? $_REQUEST['rows'] : 10}}";
            let searchCol = "{{isset($_REQUEST['searchCol']) ? $_REQUEST['searchCol'] : 1}}";
            let searchText = "{{isset($_REQUEST['searchText']) ? $_REQUEST['searchText'] : ''}}";
            let pollingBooth = "{{isset($_REQUEST['pollingBooth']) ? $_REQUEST['pollingBooth'] : ''}}";
            let electionDivision = "{{isset($_REQUEST['electionDivision']) ? $_REQUEST['electionDivision'] : ''}}";
            let ethnicity = "{{isset($_REQUEST['ethnicity']) ? $_REQUEST['ethnicity'] : ''}}";
            let village = "{{isset($_REQUEST['village']) ? $_REQUEST['village'] : ''}}";
            let gramasewaDivision = "{{isset($_REQUEST['gramasewaDivision']) ? $_REQUEST['gramasewaDivision'] : ''}}";
            let career = "{{isset($_REQUEST['career']) ? $_REQUEST['career'] : ''}}";
            let religion = "{{isset($_REQUEST['religion']) ? $_REQUEST['religion'] : ''}}";
            let education = "{{isset($_REQUEST['education']) ? $_REQUEST['education'] : ''}}";
            let income = "{{isset($_REQUEST['income']) ? $_REQUEST['income'] : ''}}";
            let jobSector = "{{isset($_REQUEST['jobSector']) ? $_REQUEST['jobSector'] : ''}}";
            let gender = "{{isset($_REQUEST['gender']) ? $_REQUEST['gender'] : ''}}";

            $('#rowsCount').val(rows);
            $('#searchText').val(searchText);
            $('#searchCol').val(searchCol).trigger('change');
            $('#pollingBooth').val(pollingBooth).trigger('change');
            $('#electionDivision').val(electionDivision).trigger('change');
            $('#ethnicity').val(ethnicity).trigger('change');
            $('#village').val(village).trigger('change');
            $('#gramasewaDivision').val(gramasewaDivision).trigger('change');
            $('#career').val(career).trigger('change');
            $('#religion').val(religion).trigger('change');
            $('#education').val(education).trigger('change');
            $('#income').val(income).trigger('change');
            $('#jobSector').val(jobSector).trigger('change');
            $('#gender').val(gender).trigger('change');
//
        }


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
    </script>
@endsection