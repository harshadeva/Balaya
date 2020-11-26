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
                            <form action="{{route('viewUser')}}" method="GET">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-dismissible " id="errorAlert"
                                             style="display:none">
                                        </div>
                                    </div>
                                </div>
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
                                                    <option value="4">USERNAME</option>
                                                </select>
                                            </div>
                                            <input class="form-control " type="text" min="0" id="searchText"
                                                   name="searchText">

                                        </div>
                                    </div>
                                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role <= 2 )
                                        <div class="form-group col-md-3">
                                            <label for="office" class="control-label">{{ __('By Office') }}</label>
                                            <select id="office" name="office" class="form-control">
                                                <option value="" disabled selected>Select Office</option>
                                                @if($offices != null)
                                                    @foreach($offices as $office)
                                                        <option value="{{$office->idoffice}}">{{$office->office_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    @endif


                                    <div class="form-group col-md-3">
                                        <label for="userRole">By User Role</label>

                                        <select class="form-control select2" name="userRole"
                                                id="userRole">
                                            <option value="" disabled selected>Select user role
                                            </option>
                                            @if($userRoles != null)
                                                @foreach($userRoles as $userRole)
                                                    <option value="{{$userRole->iduser_role}}">{{$userRole->role}}</option>
                                                @endforeach
                                            @endif
                                        </select>

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
                                            <option value="4">UNDISCLOSED
                                            </option>
                                        </select>
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
                                                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role <= 2 )
                                                        <th>OFFICE</th>
                                                    @endif
                                                    <th>USER ROLE</th>
                                                    <th>STATUS</th>
                                                    <th>GENDER</th>
                                                    <th>DOB</th>
                                                    <th>EMAIL</th>
                                                    <th>CONTACT NO</th>
                                                    <TH>CREATED AT</TH>
                                                    <th>OPTION</th>
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
                                                                <td>{{$user->userRole->role}}</td>
                                                                @if($user->iduser_role != 7)
                                                                    @if($user->status == 1)
                                                                        <td nowrap><p><em
                                                                                        class="mdi mdi-checkbox-blank-circle text-success "></em>
                                                                                ACTIVATED</p></td>
                                                                    @elseif($user->status == 2)
                                                                        <td nowrap><p><em
                                                                                        class="mdi mdi-checkbox-blank-circle text-warning "></em>
                                                                                PENDING</p></td>
                                                                    @elseif($user->status == 0)
                                                                        <td nowrap><p><em
                                                                                        class="mdi mdi-checkbox-blank-circle text-danger "></em>
                                                                                DEACTIVATED</p></td>
                                                                    @else
                                                                        <td nowrap><em
                                                                                    class="mdi mdi-checkbox-blank-circle text-black"></em>
                                                                            UNKNOWN
                                                                        </td>
                                                                    @endif
                                                                @else
                                                                    @if($user->member->memberAgents()->where('idoffice',\Illuminate\Support\Facades\Auth::user()->idoffice)->first()->status == 1)
                                                                        <td nowrap><p><em
                                                                                        class="mdi mdi-checkbox-blank-circle text-success "></em>
                                                                                ACTIVATED</p></td>
                                                                    @elseif($user->member->memberAgents()->where('idoffice',\Illuminate\Support\Facades\Auth::user()->idoffice)->first()->status == 2)
                                                                        <td nowrap><p><em
                                                                                        class="mdi mdi-checkbox-blank-circle text-warning "></em>
                                                                                PENDING</p></td>
                                                                    @elseif($user->member->memberAgents()->where('idoffice',\Illuminate\Support\Facades\Auth::user()->idoffice)->first()->status == 0)
                                                                        <td nowrap><p><em
                                                                                        class="mdi mdi-checkbox-blank-circle text-danger "></em>
                                                                                DEACTIVATED</p></td>
                                                                    @else
                                                                        <td nowrap><em
                                                                                    class="mdi mdi-checkbox-blank-circle text-black"></em>
                                                                            UNKNOWN
                                                                        </td>
                                                                    @endif
                                                                @endif
                                                                @if($user->gender == 1)
                                                                    <td>MALE</td>
                                                                @elseif ($user->gender == 2)
                                                                    <td>FEMALE</td>
                                                                @elseif ($user->gender == 3)
                                                                    <td>OTHER</td>
                                                                @else
                                                                    <td>UNDISCLOSED</td>
                                                                @endif
                                                                <td>{{$user->bday}}</td>
                                                                <td>{{$user->email}}</td>
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
                                                                            @if( $user->userRole->allow_to_manage_by == \Illuminate\Support\Facades\Auth::user()->iduser_role)
                                                                                <form id="updateForm-{{$user->idUser}}"
                                                                                      action="{{route('editUser')}}"
                                                                                      method="POST">
                                                                                    {{csrf_field()}}
                                                                                    <input type="hidden"
                                                                                           value="{{$user->idUser}}"
                                                                                           name="updateUserId">
                                                                                    <a href="#"
                                                                                       onclick="$('#updateForm-{{$user->idUser}}').submit();"
                                                                                       class="dropdown-item"
                                                                                       id="invoiceId">Edit
                                                                                    </a>
                                                                                </form>
                                                                            @endif
                                                                            <a href="#"
                                                                               onclick="viewUser({{$user->idUser}})"
                                                                               class="dropdown-item">View
                                                                            </a>
                                                                            @if( \Illuminate\Support\Facades\Auth::user()->iduser_role <= 3)
                                                                                @if($user->iduser_role == 7)
                                                                                    @if($user->member->memberAgents()->where('idoffice',\Illuminate\Support\Facades\Auth::user()->idoffice)->first()->status == 1)
                                                                                        <a href="#"
                                                                                           onclick="disableUser({{$user->idUser}})"
                                                                                           class="dropdown-item">Disable
                                                                                        </a>
                                                                                    @elseif($user->member->memberAgents()->where('idoffice',\Illuminate\Support\Facades\Auth::user()->idoffice)->first()->status == 0)
                                                                                        <a href="#"
                                                                                           onclick="enableUser({{$user->idUser}})"
                                                                                           class="dropdown-item">Enable
                                                                                        </a>
                                                                                    @else
                                                                                    @endif
                                                                                @else
                                                                                    @if($user->status == 1)
                                                                                        <a href="#"
                                                                                           onclick="disableUser({{$user->idUser}})"
                                                                                           class="dropdown-item">Disable
                                                                                        </a>
                                                                                    @elseif($user->status == 0)
                                                                                        <a href="#"
                                                                                           onclick="enableUser({{$user->idUser}})"
                                                                                           class="dropdown-item">Enable
                                                                                        </a>
                                                                                    @else
                                                                                    @endif
                                                                                @endif
                                                                                @if($user->iduser_role != 3)
                                                                                    <a href="#"
                                                                                       onclick="deleteUser({{$user->idUser}})"
                                                                                       class="dropdown-item">Delete
                                                                                    </a>
                                                                                @endif
                                                                            @endif


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
                                                                                             name="genderV">&nbsp;{{ __('Other') }}
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
                        $('.onlyAppLevel').hide();

                        $('#userTitleV').val(data.success.user_title.name_en);
                        $('#firstNameV').val(data.success.fName);
                        $('#lastNameV').val(data.success.lName);
                        $('#usernameV').val(data.success.username);
                        $("input:radio[value=" + data.success.gender + "]").prop('checked', true);

                        $('#userRoleV').val(data.success.user_role.role);
                        if (data.success.iduser_role == 4) {
                            $('.hideManagement').hide();
                        }
                        else {
                            $('.hideManagement').show();

                            $('#nicV').val(data.success.nic);
                            $('#emailV').val(data.success.email);
                            $('#phoneV').val(data.success.contact_no1);
                            $('#dobV').val(data.success.bday);

                        }
                        if (data.success.iduser_role == 3 || data.success.iduser_role == 6) {
                            $('.toggleOfficeAdmin').show();
                            if (data.success.iduser_role == 3) {
                                $('#referralV').val(data.success.office_admin.referral_code);
                            }
                            else {

                                $('#referralV').val(data.success.agent.referral_code);
                            }
                        }
                        else {
                            $('.toggleOfficeAdmin').hide();
                        }
                        if (data.success.iduser_role == 6 || data.success.iduser_role == 7) {
                            $('.hierarchy').show();
                            $('.onlyAppLevel').show();
                            if (data.success.iduser_role == 6) {
                                $('#electionDivisionV').val(data.success.agent.election_division.name_en);
                                $('#pollingBoothV').val(data.success.agent.polling_booth.name_en);
                                $('#gramasewaDivisionV').val(data.success.agent.gramasewa_division.name_en);
                                $('#villageV').val(data.success.agent.village != null ? data.success.agent.village.name_en : '');
                                $('#careerV').val(data.success.agent.career != null ? data.success.agent.career.name_en : '');
                                $('#educationV').val(data.success.agent.educational_qualification != null ? data.success.agent.educational_qualification.name_en : '');
                                $('#incomeV').val(data.success.agent.nature_of_income != null ? data.success.agent.nature_of_income.name_en : '');
                                $('#religionV').val(data.success.agent.religion != null ? data.success.agent.religion.name_en : '');
                                $('#ethnicityV').val(data.success.agent.ethnicity != null ? data.success.agent.ethnicity.name_en : '');
                            }
                            else {
                                $('#careerV').val(data.success.member.career != null ? data.success.member.career.name_en : '');
                                $('#educationV').val(data.success.member.educational_qualification != null ? data.success.member.educational_qualification.name_en : '');
                                $('#incomeV').val(data.success.member.nature_of_income != null ? data.success.member.nature_of_income.name_en : '');
                                $('#religionV').val(data.success.member.religion != null ? data.success.member.religion.name_en : '');
                                $('#ethnicityV').val(data.success.member.ethnicity != null ? data.success.member.ethnicity.name_en : '');
                                $('#electionDivisionV').val(data.success.member.election_division.name_en);
                                $('#pollingBoothV').val(data.success.member.polling_booth.name_en);
                                $('#gramasewaDivisionV').val(data.success.member.gramasewa_division.name_en);
                                $('#villageV').val(data.success.member.village != null ? data.success.member.village.name_en : '');

                            }
                        }
                        else {
                            $('.hierarchy').hide();
                        }

                        $('#viewModal').modal('show');
                    }
                }


            });


        }

        function disableUser(id) {
            swal({
                title: 'Disable this user?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Disable!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-success m-l-10',
                buttonsStyling: false
            }).then(function () {

                $.ajax({
                    url: '{{route('disableUser')}}',
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        if (data.errors != null) {

                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'DISABLE PROCESS INVALID!',
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
                                title: 'USER DISABLED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'User disabled successfully.'
                            });
                            location.reload();
                        }

                    }
                });

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
//                if (dismiss === 'cancel') {
//                    swal(
//                        'Cancelled',
//                        'Process has been cancelled',
//                        'error'
//                    )
//                }
            })
        }

        function enableUser(id) {
            swal({
                title: 'Enable this user?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Enable!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-success m-l-10',
                buttonsStyling: false
            }).then(function () {

                $.ajax({
                    url: '{{route('enableUser')}}',
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        if (data.errors != null) {
                            $.each(data.errors, function (key, value) {
                                notify({
                                    type: "error", //alert | success | error | warning | info
                                    title: 'ENABLE PROCESS INVALID!',
                                    autoHide: true, //true | false
                                    delay: 6000, //number ms
                                    position: {
                                        x: "right",
                                        y: "top"
                                    },
                                    icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                    message: value
                                });
                            });

                        }
                        if (data.success != null) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'USER ENABLED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'User enabled successfully.'
                            });
                            location.reload();
                        }

                    }
                });

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
//                if (dismiss === 'cancel') {
//                    swal(
//                        'Cancelled',
//                        'Process has been cancelled',
//                        'error'
//                    )
//                }
            })
        }

        function deleteUser(id) {
            swal({
                title: 'Delete this user?',
                text:'You will not be able to undo this process',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-success m-l-10',
                buttonsStyling: false
            }).then(function () {

                $.ajax({
                    url: '{{route('deleteUser')}}',
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        if (data.errors != null) {

                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'DELETION PROCESS INVALID!',
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
                                title: 'USER DELETED!',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<em class="mdi mdi-check-circle-outline"></em>',

                                message: 'User deleetd successfully.'
                            });
                            location.reload();
                        }

                    }
                });

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
//                if (dismiss === 'cancel') {
//                    swal(
//                        'Cancelled',
//                        'Process has been cancelled',
//                        'error'
//                    )
//                }
            })
        }


    </script>
@endsection