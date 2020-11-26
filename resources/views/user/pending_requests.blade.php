@extends('layouts.main')
@section('psStyle')
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
                                <div class="text-center col-md-4">
                                    <h6>Auto Approve Agents</h6>
                                    @if(\Illuminate\Support\Facades\Auth::user()->office->officeSetting != null && \Illuminate\Support\Facades\Auth::user()->office->officeSetting->agent_auto == 1  )

                                        <input type="checkbox"
                                               class="btn  btn-sm btn-danger"
                                               onchange="autoApproveAgent()"
                                               id="autoAgent" checked
                                               switch="none"/>
                                        <label for="autoAgent"
                                               data-on-label="On"
                                               data-off-label="Off"></label>
                                    @else
                                        <input type="checkbox"
                                               class="btn  btn-sm btn-danger"
                                               onchange="autoApproveAgent()"
                                               id="autoAgent"
                                               switch="none"/>
                                        <label for="autoAgent"
                                               data-on-label="On"
                                               data-off-label="Off"></label>
                                    @endif
                                </div>
                                <div class="text-center col-md-4">
                                    <h6>Auto Approve Member</h6>
                                    @if(\Illuminate\Support\Facades\Auth::user()->office->officeSetting != null && \Illuminate\Support\Facades\Auth::user()->office->officeSetting->member_auto == 1  )
                                        <input type="checkbox"
                                               class="btn  btn-sm btn-danger"
                                               onchange="autoApproveMember()"
                                               id="autoMember" checked
                                               switch="none"/>
                                        <label for="autoMember"
                                               data-on-label="On"
                                               data-off-label="Off"></label>
                                    @else
                                        <input type="checkbox"
                                               class="btn  btn-sm btn-danger"
                                               onchange="autoApproveMember()"
                                               id="autoMember"
                                               switch="none"/>
                                        <label for="autoMember"
                                               data-on-label="On"
                                               data-off-label="Off"></label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">
                            <form action="{{route('pendingAgents')}}" method="GET">
                                <div class="row">
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
                                                </select>
                                            </div>
                                            <input class="form-control " type="text" min="0" id="searchText"
                                                   name="searchText">

                                        </div>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="gender">By Gender</label>
                                        <select class="form-control select2" name="gender"
                                                id="gender">
                                            <option value="" disabled selected>Select gender
                                            </option>
                                            <option value="1">MALE
                                            </option>
                                            <option value="2">FEMALE
                                            </option>
                                            <option value="3">Other
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>By Requested Date</label>

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

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive b-0" data-pattern="priority-columns">
                                            <table class="table table-striped table-bordered"
                                                   cellspacing="0"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>NAME</th>
                                                    <th>ELECTION D.</th>
                                                    <th>MEMBER D.</th>
                                                    <th>GRAMASEWA D.</th>
                                                    <th>VILLAGE</th>
                                                    <th>NIC</th>
                                                    <th>GENDER</th>
                                                    <th>CONTACT NO</th>
                                                    <TH>REQUESTED AT</TH>
                                                    <th>OPTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($users))
                                                    @if(count($users) > 0)
                                                        @foreach($users as $user)
                                                            <tr id="{{$user->idUser}}">
                                                                <td>{{strtoupper($user->userTitle->name_en)}} {{strtoupper($user->fName)}} {{strtoupper($user->lName)}}</td>
                                                                <td>{{strtoupper($user->agent->electionDivision->name_en)}}</td>
                                                                <td>{{strtoupper($user->agent->pollingBooth->name_en)}}</td>
                                                                <td>{{strtoupper($user->agent->gramasewaDivision->name_en)}}</td>
                                                                <td>{{strtoupper($user->agent->village->name_en)}}</td>
                                                                <td>{{$user->nic}}</td>
                                                                @if($user->gender == 1)
                                                                    <td>MALE</td>
                                                                @elseif ($user->gender == 2)
                                                                    <td>FEMALE</td>
                                                                @elseif ($user->gender == 3)
                                                                    <td>OTHER</td>
                                                                @else
                                                                    <td>UNDISCLOSED</td>
                                                                @endif
                                                                <td>{{$user->contact_no1}}</td>
                                                                <td>{{$user->created_at}}</td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary btn-sm dropdown-toggle"
                                                                                type="button" id="dropdownMenuButton"
                                                                                data-toggle="dropdown"
                                                                                aria-haspopup="true"
                                                                                aria-expanded="false">
                                                                            Option
                                                                        </button>

                                                                        <div class="dropdown-menu"
                                                                             aria-labelledby="dropdownMenuButton">
                                                                            <a href="#"
                                                                               onclick="viewUser({{$user->idUser}})"
                                                                               class="dropdown-item">View
                                                                            </a>
                                                                            <a href="#"
                                                                               onclick="showApproveModal({{$user->idUser}})"
                                                                               class="dropdown-item">Approve
                                                                            </a>
                                                                        </div>


                                                                    </div>
                                                                </td>
                                                            </tr>

                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="10"
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


    <!-- modal start -->
    <div class="modal fade" id="viewModal" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">View User</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="text-secondary">Personal Details</h6>
                    <hr/>
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="userTitleV">{{ __('User Title') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                    </div>
                                    <input autocomplete="off" readonly type="text" class="form-control"
                                           name="userTitleV" id="userTitleV">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="firstNameV">{{ __('add_user_fname') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-account"></em></span>
                                    </div>
                                    <input autocomplete="on" type="text" class="form-control" readonly="true"
                                           placeholder="Enter first name here" name="firstNameV" id="firstNameV">
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-5">
                            <label for="lastNameV">{{ __('add_user_lname') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                            <span class="input-group-text"><em
                                                        class="mdi mdi-account-multiple"></em></span>
                                    </div>
                                    <input autocomplete="on" type="text" class="form-control" readonly="true"
                                           placeholder="Enter last name here" name="lastNameV" id="lastNameV">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4 hideManagement">
                            <label for="phoneV">{{ __('phone') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                            <span class="input-group-text"><em
                                                        class="mdi mdi-phone-classic"></em></span>
                                    </div>
                                    <input autocomplete="on" type="number" class="form-control" readonly="true"
                                           placeholder="" name="phoneV" id="phoneV">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4 hideManagement">
                            <label for="dobV">{{ __('Date of Birth') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-calendar"></em></span>
                                    </div>
                                    <input autocomplete="off" type="text" class="form-control datepicker-autoclose"
                                           readonly="true"
                                           placeholder="mm/dd/yyyy" name="dobV" id="dobV">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4 hideManagement">
                            <label for="nicV">{{ __('NIC No') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                            <span class="input-group-text"><em
                                                        class="mdi mdi-credit-card-scan"></em></span>
                                    </div>
                                    <input autocomplete="on" type="text" class="form-control" readonly="true"
                                           name="nicV"
                                           id="nicV">
                                </div>
                            </div>
                        </div>


                        <div class="form-group col-md-6 hideManagement">
                            <label for="emailV">{{ __('Email') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-at"></em></span>
                                    </div>
                                    <input autocomplete="on" type="email" class="form-control" readonly="true"
                                           placeholder="" name="emailV" id="emailV">
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label style="margin-left: 5px;" class="control-label">{{ __('Gender') }}</label>
                            <div class="row">
                                <label style="margin-left: 5px;" class="radio-inline"><input disabled
                                                                                             style="margin-left: 5px;"
                                                                                             type="radio" required
                                                                                             value="1" name="genderV"
                                                                                             checked>&nbsp;{{ __('Male') }}
                                </label>
                                &nbsp;
                                &nbsp;
                                <label style="margin-left: 5px;" class="radio-inline"><input disabled
                                                                                             style="margin-left: 5px;"
                                                                                             type="radio" value="2"
                                                                                             name="genderV">&nbsp;{{ __('Female') }}
                                </label>
                                <label style="margin-left: 5px;" class="radio-inline"><input disabled
                                                                                             style="margin-left: 5px;"
                                                                                             type="radio" value="3"
                                                                                             name="genderV">&nbsp;{{ __('Female') }}
                                </label>
                                <label style="margin-left: 5px;" class="radio-inline"><input disabled
                                                                                             style="margin-left: 5px;"
                                                                                             type="radio" value="4"
                                                                                             name="genderV">&nbsp;{{ __('Undisclosed') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-6 onlyAppLevel">
                            <label for="ethnicityV">{{ __('Ethnicity') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                    </div>
                                    <input autocomplete="on" type="text" class="form-control" readonly="true"
                                           placeholder="" name="ethnicityV" id="ethnicityV">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6 onlyAppLevel">
                            <label for="religionV">{{ __('Religion') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                    </div>
                                    <input autocomplete="on" type="text" class="form-control" readonly="true"
                                           placeholder="" name="religionV" id="religionV">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6 onlyAppLevel">
                            <label for="incomeV">{{ __('Nature of Income') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                    </div>
                                    <input autocomplete="on" type="text" class="form-control" readonly="true"
                                           placeholder="" name="incomeV" id="incomeV">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6 onlyAppLevel">
                            <label for="educationV">{{ __('Educational Qualification') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                    </div>
                                    <input autocomplete="on" type="text" class="form-control" readonly="true"
                                           placeholder="" name="educationV" id="educationV">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12 onlyAppLevel">
                            <label for="careerV">{{ __('Career') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                    </div>
                                    <input autocomplete="on" type="text" class="form-control" readonly="true"
                                           placeholder="" name="careerV" id="careerV">
                                </div>
                            </div>
                        </div>



                    </div>
                    <h6 class="text-secondary">System Details</h6>
                    <hr/>
                    <div class="row">
                        @if(\Illuminate\Support\Facades\Auth::user()->iduser_role <= 2 )
                            <div class="form-group col-md-6">
                                <label for="officeV">{{ __('Office') }}</label>
                                <div>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                        </div>
                                        <input autocomplete="off" readonly type="text" class="form-control"
                                               name="officeV" id="officeV">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="form-group col-md-6 ">
                            <label for="userRoleV">{{ __('User Role') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                    </div>
                                    <input autocomplete="off" readonly type="text" class="form-control" name="userRoleV"
                                           id="userRoleV">
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="usernameV">{{ __('Username') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                    </div>
                                    <input autocomplete="off" readonly type="text" class="form-control" name="usernameV"
                                           id="usernameV">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6 hideManagement hierarchy">
                            <label for="electionDivisionV">{{ __('Election Division') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                    </div>
                                    <input autocomplete="off" readonly type="text" class="form-control"
                                           name="electionDivisionV" id="electionDivisionV">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6 hideManagement hierarchy">
                            <label for="pollingBoothV">{{ __('Member Division') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                    </div>
                                    <input autocomplete="off" readonly type="text" class="form-control"
                                           name="pollingBoothV" id="pollingBoothV">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6 hideManagement hierarchy">
                            <label for="gramasewaDivisionV">{{ __('Gramasewa Division') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                    </div>
                                    <input autocomplete="off" readonly type="text" class="form-control"
                                           name="gramasewaDivisionV" id="gramasewaDivisionV">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6 hideManagement  hierarchy">
                            <label for="villageV">{{ __('Village') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><em class="mdi mdi-dice-3"></em></span>
                                    </div>
                                    <input autocomplete="off" readonly type="text" class="form-control" name="villageV"
                                           id="villageV">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group col-md-4 toggleOfficeAdmin">
                            <label for="referralV">{{ __('Referral Code') }}</label>
                            <div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                            <span class="input-group-text"><em
                                                        class="mdi mdi-account-key"></em></span>
                                    </div>
                                    <input type="text" class="form-control" readonly="true" name="referralV"
                                           id="referralV">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal end -->

    <div class="modal fade" id="approveModal" tabindex="-1"
         role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Approve Agent</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible " id="approveError" style="display:none">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-8 ">
                            <label for="budgetType" style="margin-left: 5px;"
                                   class="control-label">{{ __('Budget Type') }}</label>

                            <select class="form-control  " name="budgetType"
                                    onchange="setCustomValidity('')"
                                    oninvalid="this.setCustomValidity('Please select budget type')"
                                    id="budgetType" required>
                                <option value="" disabled selected>Select type</option>
                                @foreach($taskTypes as $taskType)
                                    <option value="{{$taskType->idtask_type}}">{{$taskType->name}}</option>
                                @endforeach
                            </select>

                        </div>
                        <input type="hidden" id="hiddenId">
                        <div style="margin-top: 27px;" class="form-group col-md-4">
                            <button style="background-color: #5f98ff" type="submit" onclick="approveAgent();"
                                    class="btn btn-block text-white ">{{ __('Approve') }}</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- modal end -->
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
        function showApproveModal(id) {
            $('#hiddenId').val(id);
            $('.alert').html('').hide();
            $('#approveModal').modal('show');
        }
        function approveAgent() {
            $('.alert').html('').hide();
            let id = $('#hiddenId').val();
            let type = $('#budgetType').val();
            if (type) {
                $.ajax({
                    url: '{{route('approveAgent')}}',
                    type: 'POST',
                    data: {id: id, type: type},
                    success: function (data) {
                        if (data.errors != null) {
                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'APPROVE PROCESS INVALID!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Something wrong with process.contact administrator..'
                            });
                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'AGENT APPROVED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'Agent approved successfully.'
                            });
                            $('#' + id).remove();
                            $('#approveModal').modal('hide');
                        }

                    }
                });
            }
            else {
                $('#approveError').html('Please provide budget type').show();
            }
//            swal({
//                title: 'Do you want to approve this user?',
//                type: 'warning',
//                showCancelButton: true,
//                confirmButtonText: 'Yes, Approve!',
//                cancelButtonText: 'No, cancel!',
//                confirmButtonClass: 'btn btn-success',
//                cancelButtonClass: 'btn btn-danger m-l-10',
//                buttonsStyling: false
//            }).then(function () {
//
//
//
//            }, function (dismiss) {
//                // dismiss can be 'cancel', 'overlay',
//                // 'close', and 'timer'
////                if (dismiss === 'cancel') {
////                    swal(
////                        'Cancelled',
////                        'Process has been cancelled',
////                        'error'
////                    )
////                }
//            })
        }

        function viewUser(id) {
            //initialize alert and variables
            $('.notify').empty();
            $('.alert').hide();
            $('.alert').html("");
            //initialize alert and variables end

            $.ajax({
                    url: '{{route('getUserById')}}',
                    type: 'POST',
                    data: {id: id},
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
                            @if(\Illuminate\Support\Facades\Auth::user()->iduser_role <= 2)
                                $('#officeV').val(data.success.office.office_name);
                            @endif
                            $('.hideManagement').show();
                            $('.hierarchy').show();
                            $('.onlyAppLevel').show();
                            $('#userTitleV').val(data.success.user_title.name_en);
                            $('#firstNameV').val(data.success.fName);
                            $('#lastNameV').val(data.success.lName);
                            $('#usernameV').val(data.success.username);
                            $("input:radio[value=" + data.success.gender + "]").prop('checked', true);
                            $('#userRoleV').val(data.success.user_role.role);
                            $('#nicV').val(data.success.nic);
                            $('#emailV').val(data.success.email);
                            $('#phoneV').val(data.success.contact_no1);
                            $('#dobV').val(data.success.bday);
                            $('#referralV').val(data.success.agent.referral_code);
                            $('#electionDivisionV').val(data.success.agent.election_division.name_en);
                            $('#pollingBoothV').val(data.success.agent.polling_booth.name_en);
                            $('#gramasewaDivisionV').val(data.success.agent.gramasewa_division.name_en);
                            $('#villageV').val(data.success.agent.village != null ? data.success.agent.village.name_en : '');
                            $('#careerV').val(data.success.agent.career != null ? data.success.agent.career.name_en : '');
                            $('#educationV').val(data.success.agent.educational_qualification != null ? data.success.agent.educational_qualification.name_en : '');
                            $('#incomeV').val(data.success.agent.nature_of_income != null ? data.success.agent.nature_of_income.name_en : '');
                            $('#religionV').val(data.success.agent.religion != null ? data.success.agent.religion.name_en : '');
                            $('#ethnicityV').val(data.success.agent.ethnicity != null ? data.success.agent.ethnicity.name_en : '');
                            $('#viewModal').modal('show');
                        }
                    }


                }
            );
        }

        function autoApproveMember() {

            let status = $('#autoMember').prop('checked');
            $.ajax({
                url: '{{route('isDefaultBudgetCreated')}}',
                data: {status: status},
                type: 'POST',
                success: function (data) {

                    if (data == 'true') {
                        $.ajax({
                            url: '{{route('autoApproveMember')}}',
                            data: {status: status},
                            type: 'POST',
                            success: function (data) {

                            }
                        });
                    }
                    else {
                        $('#autoMember').prop("checked", !$('#autoMember').prop("checked"));
                        swal({
                            type: 'warning',
                            title: 'Oops...',
                            text: "<a href=\"{{route('createDefaultTask')}}\">You Have To Create Default Budget</a>",
                            showConfirmButton: false,
                        });
                    }

                }
            });

        }

        function autoApproveAgent() {
            let status = $('#autoAgent').prop('checked');
            $.ajax({
                url: '{{route('isDefaultBudgetCreated')}}',
                data: {status: status},
                type: 'POST',
                success: function (data) {

                    if (data == 'true') {
                        $.ajax({
                            url: '{{route('autoApproveAgent')}}',
                            data: {status: status},
                            type: 'POST',
                            success: function (data) {

                            }
                        });
                    }
                    else {
                        $('#autoAgent').prop("checked", !$('#autoAgent').prop("checked"));

                        swal({
                            type: 'warning',
                            title: 'Oops...',
                            text: "<a href=\"{{route('createDefaultTask')}}\">You Have To Create Default Budget</a>",
                            showConfirmButton: false,
                        });
                    }


                }
            });

        }
    </script>
@endsection