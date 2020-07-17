<!-- Loader -->
<div id="preSecretariat">  <div id="status">
<div class="spinner"></div>
</div>
</div>


<!-- Begin page -->
<div id="wrapper">

    <!-- ========== Left Sidebar Start ========== -->
    <div class="left side-menu">

        <!-- LOGO -->
        <div class="topbar-left">
            <div class="">
                {{--<a href="" class="logo"><img src="{{ URL::asset('assets/images/resources/leftBar.jpg')}}" height="45" alt="logo"></a>--}}
                <a href="" class="logo text-center"><img width="80%"
                                                         src="{{ \Illuminate\Support\Facades\URL::asset('assets/images/resources/deplogo.svg')}}"></a>
            </div>
        </div>
        <br/>
        <div class="sidebar-inner slimscrollleft">
            <div id="sidebar-menu">

                <ul>


                    <li>
                        <a href="{{ url('/') }}" class="waves-effect"><i
                                    class="dripicons-device-desktop"></i><span>{{ __('Dashboard') }} </span></a>
                    </li>

                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3 || \Illuminate\Support\Facades\Auth::user()->iduser_role == 5 || \Illuminate\Support\Facades\Auth::user()->iduser_role == 10)

                        <li class="menu-title">{{ __('HIERARCHY') }}</li>
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>{{ __('Hierarchy') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('electionDivision')}}">{{ __('Election Division') }}</a></li>
                                <li><a href="{{route('pollingBooth')}}">{{ __('Member Division') }}</a></li>
                                <li><a href="{{route('gramasewaDivision')}}">{{ __('Gramasewa Division') }}</a></li>
                                <li><a href="{{route('village')}}">{{ __('Village') }}</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>{{ __('Secretariat') }}
                                    <span class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3 || \Illuminate\Support\Facades\Auth::user()->iduser_role == 5)
                                <li><a href="{{route('divisionalSecretariat')}}">{{ __('Create Secretariat') }}</a></li>
                                @endif
                                <li><a href="{{route('divisionalSecretariat-view')}}">{{ __('View Secretariats') }}</a>
                                </li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>{{ __('Council') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3 || \Illuminate\Support\Facades\Auth::user()->iduser_role == 5)
                                <li><a href="{{route('council')}}">{{ __('Create Council') }}</a></li>
                                @endif
                                <li><a href="{{route('council-view')}}">{{ __('View Councils') }}</a></li>
                            </ul>
                        </li>
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 9 && \Illuminate\Support\Facades\Auth::user()->office->ANALYSIS() == 1)
                    <li class="menu-title">{{ __('ANALYSIS') }}</li>
                    @endif


                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 9 && \Illuminate\Support\Facades\Auth::user()->office->ANALYSIS() == 1)
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="dripicons-suitcase"></i>
                                <span>{{ __('Staff') }}
                                    <span class="pull-right">
                                   <i class="mdi mdi-chevron-right"></i>
                               </span>
                           </span>
                            </a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('assignStaff')}}">{{ __('Assign Staff') }}</a></li>
                            </ul>
                        </li>
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3 ||\Illuminate\Support\Facades\Auth::user()->iduser_role == 9 || \Illuminate\Support\Facades\Auth::user()->iduser_role == 4|| \Illuminate\Support\Facades\Auth::user()->iduser_role == 8|| \Illuminate\Support\Facades\Auth::user()->iduser_role == 10)
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Post') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 8 ||  \Illuminate\Support\Facades\Auth::user()->iduser_role == 3 ||  \Illuminate\Support\Facades\Auth::user()->iduser_role == 4||  \Illuminate\Support\Facades\Auth::user()->iduser_role == 10)
                                    <li><a href="{{route('viewPost')}}">{{ __('View Posts') }}</a></li>

                                @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 8 )
                                    <li><a href="{{route('createPost')}}">{{ __('Create Post') }}</a></li>

                                @endif
                                @endif
                                 @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 9 )
                                <li><a href="{{route('pendingPosts')}}">{{ __('Pending Posts') }}</a></li>
                                <li><a href="{{route('activePosts')}}">{{ __('Active Posts') }}</a></li>
                                <li><a href="{{route('rejectedPosts')}}">{{ __('Rejected Posts') }}</a></li>
                                    @endif

                            </ul>
                        </li>
                    @endif

                    @if((\Illuminate\Support\Facades\Auth::user()->iduser_role == 3
                    || \Illuminate\Support\Facades\Auth::user()->iduser_role == 4
                    || \Illuminate\Support\Facades\Auth::user()->iduser_role == 10
                    ) && \Illuminate\Support\Facades\Auth::user()->office->MAP() == 1)
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect">
                            <i class="dripicons-suitcase"></i>
                            <span>{{ __('Map') }}
                                <span class="pull-right">
                                   <i class="mdi mdi-chevron-right"></i>
                               </span>
                           </span>
                        </a>
                        <ul class="list-unstyled">
                            {{--<li><a href="{{route('routeTest')}}">{{ __('Route Test') }}</a></li>--}}
                            <li><a href="{{route('agentDistribution')}}">{{ __('Agent Distribution') }}</a></li>
                            <li><a href="{{route('heatMap')}}">{{ __('Heat Map') }}</a></li>
                            <li><a href="{{route('liveMap')}}">{{ __('Live Map') }}</a></li>

                        </ul>
                    </li>
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 9 )
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Comments') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('pendingComments')}}">{{ __('Pending Comments') }}</a></li>

                            </ul>
                        </li>
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)
                        {{--<li class="has_sub">--}}
                            {{--<a href="javascript:void(0);" class="waves-effect"><i--}}
                                        {{--class="dripicons-suitcase"></i><span>{{ __('Responses') }}--}}
                                    {{--<span--}}
                                            {{--class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>--}}
                            {{--<ul class="list-unstyled">--}}
                                {{--<li><a href="{{route('pendingResponses')}}">{{ __('Pending Responses') }}</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)

                        {{--<li>--}}
                        {{--<a href="{{route('directMessages')}}" class="waves-effect"><i--}}
                        {{--class="dripicons-suitcase"></i><span>{{ __('Direct Messages') }} </span></a>--}}
                        {{--</li>--}}

                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 2)
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Category') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('addCategory')}}">{{ __('Add Category') }}</a></li>
                                <li><a href="{{route('viewCategory')}}">{{ __('View Category') }}</a></li>
                            </ul>
                        </li>
                    @endif
                    @if((\Illuminate\Support\Facades\Auth::user()->iduser_role == 3 || \Illuminate\Support\Facades\Auth::user()->iduser_role == 5 || \Illuminate\Support\Facades\Auth::user()->iduser_role == 10) &&  \Illuminate\Support\Facades\Auth::user()->office->CANVASSING() == 1)
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Canvassing') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                @if( \Illuminate\Support\Facades\Auth::user()->iduser_role == 5)
                                <li><a href="{{route('createCanvassing')}}">{{ __('Create Canvassing') }}</a></li>
                                {{--<li><a href="{{route('canvassingType')}}">{{ __('Canvassing Type') }}</a></li>--}}
                                @endif
                                    @if( \Illuminate\Support\Facades\Auth::user()->iduser_role == 3)
                                        <li><a href="{{route('upApprovedCanvassing')}}">{{ __('Unapproved Canvassing') }}</a></li>
                                        <li><a href="{{route('pendingCanvassing')}}">{{ __('Pending Canvassing') }}</a></li>
                                        <li><a href="{{route('rejectedCanvassing')}}">{{ __('Rejected Canvassing') }}</a></li>
                                        <li><a href="{{route('finishedCanvassing')}}">{{ __('Finished Canvassing') }}</a></li>
                                    @endif
                                    @if( \Illuminate\Support\Facades\Auth::user()->iduser_role == 3 || \Illuminate\Support\Facades\Auth::user()->iduser_role == 4)
                                        <li><a href="{{route('pendingCanvassingScreen')}}">{{ __('Canvassing Screen') }}</a></li>
                                    @endif
                            </ul>
                        </li>
                    @endif


                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 8 && \Illuminate\Support\Facades\Auth::user()->office->ANALYSIS() == 1)

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="dripicons-suitcase"></i>
                                <span>{{ __('Analyze') }}
                                    <span class="pull-right">
                                       <i class="mdi mdi-chevron-right"></i>
                                   </span>
                               </span>
                            </a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('pendingResponse')}}">{{ __('Post Response') }}</a></li>
                                {{--                                <li><a href="{{route('assignStaff')}}">{{ __('Direct Messages') }}</a></li>--}}
                            </ul>
                        </li>
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3 && \Illuminate\Support\Facades\Auth::user()->office->attendence_available == 1)

                        {{--<li class="menu-title">{{ __('ATTENDANCE') }}</li>--}}
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3  && \Illuminate\Support\Facades\Auth::user()->office->attendence_available == 1)

                        {{--<li class="has_sub">--}}
                            {{--<a href="javascript:void(0);" class="waves-effect">--}}
                                {{--<i class="dripicons-suitcase"></i>--}}
                                {{--<span>{{ __('Event') }}--}}
                                    {{--<span class="pull-right">--}}
                                       {{--<i class="mdi mdi-chevron-right"></i>--}}
                                   {{--</span>--}}
                               {{--</span>--}}
                            {{--</a>--}}
                            {{--<ul class="list-unstyled">--}}
                                {{--<li><a href="{{route('create-event')}}">{{ __('Create Event') }}</a></li>--}}
                                {{--<li><a href="{{route('view-events')}}">{{ __('View Events') }}</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3 ||\Illuminate\Support\Facades\Auth::user()->iduser_role == 4 || \Illuminate\Support\Facades\Auth::user()->iduser_role == 10)

                        <li class="menu-title">{{ __('REPORTS') }}</li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3 || \Illuminate\Support\Facades\Auth::user()->iduser_role == 10  || \Illuminate\Support\Facades\Auth::user()->iduser_role == 4)
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="dripicons-suitcase"></i>
                                <span>{{ __('Generic Reports') }}
                                    <span class="pull-right">
                                       <i class="mdi mdi-chevron-right"></i>
                                   </span>
                               </span>
                            </a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('report-agents')}}">{{ __('Agents Report') }}</a></li>
                                <li><a href="{{route('report-members')}}">{{ __('Member Report') }}</a></li>
                                <li><a href="{{route('report-age')}}">{{ __('Age Report') }}</a></li>
                                <li><a href="{{route('report-education')}}">{{ __('Education Report') }}</a></li>
                                <li><a href="{{route('report-income')}}">{{ __('Income Report') }}</a></li>
                                <li><a href="{{route('report-career')}}">{{ __('Career Report') }}</a></li>
                                <li><a href="{{route('report-religion')}}">{{ __('Religion Report') }}</a></li>
                                <li><a href="{{route('report-ethnicity')}}">{{ __('Ethnicity Report') }}</a></li>
                                <li><a href="{{route('report-voters')}}">{{ __('Voters Report') }}</a></li>
                                <li><a href="{{route('report-post-response')}}">{{ __('Post Response Summery') }}</a></li>
                                {{--<li><a href="{{route('report-community-response')}}">{{ __('Community Response Summery') }}</a></li>--}}
                            </ul>
                        </li>
                    @endif
                    @if((\Illuminate\Support\Facades\Auth::user()->iduser_role == 4  || \Illuminate\Support\Facades\Auth::user()->iduser_role == 10 ) && \Illuminate\Support\Facades\Auth::user()->office->ANALYSIS() == 1)

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="dripicons-suitcase"></i>
                                <span>{{ __('Analysis Reports') }}
                                    <span class="pull-right">
                                       <i class="mdi mdi-chevron-right"></i>
                                   </span>
                               </span>
                            </a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('report-categoryWise')}}">{{ __('Category Wise') }}</a></li>
                                <li><a href="{{route('report-locationWise')}}">{{ __('Location Wise') }}</a></li>
                            </ul>
                        </li>
                    @endif
                    @if((\Illuminate\Support\Facades\Auth::user()->iduser_role == 3  || \Illuminate\Support\Facades\Auth::user()->iduser_role == 10 || \Illuminate\Support\Facades\Auth::user()->iduser_role == 4) && \Illuminate\Support\Facades\Auth::user()->office->CANVASSING() == 1  )

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="dripicons-suitcase"></i>
                                <span>{{ __('Canvassing Reports') }}
                                    <span class="pull-right">
                                       <i class="mdi mdi-chevron-right"></i>
                                   </span>
                               </span>
                            </a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('report-votersCount')}}">{{ __('Voters Count') }}</a></li>
                                <li><a href="{{route('report-compareCanvassing')}}">{{ __('Compare Canvassing') }}</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 2)
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Payments') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('addPayment')}}">{{ __('Add Payment') }}</a></li>
                                <li><a href="{{route('viewOutstandingPayments')}}">{{ __('View Outstanding') }}</a>
                                </li>
                                <li><a href="{{route('viewPayments')}}">{{ __('View Payments') }}</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Office') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('addOffice')}}">{{ __('Add Office') }}</a></li>
                                <li><a href="{{route('viewOffice')}}">{{ __('View Offices') }}</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Votes Estimation') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('createDefaultTask')}}">{{ __('Default Estimation') }}</a></li>
                                <li><a href="{{route('viewBudget')}}">{{ __('View Estimation') }}</a></li>
                                {{--<li><a href="{{route('assignTask')}}">{{ __('Assign Budget') }}</a></li>--}}
                                {{--<li><a href="{{route('viewTasks')}}">{{ __('View Budget') }}</a></li>--}}
                            </ul>
                        </li>
                    @endif
                    @if(
                    ( \Illuminate\Support\Facades\Auth::user()->iduser_role == 5
                    || \Illuminate\Support\Facades\Auth::user()->iduser_role == 8
                    || \Illuminate\Support\Facades\Auth::user()->iduser_role == 9
                    || \Illuminate\Support\Facades\Auth::user()->iduser_role == 2 ) && \Illuminate\Support\Facades\Auth::user()->office->SMS() == 1)


                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('SMS') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 9 )

                                <li><a href="{{route('welcomeMessage')}}">{{ __('Welcome SMS') }}</a></li>
                                @endif

                            @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 8 )

                                    <li><a href="{{route('createSms')}}">{{ __('Send SMS') }}</a></li>
                                    <li><a href="{{route('sendGroup')}}">{{ __('Send Group SMS') }}</a></li>

                                @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 5 )
                                    <li><a href="{{route('createSmsGroup')}}">{{ __('Create Group') }}</a></li>
                                    <li><a href="{{route('addContacts')}}">{{ __('Add Contacts') }}</a></li>
                                    @endif

                                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 9 )
                                        <li><a href="{{route('pendingSms')}}">{{ __('Pending SMS') }}</a></li>
                                        <li><a href="{{route('sentSms')}}">{{ __('Sent SMS') }}</a></li>
                                        <li><a href="{{route('rejectedSms')}}">{{ __('Rejected SMS') }}</a></li>
                                    @endif
                                @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 2 )
                                    <li><a href="{{route('smsConfiguration')}}">{{ __('SMS Configurations') }}</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role <=3)
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Users') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('addUser')}}">{{ __('Add Users') }}</a></li>
                                @if(\Illuminate\Support\Facades\Auth::user()->iduser_role == 3)

                                    <li><a href="{{route('pendingAgents')}}">{{ __('Pending Users') }}</a></li>
                                @endif
                                <li><a href="{{route('viewUser')}}">{{ __('View Users') }}</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->iduser_role <=2)
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i
                                        class="dripicons-suitcase"></i><span>{{ __('Setting') }}
                                    <span
                                            class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{route('appVersion')}}">{{ __('App Version') }}</a></li>
                            </ul>
                        </li>

                    @endif
                </ul>
            </div>
            <div class="clearfix"></div>
        </div> <!-- end sidebarinner -->
    </div>
    <!-- Left Sidebar End -->